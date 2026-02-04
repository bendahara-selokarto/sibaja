<?php

namespace App\UseCases\Pemberitahuan;

use App\Models\Kegiatan;
use Illuminate\Support\Collection;

final class PrepareCreatePemberitahuanResult
{
    public function __construct(
        public readonly Kegiatan $kegiatan,
        public readonly Collection $penyedia,
        public readonly int $noPbJ,
    ) {}
}
