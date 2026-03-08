<?php

namespace Tests\Feature;

use App\Models\Penyedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PenyediaUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_preserves_existing_file_paths_when_no_new_uploads_are_sent(): void
    {
        $user = User::factory()->create([
            'desa' => 'Selokarto',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);

        $this->actingAs($user);

        $penyedia = Penyedia::create([
            'created_by' => $user->id,
            'nama_penyedia' => 'CV Lama',
            'alamat_penyedia' => 'Alamat Lama',
            'nama_pemilik' => 'Pemilik Lama',
            'alamat_pemilik' => 'Alamat Pemilik Lama',
            'nomor_hp' => '08123456789',
            'nomor_identitas' => '1234567890123456',
            'nomor_npwp' => '09.123.456.7-890.000',
            'nomor_izin_usaha' => 'SIUP-001',
            'jabata_pemilik' => 'Direktur',
            'instansi_pemberi_izin_usaha' => 'DPMPTSP',
            'rekening' => '1234567890',
            'bank' => 'BPD',
            'atas_nama' => 'CV Lama',
            'logo_penyedia' => 'logo/existing-logo.png',
            'data_dukung' => 'data_dukung/existing-data.pdf',
            'kop_surat' => 'kop_surat/existing-kop.png',
            'kabupaten' => 'Batang',
        ]);

        $response = $this->patch(route('penyedia.update', $penyedia->id), [
            'nama_penyedia' => 'CV Baru',
            'alamat_penyedia' => 'Alamat Baru',
            'nama_pemilik' => 'Pemilik Baru',
            'alamat_pemilik' => 'Alamat Pemilik Baru',
            'nomor_hp' => '08999999999',
            'nomor_identitas' => '6543210987654321',
            'nomor_npwp' => '09.123.456.7-890.111',
            'no_siup' => 'SIUP-002',
            'jabatan_pemilik' => 'Direktur Utama',
            'penerbit_siup' => 'OSS',
            'rekening' => '9876543210',
            'bank' => 'BRI',
            'atas_nama' => 'CV Baru',
            'kabupaten' => 'Batang',
        ]);

        $response->assertRedirect(route('menu.penyedia'));

        $this->assertDatabaseHas('penyedias', [
            'id' => $penyedia->id,
            'nama_penyedia' => 'CV Baru',
            'logo_penyedia' => 'logo/existing-logo.png',
            'data_dukung' => 'data_dukung/existing-data.pdf',
            'kop_surat' => 'kop_surat/existing-kop.png',
        ]);
    }

    public function test_non_owner_cannot_update_foreign_master_penyedia(): void
    {
        $owner = User::factory()->create([
            'desa' => 'Selokarto',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);

        $otherUser = User::factory()->create([
            'desa' => 'Bandung',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);

        $this->actingAs($owner);

        $penyedia = Penyedia::create([
            'created_by' => $owner->id,
            'nama_penyedia' => 'CV Master',
            'alamat_penyedia' => 'Alamat Lama',
            'nama_pemilik' => 'Pemilik Lama',
            'alamat_pemilik' => 'Alamat Pemilik Lama',
            'nomor_hp' => '08123456789',
            'nomor_identitas' => '1234567890123456',
            'nomor_npwp' => '09.123.456.7-890.000',
            'nomor_izin_usaha' => 'SIUP-001',
            'jabata_pemilik' => 'Direktur',
            'instansi_pemberi_izin_usaha' => 'DPMPTSP',
            'rekening' => '1234567890',
            'bank' => 'BPD',
            'atas_nama' => 'CV Master',
            'logo_penyedia' => 'logo/existing-logo.png',
            'data_dukung' => 'data_dukung/existing-data.pdf',
            'kop_surat' => 'kop_surat/existing-kop.png',
            'kabupaten' => 'Batang',
        ]);

        $otherUser->penyedias()->syncWithoutDetaching([$penyedia->id]);

        $response = $this
            ->actingAs($otherUser)
            ->patch(route('penyedia.update', $penyedia->id), [
                'nama_penyedia' => 'CV Dibajak',
                'alamat_penyedia' => 'Alamat Baru',
                'nama_pemilik' => 'Pemilik Baru',
                'alamat_pemilik' => 'Alamat Pemilik Baru',
                'nomor_hp' => '08999999999',
                'nomor_identitas' => '6543210987654321',
                'nomor_npwp' => '09.123.456.7-890.111',
                'no_siup' => 'SIUP-002',
                'jabatan_pemilik' => 'Direktur Utama',
                'penerbit_siup' => 'OSS',
                'rekening' => '9876543210',
                'bank' => 'BRI',
                'atas_nama' => 'CV Baru',
                'kabupaten' => 'Batang',
            ]);

        $response->assertRedirect(route('menu.penyedia'));

        $this->assertDatabaseHas('penyedias', [
            'id' => $penyedia->id,
            'nama_penyedia' => 'CV Master',
        ]);
    }

    public function test_attach_from_bank_only_creates_relation_without_mutating_master_data(): void
    {
        $owner = User::factory()->create([
            'desa' => 'Selokarto',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);

        $otherUser = User::factory()->create([
            'desa' => 'Bandung',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);

        $this->actingAs($owner);

        $penyedia = Penyedia::create([
            'created_by' => $owner->id,
            'nama_penyedia' => 'CV Bank',
            'alamat_penyedia' => 'Alamat Bank',
            'nama_pemilik' => 'Pemilik Bank',
            'alamat_pemilik' => 'Alamat Pemilik Bank',
            'nomor_hp' => '08123456789',
            'nomor_identitas' => '1234567890123456',
            'nomor_npwp' => '09.123.456.7-890.000',
            'nomor_izin_usaha' => 'SIUP-001',
            'jabata_pemilik' => 'Direktur',
            'instansi_pemberi_izin_usaha' => 'DPMPTSP',
            'rekening' => '1234567890',
            'bank' => 'BPD',
            'atas_nama' => 'CV Bank',
            'logo_penyedia' => 'logo/existing-logo.png',
            'data_dukung' => 'data_dukung/existing-data.pdf',
            'kop_surat' => 'kop_surat/existing-kop.png',
            'kabupaten' => 'Batang',
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->post(route('penyedia.attach', $penyedia->id));

        $response->assertRedirect(route('menu.penyedia'));

        $this->assertDatabaseHas('daftar_penyedia', [
            'user_id' => $otherUser->id,
            'penyedia_id' => $penyedia->id,
        ]);

        $this->assertDatabaseHas('penyedias', [
            'id' => $penyedia->id,
            'nama_penyedia' => 'CV Bank',
            'alamat_penyedia' => 'Alamat Bank',
        ]);
    }

    public function test_bank_penyedia_page_renders_foreign_penyedia_and_attach_button(): void
    {
        $owner = User::factory()->create([
            'desa' => 'Selokarto',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);

        $otherUser = User::factory()->create([
            'desa' => 'Bandung',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);

        $this->actingAs($owner);

        $penyedia = Penyedia::create([
            'created_by' => $owner->id,
            'nama_penyedia' => 'CV Bank',
            'alamat_penyedia' => 'Alamat Bank',
            'nama_pemilik' => 'Pemilik Bank',
            'alamat_pemilik' => 'Alamat Pemilik Bank',
            'nomor_hp' => '08123456789',
            'nomor_identitas' => '1234567890123456',
            'nomor_npwp' => '09.123.456.7-890.000',
            'nomor_izin_usaha' => 'SIUP-001',
            'jabata_pemilik' => 'Direktur',
            'instansi_pemberi_izin_usaha' => 'DPMPTSP',
            'rekening' => '1234567890',
            'bank' => 'BPD',
            'atas_nama' => 'CV Bank',
            'logo_penyedia' => 'logo/existing-logo.png',
            'data_dukung' => 'data_dukung/existing-data.pdf',
            'kop_surat' => 'kop_surat/existing-kop.png',
            'kabupaten' => 'Batang',
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->get(route('submenu.penyedia'));

        $response->assertOk();
        $response->assertSee('CV Bank');
        $response->assertSee(route('penyedia.attach', $penyedia->id), false);
    }

    public function test_update_replacing_media_deletes_old_files_and_persists_new_paths(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'desa' => 'Selokarto',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);

        $this->actingAs($user);

        Storage::disk('public')->put('logo/existing-logo.png', 'old-logo');
        Storage::disk('public')->put('kop_surat/existing-kop.png', 'old-kop');
        Storage::disk('public')->put('data_dukung/existing-data.pdf', 'old-data');

        $penyedia = Penyedia::create([
            'created_by' => $user->id,
            'nama_penyedia' => 'CV Lama',
            'alamat_penyedia' => 'Alamat Lama',
            'nama_pemilik' => 'Pemilik Lama',
            'alamat_pemilik' => 'Alamat Pemilik Lama',
            'nomor_hp' => '08123456789',
            'nomor_identitas' => '1234567890123456',
            'nomor_npwp' => '09.123.456.7-890.000',
            'nomor_izin_usaha' => 'SIUP-001',
            'jabata_pemilik' => 'Direktur',
            'instansi_pemberi_izin_usaha' => 'DPMPTSP',
            'rekening' => '1234567890',
            'bank' => 'BPD',
            'atas_nama' => 'CV Lama',
            'logo_penyedia' => 'logo/existing-logo.png',
            'data_dukung' => 'data_dukung/existing-data.pdf',
            'kop_surat' => 'kop_surat/existing-kop.png',
            'kabupaten' => 'Batang',
        ]);

        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO7Z0Y4AAAAASUVORK5CYII='
        );

        $response = $this->patch(route('penyedia.update', $penyedia->id), [
            'nama_penyedia' => 'CV Baru',
            'alamat_penyedia' => 'Alamat Baru',
            'nama_pemilik' => 'Pemilik Baru',
            'alamat_pemilik' => 'Alamat Pemilik Baru',
            'nomor_hp' => '08999999999',
            'nomor_identitas' => '6543210987654321',
            'nomor_npwp' => '09.123.456.7-890.111',
            'no_siup' => 'SIUP-002',
            'jabatan_pemilik' => 'Direktur Utama',
            'penerbit_siup' => 'OSS',
            'rekening' => '9876543210',
            'bank' => 'BRI',
            'atas_nama' => 'CV Baru',
            'kabupaten' => 'Batang',
            'logo_penyedia' => UploadedFile::fake()->createWithContent('new-logo.png', $png),
            'kop_surat' => UploadedFile::fake()->create('new-kop.pdf', 100, 'application/pdf'),
            'data_dukung' => UploadedFile::fake()->create('new-data.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('menu.penyedia'));

        $penyedia->refresh();

        Storage::disk('public')->assertMissing('logo/existing-logo.png');
        Storage::disk('public')->assertMissing('kop_surat/existing-kop.png');
        Storage::disk('public')->assertMissing('data_dukung/existing-data.pdf');
        Storage::disk('public')->assertExists($penyedia->logo_penyedia);
        Storage::disk('public')->assertExists($penyedia->kop_surat);
        Storage::disk('public')->assertExists($penyedia->data_dukung);
        $this->assertNotSame('logo/existing-logo.png', $penyedia->logo_penyedia);
        $this->assertNotSame('kop_surat/existing-kop.png', $penyedia->kop_surat);
        $this->assertNotSame('data_dukung/existing-data.pdf', $penyedia->data_dukung);
    }

    public function test_destroy_deletes_owned_media_files_when_master_penyedia_is_removed(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'desa' => 'Selokarto',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);

        $this->actingAs($user);

        Storage::disk('public')->put('logo/existing-logo.png', 'old-logo');
        Storage::disk('public')->put('kop_surat/existing-kop.png', 'old-kop');
        Storage::disk('public')->put('data_dukung/existing-data.pdf', 'old-data');

        $penyedia = Penyedia::create([
            'created_by' => $user->id,
            'nama_penyedia' => 'CV Hapus',
            'alamat_penyedia' => 'Alamat',
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
            'atas_nama' => 'CV Hapus',
            'logo_penyedia' => 'logo/existing-logo.png',
            'data_dukung' => 'data_dukung/existing-data.pdf',
            'kop_surat' => 'kop_surat/existing-kop.png',
            'kabupaten' => 'Batang',
        ]);

        $user->penyedias()->syncWithoutDetaching([$penyedia->id]);

        $response = $this->delete(route('penyedia.destroy', $penyedia->id));

        $response->assertRedirect(route('menu.penyedia'));

        Storage::disk('public')->assertMissing('logo/existing-logo.png');
        Storage::disk('public')->assertMissing('kop_surat/existing-kop.png');
        Storage::disk('public')->assertMissing('data_dukung/existing-data.pdf');
        $this->assertDatabaseMissing('penyedias', [
            'id' => $penyedia->id,
        ]);
    }

    public function test_update_can_clear_existing_media_without_uploading_replacement(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'desa' => 'Selokarto',
            'kecamatan' => 'Blado',
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);

        $this->actingAs($user);

        Storage::disk('public')->put('logo/existing-logo.png', 'old-logo');
        Storage::disk('public')->put('kop_surat/existing-kop.png', 'old-kop');
        Storage::disk('public')->put('data_dukung/existing-data.pdf', 'old-data');

        $penyedia = Penyedia::create([
            'created_by' => $user->id,
            'nama_penyedia' => 'CV Lama',
            'alamat_penyedia' => 'Alamat Lama',
            'nama_pemilik' => 'Pemilik Lama',
            'alamat_pemilik' => 'Alamat Pemilik Lama',
            'nomor_hp' => '08123456789',
            'nomor_identitas' => '1234567890123456',
            'nomor_npwp' => '09.123.456.7-890.000',
            'nomor_izin_usaha' => 'SIUP-001',
            'jabata_pemilik' => 'Direktur',
            'instansi_pemberi_izin_usaha' => 'DPMPTSP',
            'rekening' => '1234567890',
            'bank' => 'BPD',
            'atas_nama' => 'CV Lama',
            'logo_penyedia' => 'logo/existing-logo.png',
            'data_dukung' => 'data_dukung/existing-data.pdf',
            'kop_surat' => 'kop_surat/existing-kop.png',
            'kabupaten' => 'Batang',
        ]);

        $response = $this->patch(route('penyedia.update', $penyedia->id), [
            'nama_penyedia' => 'CV Baru',
            'alamat_penyedia' => 'Alamat Baru',
            'nama_pemilik' => 'Pemilik Baru',
            'alamat_pemilik' => 'Alamat Pemilik Baru',
            'nomor_hp' => '08999999999',
            'nomor_identitas' => '6543210987654321',
            'nomor_npwp' => '09.123.456.7-890.111',
            'no_siup' => 'SIUP-002',
            'jabatan_pemilik' => 'Direktur Utama',
            'penerbit_siup' => 'OSS',
            'rekening' => '9876543210',
            'bank' => 'BRI',
            'atas_nama' => 'CV Baru',
            'kabupaten' => 'Batang',
            'clear_logo_penyedia' => '1',
            'clear_kop_surat' => '1',
            'clear_data_dukung' => '1',
        ]);

        $response->assertRedirect(route('menu.penyedia'));

        $penyedia->refresh();

        Storage::disk('public')->assertMissing('logo/existing-logo.png');
        Storage::disk('public')->assertMissing('kop_surat/existing-kop.png');
        Storage::disk('public')->assertMissing('data_dukung/existing-data.pdf');
        $this->assertSame('logo/default.png', $penyedia->logo_penyedia);
        $this->assertSame('', $penyedia->kop_surat);
        $this->assertSame('', $penyedia->data_dukung);
    }
}
