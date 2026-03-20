<?php

namespace Tests\Unit;

use App\Support\Money;
use Tests\TestCase;

class MoneyTest extends TestCase
{
    public function test_it_rounds_rupiah_half_up(): void
    {
        $this->assertSame(100001, Money::rupiah('100000.50'));
        $this->assertSame(100000, Money::rupiah('100000.49'));
    }

    public function test_it_multiplies_decimal_quantity_by_rupiah_without_float_drift(): void
    {
        $this->assertSame(333300, Money::quantityTimesRupiah('3.333', 100000));
        $this->assertSame(150000, Money::quantityTimesRupiah('1.5', 100000));
    }

    public function test_it_converts_percent_to_basis_points(): void
    {
        $this->assertSame(1100, Money::percentToBasisPoints(0.11));
        $this->assertSame(150, Money::percentToBasisPoints(0.015));
    }

    public function test_it_can_calculate_a_percentage_of_rupiah_without_float_drift(): void
    {
        $this->assertSame(9850, Money::multiplyBasisPoints(98500, 1000));
    }
}
