<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Kegiatan;
use App\Models\Penyedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\HargaPenawaran;
use App\Models\Pemberitahuan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penawaran extends Model
{
    use HasUuids;
    protected $table = 'penawaran';
    protected $casts = [
        'item' => 'array',
        'is_winner' => 'boolean',
        'tgl_penawaran' => 'datetime',
    ];
   
    protected $guarded = [];

    
    public function negosiasiHarga()
    {
        return $this->hasOne(NegosiasiHarga::class, 'negosiasi_harga_id');
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }
    
    public function pemberitahuan()
    {
        return $this->belongsTo(Pemberitahuan::class);
    }

    public function penyedia(): BelongsTo
    {
        return $this->belongsTo(Penyedia::class);
    }

    public function hargaPenawaran(): HasMany
    {
        return $this->hasMany(HargaPenawaran::class);
    }
    
    public function getTglPenawaranIndoAttribute()
    {
        return Carbon::parse($this->tgl_penawaran)->translatedFormat('j F Y');
    }

}
