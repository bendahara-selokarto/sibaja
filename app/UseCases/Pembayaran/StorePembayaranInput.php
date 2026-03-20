<?php

namespace App\UseCases\Pembayaran;

final class StorePembayaranInput
{
    public function __construct(
        public readonly string $kegiatanId,
        public readonly string $tglPembayaranCms,
        public readonly string $tglInvoice,
    ) {}
}
