<?php

namespace App\UseCases\Pembayaran;

use App\Models\Kegiatan;
use App\Models\NegosiasiHarga;
use App\Models\Pembayaran;
use App\Models\Pemberitahuan;
use App\Models\Penyedia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class BuildPembayaranReportResult
{
    public function __construct(
        public readonly Kegiatan $kegiatan,
        public readonly Penyedia $penyedia,
        public readonly Pemberitahuan $pemberitahuan,
        public readonly NegosiasiHarga $negosiasiHarga,
        public readonly Collection $item,
        public readonly Carbon $tgl,
        public readonly Carbon $tglInvoice,
        public readonly Pembayaran $pembayaran,
    ) {}

    public function toViewData(): array
    {
        return [
            'kegiatan' => $this->kegiatan,
            'penyedia' => $this->penyedia,
            'pemberitahuan' => $this->pemberitahuan,
            'negosiasiHarga' => $this->negosiasiHarga,
            'item' => $this->item,
            'tgl' => $this->tgl,
            'tgl_invoice' => $this->tglInvoice,
            'pembayaran' => $this->pembayaran,
        ];
    }
}
