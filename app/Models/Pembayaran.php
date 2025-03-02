<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pembayaran extends Model
{
    use HasUuids;
    protected $table = 'pembayarans';
    protected $guarded = [];
    public function kegiatan(){
        return $this->belongsTo(Kegiatan::class);
    }
}
