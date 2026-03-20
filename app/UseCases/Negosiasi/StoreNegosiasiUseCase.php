<?php

namespace App\UseCases\Negosiasi;

use App\Models\Kegiatan;
use App\Models\NegosiasiHarga;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class StoreNegosiasiUseCase
{
    public function execute(StoreNegosiasiInput $input): NegosiasiHarga
    {
        $kegiatan = Kegiatan::with(['pemberitahuan.belanjas', 'negosiasiHarga', 'penawaran'])
            ->findOrFail($input->kegiatanId);

        if ($kegiatan->negosiasiHarga !== null) {
            throw ValidationException::withMessages([
                'kegiatan_id' => 'Negosiasi untuk kegiatan ini sudah dibuat.',
            ]);
        }

        if (!$kegiatan->penawaran->contains('is_winner', true)) {
            throw ValidationException::withMessages([
                'kegiatan_id' => 'PEMENANG belum di set',
            ]);
        }

        if ($kegiatan->pemberitahuan === null) {
            throw ValidationException::withMessages([
                'kegiatan_id' => 'Pemberitahuan not found',
            ]);
        }

        if ($kegiatan->pemberitahuan->belanjas->count() !== count($input->hargaSatuanNegosiasi)) {
            throw ValidationException::withMessages([
                'harga_satuan_negosiasi' => 'Jumlah item harga tidak sesuai dengan data belanja.',
            ]);
        }

        return DB::transaction(function () use ($input) {
            $negosiasi = NegosiasiHarga::create([
                'kegiatan_id' => $input->kegiatanId,
                'tgl_persetujuan' => Carbon::parse($input->tglPersetujuan),
                'tgl_negosiasi' => Carbon::parse($input->tglNegosiasi),
                'tgl_akhir_perjanjian' => Carbon::parse($input->tglAkhirPerjanjian),
            ]);

            $negosiasi->hargaNegosiasi()->createMany(
                collect($input->hargaSatuanNegosiasi)
                    ->map(fn ($harga) => ['harga_satuan' => $harga])
                    ->all()
            );

            return $negosiasi->fresh(['hargaNegosiasi']);
        });
    }
}
