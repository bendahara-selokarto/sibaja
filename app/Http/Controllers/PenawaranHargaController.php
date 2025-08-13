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
use App\Models\Penawaran_1;
use App\Models\Penawaran_2;
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
    public function create($id, $id2)
    {
        
        $kegiatan = Kegiatan::with('pemberitahuan')->find($id);
        
        $penyedia = Penyedia::find($id2);
              
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
            'penawaran1' => '',
            'belanja' => $belanja
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     
    $harga_satuan = $request->harga_satuan;
        
        $harga_satuan_array = collect($harga_satuan)->map(function ($item) { return [ 
            'id' => (string) Str::uuid(),
            'harga_satuan' => $item
        ];})->toArray();
    
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
    public function edit(string $id)
    {
        
        $kegiatan = Kegiatan::with('pemberitahuan', 'penawaran')->findOrFail($id);

        $pemberitahuan = $kegiatan->pemberitahuan;
        $penyedia = $kegiatan->penawaran->penyedia;


        $penawaran_1 = $kegiatan->penawaran_1;
        $penawaran_2 = $kegiatan->penawaran_2;

        return view('form.penawaran-harga-edit', [
            'kegiatan' => $kegiatan,
            'penawaran_1' => $penawaran_1,
            'penawaran_2' => $penawaran_2,
            'pemberitahuan' => $pemberitahuan,
            'penyedia' => $penyedia ?? null,
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

        return redirect()->route('kegiatan.show', ['id' => $kegiatan_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penawaran = Penawaran::where('kegiatan_id', $id)->get();
        // $penawaran_1 = Penawaran_1::where('kegiatan_id', $id)->first();
        // $penawaran_2 = Penawaran_2::where('kegiatan_id', $id)->first();
        // $penawaran_1->delete();
        // $penawaran_2->delete();
        $penawaran->each->delete();

        flash()->success('Penawaran berhasil dihapus.');
        return redirect()->route('kegiatan.show', ['id' => $id]);
    }
    public function render(string $id)
    {
        
            $kegiatan = Kegiatan::with('pemberitahuan' , 'penawaran')->find($id);
                                       
            $pemberitahuanId = $kegiatan->pemberitahuan->id;
            $pemberitahuan = Pemberitahuan::with('belanjas')->find($pemberitahuanId);
            $belanja = $pemberitahuan->belanjas;

            $penawaran = $pemberitahuan->penawaran;

            $pemenangId = collect($penawaran)->firstWhere('is_winner', true)->id;
            $pemenang = Penawaran::with('hargaPenawaran')->find($pemenangId);
            $penawaranPemenang = $pemenang->hargaPenawaran->map(function ($harga, $i) use ($belanja){
                return [
                    'uraian'       => $belanja[$i]->uraian ?? null,
                    'volume'       => $belanja[$i]->volume ?? null,
                    'satuan'       => $belanja[$i]->satuan ?? null,
                    'harga_satuan' => $harga->harga_satuan ?? null,
                    'jumlah'       => $belanja[$i]->volume  * $harga->harga_satuan ,
                ];
            });

            $pembandingId = collect($penawaran)->firstWhere('is_winner', false)->id;
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


            $nilaiPenawaranPembanding = $penawaranPembanding->sum('jumlah');

            $penyedia1 = Penyedia::find($pemenang->penyedia_id);
            $penyedia2 = Penyedia::find($pembanding->penyedia_id);
            
            $pemberitahuan = $kegiatan->pemberitahuan; 
            if(!$pemberitahuan){
                flash()->error('Tidak ada pemberitahuan yang relevan');
                return back();
            }

          
            $jumlah = $penawaranPemenang->sum(fn ($i) => $i['volume'] * $i['harga_satuan']);
            
            $ppn_1 = $jumlah * config('pajak.ppn');
            $pph_22_1 = $jumlah * config('pajak.pph_22');
            $jumlah_total_1 = $jumlah + $ppn_1 + $pph_22_1;
            $item = $penawaranPemenang;
            
            $jumlah_2 = $penawaranPembanding->sum(fn ($i) => $i['volume'] * $i['harga_satuan']);
            $ppn_2 = $jumlah_2 * config('pajak.ppn');
            $pph_22_2 = $jumlah_2 * config('pajak.pph_22');
            $jumlah_total_2 = $jumlah_2 + $ppn_2 + $pph_22_2;
            $item_2 = $penawaranPembanding;

            $pdf = Pdf::loadView('pdf.penawaran-harga', [
                'penawaran_1' => $pemenang,
                'penawaran_2' => $pembanding,
                'kegiatan' => $kegiatan,
                'penyedia1' => $penyedia1,
                'penyedia2' => $penyedia2,
                'jumlah' => $jumlah,
                'jumlah_2' => $jumlah_2,
                'ppn_1' => $ppn_1,
                'ppn_2' => $ppn_2,
                'pph_22_1' => $pph_22_1,
                'pph_22_2' => $pph_22_2, 
                'jumlah_total_1' => $jumlah_total_1,
                'jumlah_total_2' => $jumlah_total_2,              
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