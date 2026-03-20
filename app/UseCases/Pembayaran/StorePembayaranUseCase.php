<?php

namespace App\UseCases\Pembayaran;

use App\Models\Kegiatan;
use App\Models\Pembayaran;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

final class StorePembayaranUseCase
{
    public function execute(StorePembayaranInput $input): Pembayaran
    {
        $kegiatan = Kegiatan::with(['negosiasiHarga', 'pembayaran'])->findOrFail($input->kegiatanId);

        if ($kegiatan->negosiasiHarga === null) {
            throw ValidationException::withMessages([
                'kegiatan_id' => 'Negosiasi harus dibuat sebelum pembayaran.',
            ]);
        }

        if ($kegiatan->pembayaran !== null) {
            throw ValidationException::withMessages([
                'kegiatan_id' => 'Pembayaran untuk kegiatan ini sudah dibuat.',
            ]);
        }

        $tglAkhirPerjanjian = Carbon::parse($kegiatan->negosiasiHarga->tgl_akhir_perjanjian)->startOfDay();
        $tglInvoice = Carbon::parse($input->tglInvoice)->startOfDay();
        $tglPembayaranCms = Carbon::parse($input->tglPembayaranCms)->startOfDay();

        if ($tglInvoice->lt($tglAkhirPerjanjian) || $tglPembayaranCms->lt($tglAkhirPerjanjian)) {
            throw ValidationException::withMessages([
                'tgl_invoice' => 'Tanggal pembayaran tidak boleh mendahului akhir perjanjian.',
            ]);
        }

        return Pembayaran::create([
            'kegiatan_id' => $input->kegiatanId,
            'tgl_pembayaran_cms' => $tglPembayaranCms,
            'tgl_invoice' => $tglInvoice,
        ]);
    }
}
