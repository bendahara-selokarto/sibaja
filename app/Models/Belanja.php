<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Pemberitahuan;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\HargaPenawaran;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Belanja extends Model
{
    use HasUuids;
    
    protected $table = 'belanja';
    protected $guarded = [];

    public function pemberitahuan()
    {
        return $this->belongsTo(Pemberitahuan::class, 'pemberitahuan_id');
    }
    public function hargaPenawaran()
    {
        return $this->hasMany(HargaPenawaran::class, 'belanja_id');
    }
   
}
