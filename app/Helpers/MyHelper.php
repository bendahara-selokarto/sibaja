<?php

if (!function_exists('format_rupiah')) {
    function format_rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('sanitize_filename')) {
    function sanitize_filename($filename)
    {
        return preg_replace('/[\/\\\\\?\%\*\:\|\"<>\.]/', '_', $filename);
    }
}
if (!function_exists('tanggal_indo')) {
    function tanggal_indo($tanggal)
    {
        return \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');
    }
}
