<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\Models\PenawaranHarga;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\NegosiasiHarga;

class KegiatanTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function kegiatan_memiliki_satu_pemberitahuan()
    {
        // Buat data dummy
        $kegiatan = Kegiatan::factory()->create();
        $pemberitahuan = Pemberitahuan::factory()->create(['kegiatan_id' => $kegiatan->id]);
        $penawaranHarga = PenawaranHarga::factory()->create(['pemberitahuan_id' => $pemberitahuan->id]);
        NegosiasiHarga::factory()->create(['penawaran_harga_id' => $penawaranHarga->id]);

        
        // Lakukan assertion
        $this->assertInstanceOf(Pemberitahuan::class, $kegiatan->pemberitahuan);
    }
}
