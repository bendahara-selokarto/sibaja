<?php

namespace Tests\Unit;

use App\Helpers\PajakHelper;
use PHPUnit\Framework\TestCase;

class PajakHelperTest extends TestCase
{
    public function test_it_can_calculate_siskeudes_tax_correctly(): void
    {
        $result = PajakHelper::hitungSiskeudes(111000, 0.11, 0.015);

        $this->assertIsArray($result);
        $this->assertSame(11000, $result['ppn']);
        $this->assertSame(1500, $result['pph22']);
        $this->assertSame(98500, $result['bersih']);
        $this->assertSame(111000, $result['total']);
    }

    public function test_it_calculates_net_value_after_ppn_and_pph22(): void
    {
        $bersih = PajakHelper::bersihSetelahPpnDanPph22(111000, 0.11, 0.015);

        $this->assertSame(98500, $bersih);
    }

    public function test_it_calculates_precision_siskeudes_tax(): void
    {
        $result = PajakHelper::hitungSiskeudesPresisi(150000, 0.11, 0.015);

        $this->assertIsArray($result);
        $this->assertEqualsWithDelta('135135.1351', $result['dpp'], 0.0001);
        $this->assertEqualsWithDelta('14864.8648', $result['ppn'], 0.0001);
        $this->assertEqualsWithDelta('2027.0270', $result['pph22'], 0.0001);
        $this->assertEqualsWithDelta('133108.1081', $result['bersih'], 0.0001);
        $this->assertEqualsWithDelta('149999.9999', $result['total'], 0.0001);
    }

    public function test_precision_math_eliminates_drift_on_decimal_volumes(): void
    {
        $preciseUnit = PajakHelper::hitungSiskeudesPresisi(150000, 0.11, 0.015);
        $volume = '1.5';

        $preciseJumlah = bcmul($preciseUnit['bersih'], $volume, 4);
        $roundedJumlah = (int) round($preciseJumlah, 0, PHP_ROUND_HALF_UP);

        $this->assertSame(199662, $roundedJumlah);

        $preciseUnit2 = PajakHelper::hitungSiskeudesPresisi(100000, 0.11, 0.015);
        $volume2 = '1.5';

        $preciseJumlah2 = bcmul($preciseUnit2['bersih'], $volume2, 4);
        $roundedJumlah2 = (int) round($preciseJumlah2, 0, PHP_ROUND_HALF_UP);

        $this->assertSame(133108, $roundedJumlah2);
    }
}
