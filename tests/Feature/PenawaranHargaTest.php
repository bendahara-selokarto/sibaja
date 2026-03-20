<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Penawaran;
use App\Models\Pemberitahuan;
use App\Models\Penyedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenawaranHargaTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_penyedia_cannot_submit_penawaran_twice(): void
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
            'nomor_npwp' => '01.234.567.8-999.000',
        ]);

        $penyediaB = Penyedia::create([
            'created_by' => $user->id,
            'nama_penyedia' => 'Penyedia B',
            'nomor_npwp' => '01.234.567.8-999.001',
        ]);

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'penyedia' => [$penyediaA->id, $penyediaB->id],
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

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA->id,
            'tgl_penawaran' => now(),
            'no_penawaran' => '001',
            'is_winner' => true,
        ]);

        $response = $this
            ->from(route('penawaran.create', [$kegiatan->id, $penyediaA->id]))
            ->post(route('penawaran.store'), [
                'pemberitahuan_id' => $pemberitahuan->id,
                'penyedia' => $penyediaA->id,
                'tgl_surat_penawaran' => now()->toDateString(),
                'no_penawaran' => '002',
                'pemenang' => '0',
                'harga_satuan' => [125000],
            ]);

        $response->assertRedirect(route('penawaran.create', [$kegiatan->id, $penyediaA->id]));
        $response->assertSessionHasErrors('penyedia');
        $this->assertDatabaseCount('penawaran', 1);
    }

    public function test_updating_penawaran_to_pembanding_does_not_auto_promote_other_penawaran(): void
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
            'nomor_npwp' => '01.234.567.8-999.010',
        ]);

        $penyediaB = Penyedia::create([
            'created_by' => $user->id,
            'nama_penyedia' => 'Penyedia B',
            'nomor_npwp' => '01.234.567.8-999.011',
        ]);

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'penyedia' => [$penyediaA->id, $penyediaB->id],
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

        $winner = Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA->id,
            'tgl_penawaran' => now(),
            'no_penawaran' => '001',
            'is_winner' => true,
        ]);
        $winner->hargaPenawaran()->create(['harga_satuan' => 100000]);

        $comparison = Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaB->id,
            'tgl_penawaran' => now(),
            'no_penawaran' => '002',
            'is_winner' => false,
        ]);
        $comparison->hargaPenawaran()->create(['harga_satuan' => 110000]);

        $response = $this->patch(route('penawaran.update', $pemberitahuan->id), [
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia' => $penyediaA->id,
            'tgl_surat_penawaran' => now()->toDateString(),
            'no_penawaran' => '001A',
            'pemenang' => '0',
            'harga_satuan' => [99000],
        ]);

        $response->assertRedirect(route('kegiatan.show', $kegiatan->id));
        $this->assertFalse($winner->fresh()->is_winner);
        $this->assertFalse($comparison->fresh()->is_winner);
    }
}
