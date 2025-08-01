<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Penawaran extends Model
{
    use HasUuids;
    protected $table = 'penawaran';
    protected $casts = [
        'item' => 'array'
        
    ];
   
    protected $guarded = [];

    
    public function negosiasiHarga()
    {
        return $this->hasOne(NegosiasiHarga::class, 'negosiasi_harga_id');
    }
    
    public function pemberitahuan()
    {
        return $this->belongsTo(Pemberitahuan::class);
    }

        public function getTglPenawaranIndoAttribute()
    {
        return Carbon::parse($this->tgl_penawaran)->translatedFormat('j F Y');
    }

    public function getPpnAttribute()
    {
        return $this->nilai_penawaran * config('pajak.ppn');
    }

    public function getPph22Attribute()
    {
        return $this->nilai_penawaran * config('pajak.pph_22');
    }

    public function getHargaTotalAttribute()
    {
        $data = collect(json_decode($this->item, true));

        $total = collect($data['volume'])->map(function ($vol, $i) use ($data) {
            return ((float) $vol) * ((float) $data['harga_satuan'][$i]);
        })->sum();

        return $total;

    }

    
    
}
