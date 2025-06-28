<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Penawaran_2 extends Model
{
    use HasUuids;
    protected $table = 'penawaran_2';

    public function negosiasiHarga()
    {
        return $this->hasOne(NegosiasiHarga::class, 'negosiasi_harga_id');
    }
    
    public function pemberitahuan()
    {
        return $this->belongsTo(Pemberitahuan::class);
    }

    
    protected $casts = [
        'item' => 'array'
        
    ];
   
    protected $guarded = [];
}
