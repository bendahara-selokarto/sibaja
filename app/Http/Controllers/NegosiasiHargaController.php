<?php

namespace App\Http\Controllers;

use App\Http\Requests\NegosiasiRequest;
use App\UseCases\Negosiasi\StoreNegosiasiInput;
use App\UseCases\Negosiasi\StoreNegosiasiUseCase;
use App\UseCases\Negosiasi\UpdateNegosiasiInput;
use App\UseCases\Negosiasi\UpdateNegosiasiUseCase;
use App\Models\Kegiatan;
use App\Models\Penyedia;
use App\Models\Belanja;
use App\Models\NegosiasiHarga;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PajakHelper;
use App\UseCases\Negosiasi\BuildNegosiasiReportUseCase;
use DomainException;



class NegosiasiHargaController extends Controller
{
    public function index()
    {
       
    }
  
    public function create($id)
    {
        $kegiatan = Kegiatan::with('negosiasiHarga', 'penawaran.hargaPenawaran', 'pemberitahuan')->findOrFail($id);

        $penawaran = $kegiatan->penawaran->firstWhere('is_winner', true);

        if (!$penawaran) {
            return back()->with('error', 'PEMENANG belum di set');
        }

        if (!$kegiatan->pemberitahuan) {
            return back()->with('error', 'Pemberitahuan belum dibuat');
        }

        $pemberitahuanId = $penawaran->pemberitahuan_id;

        $tgl_surat_pemberitahuan = $kegiatan->pemberitahuan->tgl_surat_pemberitahuan;

        $belanja = Belanja::where('pemberitahuan_id' , $pemberitahuanId )->get();

        

        $items = $penawaran->hargaPenawaran->map(function ($harga, $i) use ($belanja){
                return [
                    'uraian'       => $belanja[$i]->uraian ?? null,
                    'volume'       => $belanja[$i]->volume ?? null,
                    'satuan'       => $belanja[$i]->satuan ?? null,
                    'harga_penawaran' => $harga->harga_satuan ?? null,
                    'jumlah'       => $belanja[$i]->volume  * $harga->harga_satuan ,
                ];
            });
              
        $kegiatan->tgl = Carbon::parse($tgl_surat_pemberitahuan )->addDays(3)->format('Y-m-d');

       return view('form.negosiasi', compact('kegiatan', 'items'));
    }

    public function store(
        NegosiasiRequest $request,
        StoreNegosiasiUseCase $storeNegosiasiUseCase,
    )
    {
        $validatedData = $request->validated();

        $negosiasi = $storeNegosiasiUseCase->execute(new StoreNegosiasiInput(
            kegiatanId: $validatedData['kegiatan_id'],
            tglPersetujuan: $validatedData['tgl_persetujuan'],
            tglNegosiasi: $validatedData['tgl_negosiasi'],
            tglAkhirPerjanjian: $validatedData['tgl_akhir_perjanjian'],
            hargaSatuanNegosiasi: $validatedData['harga_satuan_negosiasi'],
        ));

        return redirect()->route('kegiatan.show', ['id' => $negosiasi->kegiatan_id])->with('success', 'berhasil menambahkan data');



    }

    public function show(NegosiasiHarga $negosiasiHarga)
    {
        //
    }

    public function edit($kegiatan_id)
    { 
        $kegiatan = Kegiatan::with('pemberitahuan','negosiasiHarga' , 'penawaran')->find($kegiatan_id);

        $pemberitahuan = $kegiatan->pemberitahuan;
        $pemberitahuan->load('belanjas');
        $penawaranHarga = $kegiatan->penawaran()->firstWhere('is_winner' , true);
        $penawaranHarga->load('hargaPenawaran');
        $hargaPenawaran = $penawaranHarga->hargaPenawaran;
        $negosiasi  = $kegiatan->negosiasiHarga ;
        $negosiasi->load('hargaNegosiasi');
        $hargaNegosiasi = $negosiasi->hargaNegosiasi;

        $items = $pemberitahuan->belanjas->map(function($item,$k) 
        use ( $hargaPenawaran , $hargaNegosiasi )       
        {
            return [
                'uraian' => $item->uraian,
                'volume' => $item->volume,
                'satuan' => $item->satuan,
                'harga_penawaran' => $hargaPenawaran[$k]->harga_satuan,
                'harga_negosiasi' => $hargaNegosiasi[$k]->harga_satuan,
                'jumlah_penawaran' => $item->volume * $hargaPenawaran[$k]->harga_satuan,
                'jumlah_negosiasi' => $item->volume * $hargaNegosiasi[$k]->harga_satuan,
            ];
        });

        $kegiatan->tgl = Carbon::parse($penawaranHarga->tgl_penawaran)->format('Y-m-d');

        $negosiasi->tgl_negosiasi = Carbon::parse($negosiasi->tgl_negosiasi)->format('Y-m-d');

        $negosiasi->tgl_persetujuan = Carbon::parse($negosiasi->tgl_persetujuan)->format('Y-m-d');

        $negosiasi->tgl_akhir_perjanjian = Carbon::parse($negosiasi->tgl_akhir_perjanjian)->format('Y-m-d');

       return view('form.negosiasi', compact('kegiatan', 'items' , 'negosiasi'));
    }

    public function update(
        NegosiasiRequest $request,
        $kegiatan_id,
        UpdateNegosiasiUseCase $updateNegosiasiUseCase,
    )
    {
        $validatedData = $request->validated();

        $negosiasi = $updateNegosiasiUseCase->execute(new UpdateNegosiasiInput(
            kegiatanId: $validatedData['kegiatan_id'],
            tglPersetujuan: $validatedData['tgl_persetujuan'],
            tglNegosiasi: $validatedData['tgl_negosiasi'],
            tglAkhirPerjanjian: $validatedData['tgl_akhir_perjanjian'],
            hargaSatuanNegosiasi: $validatedData['harga_satuan_negosiasi'],
        ));

        return redirect()->route('kegiatan.show', ['id' => $negosiasi->kegiatan_id])->with('success', 'berhasil memperbarui data');
    }

    public function destroy($kegiatan_id)
    {
        $negosiasi = NegosiasiHarga::where('kegiatan_id', $kegiatan_id)->first();

        if (!$negosiasi) {
            return redirect()->back()->with('error', 'Negosiasi tidak ditemukan');
        }

        $negosiasi->delete();

        return redirect()->back()->with('success', 'Negosiasi berhasil dihapus');
    }

    public function renderPDF(
        $id,
        BuildNegosiasiReportUseCase $buildNegosiasiReportUseCase,
    )
    {
        try {
            $report = $buildNegosiasiReportUseCase->execute($id);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $pdf = Pdf::loadView('pdf.negosiasi-harga', [
            'data' => $report->toViewData(),
        ]);

        $filename = '3. NEGOSIASI HARGA - (' . $report->kegiatan->kegiatan . ')';
        $filename = preg_replace('/[\/\\\\\?\%\*\:\|\"<>\.]/', '_', $filename);

        return $pdf->stream($filename . '.pdf');
    }
}
