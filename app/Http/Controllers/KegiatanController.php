<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemberitahuan;
use App\Models\Penawaran_1;
use App\Models\Penawaran_2;

use function PHPUnit\Framework\isEmpty;

class KegiatanController extends Controller
{
    /**
     * Menampilkan menu Penyedia
     */
    public function index(): View
    {
        $kegiatan = Kegiatan::where('kode_desa', Auth::user()->kode_desa)
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
        $penyedia = new Penyedia();

        if($penyedia->count() == 0){
            session()->flash('error', 'Belum ada penyedia');
            return redirect()->route('menu.penyedia');
        }

        $kegiatan = new Kegiatan();
        return view('form.kegiatan', compact('kegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'rekening_apbdes' => 'required|string',
                'kegiatan' => 'required|string',
                'lokasi_kegiatan' => 'required|string',
                'ketua_tpk' => 'required|string',
                'sekretaris_tpk' => 'required|string',
                'anggota_tpk' => 'required|string',
                'pka' => 'required|string',
            ]);
        
            Kegiatan::create($validatedData);
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
        $kegiatan = Kegiatan::with('pemberitahuan')->find($id);
        if($kegiatan->pemberitahuan && $kegiatan->pemberitahuan->count() > 0){

        $penyedia = Pemberitahuan::where('kegiatan_id', $id)->pluck('penyedia')->toArray();
        $penyedia_1  = Penyedia::find($penyedia[0][0]);
        $penyedia_2  = Penyedia::find($penyedia[0][1]);
        $nama_penyedia_1 = $penyedia_1->nama_penyedia;
        $nama_penyedia_2 = $penyedia_2->nama_penyedia;
        }else{
            $penyedia = collect();
            $penyedia_1 = new Penyedia();
            $penyedia_2 = new Penyedia();
            $nama_penyedia_1 = '';
            $nama_penyedia_2 = '';

        }
       
        $pemberitahuan = Pemberitahuan::where('kegiatan_id', $id)->get();

        return view('menu.kegiatan-detail')
        ->with('kegiatan', $kegiatan)
        ->with('pemberitahuan', $pemberitahuan)
        ->with('penyedia', $penyedia[0] ?? collect())
        ->with('nama_penyedia_1', $nama_penyedia_1)
        ->with('penyedia_1', $penyedia_1)
        ->with('penyedia_2', $penyedia_2)
        ->with('nama_penyedia_2', $nama_penyedia_2);
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
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'rekening_apbdes' => 'required|string',
                'kegiatan' => 'required|string',
                'lokasi_kegiatan' => 'required|string',
                'ketua_tpk' => 'required|string',
                'sekretaris_tpk' => 'required|string',
                'anggota_tpk' => 'required|string',
                'pka' => 'required|string',
            ]);

            $kegiatan = Kegiatan::find($id);
            $kegiatan->update($validatedData);
            noty()->success('terupdate');
            
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

        if ($kegiatan->pemberitahuan && $kegiatan->pemberitahuan->count() > 0) {
            flash()->error('kegiatan sudah memiliki pemberitahuan');
            return back();
        }
            // $kegiatan->delete();
            $deleted = Kegiatan::where('id', $id)->delete();
            flash()->success('kegiatan berhasil diahpus');
       
        return redirect()->route('menu.kegiatan');
    }
}
