<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use App\Http\Requests\PembayaranRequest;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\PajakHelper;

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


    public function store(PembayaranRequest $request)
    {
        $validated = $request->validated();


        $pembayaran = new Pembayaran([
            'kegiatan_id' => $validated['kegiatan_id'],
            'tgl_pembayaran_cms' => $validated['tgl_pembayaran_cms'],
            'tgl_invoice' => $validated['tgl_invoice'],
        ]);
        $pembayaran->save();
        $kegiatan_id = $validated['kegiatan_id'];
       
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


    public function update(PembayaranRequest $request, $id)
    {
        $validated = $request->validated();
        
        $pembayaran = Pembayaran::find($id);

        $pembayaran->tgl_pembayaran_cms = $validated['tgl_pembayaran_cms'];

        $pembayaran->tgl_invoice = $validated['tgl_invoice'];

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
        
        $ppn = $kegiatan->ppn;
        
        $pph22 = $kegiatan->pph_22;

        $item = $pemberitahuan->belanjas->map(function($item,$k) 
        use ( $hargaNegosiasi, $ppn, $pph22)       
        {

            $hargaNegosiasiBersih = PajakHelper::hitungSiskeudes(
                $hargaNegosiasi[$k]->harga_satuan,
                $ppn,
                $pph22
            );

            return [
                'uraian' => $item->uraian,
                'volume' => $item->volume,
                'satuan' => $item->satuan,               
                'harga_negosiasi' =>  $hargaNegosiasiBersih['bersih'],          
                'jumlah_negosiasi' => $item->volume * $hargaNegosiasiBersih['bersih'], 
                'ppn_negosiasi' => $item->volume * $hargaNegosiasiBersih['ppn'],
                'pph22_negosiasi' => $item->volume * $hargaNegosiasiBersih['pph22'],
                'total_negosiasi' => $item->volume * ($hargaNegosiasiBersih['bersih'] + $hargaNegosiasiBersih['ppn'] + $hargaNegosiasiBersih['pph22']),
            ];
        });
       
        $penyediaId = $kegiatan->penawaran()->firstWhere('is_winner' , true)->penyedia_id;
        
        $penyedia = Penyedia::find($penyediaId);

        $tgl_invoice =  Carbon::parse($kegiatan->pembayaran->tgl_invoice);
        
        $tgl =  Carbon::parse($kegiatan->pembayaran->tgl_pembayaran_cms);
        $negosiasiHarga->ppn = $item->sum('ppn_negosiasi');
        $negosiasiHarga->pph_22 = $item->sum('pph22_negosiasi');
        $negosiasiHarga->jumlah = $item->sum('jumlah_negosiasi');
        $negosiasiHarga->pajak  = $negosiasiHarga->ppn + $negosiasiHarga->pph_22;
        $negosiasiHarga->total  = round($item->sum('total_negosiasi'), 0, PHP_ROUND_HALF_UP);

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
