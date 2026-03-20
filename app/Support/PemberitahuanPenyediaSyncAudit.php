<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PemberitahuanPenyediaSyncAudit
{
    public function run(?int $limit = null): array
    {
        if (!Schema::hasTable('pemberitahuan_penyedia')) {
            return [
                'table_exists' => false,
                'summary' => [
                    'total_pemberitahuan' => 0,
                    'matching_records' => 0,
                    'mismatched_records' => 0,
                    'records_with_legacy_ids' => 0,
                    'records_with_pivot_ids' => 0,
                    'legacy_only_links' => 0,
                    'pivot_only_links' => 0,
                ],
                'mismatches' => [],
            ];
        }

        $pemberitahuans = DB::table('pemberitahuans')
            ->select('id', 'penyedia')
            ->orderBy('id')
            ->get();

        $pivotRows = DB::table('pemberitahuan_penyedia')
            ->select('pemberitahuan_id', 'penyedia_id')
            ->orderBy('pemberitahuan_id')
            ->get()
            ->groupBy('pemberitahuan_id');

        $summary = [
            'total_pemberitahuan' => $pemberitahuans->count(),
            'matching_records' => 0,
            'mismatched_records' => 0,
            'records_with_legacy_ids' => 0,
            'records_with_pivot_ids' => 0,
            'legacy_only_links' => 0,
            'pivot_only_links' => 0,
        ];

        $mismatches = [];

        foreach ($pemberitahuans as $pemberitahuan) {
            $legacyIds = $this->normalizeLegacyIds($pemberitahuan->penyedia);
            $pivotIds = collect($pivotRows->get($pemberitahuan->id, collect()))
                ->pluck('penyedia_id')
                ->map(static fn ($id) => (string) $id)
                ->unique()
                ->values()
                ->all();

            if ($legacyIds !== []) {
                $summary['records_with_legacy_ids']++;
            }

            if ($pivotIds !== []) {
                $summary['records_with_pivot_ids']++;
            }

            $legacyOnly = array_values(array_diff($legacyIds, $pivotIds));
            $pivotOnly = array_values(array_diff($pivotIds, $legacyIds));

            $summary['legacy_only_links'] += count($legacyOnly);
            $summary['pivot_only_links'] += count($pivotOnly);

            if ($legacyOnly === [] && $pivotOnly === []) {
                $summary['matching_records']++;
                continue;
            }

            $summary['mismatched_records']++;
            $mismatches[] = [
                'pemberitahuan_id' => (string) $pemberitahuan->id,
                'legacy_ids' => $legacyIds,
                'pivot_ids' => $pivotIds,
                'legacy_only_ids' => $legacyOnly,
                'pivot_only_ids' => $pivotOnly,
            ];
        }

        if ($limit !== null) {
            $mismatches = array_slice($mismatches, 0, max($limit, 0));
        }

        return [
            'table_exists' => true,
            'summary' => $summary,
            'mismatches' => $mismatches,
        ];
    }

    private function normalizeLegacyIds(mixed $value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = json_last_error() === JSON_ERROR_NONE ? $decoded : [$value];
        }

        if (!is_array($value)) {
            $value = (array) $value;
        }

        return array_values(array_unique(array_filter(array_map(
            static fn ($id) => is_string($id) || is_numeric($id) ? (string) $id : null,
            $value
        ))));
    }
}
