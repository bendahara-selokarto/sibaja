<?php

namespace App\UseCases\Penawaran;

use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\Models\Penawaran;
use App\Models\Penyedia;
use Illuminate\Support\Collection;

final class BuildPenawaranReportResult
{
    public function __construct(
        public readonly Kegiatan $kegiatan,
        public readonly Pemberitahuan $pemberitahuan,
        public readonly Penawaran $winnerPenawaran,
        public readonly Penawaran $comparisonPenawaran,
        public readonly Penyedia $winnerPenyedia,
        public readonly Penyedia $comparisonPenyedia,
        public readonly Collection $winnerItems,
        public readonly Collection $comparisonItems,
        public readonly array $winnerPajak,
        public readonly array $comparisonPajak,
    ) {}

    public function toViewData(): array
    {
        return [
            'penawaran_1' => $this->winnerPenawaran,
            'penawaran_2' => $this->comparisonPenawaran,
            'kegiatan' => $this->kegiatan,
            'penyedia1' => $this->winnerPenyedia,
            'penyedia2' => $this->comparisonPenyedia,
            'jumlah' => $this->winnerPajak['bersih'],
            'jumlah_2' => $this->comparisonPajak['bersih'],
            'ppn_1' => $this->winnerPajak['ppn'],
            'ppn_2' => $this->comparisonPajak['ppn'],
            'pph_22_1' => $this->winnerPajak['pph22'],
            'pph_22_2' => $this->comparisonPajak['pph22'],
            'jumlah_total_1' => $this->winnerPajak['total'],
            'jumlah_total_2' => $this->comparisonPajak['total'],
            'pemberitahuan' => $this->pemberitahuan,
            'item' => $this->winnerItems,
            'item_2' => $this->comparisonItems,
        ];
    }
}
