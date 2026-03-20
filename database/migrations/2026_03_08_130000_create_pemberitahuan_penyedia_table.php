<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pemberitahuan_penyedia')) {
            Schema::create('pemberitahuan_penyedia', function (Blueprint $table) {
                $table->uuid('pemberitahuan_id');
                $table->uuid('penyedia_id');
                $table->timestamps();

                $table->unique(['pemberitahuan_id', 'penyedia_id']);
                $table->foreign('pemberitahuan_id')->references('id')->on('pemberitahuans')->cascadeOnDelete();
                $table->foreign('penyedia_id')->references('id')->on('penyedias')->cascadeOnDelete();
            });
        }

        $now = now();

        foreach (DB::table('pemberitahuans')->select('id', 'penyedia')->get() as $pemberitahuan) {
            foreach ($this->normalizeLegacyPemberitahuanPenyedia($pemberitahuan->penyedia) as $penyediaId) {
                DB::table('pemberitahuan_penyedia')->updateOrInsert(
                    [
                        'pemberitahuan_id' => $pemberitahuan->id,
                        'penyedia_id' => $penyediaId,
                    ],
                    [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pemberitahuan_penyedia');
    }

    private function normalizeLegacyPemberitahuanPenyedia(mixed $value): array
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
};
