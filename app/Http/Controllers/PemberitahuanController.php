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
        $uraian = $request->input('uraian');  
        $volume = $request->input('volume');  
        $satuan = $request->input('satuan');

        $belanja = collect($uraian)->map(function ($item, $key) use ($volume, $satuan) {
            return [
                'nomor' => $key + 1,
                'uraian' => $item,
                'volume' => $volume[$key] ?? null,
                'satuan' => $satuan[$key] ?? null,
            ];
        });

        $pekerjaan =  collect($belanja)->implode('uraian',',');
        
         $data = $request->only([
        'rekening_apbdes',
        'kegiatan_id',
        'penyedia',
        'no_pbj',
        ]);

        $data['belanja'] = $belanja;
        $data['pekerjaan'] = $pekerjaan;
        $data['tgl_surat_pemberitahuan'] = $request->input('tgl_pemberitahuan'); // otomatis parse ke Carbon

        $saveSpem = Pemberitahuan::create($data);
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
         $uraian = $request->input('uraian');  
        $volume = $request->input('volume');  
        $satuan = $request->input('satuan');

        $belanja = collect($uraian)->map(function ($item, $key) use ($volume, $satuan) {
            return [
                'nomor' => $key + 1,
                'uraian' => $item,
                'volume' => $volume[$key] ?? null,
                'satuan' => $satuan[$key] ?? null,
            ];
        });

        $pekerjaan =  collect($belanja)->implode('uraian',',');
        
         $data = $request->only([
        'rekening_apbdes',
        'kegiatan_id',
        'penyedia',
        'no_pbj',
        ]);

        $data['belanja'] = $belanja;
        $data['pekerjaan'] = $pekerjaan;
        $data['tgl_surat_pemberitahuan'] = $request->input('tgl_pemberitahuan'); // otomatis parse ke Carbon

        $saveSpem = Pemberitahuan::create($data);
        $spem = Pemberitahuan::where('kode_desa', Auth::user()->kode_desa)->get();
        return redirect()->route('menu.kegiatan')->with('pemberitahuan', $spem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemberitahuan = Pemberitahuan::where('kegiatan_id', $id)->first();
        if ($pemberitahuan) {
            $pemberitahuan->delete();
            noty()->success('Pemberitahuan berhasil dihapus.');
        } else {
            noty()->error('Pemberitahuan tidak ditemukan.');
        }
        return redirect()->route('menu.kegiatan');
    }

    public function render(string $id)
        {
           
            $pemberitahuan = Pemberitahuan::where('kegiatan_id', $id)->first();
            if (!$pemberitahuan) {
                noty()->error('Pemberitahuan tidak ditemukan.');
                return redirect()->back();
            }
            $kegiatan = Kegiatan::with('pemberitahuan')->find($id);
            if (!$kegiatan) {
                noty()->error('Kegiatan tidak ditemukan.');
                return redirect()->back();
            }         
            // $pemberitahuan = $kegiatan->pemberitahuan;

            $pdf = Pdf::loadView('pdf.pemberitahuan', ['pemberitahuan' => $pemberitahuan, 'kegiatan' => $kegiatan] );

            if (!$pdf) {
                flash()->error('Gagal membuat PDF.');
                return redirect()->back();
            }

            // Replace all invalid filename characters with underscore
            $safeKegiatan = preg_replace('/[\/\\\:\*\?"<>\|]/', '_', $kegiatan->kegiatan);
            return $pdf->stream('1. PEMBERITAHUAN- (' . $safeKegiatan . ').pdf');
        }
}
