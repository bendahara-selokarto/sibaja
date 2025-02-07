<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\Http\Request;
use App\Models\Pemberitahuan;
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
        $kegiatan = Kegiatan::find(8);
        $pemberitahuan = $kegiatan->pemberitahuan;
        $penyedia = $pemberitahuan->penyedia;
       
        

        
        return view('menu.pemberitahuan' , ['kegiatan' => $kegiatan , 'pemberitahuan' => $pemberitahuan, "penyedia" => $penyedia]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
      

        $penyedia = Penyedia::select('nama_penyedia', 'id')->get();
        
        $kegiatan = Kegiatan::find($id);
        return view('form.pemberitahuan', ['kegiatan' => $kegiatan, 'penyedia' => $penyedia]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
                toastr()->error('Gagal membuat PDF.');
                return redirect()->back();
            }

            return $pdf->stream();
        }
}
