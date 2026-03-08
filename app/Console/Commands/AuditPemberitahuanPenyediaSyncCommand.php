<?php

namespace App\Console\Commands;

use App\Support\PemberitahuanPenyediaSyncAudit;
use Illuminate\Console\Command;

class AuditPemberitahuanPenyediaSyncCommand extends Command
{
    protected $signature = 'audit:pemberitahuan-penyedia-sync {--limit=50} {--json}';

    protected $description = 'Audit kesesuaian kolom legacy pemberitahuan.penyedia dengan pivot pemberitahuan_penyedia';

    public function handle(PemberitahuanPenyediaSyncAudit $audit): int
    {
        $limit = (int) $this->option('limit');
        $result = $audit->run($limit > 0 ? $limit : null);

        if ($this->option('json')) {
            $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return self::SUCCESS;
        }

        if (!$result['table_exists']) {
            $this->warn('Tabel pivot pemberitahuan_penyedia belum ada. Jalankan migrate sebelum audit sinkronisasi.');
            return self::SUCCESS;
        }

        $summary = $result['summary'];

        $this->info('Audit sinkronisasi pemberitahuan <-> penyedia');
        $this->table(
            ['metrik', 'nilai'],
            [
                ['total_pemberitahuan', $summary['total_pemberitahuan']],
                ['matching_records', $summary['matching_records']],
                ['mismatched_records', $summary['mismatched_records']],
                ['records_with_legacy_ids', $summary['records_with_legacy_ids']],
                ['records_with_pivot_ids', $summary['records_with_pivot_ids']],
                ['legacy_only_links', $summary['legacy_only_links']],
                ['pivot_only_links', $summary['pivot_only_links']],
            ]
        );

        if ($result['mismatches'] === []) {
            $this->info('Tidak ada mismatch antara kolom legacy dan pivot.');
            return self::SUCCESS;
        }

        $this->warn('Mismatch ditemukan. Sampel baris:');
        $this->table(
            ['pemberitahuan_id', 'legacy_only_ids', 'pivot_only_ids'],
            array_map(
                static fn (array $row) => [
                    $row['pemberitahuan_id'],
                    implode(', ', $row['legacy_only_ids']),
                    implode(', ', $row['pivot_only_ids']),
                ],
                $result['mismatches']
            )
        );

        return self::SUCCESS;
    }
}
