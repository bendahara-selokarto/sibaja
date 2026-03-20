<?php

namespace App\Helpers;

final class PajakRoundingHelper
{
    private function __construct()
    {
    }

    public static function toRp(float $value): float
    {
        return round($value, 0, PHP_ROUND_HALF_UP);
    }
}
