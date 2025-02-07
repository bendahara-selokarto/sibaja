<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenawaranHarga extends Model
{
    public function negosiasiHarga()
    {
        return $this->hasOne(NegosiasiHarga::class, 'negosiasi_harga_id');
    }
    
    public function pemberitahuan()
    {
        return $this->belongsTo(Pemberitahuan::class);
    }

    
    protected $casts = [
        'item_penawaran_1' => 'array',
        'item_penawaran_2' => 'array',
    ];
    // public function kegiatan()
    // {
    //     return $this->belongsTo(Kegiatan::class);
    // }
    // public function penyedia()
    // {
    //     return $this->belongsTo(Penyedia::class);
    // }
    protected $guarded = [];
}
