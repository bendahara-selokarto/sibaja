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
    public function index()
    {
       
    }
  
    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $kegiatan = Kegiatan::with('negosiasiHarga')->find($id);
        if($kegiatan->negosiasiHarga){
            return redirect()->back()->with('warning', 'Negosiasi sudah ada, klik ubah untuk mengubah data');
        };
        $kegiatan = Kegiatan::with('penawaran_1')->find($id);    
       if (!$kegiatan) {
           return redirect()->back()->with('error', 'Data not found');
       }
       $kegiatan->tgl = Carbon::parse($kegiatan->penawaran_1->tgl_penawaran)->format('Y-m-d');
       $kegiatan->harga_penawaran = $kegiatan->penawaran_1->harga_penawaran;
       $item_penawaran = $kegiatan->penawaran_1->item;
    //    dd($item_penawaran);
       return view('form.negosiasi', compact('kegiatan', 'item_penawaran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $kegiatan = Kegiatan::with('penawaran_1')->find($request->kegiatan_id);
        if (!$kegiatan) {
            return redirect()->back()->with('error', 'Kegiatan not found');
        };
        $request->validate([
            'tgl_negosiasi' => 'required|date',
            'tgl_persetujuan' => 'required|date',
            'tgl_akhir_perjanjian' => 'required|date',
            'harga_satuan_negosiasi' => 'required|array',
            'harga_satuan_negosiasi.*' => 'required|numeric|min:0',
        ]);
        

        
        $item_negosiasi =$request->harga_satuan_negosiasi; 

        $item_penawaran = $kegiatan->penawaran_1->item;
        $item_penawaran['harga_negosiasi'] = $item_negosiasi;
        $item = $item_penawaran;

    $volume = $item['volume'];
    $totalHargaNegosiasi = 0;
    for ($i = 0; $i < count($volume); $i++) {
        $totalHargaNegosiasi += $volume[$i] * $item['harga_negosiasi'][$i];
    }
        
        $pemberitahuan = $kegiatan->pemberitahuan->first();
        if (!$pemberitahuan) {
            return redirect()->back()->with('error', 'Pemberitahuan not found');
        }
        $tgl = Carbon::parse($pemberitahuan->tgl_surat_pemberitahuan);
        NegosiasiHarga::create( [
            'kegiatan_id' => $request->kegiatan_id,
            'rekening_apbdes' =>$kegiatan->rekening_apbdes,
            'tgl_persetujuan' => Carbon::parse($request->tgl_persetujuan),
            'tgl_negosiasi' => Carbon::parse($request->tgl_negosiasi),
            'tgl_perjanjian' => Carbon::parse($request->tgl_perjanjian),
            'tgl_akhir_perjanjian' => Carbon::parse($request->tgl_akhir_perjanjian),
            'harga_negosiasi' => $totalHargaNegosiasi,
            'item' => json_encode($item),
            'jumlah_total' => $totalHargaNegosiasi + ($totalHargaNegosiasi * config('pajak.ppn')) + ($totalHargaNegosiasi * config('pajak.pph_22'))
           
        ]);

        return redirect()->route('kegiatan.show', ['id' => $kegiatan->id])->with('success', 'berhasil menambahkan data');



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
    public function edit($kegiatan_id)
    {
        $kegiatan = Kegiatan::with(['penawaran', 'negosiasiHarga'])->find($kegiatan_id);
        if (!$kegiatan || !$kegiatan->negosiasiHarga) {
            return redirect()->back()->with('error', 'Data negosiasi tidak ditemukan');
        }
        $negosiasiHarga = $kegiatan->negosiasiHarga;
        return view('form.negosiasi_edit', compact('kegiatan', 'negosiasiHarga'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kegiatan_id)
    {
        $negosiasiHarga = NegosiasiHarga::where('kegiatan_id', $kegiatan_id)->first();
        if (!$negosiasiHarga) {
            return redirect()->back()->with('error', 'Negosiasi tidak ditemukan');
        }
        $kegiatan = Kegiatan::find($kegiatan_id);
        if (!$kegiatan) {
            return redirect()->back()->with('error', 'Kegiatan tidak ditemukan');
        }
        $request->validate([
            'tgl_persetujuan' => 'required|date',
            'tgl_negosiasi' => 'required|date',
            'tgl_perjanjian' => 'required|date',
            'tgl_akhir_perjanjian' => 'required|date',
            'harga_negosiasi' => 'required|numeric|min:0',
        ]);

        $negosiasiHarga->update([
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'tgl_persetujuan' => Carbon::parse($request->tgl_persetujuan),
            'tgl_negosiasi' => Carbon::parse($request->tgl_negosiasi),
            'tgl_perjanjian' => Carbon::parse($request->tgl_perjanjian),
            'tgl_akhir_perjanjian' => Carbon::parse($request->tgl_akhir_perjanjian),
            'harga_negosiasi' => $request->harga_negosiasi,
        ]);

        return redirect()->route('kegiatan.show', ['id' => $kegiatan->kegiatan_id])->with('success', 'Negosiasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kegiatan_id)
    {
        $negosiasi = NegosiasiHarga::where('kegiatan_id', $kegiatan_id)->first();
        if (!$negosiasi) {
            return redirect()->back()->with('error', 'Negosiasi tidak ditemukan');
        }
        $negosiasi->delete();
        return redirect()->back()->with('success', 'Negosiasi berhasil dihapus');
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
        
        $kegiatan = Kegiatan::with('penawaran_1')->with('pemberitahuan')->with('negosiasiHarga')->find( $id);
       
        if (!$kegiatan) {
            flash()->error('Kegiatan tidak ditemukan');
            return redirect()->back();
        };
        $pemberitahuan = $kegiatan->pemberitahuan;
        $penawaranHarga = $kegiatan->penawaran_1;
        $penawaranHarga->tgl_penawaran =  Carbon::parse($penawaranHarga->tgl_penawaran);
        $ppn = config('pajak.ppn');
        $pph_22 = config('pajak.pph_22');
        $nilai_ppn = $penawaranHarga->nilai_penawaran * $ppn;
        $nilai_pph_22 = $penawaranHarga->nilai_penawaran * $pph_22;
        $penawaranHarga->ppn = $nilai_ppn;
        $penawaranHarga->pph_22 = $nilai_pph_22;
        $penawaranHarga->harga_total = floor($penawaranHarga->nilai_penawaran + $nilai_ppn + $nilai_pph_22); 
        
        $nilai_total_penawaran = $penawaranHarga->nilai_penawaran + ($penawaranHarga->nilai_penawaran * config('pajak.ppn')) + ($penawaranHarga->nilai_penawaran * config('pajak.pph_22'));
        $negosiasiHarga  = $kegiatan->negosiasiHarga ;
        if(!$pemberitahuan){
            flash()->error('pemberitahuna belum diset');
            return redirect()->back();
        };
        if(!$penawaranHarga){
            flash()->error('penawaran harga belum diset');
            return redirect()->back();
        };
        if(!$negosiasiHarga){
            flash()->error('Negosiasi Harga belum diset');
            return redirect()->back();
        };

        $negosiasiHarga->ppn = $negosiasiHarga->harga_negosiasi *  config('pajak.ppn') ;
        $negosiasiHarga->pph_22 = $negosiasiHarga->harga_negosiasi *  config('pajak.pph_22');
        $negosiasiHarga->harga_total = round($negosiasiHarga->harga_negosiasi + $negosiasiHarga->ppn + $negosiasiHarga->pph_22, -2);      
        $negosiasiHarga->tgl_persetujuan = Carbon::parse($negosiasiHarga->tgl_persetujuan);
        $negosiasiHarga->tgl_negosiasi = Carbon::parse($negosiasiHarga->tgl_negosiasi);
        $negosiasiHarga->tgl_perjanjian = $negosiasiHarga->tgl_persetujuan;
        $negosiasiHarga->tgl_akhir_perjanjian = Carbon::parse($negosiasiHarga->tgl_akhir_perjanjian);
        $negosiasiHarga->jumlah_hari_kerja = $negosiasiHarga->tgl_akhir_perjanjian->diffInDays($negosiasiHarga->tgl_perjanjian) * -1;
        
        // $item = $kegiatan->penawaran_1->item;
        $negosiasi = $negosiasiHarga->item;
        // $item['harga_negosiasi'] = json_decode($negosiasi, true);       
       


        $pemberitahuan->no_spk = $pemberitahuan->no_pbj . '/SPK' . $nomor_surat;
        $pemberitahuan->no_ba_negosiasi = $pemberitahuan->no_pbj . '/BA-NEGO' . $nomor_surat;
        $pemberitahuan->no_perjanjian = $pemberitahuan->no_pbj . '/PERJ' . $nomor_surat;

        $penyedia = Penyedia::find($kegiatan->penawaran_1->penyedia_id);
        $data = [
            'kegiatan' => $kegiatan,
            'penyedia' => $penyedia,
            'pemberitahuan' => $pemberitahuan,
            'penawaranHarga' => $penawaranHarga,
            'negosiasiHarga' => $negosiasiHarga,
            'nilai_total_penawaran' => $nilai_total_penawaran,
            'item' => json_decode($negosiasi, true),
        ];
        $pdf = Pdf::loadView('pdf.negosiasi-harga', compact('data'));
        // Replace invalid filename characters with underscore
        $filename = '3. NEGOSIASI HARGA - (' . $kegiatan->kegiatan . ')';
        $filename = preg_replace('/[\/\\\\\?\%\*\:\|\"<>\.]/', '_', $filename);

        return $pdf->stream($filename . '.pdf');
    }
}