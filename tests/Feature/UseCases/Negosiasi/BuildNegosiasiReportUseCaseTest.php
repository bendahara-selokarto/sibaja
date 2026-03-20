<?php

namespace Tests\Feature\UseCases\Negosiasi;

use App\Models\Belanja;
use App\Models\Kegiatan;
use App\Models\NegosiasiHarga;
use App\Models\Penawaran;
use App\Models\Pemberitahuan;
use App\Models\Penyedia;
use App\Models\User;
use App\UseCases\Negosiasi\BuildNegosiasiReportUseCase;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuildNegosiasiReportUseCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_builds_negosiasi_report_with_totals_and_identifiers(): void
    {
        [$kegiatan, $penyedia] = $this->seedReportFixture();

        $result = (new BuildNegosiasiReportUseCase())->execute($kegiatan->id);

        $this->assertSame($penyedia->id, $result->penyedia->id);
        $this->assertCount(2, $result->item);
        $this->assertEqualsWithDelta(492500.0, $result->penawaranHarga->harga_sebelum_pajak, 0.001);
        $this->assertEqualsWithDelta(555000.0, $result->negosiasiHarga->harga_total, 0.001);
        $this->assertStringContainsString('/SPK/D01/2026', $result->pemberitahuan->no_spk);
    }

    public function test_requires_negosiasi_record(): void
    {
        [$kegiatan] = $this->seedReportFixture(withNegosiasi: false);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Negosiasi belum tersedia.');

        (new BuildNegosiasiReportUseCase())->execute($kegiatan->id);
    }

    public function test_view_data_structure_provides_pdf_input(): void
    {
        [$kegiatan] = $this->seedReportFixture();
        $result = (new BuildNegosiasiReportUseCase())->execute($kegiatan->id);
        $viewData = $result->toViewData();

        $this->assertArrayHasKey('kegiatan', $viewData);
        $this->assertArrayHasKey('penyedia', $viewData);
        $this->assertArrayHasKey('pemberitahuan', $viewData);
        $this->assertArrayHasKey('penawaranHarga', $viewData);
        $this->assertArrayHasKey('negosiasiHarga', $viewData);
        $this->assertArrayHasKey('item', $viewData);
        $this->assertCount(2, $viewData['item']);
        $this->assertEqualsWithDelta($result->negosiasiHarga->harga_total, $viewData['negosiasiHarga']->harga_total, 0.001);
    }

    private function seedReportFixture(bool $withNegosiasi = true): array
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
            'ppn' => 0.11,
            'pph_22' => 0.015,
            'kegiatan' => 'Pengadaan Material',
        ]);

        $penyedia = Penyedia::create([
            'created_by' => $user->id,
            'kode_desa' => 'D01',
            'nama_penyedia' => 'PT. Pemenang',
            'nomor_npwp' => '01.000.000.0-000.000',
            'kabupaten' => 'Batang',
        ]);

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'kode_desa' => 'D01',
            'no_pbj' => 7,
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'pekerjaan' => 'Material',
            'tgl_surat_pemberitahuan' => now(),
            'tgl_batas_akhir_penawaran' => now()->addDays(3),
        ]);

        $pemberitahuan->belanjas()->createMany([
            ['uraian' => 'Semen', 'volume' => 1, 'satuan' => 'sak'],
            ['uraian' => 'Pasir', 'volume' => 2, 'satuan' => 'm3'],
        ]);

        $penawaran = Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyedia->id,
            'tgl_penawaran' => now(),
            'no_penawaran' => '001',
            'is_winner' => true,
        ]);

        $penawaran->hargaPenawaran()->createMany([
            ['harga_satuan' => 111000],
            ['harga_satuan' => 222000],
        ]);

        $negosiasi = null;
        if ($withNegosiasi) {
            $negosiasi = NegosiasiHarga::create([
                'kegiatan_id' => $kegiatan->id,
                'tgl_persetujuan' => now()->addDays(5),
                'tgl_negosiasi' => now()->addDays(6),
                'tgl_akhir_perjanjian' => now()->addDays(8),
            ]);

            $negosiasi->hargaNegosiasi()->createMany([
                ['harga_satuan' => 111000],
                ['harga_satuan' => 222000],
            ]);
        }

        return [$kegiatan, $penyedia, $negosiasi];
    }
}
