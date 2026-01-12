<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Penyedia extends Model
{
    use HasUuids;

    public static function cukupUntukKegiatan(string $kodeDesa): bool
    {
        // Tidak ada penyedia sama sekali
        if (self::count() === 0) {
            return false;
        }

        // Minimal 2 penyedia per desa
        return self::where('kode_desa', $kodeDesa)->count() >= 2;
    }

    /**
     * Pesan error yang sesuai
     */
    public static function pesanError(string $kodeDesa): string
    {
        if (self::count() === 0) {
            return 'minimal 2 penyedia terinput sebelum input kegiatan';
        }

        return self::where('kode_desa', $kodeDesa)->count() < 2
            ? 'minimal 2 penyedia terinput sebelum input kegiatan'
            : '';
    }


    public function kegiatan(): BelongsToMany
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_penyedia');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'daftar_penyedia');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    
    // public function penyedia()
    // {
    //     return $this->hasMany(PenawaranHarga::class, 'penyedia_1');
    // }
    protected $guarded = [];






    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kode_desa = Auth::user()->kode_desa;
        });
    }
}
