<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\KegiatanRequest;
use App\Models\Pemberitahuan;
use Barryvdh\DomPDF\Facade\Pdf;

class KegiatanController extends Controller
{
    /**
     * Menampilkan menu Penyedia
     */
    public function index(): View
    {
        $kegiatan = Kegiatan::where('kode_desa', Auth::user()->kode_desa)
            ->where('tahun_anggaran', Auth::user()->tahun_anggaran)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($kegiatan->isEmpty()) {
            // Jika tidak ada kegiatan, langsung render tanpa data tambahan
            return view('menu.kegiatan')->with('kegiatans', collect());
        }
       
        return view('menu.kegiatan')->with('kegiatans', $kegiatan);           
    }

    public function create()
    {
        if (!Penyedia::cukupUntukKegiatan(Auth::user()->kode_desa)) {
            return redirect()
                ->route('menu.penyedia')
                ->with(
                    'error',
                    Penyedia::pesanError(Auth::user()->kode_desa)
                );
        }

        return view('form.kegiatan', [
            'kegiatan' => new Kegiatan()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(KegiatanRequest $request)
    {
        try {
            Kegiatan::create($request->validated());
            noty()->success('berhasil ditambahkan');
        } catch (\Throwable $th) {
            noty()->error($th->getMessage());
        }
        return redirect()->route('menu.kegiatan');
    }
    
    /**
     * Display the specified resource.
    */
    public function show(string $id)
    {
        $kegiatan = Kegiatan::with([
            'pemberitahuan.penawaran',
            'pemberitahuan.belanjas',
            'negosiasiHarga',
            'pembayaran',
        ])->findOrFail($id);

        $btn = [
            'penawaran-create' => false,
            'penawaran-render' => false,
            'negosiasi-create' => false,
            'negosiasi-render' => false,
            'pembayaran-create' => false,
            'pembayaran-render' => false,
        ];

        $pemberitahuan = $kegiatan->pemberitahuan;
        $penawaran = collect();
        $penyedia = collect();
        $penyediaAda = collect();

        if ($pemberitahuan) {
            $penawaran = $pemberitahuan->penawaran;
            $penyediaIds = collect($pemberitahuan->penyedia ?? []);
            $penyediaDenganPenawaranIds = $penawaran
                ->pluck('penyedia_id')
                ->unique()
                ->values();

            if ($penyediaIds->isNotEmpty()) {
                $penyedia = Penyedia::whereIn(
                    'id',
                    $penyediaIds->diff($penyediaDenganPenawaranIds)
                )->get();
            }

            if ($penyediaDenganPenawaranIds->isNotEmpty()) {
                $penyediaAda = Penyedia::whereIn('id', $penyediaDenganPenawaranIds)->get();
            }

            $btn['penawaran-create'] = $penawaran->isNotEmpty();
            $btn['penawaran-render'] = $penawaran->count() > 1;
            $btn['negosiasi-create'] = $btn['penawaran-render'] && $kegiatan->negosiasiHarga === null;
            $btn['negosiasi-render'] = $kegiatan->negosiasiHarga !== null;
            $btn['pembayaran-create'] = $btn['negosiasi-render'] && $kegiatan->pembayaran === null;
            $btn['pembayaran-render'] = $kegiatan->pembayaran !== null;
        }

        return view('menu.kegiatan-detail')
            ->with('kegiatan', $kegiatan)
            ->with('btn', $btn)
            ->with('pemberitahuan', $pemberitahuan)
            ->with('penawaran', $penawaran)
            ->with('penyediaAda', $penyediaAda)
            ->with('penyedia', $penyedia);
       
    }
    
    /**
     * Show the form for editing the specified resource.
    */
    public function edit(string $id)
    {
        $kegiatan = Kegiatan::find($id);
        if (!$kegiatan) {
            flash()->error('kegiatan tidak ditemukan');
            return redirect()->route('menu.kegiatan');
        }
        return view('form.kegiatan', compact('kegiatan'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(KegiatanRequest $request, string $id)
    {
        try {
            $kegiatan = Kegiatan::findOrFail($id);
            $kegiatan->update($request->validated());

            noty()->success('Data kegiatan berhasil diperbarui');
        } catch (\Throwable $th) {
            noty()->error($th->getMessage());
        }

        return redirect()->route('menu.kegiatan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kegiatan = Kegiatan::find($id);
        if (!$kegiatan) {
            flash()->error('kegiatan tidak ditemukan');
            return redirect()->route('menu.kegiatan');
        }

        if ($kegiatan->pemberitahuan !== null) {
            flash()->error('kegiatan sudah memiliki pemberitahuan');
            return back();
        }
            // $kegiatan->delete();
            $deleted = Kegiatan::where('id', $id)->delete();
            flash()->success('kegiatan berhasil diahpus');
       
        return redirect()->route('menu.kegiatan');
    }

    public function rekap(string $id){
        $kegiatan = Kegiatan::with('pemberitahuan' , 'penawaran' , 'negosiasiHarga' , 'pembayaran' )->find($id);

        $pemberitahuan = $kegiatan->pemberitahuan;

        $penawaran = $kegiatan->penawaran;

        $namaPenyedia1 = optional(
            Penyedia::find(optional($kegiatan->penawaran->firstWhere('is_winner', true))->penyedia_id)
        )->nama_penyedia;

        $namaPenyedia2 = optional(
            Penyedia::find(optional($kegiatan->penawaran->firstWhere('is_winner', false))->penyedia_id)
        )->nama_penyedia;

        $negosiasiHarga = $kegiatan->negosiasiHarga;

        $pembayaran = $kegiatan->pembayaran;

        $pdf = Pdf::loadView('pdf.rekap' , compact('kegiatan', 'pemberitahuan' , 'penawaran','namaPenyedia1' , 'namaPenyedia2' , 'negosiasiHarga' , 'pembayaran'));
        
        return $pdf->stream();
       
    }
}
