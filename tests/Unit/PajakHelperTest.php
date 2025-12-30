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

    /** @test */
    public function hitung_dari_bruto_dengan_ppn_11_persen()
    {
        $totalBruto = 111_000_000;
        $ppn = 0.11;
        $pph22 = 0.015;

        $hasil = PajakHelper::hitungDariBruto(
            $totalBruto,
            $ppn,
            $pph22
        );

        $this->assertEquals(1.11, $hasil['denom']);
        $this->assertEquals(100_000_000, round($hasil['dpp']));
        $this->assertEquals(11_000_000, round($hasil['ppn']));
        $this->assertEquals(1_500_000, round($hasil['pph22']));
        $this->assertEquals(111_000_000, round($hasil['total']));
    }
    /** @test */
    public function pph_tidak_masuk_ke_denom()
    {
        $hasil = PajakHelper::hitungDariBruto(
            100_000_000,
            0.11,
            0.02
        );

        $this->assertNotEquals(
            1 + 0.11 + 0.02,
            $hasil['denom']
        );
    }

    /** @test */
    public function helper_dpp_menghasilkan_nilai_benar()
    {
        $dpp = PajakHelper::dpp(111_000_000, 0.11);

        $this->assertEquals(
            100_000_000,
            round($dpp)
        );
    }

}
