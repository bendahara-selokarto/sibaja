<?php

namespace Tests\Unit;

use App\Models\Penyedia;
use App\Models\User;
use App\Policies\PenyediaPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenyediaPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_is_allowed_for_owner_and_denied_for_non_owner(): void
    {
        $owner = $this->makeUser();
        $otherUser = $this->makeUser();
        $penyedia = $this->makePenyedia($owner);
        $policy = new PenyediaPolicy();

        $this->assertTrue($policy->update($owner, $penyedia)->allowed());
        $this->assertFalse($policy->update($otherUser, $penyedia)->allowed());
    }

    public function test_detach_is_denied_for_owner_and_allowed_for_attached_non_owner(): void
    {
        $owner = $this->makeUser();
        $otherUser = $this->makeUser();
        $penyedia = $this->makePenyedia($owner);
        $otherUser->penyedias()->syncWithoutDetaching([$penyedia->id]);
        $policy = new PenyediaPolicy();

        $this->assertFalse($policy->detach($owner, $penyedia)->allowed());
        $this->assertTrue($policy->detach($otherUser, $penyedia)->allowed());
    }

    private function makeUser(): User
    {
        return User::factory()->create([
            'desa' => fake()->city(),
            'kecamatan' => fake()->city(),
            'website' => fake()->unique()->domainName(),
            'kode_desa' => fake()->unique()->numerify('##########'),
            'tahun_anggaran' => 2026,
        ]);
    }

    private function makePenyedia(User $owner): Penyedia
    {
        $this->actingAs($owner);

        return Penyedia::create([
            'created_by' => $owner->id,
            'nama_penyedia' => 'CV Arsitektur',
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
            'atas_nama' => 'CV Arsitektur',
            'logo_penyedia' => 'logo/default.png',
            'data_dukung' => 'data_dukung/default.pdf',
            'kop_surat' => 'kop_surat/default.png',
            'kabupaten' => 'Batang',
        ]);
    }
}
