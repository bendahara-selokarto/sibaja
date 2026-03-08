<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Penawaran extends Model
{
    use HasUuids;
    protected $table = 'penawaran';
    protected $casts = [
        'item' => 'array',
        'tgl_penawaran' => 'date',
        
    ];
   
    protected $guarded = [];

    
    public function negosiasiHarga()
    {
        return $this->hasOne(NegosiasiHarga::class, 'negosiasi_harga_id');
    }
    
    public function pemberitahuan()
    {
        return $this->belongsTo(Pemberitahuan::class);
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function penyedia(): BelongsTo
    {
        return $this->belongsTo(Penyedia::class);
    }

    public function hargaPenawaran()
    {
        return $this->hasMany(HargaPenawaran::class);
    }

    public function getTglPenawaranIndoAttribute()
    {
        return Carbon::parse($this->tgl_penawaran)->translatedFormat('j F Y');
    }

}
