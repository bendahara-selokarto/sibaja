<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\Models\Penyedia;
use App\Models\User;
use App\Support\PemberitahuanPenyediaSyncAudit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PemberitahuanPenyediaAuditCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_service_reports_matching_and_mismatched_records(): void
    {
        $user = $this->makeUser();
        $this->actingAs($user);

        $kegiatanA = $this->makeKegiatan('Pengadaan Laptop');
        $kegiatanB = $this->makeKegiatan('Pengadaan Printer');

        $penyediaA = $this->makePenyedia($user, 'CV Satu');
        $penyediaB = $this->makePenyedia($user, 'CV Dua');
        $penyediaC = $this->makePenyedia($user, 'CV Tiga');

        $matching = Pemberitahuan::create([
            'kegiatan_id' => $kegiatanA->id,
            'pekerjaan' => $kegiatanA->kegiatan,
            'rekening_apbdes' => $kegiatanA->rekening_apbdes,
            'tgl_surat_pemberitahuan' => '2026-03-01 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-03-04 00:00:00',
            'no_pbj' => 1,
            'penyedia' => [$penyediaA->id, $penyediaB->id],
        ]);
        $matching->syncSelectedPenyedias([$penyediaA->id, $penyediaB->id]);

        $mismatched = Pemberitahuan::create([
            'kegiatan_id' => $kegiatanB->id,
            'pekerjaan' => $kegiatanB->kegiatan,
            'rekening_apbdes' => $kegiatanB->rekening_apbdes,
            'tgl_surat_pemberitahuan' => '2026-03-02 00:00:00',
            'tgl_batas_akhir_penawaran' => '2026-03-05 00:00:00',
            'no_pbj' => 2,
            'penyedia' => [$penyediaA->id, $penyediaB->id],
        ]);

        DB::table('pemberitahuan_penyedia')->insert([
            [
                'pemberitahuan_id' => $mismatched->id,
                'penyedia_id' => $penyediaA->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pemberitahuan_id' => $mismatched->id,
                'penyedia_id' => $penyediaC->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $result = app(PemberitahuanPenyediaSyncAudit::class)->run();

        $this->assertTrue($result['table_exists']);
        $this->assertSame(1, $result['summary']['matching_records']);
        $this->assertSame(1, $result['summary']['mismatched_records']);
        $this->assertSame(1, $result['summary']['legacy_only_links']);
        $this->assertSame(1, $result['summary']['pivot_only_links']);
        $this->assertSame((string) $mismatched->id, $result['mismatches'][0]['pemberitahuan_id']);
        $this->assertContains((string) $penyediaB->id, $result['mismatches'][0]['legacy_only_ids']);
        $this->assertContains((string) $penyediaC->id, $result['mismatches'][0]['pivot_only_ids']);
    }

    private function makeUser(): User
    {
        return User::factory()->create([
            'desa' => 'Selokarto',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);
    }

    private function makeKegiatan(string $nama): Kegiatan
    {
        return Kegiatan::create([
            'rekening_apbdes' => '5.2.1.01',
            'sumber_dana' => 'Dana Desa',
            'kegiatan' => $nama,
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
