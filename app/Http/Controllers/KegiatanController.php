<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemberitahuan;

class KegiatanController extends Controller
{
    /**
     * Menampilkan menu Penyedia
     */
    public function index(): View
    {
        $kegiatan = Kegiatan::with('pemberitahuan')->with('penawaran_1')->with('penawaran_2')->with('negosiasiHarga')->with('pembayaran')->where('kode_desa', Auth::user()->kode_desa)->orderBy('created_at', 'desc')->get();
        
        return view('menu.kegiatan')->with( 'kegiatans' , $kegiatan );
    }
    public function create()
    {
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
        $kegiatan = Kegiatan::find($id);
        if (!$kegiatan) {
            flash()->error('kegiatan tidak ditemukan');
            return redirect()->route('menu.kegiatan');
        }
        $pemberitahuan = Pemberitahuan::where('kegiatan_id', $id)->get();
        if ($pemberitahuan->isEmpty()) {
            flash()->error('kegiatan tidak memiliki pemberitahuan');
        }

        return view('detail.kegiatan')->with('kegiatan', $kegiatan)->with('pemberitahuan', $pemberitahuan);
        // return response()->json($data);
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
            'ketua_tpk' => 'required|string',
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
