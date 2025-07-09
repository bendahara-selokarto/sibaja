<?php

namespace App\Models;
use Carbon\Carbon;


use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pemberitahuan extends Model
{
    use HasUuids;

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
    
    public function penawaranHarga()
    {
        return $this->hasOne(PenawaranHarga::class , 'kegiatan_id');
    }
    protected $casts = [
        'belanja' => 'array',
        'penyedia' => 'array',
    ];
    // All attributes are mass assignable
    protected $guarded = [];

    protected $attributes = [
        'kode_desa' => null,
    ];

    public static function boot()
    {
        parent::boot();

            static::creating(function ($model) {
                if (Auth::check()) {
                    $model->kode_desa = Auth::user()->kode_desa;
                }
            });
        }
    public function getTglSuratPemberitahuanPanjangAttribute(): string
    {
        Carbon::setLocale('id'); // Pastikan format bahasa Indonesia
        return Carbon::parse($this->tgl_surat_pemberitahuan)
                     ->translatedFormat('j F Y'); // ex: 9 Juli 2025
    }
        public function getNoSpkAttribute()
    {
        return $this->no_pbj . '/SPK/' . Auth::user()->kode_desa . '/' . Auth::user()->tahun_anggaran;
    }

    public function getNoPerjanjianAttribute()
    {
        return $this->no_pbj . '/PERJ/' . Auth::user()->kode_desa . '/' . Auth::user()->tahun_anggaran;
    }

    public function getNoBaNegosiasiAttribute()
    {
        return $this->no_pbj . '/BA-NEGO/' . Auth::user()->kode_desa . '/' . Auth::user()->tahun_anggaran;
    }
    }

