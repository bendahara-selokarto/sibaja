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
    public static function hitungSiskeudes(
        float $totalBruto,
        float $ppn,
        float $pph22
    ): array {
        $faktorPajak = 1 + $ppn + $pph22; // 1.14
        $faktorSiskeudes = 1 + $ppn; // 1.11

        $dpp = (1 / $faktorSiskeudes) * $totalBruto; // DPP Siskeudes

        $ppnSiskeudes =  $dpp * $ppn; // PPN Siskeudes

        $pph22Siskeudes = $dpp * $pph22; // PPh22 Siskeudes

        $jumlahBersih = $dpp - $pph22Siskeudes;
        
        return [
            'faktorPajak' => $faktorPajak,
            'dpp'   => $dpp - $pph22Siskeudes,
            'ppn'   => $ppnSiskeudes,
            'pph22' => $pph22Siskeudes,
            'total' => $dpp + $ppnSiskeudes,
        ];
    }
        public static function bersihSetelahPpnDanPph22(
        float $nilai,
        float $ppn,
        float $pph22
    ): float {
        $dasar = $nilai / (1 + $ppn);
        return $dasar - ($dasar * $pph22);
    }
}
