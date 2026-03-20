<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Kegiatan;
use App\Models\Pembayaran;
use App\Models\Pemberitahuan;
use App\Models\Penawaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

trait InteractsWithTenantScope
{
    protected function currentTenantUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    protected function scopedKegiatanQuery(array $with = []): Builder
    {
        $user = $this->currentTenantUser();

        return Kegiatan::query()
            ->with($this->sanitizeOptionalRelations($with))
            ->where('kode_desa', $user->kode_desa)
            ->where('tahun_anggaran', $user->tahun_anggaran);
    }

    protected function findTenantKegiatan(string $id, array $with = []): ?Kegiatan
    {
        return $this->scopedKegiatanQuery($with)->find($id);
    }

    protected function scopedPemberitahuanQuery(array $with = []): Builder
    {
        $user = $this->currentTenantUser();

        return Pemberitahuan::query()
            ->with($this->sanitizeOptionalRelations($with))
            ->where('kode_desa', $user->kode_desa)
            ->whereHas('kegiatan', function (Builder $query) use ($user) {
                $query
                    ->where('kode_desa', $user->kode_desa)
                    ->where('tahun_anggaran', $user->tahun_anggaran);
            });
    }

    protected function findTenantPemberitahuan(string $id, array $with = []): ?Pemberitahuan
    {
        return $this->scopedPemberitahuanQuery($with)->find($id);
    }

    protected function scopedPenawaranQuery(array $with = []): Builder
    {
        $user = $this->currentTenantUser();

        return Penawaran::query()
            ->with($this->sanitizeOptionalRelations($with))
            ->whereHas('kegiatan', function (Builder $query) use ($user) {
                $query
                    ->where('kode_desa', $user->kode_desa)
                    ->where('tahun_anggaran', $user->tahun_anggaran);
            });
    }

    protected function scopedPembayaranQuery(array $with = []): Builder
    {
        $user = $this->currentTenantUser();

        return Pembayaran::query()
            ->with($this->sanitizeOptionalRelations($with))
            ->whereHas('kegiatan', function (Builder $query) use ($user) {
                $query
                    ->where('kode_desa', $user->kode_desa)
                    ->where('tahun_anggaran', $user->tahun_anggaran);
            });
    }

    protected function findTenantPembayaran(string $id, array $with = []): ?Pembayaran
    {
        return $this->scopedPembayaranQuery($with)->find($id);
    }

    protected function sanitizeOptionalRelations(array $with): array
    {
        if (Schema::hasTable('pemberitahuan_penyedia')) {
            return $with;
        }

        return array_values(array_filter(
            $with,
            static fn (string $relation) => !str_contains($relation, 'penyedias')
        ));
    }
}
