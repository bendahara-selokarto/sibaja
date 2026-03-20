<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithTenantScope;
use App\Http\Requests\PenawaranHargaRequest;
use App\Models\Penawaran;
use App\UseCases\Penawaran\BuildPenawaranReportUseCase;
use App\UseCases\Penawaran\StorePenawaranInput;
use App\UseCases\Penawaran\StorePenawaranUseCase;
use App\UseCases\Penawaran\UpdatePenawaranInput;
use App\UseCases\Penawaran\UpdatePenawaranUseCase;
use Barryvdh\DomPDF\Facade\Pdf;
use DomainException;
use Illuminate\Support\Facades\Validator;

class PenawaranHargaController extends Controller
{
    use InteractsWithTenantScope;

    public function index()
    {
        return view('menu.penawaran');
    }

    public function create($kegiatanId, $penyediaId)
    {
        $kegiatan = $this->findTenantKegiatan($kegiatanId, [
            'penawaran',
            'pemberitahuan.penyedias',
            'pemberitahuan.belanjas',
        ]);
        abort_if($kegiatan === null, 404);

        $pemberitahuan = $kegiatan->pemberitahuan;
        $penyedia = $pemberitahuan
            ? $pemberitahuan->selectedPenyedias()->firstWhere('id', (string) $penyediaId)
            : null;

        if (!$pemberitahuan || !$penyedia) {
            return redirect()
                ->route('kegiatan.show', ['id' => $kegiatanId])
                ->with('error', 'Penyedia tidak terdaftar pada pemberitahuan ini.');
        }

        $belanja = collect($pemberitahuan->belanjas)->map(function ($item) {
            return [
                'uraian' => $item['uraian'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'],
            ];
        });

        return view('form.penawaran-harga', [
            'kegiatan' => $kegiatan,
            'pemberitahuan' => $pemberitahuan,
            'penyedia' => $penyedia,
            'belanja' => $belanja,
            'statusPemenang' => $kegiatan->statusPemenang(),
        ]);
    }

    public function store(
        PenawaranHargaRequest $request,
        StorePenawaranUseCase $storePenawaranUseCase,
    )
    {
        $validated = $request->validated();
        $pemberitahuan = $this->findTenantPemberitahuan((string) $validated['pemberitahuan_id'], [
            'kegiatan',
            'penawaran',
            'belanjas',
            'penyedias',
        ]);
        abort_if($pemberitahuan === null, 404);

        Validator::make(
            ['penyedia' => (string) $validated['penyedia']],
            ['penyedia' => ['required', 'in:' . implode(',', $pemberitahuan->selectedPenyediaIds())]],
            ['penyedia.in' => 'Penyedia tidak terdaftar pada pemberitahuan ini.']
        )->validate();

        $penawaran = $storePenawaranUseCase->execute(new StorePenawaranInput(
            pemberitahuanId: $pemberitahuan->id,
            penyediaId: $validated['penyedia'],
            tglSuratPenawaran: $validated['tgl_surat_penawaran'],
            noPenawaran: $validated['no_penawaran'],
            isWinner: $request->isWinner(),
            hargaSatuan: $validated['harga_satuan'],
        ));

        return redirect()->route('kegiatan.show', ['id' => $penawaran->kegiatan_id]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $kegiatanId, string $penyediaId)
    {
        $kegiatan = $this->findTenantKegiatan($kegiatanId, [
            'pemberitahuan.penyedias',
            'pemberitahuan.penawaran.hargaPenawaran',
            'pemberitahuan.belanjas',
        ]);
        abort_if($kegiatan === null, 404);

        $pemberitahuan = $kegiatan->pemberitahuan;
        $penyedia = $pemberitahuan
            ? $pemberitahuan->selectedPenyedias()->firstWhere('id', (string) $penyediaId)
            : null;

        if (!$pemberitahuan || !$penyedia) {
            return redirect()
                ->route('kegiatan.show', ['id' => $kegiatanId])
                ->with('error', 'Penyedia tidak terdaftar pada pemberitahuan ini.');
        }

        $penawaran = $pemberitahuan->penawaran->firstWhere('penyedia_id', $penyediaId);
        if (!$penawaran) {
            return redirect()
                ->route('kegiatan.show', ['id' => $kegiatanId])
                ->with('error', 'Penawaran untuk penyedia ini tidak ditemukan.');
        }

        $hargaSatuan = $penawaran->hargaPenawaran->sortBy('id')->pluck('harga_satuan')->values();
        $belanja = collect($pemberitahuan->belanjas)->map(function ($item, $key) use ($hargaSatuan) {
            return [
                'uraian' => $item['uraian'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'],
                'harga_satuan' => $hargaSatuan->get($key),
            ];
        });

        return view('form.penawaran-harga', [
            'kegiatan' => $kegiatan,
            'pemberitahuan' => $pemberitahuan,
            'penyedia' => $penyedia,
            'belanja' => $belanja,
            'penawaran' => $penawaran,
            'isEdit' => true,
        ]);
    }

    public function update(
        PenawaranHargaRequest $request,
        string $pemberitahuanId,
        UpdatePenawaranUseCase $updatePenawaranUseCase,
    )
    {
        $validated = $request->validated();
        $pemberitahuan = $this->findTenantPemberitahuan((string) $validated['pemberitahuan_id'], [
            'kegiatan',
            'penyedias',
        ]);
        abort_if($pemberitahuan === null, 404);

        Validator::make(
            ['penyedia' => (string) $validated['penyedia']],
            ['penyedia' => ['required', 'in:' . implode(',', $pemberitahuan->selectedPenyediaIds())]],
            ['penyedia.in' => 'Penyedia tidak terdaftar pada pemberitahuan ini.']
        )->validate();

        $penawaran = $updatePenawaranUseCase->execute(new UpdatePenawaranInput(
            pemberitahuanId: $pemberitahuan->id,
            penyediaId: $validated['penyedia'],
            tglSuratPenawaran: $validated['tgl_surat_penawaran'],
            noPenawaran: $validated['no_penawaran'],
            isWinner: $request->isWinner(),
            hargaSatuan: $validated['harga_satuan'],
        ));

        flash()->success('Penawaran berhasil diupdate.');

        return redirect()->route('kegiatan.show', ['id' => $penawaran->kegiatan_id]);
    }

    public function destroy(string $id)
    {
        $kegiatan = $this->findTenantKegiatan($id);
        abort_if($kegiatan === null, 404);

        $this->scopedPenawaranQuery()
            ->where('kegiatan_id', $kegiatan->id)
            ->get()
            ->each
            ->delete();

        flash()->success('Penawaran berhasil dihapus.');

        return redirect()->route('kegiatan.show', ['id' => $id]);
    }

    public function render(
        string $id,
        BuildPenawaranReportUseCase $buildPenawaranReportUseCase,
    )
    {
        abort_if($this->findTenantKegiatan($id) === null, 404);

        try {
            $report = $buildPenawaranReportUseCase->execute($id);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $pdf = Pdf::loadView('pdf.penawaran-harga', $report->toViewData());
        $filename = '2. PENAWARAN HARGA - (' . $report->kegiatan->kegiatan . ')';

        return $pdf->stream(sanitize_filename($filename) . '.pdf');
    }
}
