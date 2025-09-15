<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Helpers\PajakHelper;

class PerhitunganPajakTest extends TestCase
{
    #[Test]
    public function it_can_calculate_dpp_ppn_and_pph22_correctly(): void
    {
        $result = PajakHelper::hitung(100000, 0.11, 0.015);

        $this->assertEquals(88888.89, round($result['dpp'], 2));
        $this->assertEquals(9777.78, round($result['ppn'], 2));
        $this->assertEquals(1333.33, round($result['pph'], 2));
        $this->assertEquals(100000.00, round($result['total'], 2));
    }

    #[Test]
    public function it_handles_pph_3_percent_correctly(): void
    {
        $result = PajakHelper::hitung(100000, 0.11, 0.03);

        $this->assertEquals(87719.30, round($result['dpp'], 2));
        $this->assertEquals(9649.12, round($result['ppn'], 2));
        $this->assertEquals(2631.58, round($result['pph'], 2));
        $this->assertEquals(100000.00, round($result['total'], 2));
    }

    #[Test]
    public function dpp_plus_ppn_plus_pph_must_equal_total(): void
    {
        $result = PajakHelper::hitung(500000, 0.11, 0.015);

        $this->assertEquals(500000.00, round($result['total'], 2));
    }
}
