<?php

namespace App\UseCases\Pemberitahuan;

use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\Models\User;

final class PrepareCreatePemberitahuanUseCase
{
    public function execute(PrepareCreatePemberitahuanInput $input): PrepareCreatePemberitahuanResult
    {
        $kegiatan = Kegiatan::findOrFail($input->kegiatanId);

        $penyedia = User::where('kode_desa', $input->kodeDesa)
            ->with('penyedias')
            ->get()
            ->pluck('penyedias')
            ->flatten()
            ->unique('id')
            ->values();

        $noPbJ = Pemberitahuan::where('kode_desa', $input->kodeDesa)->count() + 1;

        return new PrepareCreatePemberitahuanResult(
            kegiatan: $kegiatan,
            penyedia: $penyedia,
            noPbJ: $noPbJ
        );
    }
}
