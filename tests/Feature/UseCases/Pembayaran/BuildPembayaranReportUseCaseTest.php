<?php

namespace Tests\Feature\UseCases\Pembayaran;

use App\Models\Kegiatan;
use App\Models\NegosiasiHarga;
use App\Models\Pembayaran;
use App\Models\Pemberitahuan;
use App\Models\Penawaran;
use App\Models\Penyedia;
use App\Models\User;
use App\UseCases\Pembayaran\BuildPembayaranReportUseCase;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuildPembayaranReportUseCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_builds_pembayaran_report_summary_from_negosiasi_items(): void
    {
        [$kegiatan, $pembayaran, $penyedia] = $this->seedPembayaranReportFixture();

        $result = (new BuildPembayaranReportUseCase())->execute($kegiatan->id);

        $this->assertSame($penyedia->id, $result->penyedia->id);
        $this->assertSame(7, $result->kegiatan->nomor);
        $this->assertCount(2, $result->item);
        $this->assertEqualsWithDelta(98500.0, $result->item[0]['jumlah_negosiasi'], 0.001);
        $this->assertEqualsWithDelta(444000.0, $result->item[1]['total_negosiasi'], 0.001);
        $this->assertEqualsWithDelta(492500.0, $result->negosiasiHarga->jumlah, 0.001);
        $this->assertEqualsWithDelta(55000.0, $result->negosiasiHarga->ppn, 0.001);
        $this->assertEqualsWithDelta(7500.0, $result->negosiasiHarga->pph_22, 0.001);
        $this->assertEqualsWithDelta(62500.0, $result->negosiasiHarga->pajak, 0.001);
        $this->assertEqualsWithDelta(555000.0, $result->negosiasiHarga->total, 0.001);
        $this->assertTrue($result->tgl->isSameDay($pembayaran->tgl_pembayaran_cms));
        $this->assertTrue($result->tglInvoice->isSameDay($pembayaran->tgl_invoice));
    }

    public function test_build_pembayaran_report_requires_payment_record(): void
    {
        [$kegiatan] = $this->seedPembayaranReportFixture(withPembayaran: false);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Pembayaran belum tersedia.');

        (new BuildPembayaranReportUseCase())->execute($kegiatan->id);
    }

    public function test_view_data_structure_shapes_invoice(): void
    {
        [$kegiatan] = $this->seedPembayaranReportFixture();
        $result = (new BuildPembayaranReportUseCase())->execute($kegiatan->id);
        $viewData = $result->toViewData();

        $this->assertArrayHasKey('pembayaran', $viewData);
        $this->assertArrayHasKey('negosiasiHarga', $viewData);
        $this->assertArrayHasKey('item', $viewData);
        $this->assertArrayHasKey('tgl', $viewData);
        $this->assertArrayHasKey('tgl_invoice', $viewData);
        $this->assertCount(2, $viewData['item']);
        $this->assertTrue($viewData['tgl']->isSameDay($result->tgl));
        $this->assertTrue($viewData['tgl_invoice']->isSameDay($result->tglInvoice));
    }

    private function seedPembayaranReportFixture(bool $withPembayaran = true): array
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

        $penyedia = Penyedia::create([
            'created_by' => $user->id,
            'kode_desa' => 'D01',
            'nama_penyedia' => 'Penyedia A',
            'nomor_npwp' => '01.234.567.8-999.200',
            'kabupaten' => 'Batang',
        ]);

        $pemberitahuan = Pemberitahuan::create([
            'kegiatan_id' => $kegiatan->id,
            'kode_desa' => 'D01',
            'rekening_apbdes' => $kegiatan->rekening_apbdes,
            'penyedia' => [$penyedia->id],
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
            'penyedia_id' => $penyedia->id,
            'tgl_penawaran' => now(),
            'no_penawaran' => '001',
            'is_winner' => true,
        ]);
        $winner->hargaPenawaran()->createMany([
            ['harga_satuan' => 111000],
            ['harga_satuan' => 222000],
        ]);

        $negosiasi = NegosiasiHarga::create([
            'kegiatan_id' => $kegiatan->id,
            'tgl_persetujuan' => now()->addDays(5),
            'tgl_negosiasi' => now()->addDays(5),
            'tgl_akhir_perjanjian' => now()->addDays(6),
        ]);
        $negosiasi->hargaNegosiasi()->createMany([
            ['harga_satuan' => 111000],
            ['harga_satuan' => 222000],
        ]);

        $pembayaran = null;
        if ($withPembayaran) {
            $pembayaran = Pembayaran::create([
                'kegiatan_id' => $kegiatan->id,
                'tgl_invoice' => now()->addDays(7),
                'tgl_pembayaran_cms' => now()->addDays(8),
            ]);
        }

        return [$kegiatan, $pembayaran, $penyedia];
    }
}
