<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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

    public function getPpnAttribute()
    {
        return $this->harga_negosiasi * config('pajak.ppn');
    }

    public function getPph22Attribute()
    {
        return $this->harga_negosiasi * config('pajak.pph_22');
    }

    public function getHargaTotalAttribute()
    {
        return round($this->harga_negosiasi + $this->ppn + $this->pph22, -2);
    }

    public function getJumlahHariKerjaAttribute()
    {
        return Carbon::parse($this->tgl_akhir_perjanjian)->diffInDays(Carbon::parse($this->tgl_persetujuan)) * -1;
    }
}
