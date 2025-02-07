<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';
    protected $guarded = [];
    public function kegiatan(){
        return $this->belongsTo(Kegiatan::class);
    }
}
