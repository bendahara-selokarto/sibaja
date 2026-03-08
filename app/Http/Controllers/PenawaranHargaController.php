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
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\PajakHelper;
use App\Support\Money;
use Illuminate\Support\Facades\DB;



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
        $kegiatan = Kegiatan::with('pemberitahuan.penyedias', 'pemberitahuan.belanjas')->find($kegiatanId);
        
        $statusPemenang = $kegiatan->statusPemenang();

        $pemberitahuan = $kegiatan->pemberitahuan;
        $penyedia = $pemberitahuan
            ? $pemberitahuan->selectedPenyedias()->firstWhere('id', (string) $penyediaId)
            : null;

        if (!$pemberitahuan || !$penyedia) {
            return redirect()
                ->route('kegiatan.show', ['id' => $kegiatanId])
                ->with('error', 'Penyedia tidak terdaftar pada pemberitahuan ini.');
        }

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
            'belanja' => $belanja,
            'statusPemenang' => $statusPemenang,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        $request->validate([
        'pemberitahuan_id' => 'required|exists:pemberitahuans,id',
        'penyedia' => 'required|exists:penyedias,id',
        'tgl_surat_penawaran' => 'required|date',
        'no_penawaran' => 'required|string|max:255',
        'harga_satuan' => 'required|array',
        'harga_satuan.*' => 'required|numeric|min:0',
    ]);


    
    $pemberitahuan = Pemberitahuan::with('kegiatan', 'penawaran' , 'belanjas')->find($request->pemberitahuan_id);

    validator(
        ['penyedia' => (string) $request->penyedia],
        ['penyedia' => ['required', Rule::in($pemberitahuan->selectedPenyediaIds())]],
        ['penyedia.in' => 'Penyedia tidak terdaftar pada pemberitahuan ini.']
    )->validate();

    $harga_satuan = $request->harga_satuan;
    
    $harga_satuan_array = [];
    foreach ($harga_satuan as $item) {
        $harga_satuan_array[] = [
            'harga_satuan' => Money::rupiah($item)
        ];
    }

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
       $kegiatan = Kegiatan::with(
           'pemberitahuan.penyedias',
           'pemberitahuan.penawaran.hargaPenawaran',
           'pemberitahuan.belanjas'
       )->find($kegiatanId);

        $pemberitahuan = $kegiatan->pemberitahuan;
        $penyedia = $pemberitahuan
            ? $pemberitahuan->selectedPenyedias()->firstWhere('id', (string) $penyediaId)
            : null;

        if (!$pemberitahuan || !$penyedia) {
            return redirect()
                ->route('kegiatan.show', ['id' => $kegiatanId])
                ->with('error', 'Penyedia tidak terdaftar pada pemberitahuan ini.');
        }

        $penawaran = $pemberitahuan->penawaran->firstWhere('penyedia_id', $penyediaId);
        if (!$penawaran) {
            return redirect()
                ->route('kegiatan.show', ['id' => $kegiatanId])
                ->with('error', 'Penawaran untuk penyedia ini tidak ditemukan.');
        }

        $harga_penawaran = $penawaran->hargaPenawaran->sortBy('id')->values();

        
        $harga_satuan = $harga_penawaran->pluck('harga_satuan')->values();
        $belanja = collect($pemberitahuan->belanjas)->map(function ($item, $key) use ($harga_satuan) {
            return [
                'uraian' => $item['uraian'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'],
                'harga_satuan' => $harga_satuan->get($key),
            ];
        });
        return view('form.penawaran-harga', [
            'kegiatan' => $kegiatan,
            'pemberitahuan' => $pemberitahuan,
            'penyedia' => $penyedia,
            'belanja' => $belanja,
            'penawaran' => $penawaran,
            'isEdit' => true,
        ]); 
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $pemberitahuanId)
    {
        $pemberitahuan = Pemberitahuan::with('kegiatan')->findOrFail($request->pemberitahuan_id);

        validator(
            ['penyedia' => (string) $request->penyedia],
            ['penyedia' => ['required', Rule::in($pemberitahuan->selectedPenyediaIds())]],
            ['penyedia.in' => 'Penyedia tidak terdaftar pada pemberitahuan ini.']
        )->validate();

        $penawaran = Penawaran::where('penyedia_id', $request->penyedia)
        ->where('pemberitahuan_id', $request->pemberitahuan_id)
        ->firstOrFail();
        $penawaran2 = Penawaran::whereNot('penyedia_id', $request->penyedia)
            ->where('pemberitahuan_id', $request->pemberitahuan_id)
            ->first();


        $harga_satuan_array = $request->harga_satuan;

        $hargaLama = $penawaran->hargaPenawaran()->orderBy('id' , 'ASC')->get();

        if ($hargaLama->count() !== count($harga_satuan_array)) {
            throw new \Exception("Jumlah item harga tidak sesuai dengan data belanja!");
        };
        
        $kegiatan_id = $pemberitahuan->kegiatan->id;
        
        $is_winner = (bool) $request->pemenang;
        $is_not_winner = (bool) $request->pemenang ? false : true;



        DB::transaction(function () use (
            $penawaran,
            $penawaran2,
            $hargaLama,
            $harga_satuan_array,
            $kegiatan_id,
            $request,
            $is_winner,
            $is_not_winner,
        ) {
            $penawaran->update([
                'kegiatan_id'   => $kegiatan_id,
                'tgl_penawaran' => Carbon::parse($request->tgl_surat_penawaran),
                'no_penawaran'  => $request->no_penawaran,
                'is_winner'     => $is_winner,
            ]);

            $penawaran2->update([
                'is_winner' => $is_not_winner,
            ]);

            foreach ($hargaLama as $index => $row) {
                $row->update([
                    'harga_satuan' => Money::rupiah($harga_satuan_array[$index]),
                ]);
            };

        });
                

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
            $kegiatan = Kegiatan::with(
                'pemberitahuan.belanjas',
                'penawaran.hargaPenawaran',
                'penawaran.penyedia'
            )->find($id);
                                       
            $pemberitahuanId = $kegiatan->pemberitahuan->id;
            $pemberitahuan = $kegiatan->pemberitahuan;
            $belanja = $pemberitahuan->belanjas->sortBy('id')->values();

            $penawaran = $kegiatan->penawaran->sortBy('id')->values();
            $penawarPemenang = collect($penawaran)->firstWhere('is_winner', true);
            if(!isset($penawarPemenang)){
                return back()->with('error', 'belum ada pemenang di set');
            }

            $pemenang = $penawarPemenang;
            $penawaranPemenang = $pemenang->hargaPenawaran->map(function ($harga, $i) use ($belanja){
                return [
                    'uraian'       => $belanja[$i]->uraian ?? null,
                    'volume'       => $belanja[$i]->volume ?? null,
                    'satuan'       => $belanja[$i]->satuan ?? null,
                    'harga_satuan' => $harga->harga_satuan ?? null,
                    'jumlah'       => Money::quantityTimesRupiah($belanja[$i]->volume, $harga->harga_satuan),
                ];
            });

            
            $penawarPembanding = collect($penawaran)->firstWhere('is_winner', false);
            if(!isset($penawarPembanding)){
                return back()->with('error', 'pemenang tidak boleh lebih dari 1');
            }

            $pembanding = $penawarPembanding;
            $penawaranPembanding = $pembanding->hargaPenawaran->map(function ($harga, $i) use ($belanja){
                return [
                    'uraian'       => $belanja[$i]->uraian ?? null,
                    'volume'       => $belanja[$i]->volume ?? null,
                    'satuan'       => $belanja[$i]->satuan ?? null,
                    'harga_satuan' => $harga->harga_satuan ?? null,
                    'jumlah'       => Money::quantityTimesRupiah($belanja[$i]->volume, $harga->harga_satuan),
                ];
            });


            
            $penyedia1 = $pemenang->penyedia;
            $penyedia2 = $pembanding->penyedia;
            
            $pemberitahuan = $kegiatan->pemberitahuan; 
            
            $jumlah_1 = $penawaranPemenang->sum(
                fn ($i) => Money::quantityTimesRupiah($i['volume'], $i['harga_satuan'])
            );

            
            $item = $penawaranPemenang;

            $item->transform(function ($item) use ($kegiatan) {

                $item['harga_satuan'] = PajakHelper::bersihSetelahPpnDanPph22(
                    $item['harga_satuan'],
                    $kegiatan->ppn,
                    $kegiatan->pph_22
                );

                $item['jumlah'] = PajakHelper::bersihSetelahPpnDanPph22(
                    $item['jumlah'],
                    $kegiatan->ppn,
                    $kegiatan->pph_22
                );

                return $item;
            });
            
            $jumlah_2 = $penawaranPembanding->sum(
                fn ($i) => Money::quantityTimesRupiah($i['volume'], $i['harga_satuan'])
            );
            
            $item_2 = $penawaranPembanding;

            $item_2->transform(function ($item) use ($kegiatan) {

                $item['harga_satuan'] = PajakHelper::bersihSetelahPpnDanPph22(
                    $item['harga_satuan'],
                    $kegiatan->ppn,
                    $kegiatan->pph_22
                );

                $item['jumlah'] = PajakHelper::bersihSetelahPpnDanPph22(
                    $item['jumlah'],
                    $kegiatan->ppn,
                    $kegiatan->pph_22
                );

                return $item;
            });

            // ✅ pakai helper
            $pajak_1 = PajakHelper::hitungSiskeudes(
                $jumlah_1, 
                $kegiatan->ppn,
                $kegiatan->pph_22
            );

            $pajak_2 = PajakHelper::hitungSiskeudes(
                $jumlah_2, 
                $kegiatan->ppn,
                $kegiatan->pph_22
            );

            $pdf = Pdf::loadView('pdf.penawaran-harga', [
                'penawaran_1' => $pemenang,
                'penawaran_2' => $pembanding,
                'kegiatan' => $kegiatan,
                'penyedia1' => $penyedia1,
                'penyedia2' => $penyedia2,
                'jumlah'            => $pajak_1['bersih'],
                'jumlah_2'          => $pajak_2['bersih'],
                'ppn_1'             => $pajak_1['ppn'],
                'ppn_2'             => $pajak_2['ppn'],
                'pph_22_1'          => $pajak_1['pph22'],
                'pph_22_2'          => $pajak_2['pph22'],
                'jumlah_total_1'    => $pajak_1['total'],
                'jumlah_total_2'    => $pajak_2['total'],    
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
