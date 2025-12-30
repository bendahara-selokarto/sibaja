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
            'dpp'   => $jumlahTotal - $ppn_x - $pph_x,
            'ppn'   => $ppn_x,
            'pph'   => $pph_x,
            'total' => $dpp + $ppn_x,
        ];
    }

    public static function dpp(float $nilaiBruto, float $ppn): float
    {
        return $nilaiBruto / (1 + $ppn);
    }

    /**
     * Hitung pajak dari nilai bruto (sudah termasuk PPN)
     */
    public static function hitungDariBruto(
        float $totalBruto,
        float $ppn,
        float $pph22
    ): array {
        $denom = 1 + $ppn;

        $dpp = $totalBruto / $denom;

        return [
            'denom' => $denom,
            'dpp'   => $dpp,
            'ppn'   => $dpp * $ppn,
            'pph22' => $dpp * $pph22, // POTONGAN
            'total' => $dpp + ($dpp * $ppn), // total kontrak
        ];
    }
}
