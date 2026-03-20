<?php

namespace App\UseCases\Penawaran;

use App\Models\Pemberitahuan;
use App\Models\Penawaran;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class UpdatePenawaranUseCase
{
    public function execute(UpdatePenawaranInput $input): Penawaran
    {
        $pemberitahuan = Pemberitahuan::with(['kegiatan', 'penawaran', 'belanjas'])
            ->findOrFail($input->pemberitahuanId);

        $penawaran = Penawaran::where('penyedia_id', $input->penyediaId)
            ->where('pemberitahuan_id', $input->pemberitahuanId)
            ->firstOrFail();

        $hargaLama = $penawaran->hargaPenawaran()->orderBy('id')->get();

        if ($hargaLama->count() !== count($input->hargaSatuan)) {
            throw ValidationException::withMessages([
                'harga_satuan' => 'Jumlah item harga tidak sesuai dengan data belanja.',
            ]);
        }

        return DB::transaction(function () use ($input, $pemberitahuan, $penawaran, $hargaLama) {
            $penawaran->update([
                'kegiatan_id' => $pemberitahuan->kegiatan->id,
                'tgl_penawaran' => Carbon::parse($input->tglSuratPenawaran),
                'no_penawaran' => $input->noPenawaran,
                'is_winner' => $input->isWinner,
            ]);

            if ($input->isWinner) {
                Penawaran::where('pemberitahuan_id', $input->pemberitahuanId)
                    ->where('id', '!=', $penawaran->id)
                    ->update(['is_winner' => false]);
            }

            foreach ($hargaLama->values() as $index => $row) {
                $row->update([
                    'harga_satuan' => $input->hargaSatuan[$index],
                ]);
            }

            return $penawaran->fresh(['hargaPenawaran']);
        });
    }
}
