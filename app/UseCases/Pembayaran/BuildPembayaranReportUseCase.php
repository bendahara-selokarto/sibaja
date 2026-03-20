<?php

namespace App\UseCases\Pembayaran;

use App\Helpers\PajakHelper;
use App\Helpers\PajakRoundingHelper;
use App\Models\Kegiatan;
use DomainException;
use Illuminate\Support\Carbon;

final class BuildPembayaranReportUseCase
{
    public function execute(string $kegiatanId): BuildPembayaranReportResult
    {
        $kegiatan = Kegiatan::with([
            'pemberitahuan.belanjas',
            'penawaran.hargaPenawaran',
            'penawaran.penyedia',
            'negosiasiHarga.hargaNegosiasi',
            'pembayaran',
        ])->findOrFail($kegiatanId);

        if ($kegiatan->pemberitahuan === null) {
            throw new DomainException('Pemberitahuan belum tersedia.');
        }

        if ($kegiatan->negosiasiHarga === null) {
            throw new DomainException('Negosiasi belum tersedia.');
        }

        if ($kegiatan->pembayaran === null) {
            throw new DomainException('Pembayaran belum tersedia.');
        }

        $winnerPenawaran = $kegiatan->penawaran->firstWhere('is_winner', true);
        if ($winnerPenawaran === null || $winnerPenawaran->penyedia === null) {
            throw new DomainException('Penyedia pemenang belum tersedia.');
        }

        $belanja = $kegiatan->pemberitahuan->belanjas
            ->sortBy('id')
            ->values();
        $hargaNegosiasi = $kegiatan->negosiasiHarga->hargaNegosiasi
            ->sortBy('id')
            ->values();

        if ($belanja->count() !== $hargaNegosiasi->count()) {
            throw new DomainException('Jumlah item negosiasi tidak sesuai dengan data belanja.');
        }

        $item = $belanja->map(function ($belanjaItem, int $index) use ($hargaNegosiasi, $kegiatan) {
            $hargaNegosiasiBersih = PajakHelper::hitungSiskeudes(
                (float) $hargaNegosiasi->get($index)->harga_satuan,
                $kegiatan->ppn,
                $kegiatan->pph_22
            );

            $volume = (float) $belanjaItem->volume;

            return [
                'uraian' => $belanjaItem->uraian,
                'volume' => $volume,
                'satuan' => $belanjaItem->satuan,
                'harga_negosiasi' => $hargaNegosiasiBersih['bersih'],
                'jumlah_negosiasi' => $volume * $hargaNegosiasiBersih['bersih'],
                'ppn_negosiasi' => $volume * $hargaNegosiasiBersih['ppn'],
                'pph22_negosiasi' => $volume * $hargaNegosiasiBersih['pph22'],
                'total_negosiasi' => $volume * $hargaNegosiasiBersih['total'],
            ];
        })->values();

        $negosiasiHarga = $kegiatan->negosiasiHarga;
        $negosiasiHarga->ppn = $item->sum('ppn_negosiasi');
        $negosiasiHarga->pph_22 = $item->sum('pph22_negosiasi');
        $negosiasiHarga->jumlah = $item->sum('jumlah_negosiasi');
        $negosiasiHarga->pajak = $negosiasiHarga->ppn + $negosiasiHarga->pph_22;
        $negosiasiHarga->total = PajakRoundingHelper::toRp($item->sum('total_negosiasi'));

        $kegiatan->nomor = $kegiatan->pemberitahuan->no_pbj;

        return new BuildPembayaranReportResult(
            kegiatan: $kegiatan,
            penyedia: $winnerPenawaran->penyedia,
            pemberitahuan: $kegiatan->pemberitahuan,
            negosiasiHarga: $negosiasiHarga,
            item: $item,
            tgl: Carbon::parse($kegiatan->pembayaran->tgl_pembayaran_cms),
            tglInvoice: Carbon::parse($kegiatan->pembayaran->tgl_invoice),
            pembayaran: $kegiatan->pembayaran,
        );
    }
}
