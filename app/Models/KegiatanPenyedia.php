<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KegiatanPenyedia extends Model
{
    use HasUuids;
    public function penyedia(): BelongsToMany
    {
        return $this->belongsToMany(Penyedia::class, 'kegiatan_penyedia');
    }
}
