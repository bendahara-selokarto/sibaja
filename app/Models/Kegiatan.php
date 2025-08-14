<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kegiatan extends Model
{
    use HasUuids;
    public function penyedia(): BelongsToMany
    {
        return $this->belongsToMany(Penyedia::class, 'kegiatan_penyedia');
    }


    public function pemberitahuan()
    {
        return $this->hasOne(Pemberitahuan::class, 'kegiatan_id');
    }
    public function penawaran()
    {
        return $this->hasOne(Penawaran::class, 'kegiatan_id');
    }
    public function negosiasiHarga()
    {
        return $this->hasOne(NegosiasiHarga::class, 'kegiatan_id');
    }
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'kegiatan_id');
    }

    protected $guarded = [];

    protected $attributes = [
        'kode_desa' => null,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kode_desa = Auth::user()->kode_desa;
        });
    }

    public static function rules()
    {
        return [
            'kode_desa' => ['required', 'exists:users,kode_desa'],
        ];
    }
}
