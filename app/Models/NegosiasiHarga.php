<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class NegosiasiHarga extends Model
{
    use HasUuids;
    
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    
    protected $table = 'negosiasi_harga';
    protected $guarded =[];
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
