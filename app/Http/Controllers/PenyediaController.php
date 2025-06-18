<?php

namespace App\Http\Controllers;

use App\Models\Penyedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenyediaController extends Controller
{
    public function index(){
       
        $data = Penyedia::where('kode_desa' , Auth::user()->kode_desa)->get();
        return view('menu.penyedia', ['penyedia' => $data ]);
    }
    public function create() {
        $penyedia = new Penyedia;
        
        return view('form.penyedia' , compact('penyedia'));
    }

    public function store(Request $request) {

 
        if ($request->hasFile('logo_penyedia')) {
            $path = $request->file('logo_penyedia')->storePubliclyAs(
                'logo', 
                Auth::user()->kode_desa . $request->file('logo_penyedia')->getClientOriginalName(), 
                'public'
            );
        } else {
            $path = 'logo/default.png';
        }
        if ($request->hasFile('kop_surat')) {
            $path_kop = $request->file('kop_surat')->storePubliclyAs(
                'kop_surat', 
                Auth::user()->kode_desa . $request->file('kop_surat')->getClientOriginalName(), 
                'public'
            );
        } 
        if ($request->hasFile('data_dukung')) {
            $path_kop = $request->file('data_dukung')->storePubliclyAs(
                'data_dukung', 
                Auth::user()->kode_desa . $request->file('data_dukung')->getClientOriginalName(), 
                'public'
            );
        } 
        
    
        $nama_penyedia = $request->nama_penyedia;
        $data = [
            'nama_penyedia' => $nama_penyedia,
            'alamat_penyedia' => $request->alamat_penyedia,
            'nama_pemilik' => $request->nama_pemilik,
            'alamat_pemilik' => $request->alamat_pemilik,
            'nomor_hp' => $request->nomor_hp,
            'nomor_identitas' => $request->nomor_identitas,
            'nomor_npwp' => $request->nomor_npwp,
            'nomor_izin_usaha' => $request->no_siup,
            'jabata_pemilik' => $request->jabatan_pemilik,
            'instansi_pemberi_izin_usaha' => $request->penerbit_siup,
            'logo_penyedia' => $path,
            'kop_surat' => $path_kop,
            ];

        $penyedia = Penyedia::create($data);
         
        
        return redirect()->route('menu.penyedia');
    }

    public function destroy($id){
        

        $penyedia = Penyedia::find($id);
        if (!$penyedia) {
            flash()->error('penyedia tidak ditemukan');
            return redirect()->route('menu.penyedia');
        }

        if($penyedia->pemberitahuan){
            if ($penyedia->pemberitahuan->count() > 0) {
                flash()->error('penyedia sudah memiliki pemberitahuan');
                return back();
            }
        }
            
        
        if($penyedia){
            $penyedia->delete();
            flash()->success('penyedia berhasil diahpus');
            return redirect()->route('menu.penyedia');
        } else {
            flash()->error('penyedia tidak ditemukan');
            return redirect()->route('menu.penyedia');
        }
    }

    public function edit($id){

        $penyedia = Penyedia::find($id);
        return view('form.penyedia', compact('penyedia'));
    }

    public function update( Request $request, $id){
        $penyedia = Penyedia::find($id);
        if (!$penyedia) {
            flash()->error('Penyedia tidak ditemukan');
            return redirect()->route('menu.penyedia');
        }
        if ($request->hasFile('logo_penyedia')) {
        
        $path = $request->file('logo_penyedia')->storePubliclyAs(
            'logo',
            Auth::user()->kode_desa . $request->file('logo_penyedia')->getClientOriginalName(),
            'public'
        );
        } else {
            $path = 'logo/default.png';
        }

        if ($request->hasFile('kop_surat')) {
            $path_kop = $request->file('kop_surat')->storePubliclyAs(
                'kop_surat', 
                Auth::user()->kode_desa . $request->file('kop_surat')->getClientOriginalName(), 
                'public'
            );
        } else {
            $path_kop = 'kop_surat/default.png';
        }

        
        $data = [
            'nama_penyedia' => $request->nama_penyedia,
            'alamat_penyedia' => $request->alamat_penyedia,
            'nama_pemilik' => $request->nama_pemilik,
            'alamat_pemilik' => $request->alamat_pemilik,
            'nomor_hp' => $request->nomor_hp,
            'nomor_identitas' => $request->nomor_identitas,
            'nomor_npwp' => $request->nomor_npwp,
            'nomor_izin_usaha' => $request->no_siup,
            'jabata_pemilik' => $request->jabatan_pemilik,
            'instansi_pemberi_izin_usaha' => $request->penerbit_siup,
            'logo_penyedia' => $path,
            'kop_surat' => $path_kop,
                ];

            $penyedia->update($data);
            flash()->success('Berhasi Update Penyedia');
            return redirect()->route('menu.penyedia');


    }
}
