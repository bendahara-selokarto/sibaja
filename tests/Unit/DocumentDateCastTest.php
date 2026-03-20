<?php

namespace Tests\Unit;

use App\Models\Pemberitahuan;
use App\Models\Pembayaran;
use App\Models\Penawaran;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DocumentDateCastTest extends TestCase
{
    public function test_pemberitahuan_dates_are_cast_to_carbon(): void
    {
        $pemberitahuan = new Pemberitahuan([
            'tgl_surat_pemberitahuan' => '2026-03-01',
            'tgl_batas_akhir_penawaran' => '2026-03-04',
        ]);

        $this->assertInstanceOf(Carbon::class, $pemberitahuan->tgl_surat_pemberitahuan);
        $this->assertInstanceOf(Carbon::class, $pemberitahuan->tgl_batas_akhir_penawaran);
    }

    public function test_penawaran_date_is_cast_to_carbon(): void
    {
        $penawaran = new Penawaran([
            'tgl_penawaran' => '2026-03-02',
        ]);

        $this->assertInstanceOf(Carbon::class, $penawaran->tgl_penawaran);
    }

    public function test_pembayaran_dates_are_cast_to_carbon(): void
    {
        $pembayaran = new Pembayaran([
            'tgl_invoice' => '2026-03-10',
            'tgl_pembayaran_cms' => '2026-03-11',
        ]);

        $this->assertInstanceOf(Carbon::class, $pembayaran->tgl_invoice);
        $this->assertInstanceOf(Carbon::class, $pembayaran->tgl_pembayaran_cms);
    }
}
