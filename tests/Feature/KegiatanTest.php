<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\Models\NegosiasiHarga;
use App\Models\Pembayaran;
use App\Models\Penawaran;
use App\Models\Penyedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class KegiatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_pemenang_returns_zero_when_no_penawaran_exists(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();

        $this->assertSame(0, $kegiatan->statusPemenang());
    }

    public function test_status_pemenang_returns_two_when_all_penawaran_are_pembanding(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $pemberitahuan = $this->makePemberitahuan($kegiatan);
        $penyediaA = (string) Str::uuid();
        $penyediaB = (string) Str::uuid();

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA,
            'tgl_penawaran' => '2026-03-01 10:00:00',
            'no_penawaran' => '001',
            'is_winner' => false,
        ]);

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaB,
            'tgl_penawaran' => '2026-03-01 11:00:00',
            'no_penawaran' => '002',
            'is_winner' => false,
        ]);

        $this->assertSame(2, $kegiatan->fresh()->statusPemenang());
    }

    public function test_status_pemenang_returns_one_when_any_penawaran_is_winner(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $pemberitahuan = $this->makePemberitahuan($kegiatan);
        $penyediaA = (string) Str::uuid();
        $penyediaB = (string) Str::uuid();

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA,
            'tgl_penawaran' => '2026-03-01 10:00:00',
            'no_penawaran' => '001',
            'is_winner' => false,
        ]);

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaB,
            'tgl_penawaran' => '2026-03-01 11:00:00',
            'no_penawaran' => '002',
            'is_winner' => true,
        ]);

        $this->assertSame(1, $kegiatan->fresh()->statusPemenang());
    }

    public function test_kegiatan_penawaran_relation_returns_all_records_and_loads_penyedia(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $pemberitahuan = $this->makePemberitahuan($kegiatan);
        $penyediaA = $this->makePenyedia($user, 'CV Satu');
        $penyediaB = $this->makePenyedia($user, 'CV Dua');

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA->id,
            'tgl_penawaran' => '2026-03-01 10:00:00',
            'no_penawaran' => '001',
            'is_winner' => false,
        ]);

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaB->id,
            'tgl_penawaran' => '2026-03-01 11:00:00',
            'no_penawaran' => '002',
            'is_winner' => true,
        ]);

        $loadedKegiatan = Kegiatan::with('penawaran.penyedia')->findOrFail($kegiatan->id);

        $this->assertCount(2, $loadedKegiatan->penawaran);
        $this->assertSame(
            'CV Dua',
            optional($loadedKegiatan->penawaran->firstWhere('is_winner', true)->penyedia)->nama_penyedia
        );
    }

    public function test_kegiatan_detail_shows_create_penawaran_buttons_after_pemberitahuan_exists(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $penyediaA = $this->makePenyedia($user, 'CV Satu');
        $penyediaB = $this->makePenyedia($user, 'CV Dua');
        $user->penyedias()->syncWithoutDetaching([$penyediaA->id, $penyediaB->id]);

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'pekerjaan' => $kegiatan->kegiatan,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'tgl_surat_pemberitahuan' => '2026-02-01 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-02-04 00:00:00',
            'no_pbj' => 101,
            'penyedia' => [$penyediaA->id, $penyediaB->id],
        ]);
        $pemberitahuan->syncSelectedPenyedias([$penyediaA->id, $penyediaB->id]);

        $response = $this->get(route('kegiatan.show', $kegiatan->id));

        $response->assertOk();
        $response->assertSee('Penawaran : CV Satu');
        $response->assertSee('Penawaran : CV Dua');
        $response->assertDontSee('delete-form-' . $kegiatan->id . '-penawaran');
    }

    public function test_kegiatan_detail_shows_delete_penawaran_button_after_penawaran_exists(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatan = $this->makeKegiatan();
        $penyediaA = $this->makePenyedia($user, 'CV Satu');
        $penyediaB = $this->makePenyedia($user, 'CV Dua');
        $user->penyedias()->syncWithoutDetaching([$penyediaA->id, $penyediaB->id]);

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'pekerjaan' => $kegiatan->kegiatan,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'tgl_surat_pemberitahuan' => '2026-02-01 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-02-04 00:00:00',
            'no_pbj' => 101,
            'penyedia' => [$penyediaA->id, $penyediaB->id],
        ]);
        $pemberitahuan->syncSelectedPenyedias([$penyediaA->id, $penyediaB->id]);

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA->id,
            'tgl_penawaran' => '2026-03-01 10:00:00',
            'no_penawaran' => '001',
            'is_winner' => true,
        ]);

        $response = $this->get(route('kegiatan.show', $kegiatan->id));

        $response->assertOk();
        $response->assertSee('delete-form-' . $kegiatan->id . '-penawaran');
    }

    public function test_kegiatan_rekap_uses_single_penawaran_model_instead_of_collection(): void
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
            'tgl_surat_pemberitahuan' => '2026-02-01 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-02-04 00:00:00',
            'no_pbj' => 101,
            'penyedia' => [$penyediaA->id, $penyediaB->id],
        ]);
        $pemberitahuan->syncSelectedPenyedias([$penyediaA->id, $penyediaB->id]);

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA->id,
            'tgl_penawaran' => '2026-03-01 10:00:00',
            'no_penawaran' => '001',
            'is_winner' => false,
        ]);

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaB->id,
            'tgl_penawaran' => '2026-03-02 10:00:00',
            'no_penawaran' => '002',
            'is_winner' => true,
        ]);

        NegosiasiHarga::create([
            'kegiatan_id' => $kegiatan->id,
            'tgl_negosiasi' => '2026-03-03',
            'tgl_persetujuan' => '2026-03-04',
            'tgl_akhir_perjanjian' => '2026-03-10',
            'kode_desa' => $user->kode_desa,
        ]);

        Pembayaran::create([
            'kegiatan_id' => $kegiatan->id,
            'tgl_invoice' => '2026-03-11',
            'tgl_pembayaran_cms' => '2026-03-12',
        ]);

        $response = $this->get(route('kegiatan.rekap', $kegiatan->id));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    private function makeUser(): User
    {
        return User::factory()->create([
            'desa' => 'Selokarto',
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

    private function makePemberitahuan(Kegiatan $kegiatan): Pemberitahuan
    {
        return Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'pekerjaan' => $kegiatan->kegiatan,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'tgl_surat_pemberitahuan' => '2026-02-01 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-02-04 00:00:00',
            'no_pbj' => 101,
            'penyedia' => [(string) Str::uuid(), (string) Str::uuid()],
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
