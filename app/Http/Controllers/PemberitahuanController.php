<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithTenantScope;
use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\Http\Request;
use App\Models\Pemberitahuan;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemberitahuanController extends Controller
{
    use InteractsWithTenantScope;

    
    public function create($id)
    {
        $user = Auth::user();
        $penyedia = $user->penyedias()->get();     
        $kegiatan = $this->findTenantKegiatan($id);
        abort_if($kegiatan === null, 404);
        $nomor = Pemberitahuan::where('kode_desa', Auth::user()->kode_desa)->count() + 1;

        // $nomor = str_pad($nomor, 3, '0', STR_PAD_LEFT);
        
        return view('form.pemberitahuan', [
            'kegiatan' => $kegiatan, 
            'penyedia' => $penyedia, 
            'no_pbj' => $nomor,
            'pemberitahuan' => null,
            'belanja' => collect([['nomor' => 1, 'uraian' => '', 'volume' => '', 'satuan' => '']]),
            'penyediaTerpilih' => []
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rekening_apbdes' => 'required|string|max:255',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'penyedia'   => 'required|array|size:2',
            'penyedia.*' => [
                'required',
                'distinct',
                Rule::exists('daftar_penyedia', 'penyedia_id')->where(
                    fn ($query) => $query->where('user_id', Auth::id())
                ),
            ],
            'no_pbj' => 'required|string|max:255',
            'tgl_pemberitahuan' => 'required|date',
            'uraian.*' => 'required|string|max:255',
            'volume.*' => 'nullable|numeric',
            'satuan.*' => 'nullable|string|max:100',
        ]);

                 
       
        $kegiatan = $this->findTenantKegiatan((string) $request->input('kegiatan_id'));
        abort_if($kegiatan === null, 404);

        $uraian = $request->input('uraian');  
        $volume = $request->input('volume');  
        $satuan = $request->input('satuan');

        $belanja = collect($uraian)->map(function ($item, $key) use ($volume, $satuan) {
            return [
                'uraian' => $item,
                'volume' => $volume[$key] ?? null,
                'satuan' => $satuan[$key] ?? null,
            ];
        });

        $pekerjaan =  collect($belanja)->implode('uraian',', ');
        
         $data = $request->only([
        'rekening_apbdes',
        'kegiatan_id',
        'penyedia',
        'no_pbj',
        ]);

        $data['pekerjaan'] = $pekerjaan;

        $data['kegiatan_id'] = $kegiatan->id;
        $data['tgl_surat_pemberitahuan'] = $request->input('tgl_pemberitahuan');

        $data['tgl_batas_akhir_penawaran'] = Carbon::parse($request->input('tgl_pemberitahuan'))->addDays(3);

        DB::transaction(function () use ($data, $belanja, $request) {
            $saveSpem = Pemberitahuan::create($data);
            $saveSpem->syncSelectedPenyedias($request->input('penyedia', []));
            $saveSpem->belanjas()->createMany($belanja->toArray());
        });

        return redirect()->route('kegiatan.show' , $request->kegiatan_id);
        
        
    }
     
    public function edit(string $id)
    {
        $pemberitahuan = $this->findTenantPemberitahuan($id, ['belanjas', 'penyedias']);
        abort_if($pemberitahuan === null, 404);

        $penyediaTerpilih = $pemberitahuan->selectedPenyediaIds();
        $penyedia = Auth::user()->penyedias()->select('penyedias.nama_penyedia', 'penyedias.id')->get();
        
        $kegiatan = $this->findTenantKegiatan((string) $pemberitahuan->kegiatan_id);
        abort_if($kegiatan === null, 404);

        $belanja = $pemberitahuan->belanjas;
        
        return view('form.pemberitahuan', [
            'pemberitahuan' => $pemberitahuan, 
            'penyedia' => $penyedia, 'kegiatan' => $kegiatan, 
            'penyediaTerpilih' => $penyediaTerpilih, 
            'belanja' => $belanja]);
        }
        
    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, string $id)
{
    $request->validate([
        'rekening_apbdes' => 'required|string|max:255',
        'kegiatan_id' => 'required|exists:kegiatans,id',
        'penyedia'   => 'required|array|size:2',
        'penyedia.*' => [
            'required',
            'distinct',
            Rule::exists('daftar_penyedia', 'penyedia_id')->where(
                fn ($query) => $query->where('user_id', Auth::id())
            ),
        ],
        'no_pbj' => 'required|string|max:255',
        'tgl_pemberitahuan' => 'required|date',
        'uraian.*' => 'required|string|max:255',
        'volume.*' => 'nullable|numeric',
        'satuan.*' => 'nullable|string|max:100',
    ]);

    $pemberitahuan = $this->findTenantPemberitahuan($id);
    abort_if($pemberitahuan === null, 404);

    $kegiatan = $this->findTenantKegiatan((string) $request->input('kegiatan_id'));
    abort_if($kegiatan === null || (string) $pemberitahuan->kegiatan_id !== (string) $kegiatan->id, 404);

    $uraian = $request->input('uraian');
    $volume = $request->input('volume');
    $satuan = $request->input('satuan');

    $belanja = collect($uraian)->map(function ($item, $key) use ($volume, $satuan) {
        return [
            'uraian' => $item,
            'volume' => $volume[$key] ?? null,
            'satuan' => $satuan[$key] ?? null,
        ];
    });

    $pekerjaan = collect($belanja)->implode('uraian', ',');

    $data = $request->only([
        'rekening_apbdes',
        'kegiatan_id',
        'penyedia',
        'no_pbj',
    ]);

    $data['pekerjaan'] = $pekerjaan;
    $data['kegiatan_id'] = $kegiatan->id;
    $data['tgl_surat_pemberitahuan'] = $request->input('tgl_pemberitahuan');
    $data['tgl_batas_akhir_penawaran'] = Carbon::parse($request->input('tgl_pemberitahuan'))->addDays(3);

    DB::transaction(function () use ($pemberitahuan, $data, $request, $belanja) {
        $pemberitahuan->update($data);
        $pemberitahuan->syncSelectedPenyedias($request->input('penyedia', []));
        $pemberitahuan->belanjas()->delete();
        $pemberitahuan->belanjas()->createMany($belanja->toArray());
    });

    return redirect()->route('kegiatan.show' , ['id' => $request->input('kegiatan_id')]);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kegiatan = $this->findTenantKegiatan($id);
        abort_if($kegiatan === null, 404);

        $pemberitahuan = $this->scopedPemberitahuanQuery()->where('kegiatan_id', $kegiatan->id)->first();
        if ($pemberitahuan) {
            $pemberitahuan->delete();
            noty()->success('Pemberitahuan berhasil dihapus.');
        } else {
            noty()->error('Pemberitahuan tidak ditemukan.');
        }
        return redirect()->route('kegiatan.show', ['id' => $id]);
    }

    public function render(string $id)
        {
            $kegiatan = $this->findTenantKegiatan($id, ['pemberitahuan']);
            abort_if($kegiatan === null, 404);

            $pemberitahuan = $this->scopedPemberitahuanQuery(['belanjas', 'penyedias'])
                ->where('kegiatan_id', $kegiatan->id)
                ->first();
            abort_if($pemberitahuan === null, 404);

            $belanja = $pemberitahuan->belanjas;
            // dd($belanja);

            $pdf = Pdf::loadView('pdf.pemberitahuan', ['pemberitahuan' => $pemberitahuan, 'kegiatan' => $kegiatan , 'belanja' => $belanja] );

            if (!$pdf) {
                flash()->error('Gagal membuat PDF.');
                return redirect()->back();
            }

            // Replace all invalid filename characters with underscore
            $safeKegiatan = preg_replace('/[\/\\\:\*\?"<>\|]/', '_', $kegiatan->kegiatan);
            return $pdf->stream('1. PEMBERITAHUAN- (' . $safeKegiatan . ').pdf');
        }
}
