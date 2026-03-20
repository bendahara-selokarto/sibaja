<?php

namespace App\UseCases\Pembayaran;

final class UpdatePembayaranInput
{
    public function __construct(
        public readonly string $pembayaranId,
        public readonly string $tglPembayaranCms,
        public readonly string $tglInvoice,
    ) {}
}
