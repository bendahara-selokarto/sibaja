<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class HargaNegosiasi extends Model
{
    protected $table = 'harga_negosiasi';


    protected $guarded = [];

    public function negosiasiHarga()
    {
        return $this->belongsTo(NegosiasiHarga::class);
    }
}