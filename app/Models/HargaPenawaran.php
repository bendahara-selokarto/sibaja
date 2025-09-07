<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Belanja;


class HargaPenawaran extends Model
{
    
    use HasUuids;

    protected $table = 'harga_penawaran';
    protected $guarded = [];

    public function belanja()
    {
        return $this->belongsTo(Belanja::class);
    }
}
