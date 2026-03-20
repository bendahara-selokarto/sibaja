<?php

namespace App\UseCases\Penawaran;

use App\Models\Pemberitahuan;
use App\Models\Penawaran;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class StorePenawaranUseCase
{
    public function execute(StorePenawaranInput $input): Penawaran
    {
        $pemberitahuan = Pemberitahuan::with(['kegiatan', 'penawaran', 'belanjas'])
            ->findOrFail($input->pemberitahuanId);

        if ($pemberitahuan->penawaran->contains('penyedia_id', $input->penyediaId)) {
            throw ValidationException::withMessages([
                'penyedia' => 'Penyedia ini sudah mengirim penawaran untuk pemberitahuan ini.',
            ]);
        }

        if ($pemberitahuan->belanjas->count() !== count($input->hargaSatuan)) {
            throw ValidationException::withMessages([
                'harga_satuan' => 'Jumlah item harga tidak sesuai dengan data belanja.',
            ]);
        }

        if ($input->isWinner && $pemberitahuan->penawaran->contains('is_winner', true)) {
            throw ValidationException::withMessages([
                'pemenang' => 'Pemenang sudah ditetapkan. Ubah penawaran yang ada jika ingin mengganti pemenang.',
            ]);
        }

        return DB::transaction(function () use ($input, $pemberitahuan) {
            $penawaran = Penawaran::create([
                'kegiatan_id' => $pemberitahuan->kegiatan->id,
                'pemberitahuan_id' => $input->pemberitahuanId,
                'penyedia_id' => $input->penyediaId,
                'tgl_penawaran' => Carbon::parse($input->tglSuratPenawaran),
                'no_penawaran' => $input->noPenawaran,
                'is_winner' => $input->isWinner,
            ]);

            $penawaran->hargaPenawaran()->createMany(
                collect($input->hargaSatuan)
                    ->map(fn ($harga) => ['harga_satuan' => $harga])
                    ->all()
            );

            return $penawaran->fresh(['hargaPenawaran']);
        });
    }
}
