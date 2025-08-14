<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemberitahuan;
use Barryvdh\DomPDF\Facade\Pdf;
use function PHPUnit\Framework\isEmpty;

class KegiatanController extends Controller
{
    /**
     * Menampilkan menu Penyedia
     */
    public function index(): View
    {
        $kegiatan = Kegiatan::where('kode_desa', Auth::user()->kode_desa)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($kegiatan->isEmpty()) {
            // Jika tidak ada kegiatan, langsung render tanpa data tambahan
            return view('menu.kegiatan')->with('kegiatans', collect());
        }
       
        return view('menu.kegiatan')->with('kegiatans', $kegiatan);           
    }

    public function create()
    {
        $penyedia = new Penyedia();

        if($penyedia->count() == 0){
            session()->flash('error', 'Belum ada penyedia');
            return redirect()->route('menu.penyedia');
        }

        $kegiatan = new Kegiatan();
        return view('form.kegiatan', compact('kegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'rekening_apbdes' => 'required|string',
                'kegiatan' => 'required|string',
                'lokasi_kegiatan' => 'required|string',
                'ketua_tpk' => 'required|string',
                'sekretaris_tpk' => 'required|string',
                'anggota_tpk' => 'required|string',
                'pka' => 'required|string',
            ]);
        
            Kegiatan::create($validatedData);
            noty()->success('berhasil ditambahkan');
            
        } catch (\Throwable $th) {
            noty()->error($th->getMessage());
        }
        return redirect()->route('menu.kegiatan');
    }
    
    /**
     * Display the specified resource.
    */
    public function show(string $id)
    {
        $kegiatan = Kegiatan::with('pemberitahuan' , 'penawaran' , 'negosiasiHarga' )->find($id);
        
        $btn = [];

        if($kegiatan->pemberitahuan && $kegiatan->pemberitahuan->count() > 0){
        
            $pemberitahuanId = $kegiatan->pemberitahuan->id;

            $pemberitahuan = Pemberitahuan::with('kegiatan', 'penawaran' , 'belanjas' )->find($pemberitahuanId);
    
            $penyedias = $pemberitahuan->penyedia;

            $kegiatan_id = $id;

            $penawaran =  $pemberitahuan->penawaran;
        
                if($penawaran){
                    $ids = $pemberitahuan->penawaran->pluck('penyedia_id')
                    ->flatten()
                    ->unique()
                    ->toArray();
                    $not_in = array_diff($penyedias, $ids);
                    $penyedia = Penyedia::whereIn('id', $not_in)->get();           
                    
                }

                $btn['penawaran-create'] = ($penawaran && $penawaran->count() > 0);
                $btn['penawaran-render'] = ($penawaran && $penawaran->count() > 1);
                $btn['negosiasi-create'] = ($btn['penawaran-render'] && $kegiatan->negosiasiHarga == null );
                $btn['negosiasi-render'] = ($kegiatan->negosiasiHarga && $kegiatan->negosiasiHarga->count() > 0);
                $btn['pembayaran-create'] = ($btn['negosiasi-render'] && $kegiatan->pembayaran == null);
                $btn['pembayaran-render'] = ($kegiatan->pembayaran && $kegiatan->pembayaran->count() > 0);
           
            }

          

        return view('menu.kegiatan-detail')
        ->with('kegiatan', $kegiatan)
        ->with('btn' ,$btn)
        ->with('pemberitahuan', $pemberitahuan ?? collect())
        ->with('penawaran' , $penawaran ?? collect())
        ->with('penyedia', $penyedia ?? collect());
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kegiatan = Kegiatan::find($id);
        if (!$kegiatan) {
            flash()->error('kegiatan tidak ditemukan');
            return redirect()->route('menu.kegiatan');
        }
        return view('form.kegiatan', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'rekening_apbdes' => 'required|string',
                'kegiatan' => 'required|string',
                'lokasi_kegiatan' => 'required|string',
                'ketua_tpk' => 'required|string',
                'sekretaris_tpk' => 'required|string',
                'anggota_tpk' => 'required|string',
                'pka' => 'required|string',
            ]);

            $kegiatan = Kegiatan::find($id);
            $kegiatan->update($validatedData);
            noty()->success('terupdate');
            
        } catch (\Throwable $th) {
            noty()->error($th->getMessage());
        }


        return redirect()->route('menu.kegiatan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kegiatan = Kegiatan::find($id);
        if (!$kegiatan) {
            flash()->error('kegiatan tidak ditemukan');
            return redirect()->route('menu.kegiatan');
        }

        if ($kegiatan->pemberitahuan && $kegiatan->pemberitahuan->count() > 0) {
            flash()->error('kegiatan sudah memiliki pemberitahuan');
            return back();
        }
            // $kegiatan->delete();
            $deleted = Kegiatan::where('id', $id)->delete();
            flash()->success('kegiatan berhasil diahpus');
       
        return redirect()->route('menu.kegiatan');
    }

    public function rekap(string $id){
        $kegiatan = Kegiatan::with('pemberitahuan' , 'penawaran' , 'negosiasiHarga' , 'pembayaran' )->find($id);

        $pemberitahuan = $kegiatan->pemberitahuan;

        $penawaran = $kegiatan->penawaran;

        $namaPenyedia1 = optional(
            Penyedia::find(optional($kegiatan->penawaran->firstWhere('is_winner', true))->penyedia_id)
        )->nama_penyedia;

        $namaPenyedia2 = optional(
            Penyedia::find(optional($kegiatan->penawaran->firstWhere('is_winner', false))->penyedia_id)
        )->nama_penyedia;

        $negosiasiHarga = $kegiatan->negosiasiHarga;

        $pembayaran = $kegiatan->pembayaran;

        $pdf = Pdf::loadView('pdf.rekap' , compact('kegiatan', 'pemberitahuan' , 'penawaran','namaPenyedia1' , 'namaPenyedia2' , 'negosiasiHarga' , 'pembayaran'));
        
        return $pdf->stream();
       
    }
}
