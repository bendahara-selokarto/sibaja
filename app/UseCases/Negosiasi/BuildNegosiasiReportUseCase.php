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
            $hargaPenawaranBersih = PajakHelper::hitungSiskeudes(
                (float) $hargaPenawaran->get($index)->harga_satuan,
                $ppn,
                $pph22
            );

            $hargaNegosiasiBersih = PajakHelper::hitungSiskeudes(
                (float) $hargaNegosiasi->get($index)->harga_satuan,
                $ppn,
                $pph22
            );

            $volume = (float) $belanjaItem->volume;

            return [
                'uraian' => $belanjaItem->uraian,
                'volume' => $volume,
                'satuan' => $belanjaItem->satuan,
                'harga_penawaran' => $hargaPenawaranBersih['bersih'],
                'harga_negosiasi' => $hargaNegosiasiBersih['bersih'],
                'jumlah_penawaran' => $volume * $hargaPenawaranBersih['bersih'],
                'jumlah_negosiasi' => $volume * $hargaNegosiasiBersih['bersih'],
                'ppn_penawaran' => $volume * $hargaPenawaranBersih['ppn'],
                'ppn_negosiasi' => $volume * $hargaNegosiasiBersih['ppn'],
                'pph22_penawaran' => $volume * $hargaPenawaranBersih['pph22'],
                'pph22_negosiasi' => $volume * $hargaNegosiasiBersih['pph22'],
                'total_penawaran' => $volume * ($hargaPenawaranBersih['bersih'] + $hargaPenawaranBersih['ppn'] + $hargaPenawaranBersih['pph22']),
                'total_negosiasi' => $volume * ($hargaNegosiasiBersih['bersih'] + $hargaNegosiasiBersih['ppn'] + $hargaNegosiasiBersih['pph22']),
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
