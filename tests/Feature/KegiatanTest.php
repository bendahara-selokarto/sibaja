<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\Models\Penawaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class KegiatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_kegiatan_memiliki_satu_pemberitahuan(): void
    {
        $kegiatan = Kegiatan::factory()->create();
        $pemberitahuan = Pemberitahuan::factory()->create(['kegiatan_id' => $kegiatan->id]);

        $this->assertTrue($kegiatan->fresh()->pemberitahuan->is($pemberitahuan));
    }

    public function test_status_pemenang_memeriksa_semua_penawaran(): void
    {
        $kegiatan = Kegiatan::factory()->create();
        $pemberitahuan = Pemberitahuan::factory()->create(['kegiatan_id' => $kegiatan->id]);

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => (string) Str::uuid(),
            'tgl_penawaran' => now(),
            'no_penawaran' => '001',
            'is_winner' => false,
        ]);

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => (string) Str::uuid(),
            'tgl_penawaran' => now(),
            'no_penawaran' => '002',
            'is_winner' => true,
        ]);

        $this->assertSame(1, $kegiatan->fresh()->statusPemenang());
    }
}
