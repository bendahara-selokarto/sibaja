<?php

namespace App\Data\Pemberitahuan;

use Illuminate\Support\Collection;
use App\Models\Kegiatan;

class PrepareCreatePemberitahuanData
{
    public function __construct(
        public Kegiatan $kegiatan,
        public Collection $penyedia,
        public int $nomorPbJ
    ) {}

    public function toViewData(): array
    {
        return [
            'kegiatan' => $this->kegiatan,
            'penyedia' => $this->penyedia,
            'no_pbj' => $this->nomorPbJ,
            'pemberitahuan' => null,
            'belanja' => collect([
                ['nomor' => 1, 'uraian' => '', 'volume' => '', 'satuan' => '']
            ]),
            'penyediaTerpilih' => [],
        ];
    }
}
