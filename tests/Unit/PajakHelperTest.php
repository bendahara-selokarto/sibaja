<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\PajakHelper;


class PajakHelperTest extends TestCase
{
     /** @test */
    public function it_can_calculate_pajak_correctly()
    {
        // Arrange
        $jumlahTotal = 111000; // total termasuk PPN
        $ppn = 0.11;          // 11%
        $pph = 0.02;          // 2%

        // Act
        $result = PajakHelper::hitung($jumlahTotal, $ppn, $pph);

        // Assert
        $this->assertIsArray($result);

        // DPP raw = 111000 / 1.11 = 100000
        $this->assertEquals(100000, round($result['total'] / (1 + $ppn), 0));

        // PPN = 100000 × 11% = 11000
        $this->assertEquals(11000, round($result['ppn'], 0));

        // PPh = 100000 × 2% = 2000
        $this->assertEquals(2000, round($result['pph'], 0));

        // DPP dibayar = 111000 − 11000 − 2000 = 98000
        $this->assertEquals(98000, round($result['dpp'], 0));

        // Total harus konsisten
        $this->assertEquals(
            round($jumlahTotal, 0),
            round($result['total'], 0)
        );
    }

}
