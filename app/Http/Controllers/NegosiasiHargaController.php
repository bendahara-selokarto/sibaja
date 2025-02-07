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

class NegosiasiHargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
       $kegiatan = Kegiatan::find($id);
       if (!$kegiatan) {
           return redirect()->back()->with('error', 'Data not found');
       }
       return view('form.negosiasi', compact('kegiatan'));
    }
  
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $kegiatan = Kegiatan::find($request->kegiatan_id);
        $pemberitahuan = $kegiatan->pemberitahuan->first();
        if (!$pemberitahuan) {
            return redirect()->back()->with('error', 'Pemberitahuan not found');
        }
        $tgl = Carbon::parse($pemberitahuan->tgl_surat_pemberitahuan);
        NegosiasiHarga::updateOrCreate( ['kegiatan_id' => $request->kegiatan_id,],[
            'rekening_apbdes' =>$kegiatan->rekening_apbdes,
            'tgl_persetujuan' => (clone $tgl)->modify('+3 days'), 
            'tgl_negosiasi' => (clone $tgl)->modify('+5 days'),
            'tgl_perjanjian' => (clone $tgl)->modify('+6 days'),
            'tgl_akhir_perjanjian' => (clone $tgl)->modify('+30 days'),
            'harga_negosiasi' => $request->harga_negosiasi,
        ]);

        return redirect()->route('menu.kegiatan')->with('success', 'berhasil menambahkan data');



    }

    /**
     * Display the specified resource.
     */
    public function show(NegosiasiHarga $negosiasiHarga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NegosiasiHarga $negosiasiHarga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NegosiasiHarga $negosiasiHarga)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NegosiasiHarga $negosiasiHarga)
    {
        //
    }

    /**
     * Generate a PDF for the specified negotiation price and notification.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\NegosiasiHarga $negosiasiHarga
     * @param \App\Models\Pemberitahuan $pemberitahuan
     * @return \Illuminate\Http\Response
     */
    public function renderPDF($id)
    {
        $nomor_surat =  '/' .Auth::user()->kode_desa . '/' . Auth::user()->tahun_anggaran ;
        
        $kegiatan = Kegiatan::find( $id);
        $pemberitahuan = $kegiatan->pemberitahuan;
        $penawaranHarga = $kegiatan->penawaran;
        $negosiasiHarga  = $kegiatan->negosiasiHarga;
        $negosiasiHarga->tgl_persetujuan = Carbon::parse($negosiasiHarga->tgl_persetujuan);
        $item = $kegiatan->penawaran->item_penawaran_1;
        $negosiasiHarga->tgl_negosiasi = Carbon::parse($negosiasiHarga->tgl_negosiasi);
        $negosiasiHarga->tgl_perjanjian = Carbon::parse($negosiasiHarga->tgl_perjanjian);
        $negosiasiHarga->tgl_akhir_perjanjian = Carbon::parse($negosiasiHarga->tgl_perjanjian)->addDays(30);
        $pemberitahuan->no_spk = $pemberitahuan->no_pbj . '/SPK' . $nomor_surat;
        $pemberitahuan->no_ba_negosiasi = $pemberitahuan->no_pbj . '/BA-NEGO' . $nomor_surat;
        $pemberitahuan->no_perjanjian = $pemberitahuan->no_pbj . '/PERJ' . $nomor_surat;
        $negosiasiHarga->jumlah_hari_kerja = $negosiasiHarga->tgl_akhir_perjanjian->diffInDays($negosiasiHarga->tgl_perjanjian) * -1;
        $penyedia = Penyedia::find($penawaranHarga->penyedia_1)->first();
        
        $pdf = Pdf::loadView('pdf.negosiasi-harga', compact('pemberitahuan', 'kegiatan', 'negosiasiHarga', 'penyedia', 'penawaranHarga', 'item'));
        return $pdf->stream();
    }
}