<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\Http\Request;
use App\Models\Pemberitahuan;
use App\Models\Belanja;
use App\Models\NegosiasiHarga;
use App\Models\PenawaranHarga;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class NegosiasiHargaController extends Controller
{
    public function index()
    {
       
    }
  
    public function create($id)
    {
        $kegiatan = Kegiatan::with('negosiasiHarga' , 'penawaran', 'pemberitahuan')->find($id);

        
        $penawaran = $kegiatan->penawaran()
        ->where('is_winner', true)
        ->first();

        
        if(empty($penawaran)){
            return back()->with('error', 'PEMENANG belum di set');
        };
       
        $pemberitahuanId = $penawaran->pemberitahuan_id;

        $tgl_surat_pemberitahuan = $kegiatan->pemberitahuan->tgl_surat_pemberitahuan;

        $belanja = Belanja::where('pemberitahuan_id' , $pemberitahuanId )->get();

        

        $items = $penawaran->hargaPenawaran->map(function ($harga, $i) use ($belanja){
                return [
                    'uraian'       => $belanja[$i]->uraian ?? null,
                    'volume'       => $belanja[$i]->volume ?? null,
                    'satuan'       => $belanja[$i]->satuan ?? null,
                    'harga_penawaran' => $harga->harga_satuan ?? null,
                    'jumlah'       => $belanja[$i]->volume  * $harga->harga_satuan ,
                ];
            });
              
        $kegiatan->tgl = Carbon::parse($tgl_surat_pemberitahuan )->addDays(3)->format('Y-m-d');

       return view('form.negosiasi', compact('kegiatan', 'items'));
    }

    public function store(Request $request)
    {
        
        $kegiatan = Kegiatan::with('penawaran')->find($request->kegiatan_id);

        $validatedData = $request->validate([
            'kegiatan_id' => 'required',
            'tgl_persetujuan' => 'required|date',
            'tgl_negosiasi' => 'required|date',
            'tgl_akhir_perjanjian' => 'required|date',
            'harga_satuan_negosiasi' => 'required|array',
            'harga_satuan_negosiasi.*' => 'required|numeric|min:0',
        ]);
        
        $item_negosiasi =$request->harga_satuan_negosiasi; 

        $item_negosiasi_array = collect($item_negosiasi)->map(function ($item) { return [             
            'harga_satuan' => $item
        ];})->toArray();
        
        $pemberitahuan = $kegiatan->pemberitahuan->first();
        if (!$pemberitahuan) {
            return redirect()->back()->with('error', 'Pemberitahuan not found');
        }
        $tgl = Carbon::parse($pemberitahuan->tgl_surat_pemberitahuan);


        $negosiasi = NegosiasiHarga::create([
            'kegiatan_id' => $validatedData['kegiatan_id'],
            'tgl_persetujuan' => Carbon::parse($validatedData['tgl_persetujuan']),
            'tgl_negosiasi' => Carbon::parse($validatedData['tgl_negosiasi']),
            'tgl_akhir_perjanjian' => Carbon::parse($validatedData['tgl_akhir_perjanjian']),
        ]);

        $negosiasi->hargaNegosiasi()->createMany($item_negosiasi_array);

        return redirect()->route('kegiatan.show', ['id' => $kegiatan->id])->with('success', 'berhasil menambahkan data');



    }

    public function show(NegosiasiHarga $negosiasiHarga)
    {
        //
    }

    public function edit($kegiatan_id)
    { 
        $kegiatan = Kegiatan::with('pemberitahuan','negosiasiHarga' , 'penawaran')->find($kegiatan_id);

        $pemberitahuan = $kegiatan->pemberitahuan;
        $pemberitahuan->load('belanjas');
        $penawaranHarga = $kegiatan->penawaran()->firstWhere('is_winner' , true);
        $penawaranHarga->load('hargaPenawaran');
        $hargaPenawaran = $penawaranHarga->hargaPenawaran;
        $negosiasi  = $kegiatan->negosiasiHarga ;
        $negosiasi->load('hargaNegosiasi');
        $hargaNegosiasi = $negosiasi->hargaNegosiasi;

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

        $kegiatan->tgl = Carbon::parse($penawaranHarga->tgl_penawaran)->format('Y-m-d');

        $negosiasi->tgl_negosiasi = Carbon::parse($negosiasi->tgl_negosiasi)->format('Y-m-d');

        $negosiasi->tgl_persetujuan = Carbon::parse($negosiasi->tgl_persetujuan)->format('Y-m-d');

        $negosiasi->tgl_akhir_perjanjian = Carbon::parse($negosiasi->tgl_akhir_perjanjian)->format('Y-m-d');

       return view('form.negosiasi', compact('kegiatan', 'items' , 'negosiasi'));
    }

    public function update(Request $request, $kegiatan_id)
    {
        
        $kegiatan = Kegiatan::with('pemberitahuan','penawaran' , 'negosiasiHarga')->find($request->kegiatan_id);

        $pemberitahuan = $kegiatan->pemberitahuan;
       
        $tgl = Carbon::parse($pemberitahuan->tgl_surat_pemberitahuan);

        $negosiasi = NegosiasiHarga::where('kegiatan_id', $request->kegiatan_id)->first();

        

        $validatedData = $request->validate([
            'tgl_negosiasi' => 'required|date',
            'tgl_persetujuan' => 'required|date',
            'tgl_akhir_perjanjian' => 'required|date',
        ]);

        $negosiasi->update([
            'kegiatan_id' => $request->kegiatan_id,
            'tgl_persetujuan' => Carbon::parse($validatedData['tgl_persetujuan']),
            'tgl_negosiasi' => Carbon::parse($validatedData['tgl_negosiasi']),
            'tgl_akhir_perjanjian' => Carbon::parse($validatedData['tgl_akhir_perjanjian'])
        ]);

        $harga_satuan_array =$request->harga_satuan_negosiasi; 

        $hargaLama = $negosiasi->hargaNegosiasi()->orderBy('id')->get();

        if ($hargaLama->count() !== count($harga_satuan_array)) {
            throw new \Exception("Jumlah item harga tidak sesuai dengan data belanja!");
        }

        foreach ($hargaLama as $index => $harga) {
            $harga->update(['harga_satuan' => $harga_satuan_array[$index]]);
        }

        return redirect()->route('kegiatan.show', ['id' => $kegiatan->id])->with('success', 'berhasil memperbarui data');
    }

    public function destroy($kegiatan_id)
    {
        $negosiasi = NegosiasiHarga::where('kegiatan_id', $kegiatan_id)->get();
        if (!$negosiasi) {
            return redirect()->back()->with('error', 'Negosiasi tidak ditemukan');
        }
        $negosiasi->each->delete();
        return redirect()->back()->with('success', 'Negosiasi berhasil dihapus');
    }

    public function renderPDF($id)
    {
        
        $nomor_surat =  '/' .Auth::user()->kode_desa . '/' . Auth::user()->tahun_anggaran ;
         
        $kegiatan = Kegiatan::with('penawaran')
                                ->with('pemberitahuan')
                                ->with('negosiasiHarga')
                                ->find( $id);
       
        $pemberitahuan = $kegiatan->pemberitahuan;
         
        $pemberitahuan->load('belanjas');
        $penawaranHarga = $kegiatan->penawaran()->firstWhere('is_winner' , true);
        $penawaranHarga->load('hargaPenawaran');
        $hargaPenawaran = $penawaranHarga->hargaPenawaran;
        $negosiasiHarga  = $kegiatan->negosiasiHarga ;
        $negosiasiHarga->load('hargaNegosiasi');
        $hargaNegosiasi = $negosiasiHarga->hargaNegosiasi;

        $ppn = $kegiatan->ppn;
        $pph_22 = $kegiatan->pph_22;
        $denom = 1 + $ppn + $pph_22;

        $items = $pemberitahuan->belanjas->map(function($item,$k) 
        use ( $hargaPenawaran , $hargaNegosiasi, $ppn , $pph_22 , $denom )       
        {
            return [
                'uraian' => $item->uraian,
                'volume' => $item->volume,
                'satuan' => $item->satuan,
                'harga_penawaran' => $hargaPenawaran[$k]->harga_satuan / $denom,
                'harga_negosiasi' => $hargaNegosiasi[$k]->harga_satuan / $denom,
                'jumlah_penawaran' => ($item->volume * $hargaPenawaran[$k]->harga_satuan) / $denom,
                'jumlah_negosiasi' => ($item->volume * $hargaNegosiasi[$k]->harga_satuan) / $denom,
            ];
        });
        $penawaranHarga->tgl_penawaran =  Carbon::parse($penawaranHarga->tgl_penawaran);
        $jumlah_penawaran = $items->sum('jumlah_penawaran') * $denom;
        $nilai_ppn = $jumlah_penawaran  * ( $ppn / $denom );
        $nilai_pph_22 = $jumlah_penawaran * ( $pph_22 / $denom);
        $penawaranHarga->ppn = $nilai_ppn;
        $penawaranHarga->pph_22 = $nilai_pph_22;

        $penawaranHarga->harga_sebelum_pajak = $jumlah_penawaran / $denom ;
        $penawaranHarga->harga_total =  $jumlah_penawaran;
          
        $jumlah_negosiasi = $items->sum('jumlah_negosiasi') * $denom;

        $negosiasiHarga->ppn = $jumlah_negosiasi * ( $ppn / $denom );
        $negosiasiHarga->pph_22 = $jumlah_negosiasi * ( $pph_22 / $denom);
        $negosiasiHarga->harga_sebelum_pajak = $jumlah_negosiasi / $denom ;      
        $negosiasiHarga->tgl_negosiasi = Carbon::parse($negosiasiHarga->tgl_negosiasi);
        $negosiasiHarga->tgl_persetujuan = Carbon::parse($negosiasiHarga->tgl_persetujuan);
        $negosiasiHarga->tgl_perjanjian = $negosiasiHarga->tgl_persetujuan;
        $negosiasiHarga->tgl_akhir_perjanjian = Carbon::parse($negosiasiHarga->tgl_akhir_perjanjian);
        $negosiasiHarga->jumlah_hari_kerja = $negosiasiHarga->tgl_akhir_perjanjian->diffInDays($negosiasiHarga->tgl_perjanjian) * -1;
        $negosiasiHarga->harga_total = round($jumlah_negosiasi , -2 , PHP_ROUND_HALF_DOWN);  
        
        $negosiasi = $items;

        $pemberitahuan->no_spk = $pemberitahuan->no_pbj . '/SPK' . $nomor_surat;
        $pemberitahuan->no_ba_negosiasi = $pemberitahuan->no_pbj . '/BA-NEGO' . $nomor_surat;
        $pemberitahuan->no_perjanjian = $pemberitahuan->no_pbj . '/PERJ' . $nomor_surat;

        $penyedia = Penyedia::find($penawaranHarga->penyedia_id);
        $data = [
            'kegiatan' => $kegiatan,
            'penyedia' => $penyedia,
            'pemberitahuan' => $pemberitahuan,
            'penawaranHarga' => $penawaranHarga,
            'negosiasiHarga' => $negosiasiHarga,
            'item' => $items,
        ];

        $pdf = Pdf::loadView('pdf.negosiasi-harga', compact('data'));
        $filename = '3. NEGOSIASI HARGA - (' . $kegiatan->kegiatan . ')';
        $filename = preg_replace('/[\/\\\\\?\%\*\:\|\"<>\.]/', '_', $filename);

        return $pdf->stream($filename . '.pdf');
    }
}