<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\PajakHelper;


class PajakHelperTest extends TestCase
{
    /** @test */
    public function it_can_calculate_siskeudes_tax_correctly()
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

        $this->assertEquals(100000, round($result['total'] / 1.11, 0));
        $this->assertEquals(11000, round($result['ppn'], 0));
        $this->assertEquals(1500, round($result['pph22'], 0));
        $this->assertEquals(98500, round($result['bersih'], 0));
        $this->assertEquals(111000, round($result['total'], 0));
    }

        /** @test */
    public function it_calculates_net_value_after_ppn_and_pph22()
    {
        $nilai = 111000;
        $ppn = 0.11;
        $pph22 = 0.015;

        $bersih = PajakHelper::bersihSetelahPpnDanPph22(
            $nilai,
            $ppn,
            $pph22
        );

        $this->assertEquals(98500, round($bersih, 0));
    }
}



