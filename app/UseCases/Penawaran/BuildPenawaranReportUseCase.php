<?php

namespace App\UseCases\Penawaran;

use App\Helpers\PajakHelper;
use App\Models\Kegiatan;
use App\Models\Penawaran;
use DomainException;
use Illuminate\Support\Collection;

final class BuildPenawaranReportUseCase
{
    public function execute(string $kegiatanId): BuildPenawaranReportResult
    {
        $kegiatan = Kegiatan::with([
            'pemberitahuan.belanjas',
            'pemberitahuan.kegiatan',
            'penawaran.hargaPenawaran',
            'penawaran.penyedia',
        ])->findOrFail($kegiatanId);

        if ($kegiatan->pemberitahuan === null) {
            throw new DomainException('Pemberitahuan belum tersedia.');
        }

        $belanja = $kegiatan->pemberitahuan->belanjas
            ->sortBy('id')
            ->values();

        $penawaran = $kegiatan->penawaran
            ->sortBy('id')
            ->values();

        $winner = $penawaran->firstWhere('is_winner', true);
        if ($winner === null) {
            throw new DomainException('Belum ada pemenang yang ditetapkan.');
        }

        $comparison = $penawaran->first(
            fn (Penawaran $candidate) => !$candidate->is_winner
        );
        if ($comparison === null) {
            throw new DomainException('Penawaran pembanding belum tersedia.');
        }

        if ($winner->penyedia === null || $comparison->penyedia === null) {
            throw new DomainException('Data penyedia penawaran tidak lengkap.');
        }

        $winnerPrices = $winner->hargaPenawaran
            ->sortBy('id')
            ->values();
        $comparisonPrices = $comparison->hargaPenawaran
            ->sortBy('id')
            ->values();

        if ($belanja->count() !== $winnerPrices->count() || $belanja->count() !== $comparisonPrices->count()) {
            throw new DomainException('Jumlah item penawaran tidak sesuai dengan data belanja.');
        }

        $winnerItems = $this->buildItems($belanja, $winnerPrices, $kegiatan->ppn, $kegiatan->pph_22);
        $comparisonItems = $this->buildItems($belanja, $comparisonPrices, $kegiatan->ppn, $kegiatan->pph_22);

        return new BuildPenawaranReportResult(
            kegiatan: $kegiatan,
            pemberitahuan: $kegiatan->pemberitahuan,
            winnerPenawaran: $winner,
            comparisonPenawaran: $comparison,
            winnerPenyedia: $winner->penyedia,
            comparisonPenyedia: $comparison->penyedia,
            winnerItems: $winnerItems,
            comparisonItems: $comparisonItems,
            winnerPajak: $this->calculateTotals($belanja, $winnerPrices, $kegiatan->ppn, $kegiatan->pph_22),
            comparisonPajak: $this->calculateTotals($belanja, $comparisonPrices, $kegiatan->ppn, $kegiatan->pph_22),
        );
    }

    private function buildItems(
        Collection $belanja,
        Collection $hargaPenawaran,
        float $ppn,
        float $pph22,
    ): Collection {
        return $hargaPenawaran->map(function ($harga, int $index) use ($belanja, $ppn, $pph22) {
            $belanjaItem = $belanja->get($index);
            $volume = (float) ($belanjaItem?->volume ?? 0);
            $hargaSatuan = (float) $harga->harga_satuan;

            $preciseUnit = PajakHelper::hitungSiskeudesPresisi($hargaSatuan, $ppn, $pph22);
            $preciseJumlah = bcmul($preciseUnit['bersih'], (string) $volume, 4);

            return [
                'uraian' => $belanjaItem?->uraian,
                'volume' => $volume,
                'satuan' => $belanjaItem?->satuan,
                'harga_satuan' => (int) round($preciseUnit['bersih'], 0, PHP_ROUND_HALF_UP),
                'jumlah' => (int) round($preciseJumlah, 0, PHP_ROUND_HALF_UP),
            ];
        })->values();
    }

    private function calculateTotals(
        Collection $belanja,
        Collection $hargaPenawaran,
        float $ppn,
        float $pph22,
    ): array {
        $totalBersih = '0.0000';
        $totalPpn = '0.0000';
        $totalPph22 = '0.0000';
        $totalBruto = '0.0000';

        foreach ($hargaPenawaran as $index => $harga) {
            $volume = (float) ($belanja->get($index)?->volume ?? 0);
            $hargaSatuan = (float) $harga->harga_satuan;

            $preciseUnit = PajakHelper::hitungSiskeudesPresisi($hargaSatuan, $ppn, $pph22);

            $itemBersih = bcmul($preciseUnit['bersih'], (string) $volume, 4);
            $itemPpn = bcmul($preciseUnit['ppn'], (string) $volume, 4);
            $itemPph22 = bcmul($preciseUnit['pph22'], (string) $volume, 4);
            $itemTotal = bcmul($preciseUnit['total'], (string) $volume, 4);

            $totalBersih = bcadd($totalBersih, (string) round($itemBersih, 0, PHP_ROUND_HALF_UP), 4);
            $totalPpn = bcadd($totalPpn, (string) round($itemPpn, 0, PHP_ROUND_HALF_UP), 4);
            $totalPph22 = bcadd($totalPph22, (string) round($itemPph22, 0, PHP_ROUND_HALF_UP), 4);
            $totalBruto = bcadd($totalBruto, (string) round($itemTotal, 0, PHP_ROUND_HALF_UP), 4);
        }

        return [
            'bersih' => (int) $totalBersih,
            'ppn' => (int) $totalPpn,
            'pph22' => (int) $totalPph22,
            'total' => (int) $totalBruto,
        ];
    }
}
