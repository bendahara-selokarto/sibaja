<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\NegosiasiHarga;
use App\Models\Pembayaran;
use App\Models\Pemberitahuan;
use App\Models\Penyedia;
use App\Models\Penawaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_kegiatan_requires_explicit_panel_access(): void
    {
        $user = $this->makeUser(aksesDesaPanel: false);

        $response = $this->actingAs($user)->get(route('menu.kegiatan'));

        $response->assertRedirect(route('profile.edit'));
    }

    public function test_direct_procurement_route_requires_explicit_panel_access(): void
    {
        $user = $this->makeUser(aksesDesaPanel: false);

        $response = $this->actingAs($user)->get(route('kegiatan.create'));

        $response->assertRedirect(route('profile.edit'));
    }

    public function test_menu_kegiatan_allows_explicitly_entitled_user(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get(route('menu.kegiatan'));

        $response->assertOk();
    }

    public function test_procurement_routes_return_not_found_for_other_tenant_records(): void
    {
        $owner = $this->makeUser();
        $intruder = $this->makeUser(desa: 'Bandung');

        $kegiatan = $this->actingAs($owner)->makeKegiatan();
        $penyediaA = $this->makePenyedia($owner, 'CV Satu');
        $penyediaB = $this->makePenyedia($owner, 'CV Dua');
        $owner->penyedias()->syncWithoutDetaching([$penyediaA->id, $penyediaB->id]);

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

        Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA->id,
            'tgl_penawaran' => '2026-03-02',
            'no_penawaran' => 'P-001',
            'is_winner' => true,
        ]);

        NegosiasiHarga::create([
            'kegiatan_id' => $kegiatan->id,
            'tgl_negosiasi' => '2026-03-03',
            'tgl_persetujuan' => '2026-03-04',
            'tgl_akhir_perjanjian' => '2026-03-10',
            'kode_desa' => $owner->kode_desa,
        ]);

        $pembayaran = Pembayaran::create([
            'kegiatan_id' => $kegiatan->id,
            'tgl_invoice' => '2026-03-11',
            'tgl_pembayaran_cms' => '2026-03-12',
        ]);

        $this->actingAs($intruder);

        $this->get(route('kegiatan.show', $kegiatan->id))->assertNotFound();
        $this->get(route('pemberitahuan.edit', $pemberitahuan->id))->assertNotFound();
        $this->get(route('penawaran.create', [$kegiatan->id, $penyediaA->id]))->assertNotFound();
        $this->get(route('negosiasi.create', $kegiatan->id))->assertNotFound();
        $this->get(route('pembayaran.edit', $kegiatan->id))->assertNotFound();
        $this->patch(route('pembayaran.update', $pembayaran->id), [
            'kegiatan_id' => $kegiatan->id,
            'tgl_pembayaran_cms' => '2026-03-13',
            'tgl_invoice' => '2026-03-11',
        ])->assertNotFound();
    }

    private function makeUser(string $desa = 'Selokarto', bool $aksesDesaPanel = true): User
    {
        return User::factory()->create([
            'desa' => $desa,
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
            'role' => 'desa',
            'akses_desa_panel' => $aksesDesaPanel,
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
