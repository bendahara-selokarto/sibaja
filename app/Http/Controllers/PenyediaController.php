<?php

namespace App\Http\Controllers;

use App\Models\Penyedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PenyediaRequest;

class PenyediaController extends Controller
{
    public function index(){
       
       $data = Penyedia::with('createdBy')
        ->whereHas('createdBy', fn($q) => $q->where('id', '!=', Auth::id()))
        ->whereDoesntHave('users', fn($q) => $q->where('users.id', Auth::id()))
        ->get();
        return view('submenu.bank-penyedia', ['penyedia' => $data ]);
    }

    public function detachPenyedia($id)
    {
        $user = Auth::user();
        $user->penyedias()->detach($id);

        return back()->with('success', 'Relasi penyedia berhasil dihapus');
    }

    public function show()
    {
        $user = Auth::user();

        $penyedia = $user->penyedias()->get();
        $penyedia->load('createdBy');
        $penyedia->transform(function ($item) use ($user) {
           $createdBy = $item->createdBy->desa === $user->desa ? true : $item->createdBy->desa;
            return $item;
        });

        
        return view('menu.penyedia', [
          'penyedia' => $penyedia
        ]);
    }


    public function create() {
        $penyedia = new Penyedia;
        
        return view('form.penyedia' , compact('penyedia'));
    }

    public function store(PenyediaRequest $request) {

 
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
            $path_kop = '';
        }

        if ($request->hasFile('data_dukung')) {
            $path_data_dukung = $request->file('data_dukung')->storePubliclyAs(
                'data_dukung', 
                Auth::user()->kode_desa . $request->file('data_dukung')->getClientOriginalName(), 
                'public'
            );
        } else {
            $path_data_dukung = '';
        }   
        
    
        $nama_penyedia = $request->nama_penyedia;
        $data = [
            'created_by' => Auth::id(),
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
            'rekening' => $request->rekening,
            'bank' => $request->bank,
            'atas_nama' => $request->atas_nama,
            'logo_penyedia' => $path,
            'kop_surat' => $path_kop,
            'kabupaten' => $request->kabupaten,
            ];

        $penyedia = Penyedia::create($data);
        $user = Auth::user();
        $user->penyedias()->syncWithoutDetaching([ $penyedia->id ]);
        flash()->success('Berhasi Menambah Penyedia ' . $nama_penyedia);
         
        
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
            $path_kop = $penyedia->kop_surat;
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
            'rekening' => $request->rekening,
            'bank' => $request->bank,
            'atas_nama' => $request->atas_nama,
            'logo_penyedia' => $path,
            'kop_surat' => $path_kop,
            'kabupaten' => $request->kabupaten,
                ];

            $penyedia->update($data);
            $user = Auth::user();
            $user->penyedias()->syncWithoutDetaching([ $penyedia->id ]);
            flash()->success('Berhasi Update Penyedia');
            return redirect()->route('menu.penyedia');


    }
}
