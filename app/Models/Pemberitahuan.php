<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemberitahuan extends Model
{
    use HasFactory, HasUuids;

    /**
     * Mass assignment.
     */
    protected $guarded = [];

    /**
     * Casting array fields.
     */
    protected $casts = [
        'belanja' => 'array',
        'penyedia' => 'array',
        'tgl_surat_pemberitahuan' => 'date',
        'tgl_batas_akhir_penawaran' => 'date',
    ];

    /**
     * Default attribute.
     */
    protected $attributes = [
        'kode_desa' => null,
    ];

    /**
     * ---------------------------
     *         Relationships
     * ---------------------------
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
    public function belanjas()
    {
        return $this->hasMany(Belanja::class);
    }

    // public function penawaranHarga()
    // {
    //     return $this->hasOne(PenawaranHarga::class, 'kegiatan_id');
    // }

    public function penawaran()
    {
        return $this->hasMany(Penawaran::class, 'pemberitahuan_id');
    }

    public function penyedias(): BelongsToMany
    {
        return $this->belongsToMany(Penyedia::class, 'pemberitahuan_penyedia')
            ->withTimestamps();
    }

    public function selectedPenyediaIds(): array
    {
        if (!Schema::hasTable('pemberitahuan_penyedia')) {
            return $this->normalizeSelectedPenyediaIds($this->getAttribute('penyedia') ?? []);
        }

        if ($this->relationLoaded('penyedias') && $this->penyedias->isNotEmpty()) {
            return $this->normalizeSelectedPenyediaIds($this->penyedias->modelKeys());
        }

        $selectedFromPivot = $this->penyedias()->pluck('penyedias.id')->all();
        if (!empty($selectedFromPivot)) {
            return $this->normalizeSelectedPenyediaIds($selectedFromPivot);
        }

        return $this->normalizeSelectedPenyediaIds($this->getAttribute('penyedia') ?? []);
    }

    public function syncSelectedPenyedias(array $ids): void
    {
        $selected = $this->normalizeSelectedPenyediaIds($ids);

        $this->forceFill([
            'penyedia' => $selected,
        ])->save();

        if (Schema::hasTable('pemberitahuan_penyedia')) {
            $this->penyedias()->sync($selected);
            $this->unsetRelation('penyedias');
        }
    }

    public function selectedPenyedias(): Collection
    {
        $selectedIds = $this->selectedPenyediaIds();
        if ($selectedIds === []) {
            return collect();
        }

        if ($this->relationLoaded('penyedias') && $this->penyedias->isNotEmpty()) {
            $penyedias = $this->penyedias;
        } elseif (Schema::hasTable('pemberitahuan_penyedia')) {
            $penyedias = $this->penyedias()->get();
            if ($penyedias->isEmpty()) {
                $penyedias = Penyedia::query()
                    ->whereIn('id', $selectedIds)
                    ->get();
            }
        } else {
            $penyedias = Penyedia::query()
                ->whereIn('id', $selectedIds)
                ->get();
        }

        $penyediaById = $penyedias->keyBy(
            static fn (Penyedia $penyedia) => (string) $penyedia->getKey()
        );

        return collect($selectedIds)
            ->map(static fn (string $id) => $penyediaById->get($id))
            ->filter()
            ->values();
    }

    /**
     * ---------------------------
     *     Model Event Hooks
     * ---------------------------
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            // Auto set kode_desa dari Auth user
            if (Auth::check()) {
                $model->kode_desa = Auth::user()->kode_desa;
            }

            // Auto parse tgl_pemberitahuan dan set tgl_batas_akhir_penawaran
            // if ($model->tgl_surat_pemberitahuan) {
            //     $parsed = Carbon::parse($model->tgl_pemberitahuan);
            //     $model->tgl_surat_pemberitahuan = $parsed->format('Y-m-d');
            //     $model->tgl_batas_akhir_penawaran = $parsed->addDays(3)->format('Y-m-d');
            // }
        });
    }

    /**
     * ---------------------------
     *         Accessors
     * ---------------------------
     */

    /**
     * Tanggal surat pemberitahuan dalam format panjang bahasa Indonesia.
     */
    public function getTglSuratPemberitahuanPanjangAttribute(): string
    {
        Carbon::setLocale('id');
        return Carbon::parse($this->tgl_surat_pemberitahuan)
            ->translatedFormat('j F Y'); // contoh: 17 Juli 2025
    }

    /**
     * Nomor SPK format otomatis.
     */
    public function getNoSpkAttribute(): string
    {
        $user = Auth::user();
        return $this->no_pbj . '/SPK/' . optional($user)->kode_desa . '/' . optional($user)->tahun_anggaran;
    }

    /**
     * Nomor Perjanjian format otomatis.
     */
    public function getNoPerjanjianAttribute(): string
    {
        $user = Auth::user();
        return $this->no_pbj . '/PERJ/' . optional($user)->kode_desa . '/' . optional($user)->tahun_anggaran;
    }

    /**
     * Nomor BA Nego format otomatis.
     */
    public function getNoBaNegosiasiAttribute(): string
    {
        $user = Auth::user();
        return $this->no_pbj . '/BA-NEGO/' . optional($user)->kode_desa . '/' . optional($user)->tahun_anggaran;
    }

    private function normalizeSelectedPenyediaIds(mixed $ids): array
    {
        if (!is_array($ids)) {
            $ids = (array) $ids;
        }

        return array_values(array_unique(array_filter(array_map(
            static fn ($id) => is_string($id) || is_numeric($id) ? (string) $id : null,
            $ids
        ))));
    }
}
