<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\Models\Penyedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PemberitahuanPenyediaRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_penyedia_ids_falls_back_to_legacy_column_when_pivot_is_empty(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $penyediaA = $this->makePenyedia($user, 'CV Satu');
        $penyediaB = $this->makePenyedia($user, 'CV Dua');

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'pekerjaan' => $kegiatan->kegiatan,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'tgl_surat_pemberitahuan' => '2026-03-01 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-03-04 00:00:00',
            'no_pbj' => 1,
            'penyedia' => [$penyediaA->id, $penyediaB->id],
        ]);

        $this->assertDatabaseMissing('pemberitahuan_penyedia', [
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA->id,
        ]);

        $this->assertSame(
            [$penyediaA->id, $penyediaB->id],
            $pemberitahuan->selectedPenyediaIds()
        );

        $this->assertSame(
            [$penyediaA->id, $penyediaB->id],
            $pemberitahuan->selectedPenyedias()->pluck('id')->all()
        );
    }

    public function test_sync_selected_penyedias_updates_legacy_column_and_pivot(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $penyediaA = $this->makePenyedia($user, 'CV Satu');
        $penyediaB = $this->makePenyedia($user, 'CV Dua');

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'pekerjaan' => $kegiatan->kegiatan,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'tgl_surat_pemberitahuan' => '2026-03-01 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-03-04 00:00:00',
            'no_pbj' => 1,
            'penyedia' => [],
        ]);

        $pemberitahuan->syncSelectedPenyedias([$penyediaA->id, $penyediaB->id]);

        $this->assertSame(
            [$penyediaA->id, $penyediaB->id],
            $pemberitahuan->fresh()->selectedPenyediaIds()
        );

        $this->assertDatabaseHas('pemberitahuan_penyedia', [
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA->id,
        ]);

        $this->assertDatabaseHas('pemberitahuan_penyedia', [
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaB->id,
        ]);
    }

    public function test_penyedia_is_referenced_in_procurement_when_linked_via_pemberitahuan_pivot(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $penyedia = $this->makePenyedia($user, 'CV Referensi');

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'pekerjaan' => $kegiatan->kegiatan,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'tgl_surat_pemberitahuan' => '2026-03-01 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-03-04 00:00:00',
            'no_pbj' => 1,
            'penyedia' => [],
        ]);

        DB::table('pemberitahuan_penyedia')->insert([
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyedia->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue($penyedia->fresh()->isReferencedInProcurement());
    }

    public function test_store_pemberitahuan_rejects_penyedia_outside_active_user_list(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $penyediaA = $this->makePenyedia($user, 'CV Satu');
        $penyediaB = $this->makePenyedia($user, 'CV Dua');
        $penyediaC = $this->makePenyedia($user, 'CV Tiga');

        $user->penyedias()->syncWithoutDetaching([$penyediaA->id, $penyediaB->id]);

        $response = $this->from(route('pemberitahuan.create', $kegiatan->id))
            ->post(route('pemberitahuan.store'), [
                'rekening_apbdes' => $kegiatan->rekening_apbdes,
                'kegiatan_id' => $kegiatan->id,
                'penyedia' => [$penyediaA->id, $penyediaC->id],
                'no_pbj' => '1',
                'tgl_pemberitahuan' => '2026-03-01',
                'uraian' => ['Laptop'],
                'volume' => [1],
                'satuan' => ['Unit'],
            ]);

        $response->assertRedirect(route('pemberitahuan.create', $kegiatan->id));
        $response->assertSessionHasErrors('penyedia.1');

        $this->assertDatabaseCount('pemberitahuans', 0);
    }

    public function test_store_penawaran_rejects_penyedia_not_selected_in_pemberitahuan(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $penyediaA = $this->makePenyedia($user, 'CV Satu');
        $penyediaB = $this->makePenyedia($user, 'CV Dua');
        $penyediaC = $this->makePenyedia($user, 'CV Tiga');

        $user->penyedias()->syncWithoutDetaching([$penyediaA->id, $penyediaB->id, $penyediaC->id]);

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'pekerjaan' => $kegiatan->kegiatan,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'tgl_surat_pemberitahuan' => '2026-03-01 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-03-04 00:00:00',
            'no_pbj' => 1,
            'penyedia' => [],
        ]);
        $pemberitahuan->syncSelectedPenyedias([$penyediaA->id, $penyediaB->id]);

        $response = $this->from(route('penawaran.create', [$kegiatan->id, $penyediaA->id]))
            ->post(route('penawaran.store'), [
                'pemberitahuan_id' => $pemberitahuan->id,
                'penyedia' => $penyediaC->id,
                'tgl_surat_penawaran' => '2026-03-02',
                'no_penawaran' => '001',
                'harga_satuan' => [100000],
            ]);

        $response->assertRedirect(route('penawaran.create', [$kegiatan->id, $penyediaA->id]));
        $response->assertSessionHasErrors('penyedia');

        $this->assertDatabaseMissing('penawaran', [
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaC->id,
        ]);
    }

    public function test_edit_pemberitahuan_lists_attached_bank_penyedia_from_user_relation(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $ownedPenyedia = $this->makePenyedia($user, 'CV Milik Desa');

        $bankOwner = $this->makeUser('Bandung');
        $bankPenyedia = $this->makePenyedia($bankOwner, 'CV Bank Penyedia');

        $user->penyedias()->syncWithoutDetaching([$ownedPenyedia->id, $bankPenyedia->id]);

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'pekerjaan' => $kegiatan->kegiatan,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'tgl_surat_pemberitahuan' => '2026-03-01 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-03-04 00:00:00',
            'no_pbj' => 1,
            'penyedia' => [],
        ]);
        $pemberitahuan->syncSelectedPenyedias([$ownedPenyedia->id, $bankPenyedia->id]);

        $response = $this->get(route('pemberitahuan.edit', $pemberitahuan->id));

        $response->assertOk();
        $response->assertSee('CV Milik Desa');
        $response->assertSee('CV Bank Penyedia');
    }

    private function makeUser(string $desa = 'Selokarto'): User
    {
        return User::factory()->create([
            'desa' => $desa,
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
            'akses_desa_panel' => true,
        ]);
    }

    private function makeKegiatan(): Kegiatan
    {
        return Kegiatan::create([
            'rekening_apbdes' => '5.2.1.01',
            'sumber_dana' => 'Dana Desa',
            'kegiatan' => 'Pengadaan Laptop',
            'lokasi_kegiatan' => 'Balai Desa',
            'ketua_tpk' => 'Ketua',
            'sekretaris_tpk' => 'Sekretaris',
            'anggota_tpk' => 'Anggota',
            'nomor_sk_tpk' => 1,
            'tgl_sk_tpk' => '2026-01-10 00:00:00',
            'nomor_sk_pka' => 2,
            'tgl_sk_pka' => '2026-01-11 00:00:00',
            'pph_22' => 0.03,
            'pka' => 'PKA Desa',
        ]);
    }

    private function makePenyedia(User $user, string $nama): Penyedia
    {
        return Penyedia::create([
            'created_by' => $user->id,
            'nama_penyedia' => $nama,
            'alamat_penyedia' => 'Alamat Penyedia',
            'nama_pemilik' => 'Pemilik',
            'alamat_pemilik' => 'Alamat Pemilik',
            'nomor_hp' => '08123456789',
            'nomor_identitas' => '1234567890123456',
            'nomor_npwp' => '09.123.456.7-890.000',
            'nomor_izin_usaha' => 'SIUP-001',
            'jabata_pemilik' => 'Direktur',
            'instansi_pemberi_izin_usaha' => 'DPMPTSP',
            'rekening' => '1234567890',
            'bank' => 'BPD',
            'atas_nama' => $nama,
            'logo_penyedia' => 'logo/default.png',
            'data_dukung' => 'data_dukung/default.pdf',
            'kop_surat' => 'kop_surat/default.png',
            'kabupaten' => 'Batang',
        ]);
    }
}
