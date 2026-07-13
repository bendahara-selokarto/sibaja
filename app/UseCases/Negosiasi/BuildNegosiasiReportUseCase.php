<?php

namespace App\UseCases\Negosiasi;

use App\Helpers\PajakHelper;
use App\Helpers\PajakRoundingHelper;
use App\Models\Kegiatan;
use App\Models\Penawaran;
use App\Models\NegosiasiHarga;
use DomainException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class BuildNegosiasiReportUseCase
{
    public function execute(string $kegiatanId): BuildNegosiasiReportResult
    {
        $kegiatan = Kegiatan::with([
            'penawaran.hargaPenawaran',
            'penawaran.penyedia',
            'pemberitahuan.belanjas',
            'negosiasiHarga.hargaNegosiasi',
        ])->findOrFail($kegiatanId);

        if ($kegiatan->pemberitahuan === null) {
            throw new DomainException('Pemberitahuan belum tersedia.');
        }

        if ($kegiatan->negosiasiHarga === null) {
            throw new DomainException('Negosiasi belum tersedia.');
        }

        $penawaran = $kegiatan->penawaran->firstWhere('is_winner', true);
        if ($penawaran === null || $penawaran->penyedia === null) {
            throw new DomainException('Penyedia pemenang belum tersedia.');
        }

        $belanja = $kegiatan->pemberitahuan->belanjas
            ->sortBy('id')
            ->values();
        $hargaPenawaran = $penawaran->hargaPenawaran
            ->sortBy('id')
            ->values();
        $hargaNegosiasi = $kegiatan->negosiasiHarga->hargaNegosiasi
            ->sortBy('id')
            ->values();

        if ($belanja->count() !== $hargaPenawaran->count() || $belanja->count() !== $hargaNegosiasi->count()) {
            throw new DomainException('Jumlah data belanja tidak konsisten.');
        }

        $ppn = $kegiatan->ppn;
        $pph22 = $kegiatan->pph_22;

        $items = $belanja->map(function ($belanjaItem, int $index) use ($hargaPenawaran, $hargaNegosiasi, $ppn, $pph22) {
            $volume = (float) $belanjaItem->volume;

            $precisePenawaran = PajakHelper::hitungSiskeudesPresisi(
                (float) $hargaPenawaran->get($index)->harga_satuan,
                $ppn,
                $pph22
            );

            $preciseNegosiasi = PajakHelper::hitungSiskeudesPresisi(
                (float) $hargaNegosiasi->get($index)->harga_satuan,
                $ppn,
                $pph22
            );

            $jumlahPenawaranPrecise = bcmul($precisePenawaran['bersih'], (string) $volume, 4);
            $jumlahNegosiasiPrecise = bcmul($preciseNegosiasi['bersih'], (string) $volume, 4);

            $ppnPenawaranPrecise = bcmul($precisePenawaran['ppn'], (string) $volume, 4);
            $ppnNegosiasiPrecise = bcmul($preciseNegosiasi['ppn'], (string) $volume, 4);

            $pph22PenawaranPrecise = bcmul($precisePenawaran['pph22'], (string) $volume, 4);
            $pph22NegosiasiPrecise = bcmul($preciseNegosiasi['pph22'], (string) $volume, 4);

            $totalPenawaranPrecise = bcmul($precisePenawaran['total'], (string) $volume, 4);
            $totalNegosiasiPrecise = bcmul($preciseNegosiasi['total'], (string) $volume, 4);

            return [
                'uraian' => $belanjaItem->uraian,
                'volume' => $volume,
                'satuan' => $belanjaItem->satuan,
                'harga_penawaran' => (int) round($precisePenawaran['bersih'], 0, PHP_ROUND_HALF_UP),
                'harga_negosiasi' => (int) round($preciseNegosiasi['bersih'], 0, PHP_ROUND_HALF_UP),
                'jumlah_penawaran' => (int) round($jumlahPenawaranPrecise, 0, PHP_ROUND_HALF_UP),
                'jumlah_negosiasi' => (int) round($jumlahNegosiasiPrecise, 0, PHP_ROUND_HALF_UP),
                'ppn_penawaran' => (int) round($ppnPenawaranPrecise, 0, PHP_ROUND_HALF_UP),
                'ppn_negosiasi' => (int) round($ppnNegosiasiPrecise, 0, PHP_ROUND_HALF_UP),
                'pph22_penawaran' => (int) round($pph22PenawaranPrecise, 0, PHP_ROUND_HALF_UP),
                'pph22_negosiasi' => (int) round($pph22NegosiasiPrecise, 0, PHP_ROUND_HALF_UP),
                'total_penawaran' => (int) round($totalPenawaranPrecise, 0, PHP_ROUND_HALF_UP),
                'total_negosiasi' => (int) round($totalNegosiasiPrecise, 0, PHP_ROUND_HALF_UP),
            ];
        })->values();

        $penawaran->tgl_penawaran = Carbon::parse($penawaran->tgl_penawaran);
        $penawaran->harga_sebelum_pajak = $items->sum('jumlah_penawaran');
        $penawaran->ppn = $items->sum('ppn_penawaran');
        $penawaran->pph_22 = $items->sum('pph22_penawaran');
        $penawaran->harga_total = PajakRoundingHelper::toRp($items->sum('total_penawaran'));

        $negosiasi = $kegiatan->negosiasiHarga;
        $negosiasi->harga_sebelum_pajak = $items->sum('jumlah_negosiasi');
        $negosiasi->ppn = $items->sum('ppn_negosiasi');
        $negosiasi->pph_22 = $items->sum('pph22_negosiasi');
        $negosiasi->harga_total = PajakRoundingHelper::toRp($items->sum('total_negosiasi'));
        $negosiasi->jumlah_total = $negosiasi->harga_total;

        $tglNegosiasi = Carbon::parse($negosiasi->tgl_negosiasi);
        $tglPersetujuan = Carbon::parse($negosiasi->tgl_persetujuan);
        $tglAkhir = Carbon::parse($negosiasi->tgl_akhir_perjanjian);

        $negosiasi->tgl_negosiasi = $tglNegosiasi;
        $negosiasi->tgl_persetujuan = $tglPersetujuan;
        $negosiasi->tgl_perjanjian = $tglPersetujuan;
        $negosiasi->tgl_akhir_perjanjian = $tglAkhir;
        $negosiasi->jumlah_hari_kerja = $tglAkhir->diffInDays($tglPersetujuan) * -1;

        $nomorSurat = '/' . Auth::user()->kode_desa . '/' . Auth::user()->tahun_anggaran;
        $pemberitahuan = $kegiatan->pemberitahuan;
        $pemberitahuan->no_spk = $pemberitahuan->no_pbj . '/SPK' . $nomorSurat;
        $pemberitahuan->no_ba_negosiasi = $pemberitahuan->no_pbj . '/BA-NEGO' . $nomorSurat;
        $pemberitahuan->no_perjanjian = $pemberitahuan->no_pbj . '/PERJ' . $nomorSurat;

        return new BuildNegosiasiReportResult(
            kegiatan: $kegiatan,
            pemberitahuan: $pemberitahuan,
            penawaranHarga: $penawaran,
            negosiasiHarga: $negosiasi,
            penyedia: $penawaran->penyedia,
            item: $items,
        );
    }
}
