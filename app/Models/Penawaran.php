<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\HargaPenawaran;
use App\Models\pemberitahuan;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penawaran extends Model
{
    use HasUuids;
    protected $table = 'penawaran';
    protected $casts = [
        'item' => 'array'
        
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

        public function hargaPenawaran(){
            return $this->hasMany(HargaPenawaran::class);
        }
        
        public function getTglPenawaranIndoAttribute()
    {
        return Carbon::parse($this->tgl_penawaran)->translatedFormat('j F Y');
    }

    public function getPpnAttribute()
    {
        return $this->nilai_penawaran * config('pajak.ppn');
    }

    public function getPph22Attribute()
    {
        return $this->nilai_penawaran * config('pajak.pph_22');
    }

   public function getHargaTotalAttribute()
        {
            $this->loadMissing('pemberitahuan');

            // Ambil data belanja dari JSON
            $dataBelanja = collect(json_decode($this->pemberitahuan->belanjas, true));

            // Hitung total langsung dari JSON
            $total = $dataBelanja->map(function ($item) {
                $volume = (float) ($item['volume'] ?? 0);
                $hargaSatuan = (float) ($item['harga_satuan'] ?? 0);
                return $volume * $hargaSatuan;
            })->sum();

            return $total;
        }



    public function getNilaiPenawaranAttribute()
    {
        return $this->relationLoaded('hargaPenawaran')
            ? $this->hargaPenawaran->sum('harga_satuan')
            : $this->hargaPenawaran()->sum('harga_satuan');
    }


    
    
}
