<?php

namespace App\UseCases\Pemberitahuan;

use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class UpsertPemberitahuanUseCase
{
    public function execute(UpsertPemberitahuanInput $input): Pemberitahuan
    {
        $kegiatan = Kegiatan::with('pemberitahuan')->findOrFail($input->kegiatanId);

        if ($input->pemberitahuanId === null && $kegiatan->pemberitahuan !== null) {
            throw ValidationException::withMessages([
                'kegiatan_id' => 'Kegiatan ini sudah memiliki pemberitahuan.',
            ]);
        }

        if ($input->belanjaItems->isEmpty()) {
            throw ValidationException::withMessages([
                'uraian' => 'Minimal satu item belanja harus diisi.',
            ]);
        }

        $pemberitahuan = $input->pemberitahuanId
            ? Pemberitahuan::findOrFail($input->pemberitahuanId)
            : new Pemberitahuan();

        if (
            $input->pemberitahuanId !== null
            && $pemberitahuan->kegiatan_id !== $input->kegiatanId
        ) {
            throw ValidationException::withMessages([
                'kegiatan_id' => 'Pemberitahuan tidak cocok dengan kegiatan yang dipilih.',
            ]);
        }

        return DB::transaction(function () use ($input, $pemberitahuan) {
            $pemberitahuan->fill([
                'rekening_apbdes' => $input->rekeningApbdes,
                'kegiatan_id' => $input->kegiatanId,
                'penyedia' => array_values($input->penyediaIds),
                'no_pbj' => $input->noPbj,
                'pekerjaan' => $input->belanjaItems->implode('uraian', ', '),
                'tgl_surat_pemberitahuan' => $input->tglPemberitahuan,
                'tgl_batas_akhir_penawaran' => Carbon::parse($input->tglPemberitahuan)->addDays(3),
            ]);
            $pemberitahuan->save();
            $pemberitahuan->syncSelectedPenyedias(array_values($input->penyediaIds));

            $pemberitahuan->belanjas()->delete();
            $pemberitahuan->belanjas()->createMany($input->belanjaItems->all());

            return $pemberitahuan->fresh(['belanjas', 'penyedias']);
        });
    }
}
