<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KegiatanPenyedia extends Model
{
    public function penyedia(): BelongsToMany
    {
        return $this->belongsToMany(Penyedia::class, 'kegiatan_penyedia');
    }
}
