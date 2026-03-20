<?php

namespace App\Policies;

use App\Models\Penyedia;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PenyediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Penyedia $penyedia): Response
    {
        return $penyedia->isOwnedBy($user)
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan mengubah master penyedia ini.');
    }

    public function delete(User $user, Penyedia $penyedia): Response
    {
        return $penyedia->isOwnedBy($user)
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan menghapus master penyedia ini.');
    }

    public function attach(User $user, Penyedia $penyedia): Response
    {
        if ($penyedia->isOwnedBy($user)) {
            return Response::deny('Penyedia milik Anda sudah tersedia di daftar.');
        }

        return Response::allow();
    }

    public function detach(User $user, Penyedia $penyedia): Response
    {
        if ($penyedia->isOwnedBy($user)) {
            return Response::deny('Penyedia milik Anda tidak dapat dilepas dari daftar. Gunakan hapus bila diperlukan.');
        }

        if (!$penyedia->isAttachedTo($user)) {
            return Response::deny('Penyedia tidak terhubung ke daftar Anda.');
        }

        return Response::allow();
    }
}
