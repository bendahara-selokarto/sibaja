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
    
    public function index()
    {
        //
    }

    
    public function create($id)
    {   
        $kegiatan = Kegiatan::with('negosiasiHarga')->find($id);        
        return view ('form.pembayaran', compact('kegiatan'));
    }

    
    public function store(Request $request)
    {
        $pembayaran = new Pembayaran([
            'kegiatan_id' => $request->kegiatan_id,
            'tgl_pembayaran_cms' => $request->tgl_pembayaran_cms,
            'tgl_invoice' => $request->tgl_invoice,
        ]);
        $pembayaran->save();
        $kegiatan_id = $request->kegiatan_id;
       
        return redirect()->route('kegiatan.show', ['id' => $kegiatan_id]);

    }

    
    public function show(Pembayaran $pembayaran)
    {
        //
    }

    
    public function edit($id)
    {
        $kegiatan = Kegiatan::with('negosiasiHarga', 'pembayaran')->find($id);     
        $pembayaran = $kegiatan->pembayaran;  
        
        return view ('form.pembayaran', compact('pembayaran', 'kegiatan'));
    }

    
    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::find($id);

        $pembayaran->tgl_pembayaran_cms = $request->tgl_pembayaran_cms;
        $pembayaran->save();
        $kegiatan_id = $pembayaran->kegiatan_id;

        return redirect()->route('kegiatan.show' , ['id' => $kegiatan_id])->with('success', 'Pembayaran berhasil diperbarui.');
    }

   
    public function destroy($kegiatan_id)
    {
        $pembayaran = Pembayaran::where('kegiatan_id', $kegiatan_id)->first();
        if ($pembayaran) {
            $pembayaran->delete();
            return redirect()->route('kegiatan.show' , ['id' => $kegiatan_id])->with('success', 'Pembayaran berhasil dihapus.');
        }
        return redirect()->route('kegiatan.show' , ['id' => $kegiatan_id]);
    }

    public function render($id){
        $kegiatan = Kegiatan::with('negosiasiHarga', 'pemberitahuan', 'penawaran', 'pembayaran' )->find($id);

        $pemberitahuan = $kegiatan->pemberitahuan;
        $pemberitahuan->load('belanjas');
        $penawaranHarga = $kegiatan->penawaran()->firstWhere('is_winner' , true);
        $penawaranHarga->load('hargaPenawaran');
        $hargaPenawaran = $penawaranHarga->hargaPenawaran;
        $negosiasiHarga  = $kegiatan->negosiasiHarga ;
        $negosiasiHarga->load('hargaNegosiasi');
        $hargaNegosiasi = $negosiasiHarga->hargaNegosiasi;

        $items = $pemberitahuan->belanjas->map(function($item,$k) 
        use ( $hargaPenawaran , $hargaNegosiasi )       
        {
            return [
                'uraian' => $item->uraian,
                'volume' => $item->volume,
                'satuan' => $item->satuan,
                'harga_penawaran' => $hargaPenawaran[$k]->harga_satuan,
                'harga_negosiasi' => $hargaNegosiasi[$k]->harga_satuan,
                'jumlah_penawaran' => $item->volume * $hargaPenawaran[$k]->harga_satuan,
                'jumlah_negosiasi' => $item->volume * $hargaNegosiasi[$k]->harga_satuan,
            ];
        });
       
        $penyediaId = $kegiatan->penawaran()->firstWhere('is_winner' , true)->penyedia_id;
        
        $penyedia = Penyedia::find($penyediaId);

        $tgl_invoice =  Carbon::parse($kegiatan->pembayaran->tgl_invoice);

        $tgl =  Carbon::parse($kegiatan->pembayaran->tgl_pembayaran_cms);

        $item = $items;

        $negosiasiHarga->ppn = $negosiasiHarga->harga_negosiasi *  config('pajak.ppn') ;

        $negosiasiHarga->pph_22 = $negosiasiHarga->harga_negosiasi *  config('pajak.pph_22') ;

        $negosiasiHarga->total = $negosiasiHarga->harga_negosiasi + $negosiasiHarga->ppn + $negosiasiHarga->pph_22;
        
        $pemberitahuan = $kegiatan->pemberitahuan;
        
        $kegiatan->nomor = $kegiatan->pemberitahuan->no_pbj;

        $pembayaran =  $kegiatan->pembayaran;
        
        $pdf = Pdf::loadView('pdf.pembayaran.kuitansi', compact(
        'kegiatan',
        'penyedia',
        'pemberitahuan',
        'negosiasiHarga',
        'item',
        'tgl',
        'tgl_invoice',
        'pembayaran'
        ));

        $filename = '4. PEMBAYARAN - (' . $kegiatan->kegiatan . ')';

        $filename = preg_replace('/[\/\\\\\?\%\*\:\|\"<>\.]/', '_', $filename);

        return $pdf->stream($filename . '.pdf');
    }
}
