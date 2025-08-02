<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Pemberitahuan;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Belanja extends Model
{
    use HasUuids;
    
    protected $table = 'belanja';
    protected $guarded = [];

    public function pemberitahuan()
    {
        return $this->belongsTo(pemberitahuan::class, 'pemberitahuan_id');
    }
   
}
