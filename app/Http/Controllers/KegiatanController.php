<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\error;

class KegiatanController extends Controller
{
    /**
     * Menampilkan menu Penyedia
     */
    public function index(): View
    {
        $kegiatan = Kegiatan::where('kode_desa', Auth::user()->kode_desa)->orderBy('created_at', 'desc')->get();
        return view('menu.kegiatan')->with( 'kegiatans' , $kegiatan );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $kegiatan = Kegiatan::where('kode_desa', Auth::user()->kode_desa);
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
                'ketua_tpk' => 'required|string',
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
        //
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
            $kegiatan = Kegiatan::find($id);
            $kegiatan->rekening_apbdes = $request->rekening_apbdes;
            $kegiatan->kegiatan = $request->kegiatan;
            $kegiatan->ketua_tpk = $request->ketua_tpk;
            $kegiatan->pka = $request->pka;
            $kegiatan->save();
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

        try {
            $kegiatan->delete();
            flash()->success('kegiatan berhasil diahpus');
        } catch (\Exception $e) {
            flash()->error($e->getMessage());
        }
        return redirect()->route('menu.kegiatan');
    }
}
