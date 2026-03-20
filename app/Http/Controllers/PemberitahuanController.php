<?php

namespace App\Http\Controllers;

use App\Data\Pemberitahuan\PrepareCreatePemberitahuanData;
use App\Models\Kegiatan;
use App\Models\Penyedia;
use App\Models\Pemberitahuan;
use App\Http\Requests\PemberitahuanRequest;
use App\UseCases\Pemberitahuan\PrepareCreatePemberitahuanInput;
use App\UseCases\Pemberitahuan\PrepareCreatePemberitahuanUseCase;
use App\UseCases\Pemberitahuan\UpsertPemberitahuanInput;
use App\UseCases\Pemberitahuan\UpsertPemberitahuanUseCase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PemberitahuanController extends Controller
{
    public function index()
    {
        return redirect()->route('menu.kegiatan');
    }

    
    public function create(
        $id,
        PrepareCreatePemberitahuanUseCase $prepareCreatePemberitahuanUseCase,
    )
    {
        $result = $prepareCreatePemberitahuanUseCase->execute(
            new PrepareCreatePemberitahuanInput(
                kegiatanId: $id,
                kodeDesa: Auth::user()->kode_desa,
            )
        );

        $viewData = new PrepareCreatePemberitahuanData(
            kegiatan: $result->kegiatan,
            penyedia: $result->penyedia,
            nomorPbJ: $result->noPbJ,
        );

        return view('form.pemberitahuan', $viewData->toViewData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        PemberitahuanRequest $request,
        UpsertPemberitahuanUseCase $upsertPemberitahuanUseCase,
    )
    {
        $validated = $request->validated();

        $upsertPemberitahuanUseCase->execute(new UpsertPemberitahuanInput(
            pemberitahuanId: null,
            kegiatanId: $validated['kegiatan_id'],
            rekeningApbdes: $validated['rekening_apbdes'],
            penyediaIds: array_values($validated['penyedia']),
            noPbj: $validated['no_pbj'],
            tglPemberitahuan: $validated['tgl_pemberitahuan'],
            belanjaItems: $request->belanjaItems(),
        ));

        return redirect()->route('kegiatan.show', $request->validated('kegiatan_id'));
    }
     
    public function edit(string $id)
    {
        $pemberitahuan = Pemberitahuan::with('belanjas')->find($id);
        if (!$pemberitahuan) {
            noty()->error('Pemberitahuan tidak ditemukan.');
            return redirect()->back();
        }
        $penyediaTerpilih = $pemberitahuan->penyedia;
        $penyedia = Auth::user()->penyedias()->get();
        
        $kegiatan = Kegiatan::find($pemberitahuan->kegiatan_id);
        if (!$kegiatan) {
            noty()->error('Kegiatan tidak ditemukan.');
            return redirect()->back();
        }
        $belanja = $pemberitahuan->belanjas;
        
        return view('form.pemberitahuan', [
            'pemberitahuan' => $pemberitahuan, 
            'penyedia' => $penyedia, 'kegiatan' => $kegiatan, 
            'penyediaTerpilih' => $penyediaTerpilih, 
            'belanja' => $belanja]);
        }
        
    /**
     * Update the specified resource in storage.
     */
    public function update(
        PemberitahuanRequest $request,
        string $id,
        UpsertPemberitahuanUseCase $upsertPemberitahuanUseCase,
    )
    {
        $validated = $request->validated();

        $upsertPemberitahuanUseCase->execute(new UpsertPemberitahuanInput(
            pemberitahuanId: $id,
            kegiatanId: $validated['kegiatan_id'],
            rekeningApbdes: $validated['rekening_apbdes'],
            penyediaIds: array_values($validated['penyedia']),
            noPbj: $validated['no_pbj'],
            tglPemberitahuan: $validated['tgl_pemberitahuan'],
            belanjaItems: $request->belanjaItems(),
        ));

        return redirect()->route('kegiatan.show', ['id' => $request->validated('kegiatan_id')]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemberitahuan = Pemberitahuan::where('kegiatan_id', $id)->first();
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
           
            $pemberitahuan = Pemberitahuan::with('belanjas')->where('kegiatan_id', $id)->first();
            if (!$pemberitahuan) {
                noty()->error('Pemberitahuan tidak ditemukan.');
                return redirect()->back();
            }
           
            $kegiatan = Kegiatan::with('pemberitahuan')->find($id);
            if (!$kegiatan) {
                noty()->error('Kegiatan tidak ditemukan.');
                return redirect()->back();
            }         
            $belanja = $pemberitahuan->belanjas;
            // dd($belanja);

            $pdf = Pdf::loadView('pdf.pemberitahuan', ['pemberitahuan' => $pemberitahuan, 'kegiatan' => $kegiatan , 'belanja' => $belanja] );

            if (!$pdf) {
                flash()->error('Gagal membuat PDF.');
                return redirect()->back();
            }

            // Replace all invalid filename characters with underscore
            $safeKegiatan = preg_replace('/[\/\\\:\*\?"<>\|]/', '_', $kegiatan->kegiatan);
            return $pdf->stream('1. PEMBERITAHUAN- (' . $safeKegiatan . ').pdf');
        }
}
