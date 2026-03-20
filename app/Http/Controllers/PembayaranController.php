<?php

namespace App\Http\Controllers;

use App\UseCases\Pembayaran\StorePembayaranInput;
use App\UseCases\Pembayaran\StorePembayaranUseCase;
use App\UseCases\Pembayaran\UpdatePembayaranInput;
use App\UseCases\Pembayaran\UpdatePembayaranUseCase;
use App\UseCases\Pembayaran\BuildPembayaranReportUseCase;
use App\Models\Kegiatan;
use App\Models\Pembayaran;
use App\Http\Requests\PembayaranRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use DomainException;

class PembayaranController extends Controller
{
    
    public function index()
    {
        //
    }

    
    public function create($id)
    {   
        $kegiatan = Kegiatan::with('negosiasiHarga')->find($id);        
        return view ('form.pembayaran', compact('kegiatan'));
    }


    public function store(
        PembayaranRequest $request,
        StorePembayaranUseCase $storePembayaranUseCase,
    )
    {
        $validated = $request->validated();

        $pembayaran = $storePembayaranUseCase->execute(new StorePembayaranInput(
            kegiatanId: $validated['kegiatan_id'],
            tglPembayaranCms: $validated['tgl_pembayaran_cms'],
            tglInvoice: $validated['tgl_invoice'],
        ));
       
        return redirect()->route('kegiatan.show', ['id' => $pembayaran->kegiatan_id]);

    }

    
    public function show(Pembayaran $pembayaran)
    {
        //
    }

    
    public function edit($id)
    {
        $kegiatan = Kegiatan::with('negosiasiHarga', 'pembayaran')->find($id);     
        $pembayaran = $kegiatan->pembayaran;  
        
        return view ('form.pembayaran', compact('pembayaran', 'kegiatan'));
    }


    public function update(
        PembayaranRequest $request,
        $id,
        UpdatePembayaranUseCase $updatePembayaranUseCase,
    )
    {
        $validated = $request->validated();

        $pembayaran = $updatePembayaranUseCase->execute(new UpdatePembayaranInput(
            pembayaranId: $id,
            tglPembayaranCms: $validated['tgl_pembayaran_cms'],
            tglInvoice: $validated['tgl_invoice'],
        ));

        return redirect()->route('kegiatan.show' , ['id' => $pembayaran->kegiatan_id])->with('success', 'Pembayaran berhasil diperbarui.');
    }

   
    public function destroy($kegiatan_id)
    {
        $pembayaran = Pembayaran::where('kegiatan_id', $kegiatan_id)->first();
        if ($pembayaran) {
            $pembayaran->delete();
            return redirect()->route('kegiatan.show' , ['id' => $kegiatan_id])->with('success', 'Pembayaran berhasil dihapus.');
        }
        return redirect()->route('kegiatan.show' , ['id' => $kegiatan_id]);
    }

    public function render(
        $id,
        BuildPembayaranReportUseCase $buildPembayaranReportUseCase,
    ){
        try {
            $report = $buildPembayaranReportUseCase->execute($id);
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $pdf = Pdf::loadView('pdf.pembayaran.kuitansi', $report->toViewData());

        $filename = '4. PEMBAYARAN - (' . $report->kegiatan->kegiatan . ')';

        $filename = preg_replace('/[\/\\\\\?\%\*\:\|\"<>\.]/', '_', $filename);

        return $pdf->stream($filename . '.pdf');
    }
}
