<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
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
    public function edit(Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        //
    }

    public function render($id){
        $kegiatan = Kegiatan::find($id);
        $id_penyedia = $kegiatan->penawaran->penyedia_1;
        if(!$id_penyedia){
            flash()->error('belum ada penyedia ditunjuk');
            return redirect()->back();
        }
        $penyedia = Penyedia::find($id_penyedia);

        try {
            $tgl_pembayaran = $kegiatan->pembayaran->tgl_pembayaran_cms;
            $item = $kegiatan->penawaran->item_penawaran_1;
        } catch (\Exception $e) {
            flash()->error('belum ada pembayaran');
            return redirect()->back();
        }
        


        $pdf = Pdf::loadView('pdf.pembayaran.kwitansi', compact('kegiatan', 'penyedia' , 'item'));
        return $pdf->stream();
    }
}
