<?php

namespace App\UseCases\Negosiasi;

final class StoreNegosiasiInput
{
    public function __construct(
        public readonly string $kegiatanId,
        public readonly string $tglPersetujuan,
        public readonly string $tglNegosiasi,
        public readonly string $tglAkhirPerjanjian,
        public readonly array $hargaSatuanNegosiasi,
    ) {}
}
