<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenawaranHargaRequest;
use App\Models\Kegiatan;
use App\Models\HargaPenawaran;
use App\Models\Pemberitahuan;
use App\Models\Penawaran;
use App\Models\Penyedia;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\PajakHelper;
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
        $kegiatan = Kegiatan::with('pemberitahuan')->find($kegiatanId);
        
        $statusPemenang = $kegiatan->statusPemenang();
        
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
            'belanja' => $belanja,
            'statusPemenang' => $statusPemenang,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PenawaranHargaRequest $request)
    {
        $validated = $request->validated();

        $pemberitahuan = Pemberitahuan::with(['kegiatan', 'penawaran', 'belanjas'])
            ->findOrFail($validated['pemberitahuan_id']);

        if ($pemberitahuan->penawaran->contains('penyedia_id', $validated['penyedia'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['penyedia' => 'Penyedia ini sudah mengirim penawaran untuk pemberitahuan ini.']);
        }

        if ($pemberitahuan->belanjas->count() !== count($validated['harga_satuan'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['harga_satuan' => 'Jumlah item harga tidak sesuai dengan data belanja.']);
        }

        if ($request->isWinner() && $pemberitahuan->penawaran->contains('is_winner', true)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['pemenang' => 'Pemenang sudah ditetapkan. Ubah penawaran yang ada jika ingin mengganti pemenang.']);
        }

        DB::transaction(function () use ($request, $pemberitahuan, $validated) {
            $penawaran = Penawaran::create([
                'kegiatan_id' => $pemberitahuan->kegiatan->id,
                'pemberitahuan_id' => $validated['pemberitahuan_id'],
                'penyedia_id' => $validated['penyedia'],
                'tgl_penawaran' => Carbon::parse($validated['tgl_surat_penawaran']),
                'no_penawaran' => $validated['no_penawaran'],
                'is_winner' => $request->isWinner(),
            ]);

            $penawaran->hargaPenawaran()->createMany($request->hargaPenawaranPayload());
        });

        return redirect()->route('kegiatan.show', ['id' => $pemberitahuan->kegiatan->id]);
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
            'isEdit' => true,
        ]); 
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PenawaranHargaRequest $request, string $pemberitahuanId)
    {
        $validated = $request->validated();

        $penawaran = Penawaran::where('penyedia_id', $validated['penyedia'])
            ->where('pemberitahuan_id', $validated['pemberitahuan_id'])
            ->firstOrFail();

        $hargaLama = $penawaran->hargaPenawaran()->orderBy('id', 'ASC')->get();

        if ($hargaLama->count() !== count($validated['harga_satuan'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['harga_satuan' => 'Jumlah item harga tidak sesuai dengan data belanja.']);
        }

        $pemberitahuan = Pemberitahuan::with(['kegiatan', 'penawaran'])
            ->findOrFail($validated['pemberitahuan_id']);

        $kegiatan_id = $pemberitahuan->kegiatan->id;

        DB::transaction(function () use (
            $penawaran,
            $hargaLama,
            $validated,
            $kegiatan_id,
            $request,
        ) {
            $penawaran->update([
                'kegiatan_id'   => $kegiatan_id,
                'tgl_penawaran' => Carbon::parse($validated['tgl_surat_penawaran']),
                'no_penawaran'  => $validated['no_penawaran'],
                'is_winner'     => $request->isWinner(),
            ]);

            Penawaran::where('pemberitahuan_id', $validated['pemberitahuan_id'])
                ->where('id', '!=', $penawaran->id)
                ->update(['is_winner' => !$request->isWinner()]);

            foreach ($hargaLama as $index => $row) {
                $row->update([
                    'harga_satuan' => $validated['harga_satuan'][$index],
                ]);
            }
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
            
            $jumlah_2 = $penawaranPembanding->sum(fn ($i) => $i['volume'] * $i['harga_satuan']);
            
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
