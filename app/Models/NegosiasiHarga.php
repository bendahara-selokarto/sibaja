<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;


class NegosiasiHarga extends Model
{
    use HasUuids;
    
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
    public function hargaNegosiasi()
    {
        return $this->hasMany(HargaNegosiasi::class);
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

        public function getTglPersetujuanIndoAttribute()
    {
        return Carbon::parse($this->tgl_persetujuan)->translatedFormat('j F Y');
    }

    public function getTglNegosiasiIndoAttribute()
    {
        return Carbon::parse($this->tgl_negosiasi)->translatedFormat('j F Y');
    }

    public function getTglAkhirPerjanjianIndoAttribute()
    {
        return Carbon::parse($this->tgl_akhir_perjanjian)->translatedFormat('j F Y');
    }

    public function getJumlahHariKerjaAttribute()
    {
        return Carbon::parse($this->tgl_akhir_perjanjian)->diffInDays(Carbon::parse($this->tgl_persetujuan)) * -1;
    }

    protected function tglNegosiasi(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d')
        );
    }

    protected function tglPersetujuan(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d')
        );
    }

    protected function tglAkhirPerjanjian(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d')
        );
    }

}
