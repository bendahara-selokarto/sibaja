<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\PajakHelper;


class PajakHelperTest extends TestCase
{
    public function test_it_can_calculate_siskeudes_tax_correctly(): void
    {
        $totalBruto = 111000;
        $ppn = 0.11;
        $pph22 = 0.015;

        $result = PajakHelper::hitungSiskeudes(
            $totalBruto,
            $ppn,
            $pph22
        );

        $this->assertIsArray($result);

        $this->assertSame(11000, $result['ppn']);
        $this->assertSame(1500, $result['pph22']);
        $this->assertSame(98500, $result['bersih']);
        $this->assertSame(111000, $result['total']);
    }

    public function test_it_calculates_net_value_after_ppn_and_pph22(): void
    {
        $nilai = 111000;
        $ppn = 0.11;
        $pph22 = 0.015;

        $bersih = PajakHelper::bersihSetelahPpnDanPph22(
            $nilai,
            $ppn,
            $pph22
        );

        $this->assertSame(98500, $bersih);
    }
}

