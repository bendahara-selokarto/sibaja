<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kegiatan = Kegiatan::with('penyedia')->get();
      
        
        $kegiatan   = Kegiatan::where('kode_desa' , Auth::user()->kode_desa)->get();       
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
            noty()->success('berhasil tersimpan');
            
        } catch (\Throwable $th) {
            noty()->error($th);
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
            noty()->error($th);
        }


        return redirect()->route('menu.kegiatan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
