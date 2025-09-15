<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Penyedia extends Model
{
    use HasUuids;

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
