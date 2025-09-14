<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\Http\Request;
use App\Models\Pemberitahuan;
use App\Models\NegosiasiHarga;
use App\Models\PenawaranHarga;
use App\Models\Belanja;
use App\Models\HargaPenawaran;
use App\Models\Penawaran;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
    public function create($kegiatanId, $penyediaId)
    {
        
        $kegiatan = Kegiatan::with('pemberitahuan')->find($kegiatanId);
        
        $penyedia = Penyedia::find($penyediaId);
              
        $pemberitahuan = $kegiatan->pemberitahuan;
        $belanja = collect($pemberitahuan->belanjas)->map(function ($item, $key) {
            return [
                'uraian' => $item['uraian'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'],
            ];
        });
        
        return view('form.penawaran-harga', [
            'kegiatan' => $kegiatan,
            'pemberitahuan' => $pemberitahuan,
            'penyedia' => $penyedia,
            'belanja' => $belanja
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     
    $harga_satuan = $request->harga_satuan;
    
    $harga_satuan_array = [];
    foreach ($harga_satuan as $item) {
        $harga_satuan_array[] = [
            'harga_satuan' => $item
        ];
    }

    
    $pemberitahuan = Pemberitahuan::with('kegiatan', 'penawaran' , 'belanjas')->find($request->pemberitahuan_id);
   
    $penyedias = $pemberitahuan->penyedia;

    $kegiatan_id = $pemberitahuan->kegiatan->id;
    
    $is_winner = $request->pemenang ? true : false;
    
        $penawaran = Penawaran::create([
            'kegiatan_id' => $kegiatan_id,
            'pemberitahuan_id' => $request->pemberitahuan_id,
            'penyedia_id' => $request->penyedia,
            'tgl_penawaran' => Carbon::parse($request->tgl_surat_penawaran),
            'no_penawaran' => $request->no_penawaran,
            'is_winner' => $is_winner,
            
        ]);

            $penawaran->hargaPenawaran()->createMany($harga_satuan_array);

    return redirect()->route('kegiatan.show', ['id' => $kegiatan_id ]);
        
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
    public function edit(string $kegiatanId, string $penyediaId)
    {
       $kegiatan = Kegiatan::with('pemberitahuan' , 'penawaran')->find($kegiatanId);

        $penyedia = Penyedia::find($penyediaId);
              
        $pemberitahuan = $kegiatan->pemberitahuan;

        $pemberitahuan->load('penawaran');

        $penawaran = $pemberitahuan->penawaran->firstWhere('penyedia_id', $penyediaId);

        $penawaran->load('hargaPenawaran');

        $harga_penawaran = $penawaran->hargaPenawaran()->orderBy('id', 'ASC')->get();

        
        $harga_satuan = $harga_penawaran->pluck('harga_satuan')->values();
        
        $belanja = collect($pemberitahuan->belanjas)->map(function ($item, $key) use ($harga_satuan) {
            return [
                'uraian' => $item['uraian'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'],
                'harga_satuan' => $harga_satuan->get($key),
            ];
        });
        $penawaran->tgl_penawaran = Carbon::parse($penawaran->tgl_penawaran)->format('Y-m-d');

        return view('form.penawaran-harga', [
            'kegiatan' => $kegiatan,
            'pemberitahuan' => $pemberitahuan,
            'penyedia' => $penyedia,
            'belanja' => $belanja,
            'penawaran' => $penawaran,
            'isEdit' => true
        ]); 
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $pemberitahuanId)
    {
       $penawaran = Penawaran::where('penyedia_id', $request->penyedia)
        ->where('pemberitahuan_id', $request->pemberitahuan_id)
        ->firstOrFail();

        $harga_satuan_array = $request->harga_satuan;

        $hargaLama = $penawaran->hargaPenawaran()->orderBy('id' , 'ASC')->get();

        if ($hargaLama->count() !== count($harga_satuan_array)) {
            throw new \Exception("Jumlah item harga tidak sesuai dengan data belanja!");
        };
        
        $pemberitahuan = Pemberitahuan::with('kegiatan')->findOrFail($request->pemberitahuan_id);
        
        $kegiatan_id = $pemberitahuan->kegiatan->id;
        
        $is_winner = (bool) $request->pemenang;
        
        $penawaran->update([
            'kegiatan_id' => $kegiatan_id,
            'tgl_penawaran' => Carbon::parse($request->tgl_surat_penawaran),
            'no_penawaran' => $request->no_penawaran,
            'is_winner' => $is_winner,
        ]);
        
        foreach ($hargaLama as $index => $row) {
            $row->update([
                'harga_satuan' => $harga_satuan_array[$index],
            ]);
        };

        flash()->success('Penawaran berhasil diupdate.');

        return redirect()->route('kegiatan.show', ['id' => $kegiatan_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penawaran = Penawaran::where('kegiatan_id', $id)->get();
       
        $penawaran->each->delete();

        flash()->success('Penawaran berhasil dihapus.');
        
        return redirect()->route('kegiatan.show', ['id' => $id]);
    }
    public function render(string $id)
    {
        
            $kegiatan = Kegiatan::with('pemberitahuan' , 'penawaran')->find($id);
                                       
            $pemberitahuanId = $kegiatan->pemberitahuan->id;
            $pemberitahuan = Pemberitahuan::with('belanjas')->find($pemberitahuanId);
            $belanja = $pemberitahuan->belanjas()->orderBy('id')->get();

            $penawaran = $pemberitahuan->penawaran()->orderBy('id')->get();
            $penawarPemenang = collect($penawaran)->firstWhere('is_winner', true);
            if(!isset($penawarPemenang)){
                return back()->with('error', 'belum ada pemenang di set');
            }

            $pemenangId = $penawarPemenang->id;

            $pemenang = Penawaran::with('hargaPenawaran')->orderBy('id', 'asc')->find($pemenangId);
            $penawaranPemenang = $pemenang->hargaPenawaran->map(function ($harga, $i) use ($belanja){
                return [
                    'uraian'       => $belanja[$i]->uraian ?? null,
                    'volume'       => $belanja[$i]->volume ?? null,
                    'satuan'       => $belanja[$i]->satuan ?? null,
                    'harga_satuan' => $harga->harga_satuan ?? null,
                    'jumlah'       => $belanja[$i]->volume  * $harga->harga_satuan ,
                ];
            });

            
            $penawarPembanding = collect($penawaran)->firstWhere('is_winner', false);
            if(!isset($penawarPembanding)){
                return back()->with('error', 'pemenang tidak boleh lebih dari 1');
            }

            $pembandingId = $penawarPembanding->id;

            $pembanding = Penawaran::with('hargaPenawaran')->find($pembandingId);
            $penawaranPembanding = $pembanding->hargaPenawaran->map(function ($harga, $i) use ($belanja){
                return [
                    'uraian'       => $belanja[$i]->uraian ?? null,
                    'volume'       => $belanja[$i]->volume ?? null,
                    'satuan'       => $belanja[$i]->satuan ?? null,
                    'harga_satuan' => $harga->harga_satuan ?? null,
                    'jumlah'       => $belanja[$i]->volume  * $harga->harga_satuan ,
                ];
            });


            
            $penyedia1 = Penyedia::find($pemenang->penyedia_id);
            $penyedia2 = Penyedia::find($pembanding->penyedia_id);
            
            $pemberitahuan = $kegiatan->pemberitahuan; 
            
            $jumlah_1 = $penawaranPemenang->sum(fn ($i) => $i['volume'] * $i['harga_satuan']);

            $ppn = $kegiatan->ppn ? config('pajak.ppn') : 0;

            $pph = $kegiatan->pph_22 ? config('pajak.pph_22') : 0;

            $factor_pajak = $ppn + $pph;
            
            $ppn_1 = $jumlah_1 * ($ppn / (1 + $factor_pajak));
            
            $pph_22_1 = $jumlah_1 * ($pph / (1 + $factor_pajak));

            $jumlah_sebelum_pajak_1 = $jumlah_1 * ( 1 / ( 1 + $factor_pajak) );
            
            $item = $penawaranPemenang;
            
            $jumlah_2 = $penawaranPembanding->sum(fn ($i) => $i['volume'] * $i['harga_satuan']);

            $ppn_2 = $jumlah_2 * ($ppn / (1 + $factor_pajak));
            
            $pph_22_2 = $jumlah_2 * ($pph / (1 + $factor_pajak));

            $jumlah_sebelum_pajak_2 = $jumlah_2 * ( 1 / ( 1 + $factor_pajak) );

            $item_2 = $penawaranPembanding;

            $pdf = Pdf::loadView('pdf.penawaran-harga', [
                'penawaran_1' => $pemenang,
                'penawaran_2' => $pembanding,
                'kegiatan' => $kegiatan,
                'penyedia1' => $penyedia1,
                'penyedia2' => $penyedia2,
                'jumlah' => $jumlah_sebelum_pajak_1,
                'jumlah_2' => $jumlah_sebelum_pajak_2,
                'ppn_1' => $ppn_1,
                'ppn_2' => $ppn_2,
                'pph_22_1' => $pph_22_1,
                'pph_22_2' => $pph_22_2, 
                'jumlah_total_1' => $jumlah_1,
                'jumlah_total_2' => $jumlah_2,              
                'pemberitahuan' => $pemberitahuan,
                'item' => $item,
                'item_2' => $item_2
            ]);
            // Replace invalid filename characters with underscore
            $filename = '2. PENAWARAN HARGA - (' . $kegiatan->kegiatan . ')';
            // $filename = preg_replace('/[\/\\\\\?\%\*\:\|\"<>\.]/', '_', $filename);

            return $pdf->stream(sanitize_filename($filename) . '.pdf');
       
    }
}