<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pemberitahuan extends Model
{

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
    }

