<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenawaranHargaRequest;
use App\Models\Kegiatan;
use App\Models\Penawaran;
use App\Models\Penyedia;
use DomainException;
use App\UseCases\Penawaran\StorePenawaranInput;
use App\UseCases\Penawaran\StorePenawaranUseCase;
use App\UseCases\Penawaran\UpdatePenawaranInput;
use App\UseCases\Penawaran\UpdatePenawaranUseCase;
use App\UseCases\Penawaran\BuildPenawaranReportUseCase;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;



class PenawaranHargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
       
        
        return view('menu.penawaran');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($kegiatanId, $penyediaId)
    {
        $kegiatan = Kegiatan::with('pemberitahuan')->find($kegiatanId);
        
        $statusPemenang = $kegiatan->statusPemenang();
        
        $penyedia = Penyedia::find($penyediaId);
              
        $pemberitahuan = $kegiatan->pemberitahuan;
        $belanja = collect($pemberitahuan->belanjas)->map(function ($item, $key) {
            return [
                'uraian' => $item['uraian'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'],
            ];
        });
        
        return view('form.penawaran-harga', [
            'kegiatan' => $kegiatan,
            'pemberitahuan' => $pemberitahuan,
            'penyedia' => $penyedia,
            'belanja' => $belanja,
            'statusPemenang' => $statusPemenang,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        PenawaranHargaRequest $request,
        StorePenawaranUseCase $storePenawaranUseCase,
    )
    {
        $validated = $request->validated();

        $penawaran = $storePenawaranUseCase->execute(new StorePenawaranInput(
            pemberitahuanId: $validated['pemberitahuan_id'],
            penyediaId: $validated['penyedia'],
            tglSuratPenawaran: $validated['tgl_surat_penawaran'],
            noPenawaran: $validated['no_penawaran'],
            isWinner: $request->isWinner(),
            hargaSatuan: $validated['harga_satuan'],
        ));

        return redirect()->route('kegiatan.show', ['id' => $penawaran->kegiatan_id]);
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
    public function edit(string $kegiatanId, string $penyediaId)
    {
       $kegiatan = Kegiatan::with('pemberitahuan' , 'penawaran')->find($kegiatanId);

        $penyedia = Penyedia::find($penyediaId);
              
        $pemberitahuan = $kegiatan->pemberitahuan;

        $pemberitahuan->load('penawaran');

        $penawaran = $pemberitahuan->penawaran->firstWhere('penyedia_id', $penyediaId);

        $penawaran->load('hargaPenawaran');

        $harga_penawaran = $penawaran->hargaPenawaran()->orderBy('id', 'ASC')->get();

        
        $harga_satuan = $harga_penawaran->pluck('harga_satuan')->values();
        $belanja = collect($pemberitahuan->belanjas)->map(function ($item, $key) use ($harga_satuan) {
            return [
                'uraian' => $item['uraian'],
                'volume' => $item['volume'],
                'satuan' => $item['satuan'],
                'harga_satuan' => $harga_satuan->get($key),
            ];
        });
        $penawaran->tgl_penawaran = Carbon::parse($penawaran->tgl_penawaran)->format('Y-m-d');

        return view('form.penawaran-harga', [
            'kegiatan' => $kegiatan,
            'pemberitahuan' => $pemberitahuan,
            'penyedia' => $penyedia,
            'belanja' => $belanja,
            'penawaran' => $penawaran,
            'isEdit' => true,
        ]); 
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        PenawaranHargaRequest $request,
        string $pemberitahuanId,
        UpdatePenawaranUseCase $updatePenawaranUseCase,
    )
    {
        $validated = $request->validated();

        $penawaran = $updatePenawaranUseCase->execute(new UpdatePenawaranInput(
            pemberitahuanId: $validated['pemberitahuan_id'],
            penyediaId: $validated['penyedia'],
            tglSuratPenawaran: $validated['tgl_surat_penawaran'],
            noPenawaran: $validated['no_penawaran'],
            isWinner: $request->isWinner(),
            hargaSatuan: $validated['harga_satuan'],
        ));
                
        flash()->success('Penawaran berhasil diupdate.');

        return redirect()->route('kegiatan.show', ['id' => $penawaran->kegiatan_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penawaran = Penawaran::where('kegiatan_id', $id)->get();
       
        $penawaran->each->delete();

        flash()->success('Penawaran berhasil dihapus.');
        
        return redirect()->route('kegiatan.show', ['id' => $id]);
    }
    public function render(
        string $id,
        BuildPenawaranReportUseCase $buildPenawaranReportUseCase,
    )
    {
        try {
            $report = $buildPenawaranReportUseCase->execute($id);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $pdf = Pdf::loadView('pdf.penawaran-harga', $report->toViewData());

        $filename = '2. PENAWARAN HARGA - (' . $report->kegiatan->kegiatan . ')';

        return $pdf->stream(sanitize_filename($filename) . '.pdf');
       
    }
}
