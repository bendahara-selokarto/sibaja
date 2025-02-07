<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Penyedia extends Model
{
    public function kegiatan(): BelongsToMany
    {
        return $this->belongsToMany(Kegiatan::class, 'kegiatan_penyedia');
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
