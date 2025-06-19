<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\Http\Request;
use App\Models\Pemberitahuan;
use App\Models\NegosiasiHarga;
use App\Models\PenawaranHarga;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PenawaranHargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
       
        
        return view('menu.penawaran');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $kegiatan = Kegiatan::with('pemberitahuan')->find($id);
        if (!$kegiatan) {
            noty()->error('Kegiatan tidak ditemukan');
            return redirect()->back();
        }
        
        $pemberitahuan = $kegiatan->pemberitahuan;
        if (!$pemberitahuan) {
            noty()->error('Tidak ada pemberitahuan terkait');
            return redirect()->back();
        }
        
        return view('form.penawaran-harga', [
            'kegiatan' => $kegiatan,
            'pemberitahuan' => $pemberitahuan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     
    $pemberitahuan = Pemberitahuan::with('kegiatan')->find($request->pemberitahuan_id);
    if (!$pemberitahuan || !$pemberitahuan->kegiatan) {
        noty()->error('gagal menyimpan');
        return redirect()->back();
    }
    $kegiatan_id = $pemberitahuan->kegiatan->id;
    $volume = $request->volume;
    $totalHarga = 0;
    for ($i = 0; $i < count($volume); $i++) {
        $totalHarga += $volume[$i] * $request->harga_satuan[$i];
    }

    $item_penawaran = [
        'uraian' => $request->uraian,
        'volume' => $request->volume,
        'satuan' => $request->satuan,
        'harga_satuan' => $request->harga_satuan,
    ];
  

        
        if($request->pemenang){
            PenawaranHarga::updateOrCreate([
                'kegiatan_id' => $kegiatan_id ,
            ], [
                'pemberitahuan_id' => $request->pemberitahuan_id ,
                'penyedia_1' => $request->id_penyedia,
                'tgl_penawaran_1' => Carbon::parse($request->tgl_surat_penawaran),
                'no_penawaran_1' => $request->no_penawaran,
                'harga_penawaran_1' => $totalHarga,
                'item_penawaran_1' => $item_penawaran
            ]);
            // NegosiasiHarga::updateOrCreate([
            //     'pemberitahuan_id' => $request->pemberitahuan_id ],
            //     [
            //     'id_penyedia' => $request->id_penyedia,                
            // ]);
                   
        }
        
        if(!$request->pemenang){
            PenawaranHarga::updateOrCreate([
                'kegiatan_id' => $kegiatan_id ,
            ],[   
                'pemberitahuan_id' => $request->pemberitahuan_id ,
                'penyedia_2' => $request->id_penyedia,             
                'tgl_penawaran_2' => Carbon::parse($request->tgl_surat_penawaran),
                'no_penawaran_2' => $request->no_penawaran,
                'harga_penawaran_2' => $totalHarga,
                'item_penawaran_2' => $item_penawaran
            ]);
        }
        
     return redirect()->route('menu.kegiatan');         

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kegiatan = Kegiatan::with('pemberitahuan', 'penawaran')->find($id);

        if (!$kegiatan) {
            flash()->error('Kegiatan tidak ditemukan.');
            return redirect()->back();
        }

        $penawaran = $kegiatan->penawaran;
        if (!$penawaran) {
            flash()->error('Penawaran tidak ditemukan.');
            return redirect()->back();
        }

        $pemberitahuan = $kegiatan->pemberitahuan;
        if (!$pemberitahuan) {
            flash()->error('Pemberitahuan tidak ditemukan.');
            return redirect()->back();
        }

        return view('form.penawaran-harga-edit', [
            'kegiatan' => $kegiatan,
            'penawaran' => $penawaran,
            'pemberitahuan' => $pemberitahuan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penawaran = PenawaranHarga::where('kegiatan_id', $id)->first();

        if (!$penawaran) {
            flash()->error('Penawaran tidak ditemukan.');
            return redirect()->back();
        }

        $volume = $request->volume;
        $totalHarga = 0;
        for ($i = 0; $i < count($volume); $i++) {
            $totalHarga += $volume[$i] * $request->harga_satuan[$i];
        }

        $item_penawaran = [
            'uraian' => $request->uraian,
            'volume' => $request->volume,
            'satuan' => $request->satuan,
            'harga_satuan' => $request->harga_satuan,
        ];

        if ($request->pemenang) {
            $penawaran->update([
                'penyedia_1' => $request->id_penyedia,
                'tgl_penawaran_1' => \Carbon\Carbon::parse($request->tgl_surat_penawaran),
                'no_penawaran_1' => $request->no_penawaran,
                'harga_penawaran_1' => $totalHarga,
                'item_penawaran_1' => $item_penawaran
            ]);
            flash()->success('Penawaran pemenang berhasil diperbarui.');
        } else {
            $penawaran->update([
                'penyedia_2' => $request->id_penyedia,
                'tgl_penawaran_2' => \Carbon\Carbon::parse($request->tgl_surat_penawaran),
                'no_penawaran_2' => $request->no_penawaran,
                'harga_penawaran_2' => $totalHarga,
                'item_penawaran_2' => $item_penawaran
            ]);
            flash()->success('Penawaran pembanding berhasil diperbarui.');
        }

        return redirect()->route('menu.kegiatan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penawaran = PenawaranHarga::where('kegiatan_id', $id)->first();

        if (!$penawaran) {
            flash()->error('Penawaran tidak ditemukan.');
            return redirect()->back();
        }

        $penawaran->delete();

        flash()->success('Penawaran berhasil dihapus.');
        return redirect()->route('menu.kegiatan');
    }
    public function render(string $id)
    {
        
            $kegiatan = Kegiatan::with('pemberitahuan' , 'penawaran')->find($id);
            if (!$kegiatan) {
                flash()->error('Kegiatan tidak ditemukan');
                return back();
            }       
    
        
            $penawaran = $kegiatan->penawaran;        
            if(!$penawaran){
                flash()->error('Tidak ada penawaran yang relevan');
                return back();
            }
                            
            $penyedia1 = Penyedia::find($penawaran->penyedia_1);
            if(!$penyedia1){
                flash()->error('Penyedia yang ditunjuk belum diset');
                return back();
            }
            $penyedia2 = Penyedia::find($penawaran->penyedia_2);
            if(!$penyedia2){
                flash()->error('Penyedia pembanding belum diset');
                return back();
            }
            $pemberitahuan = $kegiatan->pemberitahuan; 
            if(!$pemberitahuan){
                flash()->error('Tidak ada pemberitahuan yang relevan');
                return back();
            }
            
                
            $jumlah = $penawaran->harga_penawaran_1;
            $jumlah_2 = $penawaran->harga_penawaran_2;
            $item = $penawaran->item_penawaran_1;
            $item_2 = $penawaran->item_penawaran_2;
            $pdf = Pdf::loadView('pdf.penawaran-harga', [
                'penawaran' => $penawaran,
                'kegiatan' => $kegiatan,
                'penyedia1' => $penyedia1,
                'penyedia2' => $penyedia2,
                'jumlah' => $jumlah,
                'jumlah_2' => $jumlah_2,
                'pemberitahuan' => $pemberitahuan,
                'item' => $item,
                'item_2' => $item_2
            ]);
            // Replace invalid filename characters with underscore
            $filename = '2. PENAWARAN HARGA - (' . $kegiatan->kegiatan . ')';
            $filename = preg_replace('/[\/\\\\\?\%\*\:\|\"<>\.]/', '_', $filename);

            return $pdf->stream($filename . '.pdf');
       
    }
}