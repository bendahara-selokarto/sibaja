<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\NegosiasiHarga;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembayaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_kegiatan_cannot_store_duplicate_pembayaran(): void
    {
        [$user, $kegiatan, $negosiasi] = $this->seedKegiatanDenganNegosiasi();

        $this->actingAs($user);

        Pembayaran::create([
            'kegiatan_id' => $kegiatan->id,
            'tgl_invoice' => $negosiasi->tgl_akhir_perjanjian,
            'tgl_pembayaran_cms' => $negosiasi->tgl_akhir_perjanjian,
        ]);

        $response = $this
            ->from(route('pembayaran.create', $kegiatan->id))
            ->post(route('pembayaran.store'), [
                'kegiatan_id' => $kegiatan->id,
                'tgl_invoice' => now()->addDays(7)->toDateString(),
                'tgl_pembayaran_cms' => now()->addDays(7)->toDateString(),
            ]);

        $response->assertRedirect(route('pembayaran.create', $kegiatan->id));
        $response->assertSessionHasErrors('kegiatan_id');
        $this->assertDatabaseCount('pembayarans', 1);
    }

    public function test_pembayaran_dates_cannot_precede_negosiasi_end_date(): void
    {
        [$user, $kegiatan, $negosiasi] = $this->seedKegiatanDenganNegosiasi();

        $this->actingAs($user);

        $response = $this
            ->from(route('pembayaran.create', $kegiatan->id))
            ->post(route('pembayaran.store'), [
                'kegiatan_id' => $kegiatan->id,
                'tgl_invoice' => now()->addDays(4)->toDateString(),
                'tgl_pembayaran_cms' => now()->addDays(4)->toDateString(),
            ]);

        $response->assertRedirect(route('pembayaran.create', $kegiatan->id));
        $response->assertSessionHasErrors('tgl_invoice');
        $this->assertDatabaseCount('pembayarans', 0);
    }

    /**
     * @return array{0: User, 1: Kegiatan, 2: NegosiasiHarga}
     */
    private function seedKegiatanDenganNegosiasi(): array
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

        $negosiasi = NegosiasiHarga::create([
            'kegiatan_id' => $kegiatan->id,
            'tgl_persetujuan' => now()->addDays(5),
            'tgl_negosiasi' => now()->addDays(5),
            'tgl_akhir_perjanjian' => now()->addDays(6),
        ]);

        return [$user, $kegiatan, $negosiasi];
    }
}
