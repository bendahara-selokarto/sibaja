<?php

namespace Tests\Unit;

use App\Models\NegosiasiHarga;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class NegosiasiHargaTest extends TestCase
{
    public function test_it_casts_negosiasi_dates_to_carbon_instances(): void
    {
        $negosiasi = new NegosiasiHarga([
            'tgl_negosiasi' => '2026-03-03',
            'tgl_persetujuan' => '2026-03-04',
            'tgl_akhir_perjanjian' => '2026-03-10',
        ]);

        $this->assertInstanceOf(Carbon::class, $negosiasi->tgl_negosiasi);
        $this->assertInstanceOf(Carbon::class, $negosiasi->tgl_persetujuan);
        $this->assertInstanceOf(Carbon::class, $negosiasi->tgl_akhir_perjanjian);
        $this->assertSame('2026-03-04', $negosiasi->tgl_persetujuan->format('Y-m-d'));
    }

    public function test_it_keeps_jumlah_hari_kerja_available_from_cast_dates(): void
    {
        $negosiasi = new NegosiasiHarga([
            'tgl_persetujuan' => '2026-03-04',
            'tgl_akhir_perjanjian' => '2026-03-10',
        ]);

        $this->assertSame(6.0, $negosiasi->jumlah_hari_kerja);
    }
}
