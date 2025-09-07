<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\NegosiasiHarga;


class HargaNegosiasi extends Model
{
    use HasUuids;
    
    protected $table = 'harga_negosiasi';


    protected $guarded = [];

    public function negosiasiHarga()
    {
        return $this->belongsTo(NegosiasiHarga::class);
    }
}