<?php

namespace Tests\Feature\UseCases\Penawaran;

use App\Models\Kegiatan;
use App\Models\Pemberitahuan;
use App\Models\Penawaran;
use App\Models\Penyedia;
use App\Models\User;
use App\UseCases\Penawaran\BuildPenawaranReportUseCase;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuildPenawaranReportUseCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_builds_penawaran_report_with_winner_and_comparison_totals(): void
    {
        [$kegiatan, $winner, $comparison] = $this->seedPenawaranReportFixture();

        $result = (new BuildPenawaranReportUseCase())->execute($kegiatan->id);

        $this->assertSame($winner->id, $result->winnerPenawaran->id);
        $this->assertSame($comparison->id, $result->comparisonPenawaran->id);
        $this->assertSame('Penyedia A', $result->winnerPenyedia->nama_penyedia);
        $this->assertSame('Penyedia B', $result->comparisonPenyedia->nama_penyedia);
        $this->assertCount(2, $result->winnerItems);
        $this->assertEqualsWithDelta(98500.0, $result->winnerItems[0]['harga_satuan'], 0.001);
        $this->assertEqualsWithDelta(394000.0, $result->winnerItems[1]['jumlah'], 0.001);
        $this->assertEqualsWithDelta(492500.0, $result->winnerPajak['bersih'], 0.001);
        $this->assertEqualsWithDelta(55000.0, $result->winnerPajak['ppn'], 0.001);
        $this->assertEqualsWithDelta(7500.0, $result->winnerPajak['pph22'], 0.001);
        $this->assertEqualsWithDelta(555000.0, $result->winnerPajak['total'], 0.001);
        $this->assertEqualsWithDelta(541750.0, $result->comparisonPajak['bersih'], 0.001);
        $this->assertEqualsWithDelta(610500.0, $result->comparisonPajak['total'], 0.001);
    }

    public function test_build_penawaran_report_requires_comparison_offer(): void
    {
        [$kegiatan] = $this->seedPenawaranReportFixture(withComparison: false);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Penawaran pembanding belum tersedia.');

        (new BuildPenawaranReportUseCase())->execute($kegiatan->id);
    }

    public function test_view_data_matches_pdf_contract(): void
    {
        [$kegiatan] = $this->seedPenawaranReportFixture();
        $result = (new BuildPenawaranReportUseCase())->execute($kegiatan->id);
        $viewData = $result->toViewData();

        $this->assertArrayHasKey('penyedia1', $viewData);
        $this->assertArrayHasKey('penyedia2', $viewData);
        $this->assertArrayHasKey('item', $viewData);
        $this->assertArrayHasKey('item_2', $viewData);
        $this->assertCount(2, $viewData['item']);
        $this->assertEqualsWithDelta($result->winnerPajak['bersih'], $viewData['jumlah'], 0.001);
        $this->assertEqualsWithDelta($result->comparisonPajak['total'], $viewData['jumlah_total_2'], 0.001);
    }

    private function seedPenawaranReportFixture(bool $withComparison = true): array
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
            'rekening_apbdes' => '5.1.02.01',
            'kegiatan' => 'Pengadaan Material',
            'ppn' => 0.11,
            'pph_22' => 0.015,
        ]);

        $penyediaA = Penyedia::create([
            'created_by' => $user->id,
            'kode_desa' => 'D01',
            'nama_penyedia' => 'Penyedia A',
            'nomor_npwp' => '01.234.567.8-999.100',
            'kabupaten' => 'Batang',
        ]);

        $penyediaB = Penyedia::create([
            'created_by' => $user->id,
            'kode_desa' => 'D01',
            'nama_penyedia' => 'Penyedia B',
            'nomor_npwp' => '01.234.567.8-999.101',
            'kabupaten' => 'Batang',
        ]);

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'kode_desa' => 'D01',
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'penyedia' => [$penyediaA->id, $penyediaB->id],
            'no_pbj' => 7,
            'pekerjaan' => 'Material',
            'tgl_surat_pemberitahuan' => now(),
            'tgl_batas_akhir_penawaran' => now()->addDays(3),
        ]);

        $pemberitahuan->belanjas()->createMany([
            [
                'uraian' => 'Semen',
                'volume' => 1,
                'satuan' => 'sak',
            ],
            [
                'uraian' => 'Pasir',
                'volume' => 2,
                'satuan' => 'm3',
            ],
        ]);

        $winner = Penawaran::create([
            'kegiatan_id' => $kegiatan->id,
            'pemberitahuan_id' => $pemberitahuan->id,
            'penyedia_id' => $penyediaA->id,
            'tgl_penawaran' => now(),
            'no_penawaran' => '001',
            'is_winner' => true,
        ]);
        $winner->hargaPenawaran()->createMany([
            ['harga_satuan' => 111000],
            ['harga_satuan' => 222000],
        ]);

        $comparison = null;
        if ($withComparison) {
            $comparison = Penawaran::create([
                'kegiatan_id' => $kegiatan->id,
                'pemberitahuan_id' => $pemberitahuan->id,
                'penyedia_id' => $penyediaB->id,
                'tgl_penawaran' => now(),
                'no_penawaran' => '002',
                'is_winner' => false,
            ]);
            $comparison->hargaPenawaran()->createMany([
                ['harga_satuan' => 122100],
                ['harga_satuan' => 244200],
            ]);
        }

        return [$kegiatan, $winner, $comparison];
    }
}
