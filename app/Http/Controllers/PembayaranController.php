<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithTenantScope;
use App\Http\Requests\PembayaranRequest;
use App\Models\Pembayaran;
use App\UseCases\Pembayaran\BuildPembayaranReportUseCase;
use App\UseCases\Pembayaran\StorePembayaranInput;
use App\UseCases\Pembayaran\StorePembayaranUseCase;
use App\UseCases\Pembayaran\UpdatePembayaranInput;
use App\UseCases\Pembayaran\UpdatePembayaranUseCase;
use Barryvdh\DomPDF\Facade\Pdf;
use DomainException;

class PembayaranController extends Controller
{
    use InteractsWithTenantScope;

    public function index()
    {
        //
    }

    public function create($id)
    {
        $kegiatan = $this->findTenantKegiatan($id, ['negosiasiHarga']);
        abort_if($kegiatan === null, 404);

        return view('form.pembayaran', compact('kegiatan'));
    }

    public function store(
        PembayaranRequest $request,
        StorePembayaranUseCase $storePembayaranUseCase,
    )
    {
        $validated = $request->validated();
        $kegiatan = $this->findTenantKegiatan((string) $validated['kegiatan_id']);
        abort_if($kegiatan === null, 404);

        $pembayaran = $storePembayaranUseCase->execute(new StorePembayaranInput(
            kegiatanId: $kegiatan->id,
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
        $kegiatan = $this->findTenantKegiatan($id, ['negosiasiHarga', 'pembayaran']);
        abort_if($kegiatan === null, 404);

        $pembayaran = $kegiatan->pembayaran;

        return view('form.pembayaran', compact('pembayaran', 'kegiatan'));
    }

    public function update(
        PembayaranRequest $request,
        $id,
        UpdatePembayaranUseCase $updatePembayaranUseCase,
    )
    {
        $validated = $request->validated();
        $pembayaran = $this->findTenantPembayaran((string) $id);
        abort_if($pembayaran === null, 404);

        $pembayaran = $updatePembayaranUseCase->execute(new UpdatePembayaranInput(
            pembayaranId: $pembayaran->id,
            tglPembayaranCms: $validated['tgl_pembayaran_cms'],
            tglInvoice: $validated['tgl_invoice'],
        ));

        return redirect()->route('kegiatan.show', ['id' => $pembayaran->kegiatan_id])
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy($kegiatan_id)
    {
        $kegiatan = $this->findTenantKegiatan($kegiatan_id);
        abort_if($kegiatan === null, 404);

        $pembayaran = $this->scopedPembayaranQuery()
            ->where('kegiatan_id', $kegiatan->id)
            ->first();

        if ($pembayaran) {
            $pembayaran->delete();

            return redirect()->route('kegiatan.show', ['id' => $kegiatan_id])
                ->with('success', 'Pembayaran berhasil dihapus.');
        }

        return redirect()->route('kegiatan.show', ['id' => $kegiatan_id]);
    }

    public function render(
        $id,
        BuildPembayaranReportUseCase $buildPembayaranReportUseCase,
    ) {
        abort_if($this->findTenantKegiatan((string) $id) === null, 404);

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
