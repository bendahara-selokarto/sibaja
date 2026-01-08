<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kegiatan extends Model
{
    use HasUuids;

    public function statusPemenang(): int
    {
        $penawaran = $this->penawaran()->first();

        if (!$penawaran) {
            return 0;
        }

        if ($penawaran && !$penawaran->is_winner) {
            return 2;
        }

        return 1;
    }

    public function penyedia(): BelongsToMany
    {
        return $this->belongsToMany(Penyedia::class, 'kegiatan_penyedia');
    }



    public function pemberitahuan()
    {
        return $this->hasOne(Pemberitahuan::class, 'kegiatan_id');
    }
    public function penawaran()
    {
        return $this->hasOne(Penawaran::class, 'kegiatan_id');
    }
    public function negosiasiHarga()
    {
        return $this->hasOne(NegosiasiHarga::class, 'kegiatan_id');
    }
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'kegiatan_id');
    }

    protected $guarded = [];

    protected $attributes = [
        'kode_desa' => null,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->kode_desa = Auth::user()->kode_desa;
            $model->tahun_anggaran = Auth::user()->tahun_anggaran;
        });
    }

    public static function rules()
    {
        return [
            'kode_desa' => ['required', 'exists:users,kode_desa'],
        ];
    }

    public function getNomorTpkAttribute()
    {
        $tgl = $this->tgl_sk_tpk ?? date('Y-m-d');
        $month = date('m', strtotime($tgl));
        $year = date('Y', strtotime($tgl));
        $roman = $this->getRomanMonth($month);
        return "140/" . $this->nomor_sk_tpk . "/" . $roman . "/" . $year;
    }
    public function getNomorPkaAttribute()
    {
        $tgl = $this->tgl_sk_pka ?? date('Y-m-d');
        $month = date('m', strtotime($tgl));
        $year = date('Y', strtotime($tgl));
        $roman = $this->getRomanMonth($month);
        return "140/" . $this->nomor_sk_pka . "/" . $roman . "/" . $year;
    }
    
    

    private function getRomanMonth($month)
    {
        $roman = '';
        switch ($month) {
            case 1:
                $roman = 'I';
                break;
            case 2:
                $roman = 'II';
                break;
            case 3:
                $roman = 'III';
                break;
            case 4:
                $roman = 'IV';
                break;
            case 5:
                $roman = 'V';
                break;
            case 6:
                $roman = 'VI';
                break;
            case 7:
                $roman = 'VII';
                break;
            case 8:
                $roman = 'VIII';
                break;
            case 9:
                $roman = 'IX';
                break;
            case 10:
                $roman = 'X';
                break;
            case 11:
                $roman = 'XI';
                break;
            case 12:
                $roman = 'XII';
                break;
        }
        return $roman;
    }
}
