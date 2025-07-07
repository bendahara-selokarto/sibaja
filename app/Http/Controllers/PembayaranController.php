<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {   
        $kegiatan = Kegiatan::find($id);        
        return view ('form.pembayaran', compact('kegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pembayaran = new Pembayaran([
            'kegiatan_id' => $request->kegiatan_id,
            'tgl_pembayaran_cms' => $request->tgl_pembayaran_cms,
        ]);
        $pembayaran->save();
       
        return redirect()->route('menu.kegiatan');

    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pembayaran = Kegiatan::with('pembayaran')->find($id);
        if (!$pembayaran || !$pembayaran->pembayaran) {
            return redirect()->route('menu.kegiatan')->with('error', 'Pembayaran tidak ditemukan.');
        }
        $kegiatan = Kegiatan::find($id);
        if (!$kegiatan) {
            return redirect()->route('menu.kegiatan')->with('error', 'Kegiatan tidak ditemukan.');
        }


       
        return view('form.pembayaran_edit', compact('pembayaran', 'kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::where('kegiatan_id', $id)->first();
        if (!$pembayaran) {
            return redirect()->route('menu.kegiatan')->with('error', 'Pembayaran tidak ditemukan.');
        }

        $pembayaran->tgl_pembayaran_cms = $request->tgl_pembayaran_cms;
        $pembayaran->save();

        return redirect()->route('menu.kegiatan')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kegiatan_id)
    {
        $pembayaran = Pembayaran::where('kegiatan_id', $kegiatan_id)->first();
        if ($pembayaran) {
            $pembayaran->delete();
            return redirect()->route('menu.kegiatan')->with('success', 'Pembayaran berhasil dihapus.');
        }
        return redirect()->route('menu.kegiatan')->with('error', 'Pembayaran tidak ditemukan.');
    }

    public function render($id){
        $kegiatan = Kegiatan::with('negosiasiHarga')->with('penawaran_1')->find($id);
        if (!$kegiatan) {
            flash()->error('Kegiatan tidak ditemukan');
            return redirect()->back();
        }
        $id_penyedia = $kegiatan->penawaran_1->penyedia_id;
        if(!$id_penyedia){
            flash()->error('belum ada penyedia ditunjuk');
            return redirect()->back();
        }
        $penyedia = Penyedia::find($id_penyedia);

        try {
            $tgl =  Carbon::parse($kegiatan->pembayaran->tgl_pembayaran_cms);
            $items = $kegiatan->negosiasiHarga->item;
            $item = json_decode($items, true);
            if (!$item) {
                flash()->error('belum ada item');
                return redirect()->back();
            };
        } catch (\Exception $e) {
            flash()->error('belum ada pembayaran');
            return redirect()->back();
        }
        $negosiasiHarga = $kegiatan->negosiasiHarga;
        $negosiasiHarga->ppn = $negosiasiHarga->harga_negosiasi *  config('pajak.ppn') ;
        $negosiasiHarga->pph_22 = $negosiasiHarga->harga_negosiasi *  config('pajak.pph_22') ;
        $negosiasiHarga->total = $negosiasiHarga->harga_negosiasi + $negosiasiHarga->ppn + $negosiasiHarga->pph_22;
                


        $kegiatan->nomor = $kegiatan->pemberitahuan->no_pbj;
        $pdf = Pdf::loadView('pdf.pembayaran.kuitansi', compact('kegiatan', 'penyedia' , 'item', 'tgl'));
        $filename = '4. PEMBAYARAN - (' . $kegiatan->kegiatan . ')';
        // Replace invalid filename characters with underscore
        $filename = preg_replace('/[\/\\\\\?\%\*\:\|\"<>\.]/', '_', $filename);
        return $pdf->stream($filename . '.pdf');
    }
}
