<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use App\Models\Pemberitahuan;
use App\Http\Requests\PemberitahuanRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemberitahuanController extends Controller
{
    public function index()
    {
        return redirect()->route('menu.kegiatan');
    }

    
    public function create($id)
    {
        $user = Auth::user();
        $penyedia = $user->penyedias()->get();     
        $kegiatan = Kegiatan::find($id);
        $nomor = Pemberitahuan::where('kode_desa', Auth::user()->kode_desa)->count() + 1;
        
        return view('form.pemberitahuan', [
            'kegiatan' => $kegiatan, 
            'penyedia' => $penyedia, 
            'no_pbj' => $nomor,
            'pemberitahuan' => null,
            'belanja' => collect([['nomor' => 1, 'uraian' => '', 'volume' => '', 'satuan' => '']]),
            'penyediaTerpilih' => []
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PemberitahuanRequest $request)
    {
        $belanja = $request->belanjaItems();

        DB::transaction(function () use ($request, $belanja) {
            $pemberitahuan = Pemberitahuan::create($request->pemberitahuanPayload());
            $pemberitahuan->belanjas()->createMany($belanja->all());
        });

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
    public function update(PemberitahuanRequest $request, string $id)
    {
        $pemberitahuan = Pemberitahuan::findOrFail($id);
        $belanja = $request->belanjaItems();

        DB::transaction(function () use ($pemberitahuan, $request, $belanja) {
            $pemberitahuan->update($request->pemberitahuanPayload());
            $pemberitahuan->belanjas()->delete();
            $pemberitahuan->belanjas()->createMany($belanja->all());
        });

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
