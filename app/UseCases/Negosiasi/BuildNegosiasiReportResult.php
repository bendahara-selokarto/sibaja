<?php

namespace App\UseCases\Negosiasi;

use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\Models\Penawaran;
use App\Models\NegosiasiHarga;
use App\Models\Penyedia;
use Illuminate\Support\Collection;

final class BuildNegosiasiReportResult
{
    public function __construct(
        public readonly Kegiatan $kegiatan,
        public readonly Pemberitahuan $pemberitahuan,
        public readonly Penawaran $penawaranHarga,
        public readonly NegosiasiHarga $negosiasiHarga,
        public readonly Penyedia $penyedia,
        public readonly Collection $item,
    ) {}

    public function toViewData(): array
    {
        return [
            'kegiatan' => $this->kegiatan,
            'penyedia' => $this->penyedia,
            'pemberitahuan' => $this->pemberitahuan,
            'penawaranHarga' => $this->penawaranHarga,
            'negosiasiHarga' => $this->negosiasiHarga,
            'item' => $this->item,
        ];
    }
}
