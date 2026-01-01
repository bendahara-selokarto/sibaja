<?php

namespace App\Helpers;

class PajakHelper
{


    public static function hitungSiskeudes(
        float $totalBruto,
        float $ppn,
        float $pph22
    ): array {
        $faktor = 1 + $ppn;

        $dpp = (1 / $faktor) * $totalBruto; 

        $ppn =  $dpp * $ppn; 

        $pph22 = $dpp * $pph22; 
        
        return [
            'bersih'=> $dpp - $pph22,
            'ppn'   => $ppn,
            'pph22' => $pph22,
            'total' => $dpp + $ppn,        
        ];
    }
        public static function bersihSetelahPpnDanPph22(
        float $nilai,
        float $ppn,
        float $pph22
    ): float {
        $dpp = $nilai / (1 + $ppn);
        return $dpp - ($dpp * $pph22);
    }
}
