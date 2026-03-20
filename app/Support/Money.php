<?php

namespace App\Support;

class Money
{
    private const QUANTITY_SCALE = 1000;
    private const BASIS_POINTS = 10000;

    public static function rupiah(int|float|string|null $value): int
    {
        return self::toScaledInteger($value, 0);
    }

    public static function quantity(int|float|string|null $value): int
    {
        return self::toScaledInteger($value, 3);
    }

    public static function percentToBasisPoints(int|float|string|null $value): int
    {
        return self::toScaledInteger($value, 4);
    }

    public static function quantityTimesRupiah(int|float|string|null $quantity, int|float|string|null $rupiah): int
    {
        $quantityScaled = self::quantity($quantity);
        $rupiahValue = self::rupiah($rupiah);

        if (self::hasBcMath()) {
            return self::divideAndRoundHalfUpString(
                bcmul((string) $quantityScaled, (string) $rupiahValue, 0),
                (string) self::QUANTITY_SCALE
            );
        }

        return self::divideAndRoundHalfUp($quantityScaled * $rupiahValue, self::QUANTITY_SCALE);
    }

    public static function multiplyBasisPoints(int $amount, int $basisPoints): int
    {
        if (self::hasBcMath()) {
            return self::divideAndRoundHalfUpString(
                bcmul((string) $amount, (string) $basisPoints, 0),
                (string) self::BASIS_POINTS
            );
        }

        return self::divideAndRoundHalfUp($amount * $basisPoints, self::BASIS_POINTS);
    }

    public static function divideByBasisPoints(int $amount, int $basisPointsDivisor): int
    {
        if (self::hasBcMath()) {
            return self::divideAndRoundHalfUpString(
                bcmul((string) $amount, (string) self::BASIS_POINTS, 0),
                (string) $basisPointsDivisor
            );
        }

        return self::divideAndRoundHalfUp($amount * self::BASIS_POINTS, $basisPointsDivisor);
    }

    public static function divideAndRoundHalfUp(int $numerator, int $denominator): int
    {
        if (self::hasBcMath()) {
            return self::divideAndRoundHalfUpString((string) $numerator, (string) $denominator);
        }

        return self::divideAndRoundHalfUpFallback($numerator, $denominator);
    }

    private static function divideAndRoundHalfUpString(string $numerator, string $denominator): int
    {
        if ($denominator === '0') {
            throw new \InvalidArgumentException('Denominator cannot be zero.');
        }

        if (bccomp($numerator, '0', 0) === 0) {
            return 0;
        }

        $sign = 1;
        if (str_starts_with($numerator, '-')) {
            $sign *= -1;
            $numerator = substr($numerator, 1);
        }

        if (str_starts_with($denominator, '-')) {
            $sign *= -1;
            $denominator = substr($denominator, 1);
        }

        $quotient = bcdiv($numerator, $denominator, 0);
        $remainder = bcsub($numerator, bcmul($quotient, $denominator, 0), 0);

        if (bccomp(bcmul($remainder, '2', 0), $denominator, 0) >= 0) {
            $quotient = bcadd($quotient, '1', 0);
        }

        return (int) ($sign < 0 ? '-' . $quotient : $quotient);
    }

    private static function divideAndRoundHalfUpFallback(int $numerator, int $denominator): int
    {
        if ($denominator === 0) {
            throw new \InvalidArgumentException('Denominator cannot be zero.');
        }

        if ($numerator === 0) {
            return 0;
        }

        $sign = $numerator < 0 ? -1 : 1;
        $numerator = abs($numerator);
        $quotient = intdiv($numerator, $denominator);
        $remainder = $numerator % $denominator;

        if ($remainder * 2 >= $denominator) {
            $quotient++;
        }

        return $quotient * $sign;
    }

    private static function toScaledInteger(int|float|string|null $value, int $scale): int
    {
        if (self::hasBcMath()) {
            return self::toScaledIntegerWithBcMath($value, $scale);
        }

        return self::toScaledIntegerFallback($value, $scale);
    }

    private static function toScaledIntegerWithBcMath(int|float|string|null $value, int $scale): int
    {
        $normalized = self::normalizeNumericString($value);
        $negative = str_starts_with($normalized, '-');
        $unsigned = $negative ? substr($normalized, 1) : $normalized;
        [, $fraction] = array_pad(explode('.', $unsigned, 2), 2, '');
        $factor = '1' . str_repeat('0', $scale + 1);
        $bcScale = max(strlen($fraction), 1);
        $shifted = bcmul($unsigned, $factor, $bcScale);
        [$whole] = array_pad(explode('.', $shifted, 2), 2, '');

        $whole = ltrim($whole, '0');
        if ($whole === '') {
            $whole = '0';
        }

        $carryDigit = (int) substr($whole, -1);
        $scaledDigits = substr($whole, 0, -1);
        $scaled = $scaledDigits === '' ? 0 : (int) $scaledDigits;

        if ($carryDigit >= 5) {
            $scaled++;
        }

        return $negative ? -$scaled : $scaled;
    }

    private static function toScaledIntegerFallback(int|float|string|null $value, int $scale): int
    {
        $normalized = self::normalizeNumericString($value);
        $negative = str_starts_with($normalized, '-');
        $unsigned = $negative ? substr($normalized, 1) : $normalized;
        [$whole, $fraction] = array_pad(explode('.', $unsigned, 2), 2, '');

        $fraction = preg_replace('/\D/', '', $fraction);
        $whole = preg_replace('/\D/', '', $whole);

        if ($whole === '') {
            $whole = '0';
        }

        $fraction = substr($fraction . str_repeat('0', $scale + 1), 0, $scale + 1);
        $carryDigit = $scale === 0
            ? (int) $fraction[0]
            : (int) substr($fraction, $scale, 1);
        $fractionDigits = $scale === 0
            ? ''
            : substr($fraction, 0, $scale);

        $composed = ltrim($whole . $fractionDigits, '0');
        $scaled = $composed === '' ? 0 : (int) $composed;

        if ($carryDigit >= 5) {
            $scaled++;
        }

        return $negative ? -$scaled : $scaled;
    }

    private static function hasBcMath(): bool
    {
        return function_exists('bcadd');
    }

    private static function normalizeNumericString(int|float|string|null $value): string
    {
        if ($value === null) {
            return '0';
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            $value = rtrim(rtrim(sprintf('%.12F', $value), '0'), '.');
        }

        $value = trim((string) $value);
        if ($value === '') {
            return '0';
        }

        $value = str_replace(',', '.', $value);

        if (!preg_match('/^-?\d+(\.\d+)?$/', $value)) {
            throw new \InvalidArgumentException('Invalid numeric value: ' . $value);
        }

        return $value;
    }
}
