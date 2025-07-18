<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Penawaran extends Model
{
    use HasUuids;

    protected $table = 'penawaran';

    protected $casts = [
        'item' => 'array'        
    ];
   
    protected $guarded = [];

    // relation
     
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

    // accessor

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
        return floor($this->nilai_penawaran + $this->ppn + $this->pph22);
    }


    
    
}
