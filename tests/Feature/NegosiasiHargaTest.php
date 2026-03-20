<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\NegosiasiHarga;
use App\Models\Penawaran;
use App\Models\Pemberitahuan;
use App\Models\Penyedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NegosiasiHargaTest extends TestCase
{
    use RefreshDatabase;

    public function test_kegiatan_cannot_store_duplicate_negosiasi(): void
    {
        [$user, $kegiatan, $pemberitahuan] = $this->seedWorkflow();

        $this->actingAs($user);

        $negosiasi = NegosiasiHarga::create([
            'kegiatan_id' => $kegiatan->id,
            'tgl_persetujuan' => now(),
            'tgl_negosiasi' => now(),
            'tgl_akhir_perjanjian' => now()->addDays(7),
        ]);
        $negosiasi->hargaNegosiasi()->create(['harga_satuan' => 98000]);

        $response = $this
            ->from(route('negosiasi.create', $kegiatan->id))
            ->post(route('negosiasi.store'), [
                'kegiatan_id' => $kegiatan->id,
                'tgl_persetujuan' => now()->toDateString(),
                'tgl_negosiasi' => now()->toDateString(),
                'tgl_akhir_perjanjian' => now()->addDays(7)->toDateString(),
                'harga_satuan_negosiasi' => [97000],
            ]);

        $response->assertRedirect(route('negosiasi.create', $kegiatan->id));
        $response->assertSessionHasErrors('kegiatan_id');
        $this->assertDatabaseCount('negosiasi_harga', 1);
    }

    public function test_update_negosiasi_keeps_item_count_in_sync(): void
    {
        [$user, $kegiatan, $pemberitahuan] = $this->seedWorkflow();

        $this->actingAs($user);

        $negosiasi = NegosiasiHarga::create([
            'kegiatan_id' => $kegiatan->id,
            'tgl_persetujuan' => now(),
            'tgl_negosiasi' => now(),
            'tgl_akhir_perjanjian' => now()->addDays(7),
        ]);
        $negosiasi->hargaNegosiasi()->create(['harga_satuan' => 98000]);

        $response = $this->patch(route('negosiasi.update', $negosiasi->id), [
            'kegiatan_id' => $kegiatan->id,
            'tgl_persetujuan' => now()->addDay()->toDateString(),
            'tgl_negosiasi' => now()->addDay()->toDateString(),
            'tgl_akhir_perjanjian' => now()->addDays(10)->toDateString(),
            'harga_satuan_negosiasi' => [96000],
        ]);

        $response->assertRedirect(route('kegiatan.show', $kegiatan->id));
        $this->assertSame(96000.0, (float) $negosiasi->fresh()->hargaNegosiasi()->first()->harga_satuan);
    }

    /**
     * @return array{0: User, 1: Kegiatan, 2: Pemberitahuan}
     */
    private function seedWorkflow(): array
    {
        $user = User::factory()->create([
            'kode_desa' => 'D01',
            'tahun_anggaran' => 2026,
            'role' => 'desa',
            'desa' => 'Selokarto',
        ]);

        $kegiatan = Kegiatan::factory()->create([
            'kode_desa' => 'D01',
            'tahun_anggaran' => 2026,
        ]);

        $penyedia = Penyedia::create([
            'created_by' => $user->id,
            'kode_desa' => 'D01',
            'nama_penyedia' => 'Penyedia A',
            'nomor_npwp' => '03.234.567.8-999.000',
        ]);

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'penyedia' => [$penyedia->id],
            'no_pbj' => 1,
            'pekerjaan' => 'Material',
            'tgl_surat_pemberitahuan' => now(),
            'tgl_batas_akhir_penawaran' => now()->addDays(3),
        ]);

        $pemberitahuan->belanjas()->create([
            'uraian' => 'Semen',
            'volume' => 1,
            'satuan' => 'sak',
        ]);

        $penawaran = Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyedia->id,
            'tgl_penawaran' => now(),
            'no_penawaran' => '001',
            'is_winner' => true,
        ]);
        $penawaran->hargaPenawaran()->create(['harga_satuan' => 100000]);

        return [$user, $kegiatan, $pemberitahuan];
    }
}
