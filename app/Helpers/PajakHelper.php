<?php

namespace App\Helpers;

use App\Support\Money;

class PajakHelper
{
    public static function hitungSiskeudes(
        int|float|string $totalBruto,
        int|float|string $ppn,
        int|float|string $pph22
    ): array {
        $totalBrutoRupiah = Money::rupiah($totalBruto);
        $ppnBasisPoints = Money::percentToBasisPoints($ppn);
        $pph22BasisPoints = Money::percentToBasisPoints($pph22);
        $divisor = 10000 + $ppnBasisPoints;

        $dpp = Money::divideByBasisPoints($totalBrutoRupiah, $divisor);
        $ppnNominal = Money::multiplyBasisPoints($dpp, $ppnBasisPoints);
        $pph22Nominal = Money::multiplyBasisPoints($dpp, $pph22BasisPoints);
        
        return [
            'bersih'=> $dpp - $pph22Nominal,
            'ppn'   => $ppnNominal,
            'pph22' => $pph22Nominal,
            'total' => $dpp + $ppnNominal,
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
