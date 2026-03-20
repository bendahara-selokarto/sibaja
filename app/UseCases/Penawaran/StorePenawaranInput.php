<?php

namespace App\UseCases\Penawaran;

final class StorePenawaranInput
{
    public function __construct(
        public readonly string $pemberitahuanId,
        public readonly string $penyediaId,
        public readonly string $tglSuratPenawaran,
        public readonly string $noPenawaran,
        public readonly bool $isWinner,
        public readonly array $hargaSatuan,
    ) {}
}
