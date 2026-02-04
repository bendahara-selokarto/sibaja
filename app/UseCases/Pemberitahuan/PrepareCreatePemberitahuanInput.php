<?php

namespace App\UseCases\Pemberitahuan;

final class PrepareCreatePemberitahuanInput
{
    public function __construct(
        public readonly string $kegiatanId,
        public readonly string $kodeDesa,
    ) {}
}
