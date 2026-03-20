<?php

namespace App\UseCases\Negosiasi;

use App\Models\Kegiatan;
use App\Models\NegosiasiHarga;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class UpdateNegosiasiUseCase
{
    public function execute(UpdateNegosiasiInput $input): NegosiasiHarga
    {
        $kegiatan = Kegiatan::with(['pemberitahuan.belanjas', 'negosiasiHarga.hargaNegosiasi'])
            ->findOrFail($input->kegiatanId);

        if ($kegiatan->pemberitahuan === null) {
            throw ValidationException::withMessages([
                'kegiatan_id' => 'Pemberitahuan not found',
            ]);
        }

        $negosiasi = $kegiatan->negosiasiHarga;

        if ($negosiasi === null) {
            throw ValidationException::withMessages([
                'kegiatan_id' => 'Negosiasi tidak ditemukan',
            ]);
        }

        if ($kegiatan->pemberitahuan->belanjas->count() !== count($input->hargaSatuanNegosiasi)) {
            throw ValidationException::withMessages([
                'harga_satuan_negosiasi' => 'Jumlah item harga tidak sesuai dengan data belanja.',
            ]);
        }

        return DB::transaction(function () use ($input, $negosiasi) {
            $negosiasi->update([
                'kegiatan_id' => $input->kegiatanId,
                'tgl_persetujuan' => Carbon::parse($input->tglPersetujuan),
                'tgl_negosiasi' => Carbon::parse($input->tglNegosiasi),
                'tgl_akhir_perjanjian' => Carbon::parse($input->tglAkhirPerjanjian),
            ]);

            foreach ($negosiasi->hargaNegosiasi->values() as $index => $harga) {
                $harga->update([
                    'harga_satuan' => $input->hargaSatuanNegosiasi[$index],
                ]);
            }

            return $negosiasi->fresh(['hargaNegosiasi']);
        });
    }
}
