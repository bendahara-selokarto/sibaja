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
}
