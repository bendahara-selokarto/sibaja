<?php

namespace App\Helpers;

class PajakHelper
{
    /**
     * Hitung DPP, PPN, dan PPh dari jumlah total
     */
    public static function hitung(float $jumlahTotal, float $ppn, float $pph): array
    {
        $factor = 1 + $ppn;
        $dpp = $jumlahTotal / $factor;

        $ppn_x = $dpp * $ppn;
        $pph_x = $dpp * $pph;

        return [
            'dpp'   => $dpp,
            'ppn'   => $ppn_x,
            'pph'   => $pph_x,
            'total' => $dpp + $ppn_x + $pph_x,
        ];
    }
}
