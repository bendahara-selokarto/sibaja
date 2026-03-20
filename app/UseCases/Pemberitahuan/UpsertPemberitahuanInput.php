<?php

namespace App\UseCases\Pemberitahuan;

use Illuminate\Support\Collection;

final class UpsertPemberitahuanInput
{
    public function __construct(
        public readonly ?string $pemberitahuanId,
        public readonly string $kegiatanId,
        public readonly string $rekeningApbdes,
        public readonly array $penyediaIds,
        public readonly int $noPbj,
        public readonly string $tglPemberitahuan,
        public readonly Collection $belanjaItems,
    ) {}
}
