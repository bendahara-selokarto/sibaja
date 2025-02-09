<?php

namespace App\Http\Controllers;

use App\Kategori;
use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\Http\Request;
use App\Models\Pemberitahuan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PBJController extends Controller
{
    
    public function create($id)
    {
        //ambil kolom nama penyedia, id

        $penyedia = Penyedia::select('nama_penyedia', 'id')->get();
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('You must be logged in to access this page.');
        }
        $kegiatan = Kegiatan::find($id)->first();
        return view('form.pemberitahuan', ['kegiatan' => $kegiatan, 'penyedia' => $penyedia]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
       
        $inputField1 = $request->input('inputField1');  
        $inputField2 = $request->input('inputField2');  
        $inputField3 = $request->input('inputField3');

        $request->validate([
            'rekening_apbdes' => 'required',
        ]);

        $pekerjaan = []; 
        $belanja = []; 
        for ($i = 0; $i < count($inputField1); $i++) { 
            $belanja[] = [ 
                'field0' => $i+ 1, 
                'field1' => $inputField1[$i], 
                'field2' => $inputField2[$i], 
                'field3' => $inputField3[$i], 
            ]; 
            $pekerjaan[] = [ 
                'field1' => $inputField1[$i], 
            ]; 
        } 
        $string = implode(", ", array_column($pekerjaan, "field1"));
        $request->validate([
            'tgl_pemberitahuan' => 'required|date',
        ]);
        $tgl_surat_pemberitahuan = Carbon::parse($request->input('tgl_pemberitahuan'));
        $tgl_batas_akhir_penawaran = $tgl_surat_pemberitahuan->copy()->modify('+3 days');

        $data = [
            // 'rekening_apbdes' => $request->input('rekening_apbdes'),
            'kegiatan_id' => $request->input('kegiatan_id'),
            'belanja' => $belanja,
            'pekerjaan' => $string,
            'tgl_surat_pemberitahuan' => Carbon::parse($request->input('tgl_pemberitahuan')),
            'tgl_batas_akhir_penawaran' => $tgl_batas_akhir_penawaran,
            'penyedia' => $request->input('penyedia'),
            'no_pbj' => $request->input('no_pbj'),
        ];

        $saveSpem = Pemberitahuan::updateOrCreate(
            ['rekening_apbdes' => $request->input('rekening_apbdes')],
            $data
        );
        $spem = Pemberitahuan::where('kode_desa', Auth::user()->kode_desa)->get();
        return redirect()->route('menu.kegiatan')->with('pemberitahuan', $spem);
        

    
    }

    public function getEnumKategori()   {
        dd(Kategori::Kecil->value);
    }

}