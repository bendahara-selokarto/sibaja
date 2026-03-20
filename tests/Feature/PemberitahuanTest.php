<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\Models\Penyedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemberitahuanTest extends TestCase
{
    use RefreshDatabase;

    public function test_kegiatan_cannot_store_duplicate_pemberitahuan(): void
    {
        $user = User::factory()->create([
            'kode_desa' => 'D01',
            'tahun_anggaran' => 2026,
            'role' => 'desa',
            'desa' => 'Selokarto',
        ]);

        $this->actingAs($user);

        $kegiatan = Kegiatan::factory()->create([
            'kode_desa' => 'D01',
            'tahun_anggaran' => 2026,
        ]);

        $penyediaA = Penyedia::create([
            'created_by' => $user->id,
            'nama_penyedia' => 'Penyedia A',
            'nomor_npwp' => '02.234.567.8-999.000',
        ]);

        $penyediaB = Penyedia::create([
            'created_by' => $user->id,
            'nama_penyedia' => 'Penyedia B',
            'nomor_npwp' => '02.234.567.8-999.001',
        ]);

        Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'penyedia' => [$penyediaA->id, $penyediaB->id],
            'no_pbj' => 1,
            'pekerjaan' => 'Material',
            'tgl_surat_pemberitahuan' => now(),
            'tgl_batas_akhir_penawaran' => now()->addDays(3),
        ]);

        $response = $this
            ->from(route('pemberitahuan.create', $kegiatan->id))
            ->post(route('pemberitahuan.store'), [
                'rekening_apbdes' => $kegiatan->rekening_apbdes,
                'kegiatan_id' => $kegiatan->id,
                'penyedia' => [$penyediaA->id, $penyediaB->id],
                'no_pbj' => 2,
                'tgl_pemberitahuan' => now()->toDateString(),
                'uraian' => ['Semen'],
                'volume' => [1],
                'satuan' => ['sak'],
            ]);

        $response->assertRedirect(route('pemberitahuan.create', $kegiatan->id));
        $response->assertSessionHasErrors('kegiatan_id');
        $this->assertDatabaseCount('pemberitahuans', 1);
    }
}
