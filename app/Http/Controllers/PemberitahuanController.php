<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\Http\Request;
use App\Models\Pemberitahuan;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PemberitahuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $kegiatan = Kegiatan::with('pemberitahuan')->find(8);
        // $kegiatan = Kegiatan::find(8);
        // $pemberitahuan = $kegiatan->pemberitahuan;
        // $penyedia = $pemberitahuan->penyedia;
       
        // return view('menu.pemberitahuan' , ['kegiatan' => $kegiatan , 'pemberitahuan' => $pemberitahuan, "penyedia" => $penyedia]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $penyedia = Penyedia::select('nama_penyedia', 'id')->where('kode_desa' , Auth::user()->kode_desa)->get();
        
        $kegiatan = Kegiatan::find($id);
        $nomor = Pemberitahuan::where('kode_desa', Auth::user()->kode_desa)->count() + 1;

        // $nomor = str_pad($nomor, 3, '0', STR_PAD_LEFT);
        
        return view('form.pemberitahuan', ['kegiatan' => $kegiatan, 'penyedia' => $penyedia, 'nomor' => $nomor]);
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
        $tgl_batas_akhir_penawaran = Carbon::parse($request->input('tgl_batas_akhir_penawaran'));

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function render(string $id)
        {
           
            $kegiatan = Kegiatan::with('pemberitahuan')->find($id);         
            $pemberitahuan = $kegiatan->pemberitahuan;
            
            
            
            if (!$pemberitahuan) {
                noty()->error('Pemberitahuan belum dibuat.');
                return redirect()->back();
            }

            $pdf = Pdf::loadView('pdf.pemberitahuan', ['pemberitahuan' => $pemberitahuan, 'kegiatan' => $kegiatan] );

            if (!$pdf) {
                flash()->error('Gagal membuat PDF.');
                return redirect()->back();
            }

            return $pdf->stream();
        }
}
