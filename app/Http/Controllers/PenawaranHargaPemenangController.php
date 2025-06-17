<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenawaranHargaPemenangController extends Controller
{
    public function create($id)
    {
        $kegiatan = Kegiatan::with('pemberitahuan')->find($id);
        if (!$kegiatan) {
            noty()->error('Kegiatan tidak ditemukan');
            return redirect()->back();
        }
        
        $pemberitahuan = $kegiatan->pemberitahuan;
        if (!$pemberitahuan) {
            noty()->error('Tidak ada pemberitahuan terkait');
            return redirect()->back();
        }
        
        return view('form.penawaran-pemenang', [
            'kegiatan' => $kegiatan,
            'pemberitahuan' => $pemberitahuan
        ]);
    }
}
