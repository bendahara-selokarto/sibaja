<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithTenantScope;
use App\Http\Requests\NegosiasiRequest;
use App\Models\NegosiasiHarga;
use App\UseCases\Negosiasi\BuildNegosiasiReportUseCase;
use App\UseCases\Negosiasi\StoreNegosiasiInput;
use App\UseCases\Negosiasi\StoreNegosiasiUseCase;
use App\UseCases\Negosiasi\UpdateNegosiasiInput;
use App\UseCases\Negosiasi\UpdateNegosiasiUseCase;
use Barryvdh\DomPDF\Facade\Pdf;
use DomainException;
use Illuminate\Support\Carbon;

class NegosiasiHargaController extends Controller
{
    use InteractsWithTenantScope;

    public function index()
    {
        //
    }

    public function create($id)
    {
        $kegiatan = $this->findTenantKegiatan($id, [
            'negosiasiHarga',
            'penawaran.hargaPenawaran',
            'pemberitahuan.belanjas',
        ]);
        abort_if($kegiatan === null, 404);

        $penawaran = $kegiatan->penawaran->firstWhere('is_winner', true);
        if (!$penawaran) {
            return back()->with('error', 'PEMENANG belum di set');
        }

        if (!$kegiatan->pemberitahuan) {
            return back()->with('error', 'Pemberitahuan belum dibuat');
        }

        $belanja = $kegiatan->pemberitahuan->belanjas->sortBy('id')->values();
        $items = $penawaran->hargaPenawaran->map(function ($harga, $i) use ($belanja) {
            return [
                'uraian' => $belanja[$i]->uraian ?? null,
                'volume' => $belanja[$i]->volume ?? null,
                'satuan' => $belanja[$i]->satuan ?? null,
                'harga_penawaran' => $harga->harga_satuan ?? null,
                'jumlah' => ($belanja[$i]->volume ?? 0) * ($harga->harga_satuan ?? 0),
            ];
        });

        $kegiatan->tgl = Carbon::parse($kegiatan->pemberitahuan->tgl_surat_pemberitahuan)
            ->addDays(3)
            ->format('Y-m-d');

        return view('form.negosiasi', compact('kegiatan', 'items'));
    }

    public function store(
        NegosiasiRequest $request,
        StoreNegosiasiUseCase $storeNegosiasiUseCase,
    )
    {
        $validated = $request->validated();
        $kegiatan = $this->findTenantKegiatan((string) $validated['kegiatan_id'], [
            'penawaran',
            'pemberitahuan',
        ]);
        abort_if($kegiatan === null, 404);

        $negosiasi = $storeNegosiasiUseCase->execute(new StoreNegosiasiInput(
            kegiatanId: $kegiatan->id,
            tglPersetujuan: $validated['tgl_persetujuan'],
            tglNegosiasi: $validated['tgl_negosiasi'],
            tglAkhirPerjanjian: $validated['tgl_akhir_perjanjian'],
            hargaSatuanNegosiasi: $validated['harga_satuan_negosiasi'],
        ));

        return redirect()->route('kegiatan.show', ['id' => $negosiasi->kegiatan_id])
            ->with('success', 'berhasil menambahkan data');
    }

    public function show(NegosiasiHarga $negosiasiHarga)
    {
        //
    }

    public function edit($kegiatan_id)
    {
        $kegiatan = $this->findTenantKegiatan($kegiatan_id, [
            'pemberitahuan.belanjas',
            'negosiasiHarga.hargaNegosiasi',
            'penawaran.hargaPenawaran',
        ]);
        abort_if($kegiatan === null, 404);

        $pemberitahuan = $kegiatan->pemberitahuan;
        $penawaranHarga = $kegiatan->penawaran->firstWhere('is_winner', true);
        $negosiasi = $kegiatan->negosiasiHarga;

        $items = $pemberitahuan->belanjas->map(function ($item, $k) use ($penawaranHarga, $negosiasi) {
            return [
                'uraian' => $item->uraian,
                'volume' => $item->volume,
                'satuan' => $item->satuan,
                'harga_penawaran' => $penawaranHarga->hargaPenawaran[$k]->harga_satuan,
                'harga_negosiasi' => $negosiasi->hargaNegosiasi[$k]->harga_satuan,
                'jumlah_penawaran' => $item->volume * $penawaranHarga->hargaPenawaran[$k]->harga_satuan,
                'jumlah_negosiasi' => $item->volume * $negosiasi->hargaNegosiasi[$k]->harga_satuan,
            ];
        });

        $kegiatan->tgl = Carbon::parse($penawaranHarga->tgl_penawaran)->format('Y-m-d');
        $negosiasi->tgl_negosiasi = Carbon::parse($negosiasi->tgl_negosiasi)->format('Y-m-d');
        $negosiasi->tgl_persetujuan = Carbon::parse($negosiasi->tgl_persetujuan)->format('Y-m-d');
        $negosiasi->tgl_akhir_perjanjian = Carbon::parse($negosiasi->tgl_akhir_perjanjian)->format('Y-m-d');

        return view('form.negosiasi', compact('kegiatan', 'items', 'negosiasi'));
    }

    public function update(
        NegosiasiRequest $request,
        $kegiatan_id,
        UpdateNegosiasiUseCase $updateNegosiasiUseCase,
    )
    {
        $validated = $request->validated();
        $kegiatan = $this->findTenantKegiatan((string) $validated['kegiatan_id'], [
            'pemberitahuan',
            'negosiasiHarga',
        ]);
        abort_if($kegiatan === null, 404);

        $negosiasi = $updateNegosiasiUseCase->execute(new UpdateNegosiasiInput(
            kegiatanId: $kegiatan->id,
            tglPersetujuan: $validated['tgl_persetujuan'],
            tglNegosiasi: $validated['tgl_negosiasi'],
            tglAkhirPerjanjian: $validated['tgl_akhir_perjanjian'],
            hargaSatuanNegosiasi: $validated['harga_satuan_negosiasi'],
        ));

        return redirect()->route('kegiatan.show', ['id' => $negosiasi->kegiatan_id])
            ->with('success', 'berhasil memperbarui data');
    }

    public function destroy($kegiatan_id)
    {
        $kegiatan = $this->findTenantKegiatan($kegiatan_id);
        abort_if($kegiatan === null, 404);

        $negosiasi = NegosiasiHarga::where('kegiatan_id', $kegiatan->id)->first();
        if (!$negosiasi) {
            return redirect()->back()->with('error', 'Negosiasi tidak ditemukan');
        }

        $negosiasi->delete();

        return redirect()->back()->with('success', 'Negosiasi berhasil dihapus');
    }

    public function renderPDF(
        $id,
        BuildNegosiasiReportUseCase $buildNegosiasiReportUseCase,
    )
    {
        abort_if($this->findTenantKegiatan((string) $id) === null, 404);

        try {
            $report = $buildNegosiasiReportUseCase->execute($id);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $pdf = Pdf::loadView('pdf.negosiasi-harga', [
            'data' => $report->toViewData(),
        ]);

        $filename = '3. NEGOSIASI HARGA - (' . $report->kegiatan->kegiatan . ')';
        $filename = preg_replace('/[\/\\\\\?\%\*\:\|\"<>\.]/', '_', $filename);

        return $pdf->stream($filename . '.pdf');
    }
}
