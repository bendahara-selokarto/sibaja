<?php

namespace App\UseCases\Pembayaran;

use App\Models\Pembayaran;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

final class UpdatePembayaranUseCase
{
    public function execute(UpdatePembayaranInput $input): Pembayaran
    {
        $pembayaran = Pembayaran::with('kegiatan.negosiasiHarga')->findOrFail($input->pembayaranId);

        if ($pembayaran->kegiatan?->negosiasiHarga === null) {
            throw ValidationException::withMessages([
                'kegiatan_id' => 'Negosiasi harus tersedia sebelum pembayaran diperbarui.',
            ]);
        }

        $tglAkhirPerjanjian = Carbon::parse($pembayaran->kegiatan->negosiasiHarga->tgl_akhir_perjanjian)->startOfDay();
        $tglInvoice = Carbon::parse($input->tglInvoice)->startOfDay();
        $tglPembayaranCms = Carbon::parse($input->tglPembayaranCms)->startOfDay();

        if ($tglInvoice->lt($tglAkhirPerjanjian) || $tglPembayaranCms->lt($tglAkhirPerjanjian)) {
            throw ValidationException::withMessages([
                'tgl_invoice' => 'Tanggal pembayaran tidak boleh mendahului akhir perjanjian.',
            ]);
        }

        $pembayaran->update([
            'tgl_pembayaran_cms' => $tglPembayaranCms,
            'tgl_invoice' => $tglInvoice,
        ]);

        return $pembayaran->fresh();
    }
}
