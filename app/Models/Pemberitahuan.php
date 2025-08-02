<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pemberitahuan extends Model
{
    use HasUuids;

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

    public function penawaranHarga()
    {
        return $this->hasOne(PenawaranHarga::class, 'kegiatan_id');
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
            if ($model->tgl_surat_pemberitahuan) {
                $parsed = Carbon::parse($model->tgl_pemberitahuan);
                $model->tgl_surat_pemberitahuan = $parsed->format('Y-m-d');
                $model->tgl_batas_akhir_penawaran = $parsed->addDays(3)->format('Y-m-d');
            }
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
}
