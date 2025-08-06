<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaPenawaran extends Model
{
    
    protected $table = 'harga_penawaran';
    protected $guarded = [];

    public function belanja()
    {
        return $this->belongsTo(Belanja::class);
    }
}
