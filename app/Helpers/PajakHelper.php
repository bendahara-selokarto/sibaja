<?php

namespace App\Helpers;

use App\Support\Money;

class PajakHelper
{
    public static function hitungSiskeudesPresisi(
        int|float|string $totalBruto,
        int|float|string $ppn,
        int|float|string $pph22
    ): array {
        $brutoStr = (string) $totalBruto;
        $ppnStr = (string) $ppn;
        $pph22Str = (string) $pph22;

        // Divisor = 1 + PPN
        $divisor = bcadd('1', $ppnStr, 4);

        // DPP = Bruto / Divisor
        $dpp = bcdiv($brutoStr, $divisor, 4);

        // PPN Nominal = DPP * PPN rate
        $ppnNominal = bcmul($dpp, $ppnStr, 4);

        // PPh 22 Nominal = DPP * PPh 22 rate
        $pph22Nominal = bcmul($dpp, $pph22Str, 4);

        // Bersih = DPP - PPh 22 Nominal
        $bersih = bcsub($dpp, $pph22Nominal, 4);

        // Total = DPP + PPN Nominal
        $total = bcadd($dpp, $ppnNominal, 4);

        return [
            'dpp' => $dpp,
            'bersih' => $bersih,
            'ppn' => $ppnNominal,
            'pph22' => $pph22Nominal,
            'total' => $total,
        ];
    }

    public static function hitungSiskeudes(
        int|float|string $totalBruto,
        int|float|string $ppn,
        int|float|string $pph22
    ): array {
        $precise = self::hitungSiskeudesPresisi($totalBruto, $ppn, $pph22);

        return [
            'bersih' => (int) round($precise['bersih'], 0, PHP_ROUND_HALF_UP),
            'ppn' => (int) round($precise['ppn'], 0, PHP_ROUND_HALF_UP),
            'pph22' => (int) round($precise['pph22'], 0, PHP_ROUND_HALF_UP),
            'total' => (int) round($precise['total'], 0, PHP_ROUND_HALF_UP),
        ];
    }

    public static function bersihSetelahPpnDanPph22(
        int|float|string $nilai,
        int|float|string $ppn,
        int|float|string $pph22
    ): int {
        return self::hitungSiskeudes($nilai, $ppn, $pph22)['bersih'];
    }
}
