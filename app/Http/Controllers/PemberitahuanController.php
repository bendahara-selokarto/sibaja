<?php

namespace App\Http\Controllers;

use App\Data\Pemberitahuan\PrepareCreatePemberitahuanData;
use App\Http\Controllers\Concerns\InteractsWithTenantScope;
use App\Http\Requests\PemberitahuanRequest;
use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\UseCases\Pemberitahuan\PrepareCreatePemberitahuanInput;
use App\UseCases\Pemberitahuan\PrepareCreatePemberitahuanUseCase;
use App\UseCases\Pemberitahuan\UpsertPemberitahuanInput;
use App\UseCases\Pemberitahuan\UpsertPemberitahuanUseCase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PemberitahuanController extends Controller
{
    use InteractsWithTenantScope;

    public function index()
    {
        return redirect()->route('menu.kegiatan');
    }

    public function create(
        $id,
        PrepareCreatePemberitahuanUseCase $prepareCreatePemberitahuanUseCase,
    )
    {
        $kegiatan = $this->findTenantKegiatan((string) $id);
        abort_if($kegiatan === null, 404);

        $result = $prepareCreatePemberitahuanUseCase->execute(
            new PrepareCreatePemberitahuanInput(
                kegiatanId: $kegiatan->id,
                kodeDesa: Auth::user()->kode_desa,
            )
        );

        $viewData = new PrepareCreatePemberitahuanData(
            kegiatan: $result->kegiatan,
            penyedia: Auth::user()->penyedias()->select('penyedias.nama_penyedia', 'penyedias.id')->get(),
            nomorPbJ: $result->noPbJ,
        );

        return view('form.pemberitahuan', $viewData->toViewData());
    }

    public function store(
        PemberitahuanRequest $request,
        UpsertPemberitahuanUseCase $upsertPemberitahuanUseCase,
    )
    {
        $validated = $request->validated();
        $kegiatan = $this->findTenantKegiatan((string) $validated['kegiatan_id']);
        abort_if($kegiatan === null, 404);

        $upsertPemberitahuanUseCase->execute(new UpsertPemberitahuanInput(
            pemberitahuanId: null,
            kegiatanId: $kegiatan->id,
            rekeningApbdes: $validated['rekening_apbdes'],
            penyediaIds: array_values($validated['penyedia']),
            noPbj: $validated['no_pbj'],
            tglPemberitahuan: $validated['tgl_pemberitahuan'],
            belanjaItems: $request->belanjaItems(),
        ));

        return redirect()->route('kegiatan.show', $kegiatan->id);
    }

    public function edit(string $id)
    {
        $pemberitahuan = $this->findTenantPemberitahuan($id, ['belanjas', 'penyedias']);
        abort_if($pemberitahuan === null, 404);

        $kegiatan = $this->findTenantKegiatan((string) $pemberitahuan->kegiatan_id);
        abort_if($kegiatan === null, 404);

        return view('form.pemberitahuan', [
            'pemberitahuan' => $pemberitahuan,
            'penyedia' => Auth::user()->penyedias()->select('penyedias.nama_penyedia', 'penyedias.id')->get(),
            'kegiatan' => $kegiatan,
            'penyediaTerpilih' => $pemberitahuan->selectedPenyediaIds(),
            'belanja' => $pemberitahuan->belanjas,
        ]);
    }

    public function update(
        PemberitahuanRequest $request,
        string $id,
        UpsertPemberitahuanUseCase $upsertPemberitahuanUseCase,
    )
    {
        $validated = $request->validated();
        $pemberitahuan = $this->findTenantPemberitahuan($id);
        abort_if($pemberitahuan === null, 404);

        $kegiatan = $this->findTenantKegiatan((string) $validated['kegiatan_id']);
        abort_if($kegiatan === null || (string) $pemberitahuan->kegiatan_id !== (string) $kegiatan->id, 404);

        $upsertPemberitahuanUseCase->execute(new UpsertPemberitahuanInput(
            pemberitahuanId: $pemberitahuan->id,
            kegiatanId: $kegiatan->id,
            rekeningApbdes: $validated['rekening_apbdes'],
            penyediaIds: array_values($validated['penyedia']),
            noPbj: $validated['no_pbj'],
            tglPemberitahuan: $validated['tgl_pemberitahuan'],
            belanjaItems: $request->belanjaItems(),
        ));

        return redirect()->route('kegiatan.show', ['id' => $kegiatan->id]);
    }

    public function destroy(string $id)
    {
        $kegiatan = $this->findTenantKegiatan($id);
        abort_if($kegiatan === null, 404);

        $pemberitahuan = $this->scopedPemberitahuanQuery()->where('kegiatan_id', $kegiatan->id)->first();
        if ($pemberitahuan) {
            $pemberitahuan->delete();
            noty()->success('Pemberitahuan berhasil dihapus.');
        } else {
            noty()->error('Pemberitahuan tidak ditemukan.');
        }

        return redirect()->route('kegiatan.show', ['id' => $id]);
    }

    public function render(string $id)
    {
        $kegiatan = $this->findTenantKegiatan($id, ['pemberitahuan']);
        abort_if($kegiatan === null, 404);

        $pemberitahuan = $this->scopedPemberitahuanQuery(['belanjas', 'penyedias'])
            ->where('kegiatan_id', $kegiatan->id)
            ->first();
        abort_if($pemberitahuan === null, 404);

        $pdf = Pdf::loadView('pdf.pemberitahuan', [
            'pemberitahuan' => $pemberitahuan,
            'kegiatan' => $kegiatan,
            'belanja' => $pemberitahuan->belanjas,
        ]);

        $safeKegiatan = preg_replace('/[\/\\\:\*\?"<>\|]/', '_', $kegiatan->kegiatan);

        return $pdf->stream('1. PEMBERITAHUAN- (' . $safeKegiatan . ').pdf');
    }
}
