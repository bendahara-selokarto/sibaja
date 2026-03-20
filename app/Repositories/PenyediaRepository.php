<?php

namespace App\Repositories;

use App\Contracts\PenyediaRepositoryInterface;
use App\Models\Penyedia;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class PenyediaRepository implements PenyediaRepositoryInterface
{
    public function getBankForUser(User $user): Collection
    {
        return Penyedia::query()
            ->with('createdBy')
            ->whereHas('createdBy', fn ($query) => $query->where('id', '!=', $user->id))
            ->whereDoesntHave('users', fn ($query) => $query->where('users.id', $user->id))
            ->get();
    }

    public function getForUser(User $user): Collection
    {
        return $user->penyedias()->with('createdBy')->get();
    }

    public function store(array $attributes): Penyedia
    {
        return Penyedia::create($attributes);
    }

    public function update(Penyedia $penyedia, array $attributes): Penyedia
    {
        $penyedia->update($attributes);

        return $penyedia;
    }

    public function attachToUser(User $user, Penyedia $penyedia): void
    {
        $user->penyedias()->syncWithoutDetaching([$penyedia->id]);
    }

    public function detachFromUser(User $user, Penyedia $penyedia): void
    {
        $user->penyedias()->detach($penyedia->id);
    }

    public function delete(Penyedia $penyedia): void
    {
        $penyedia->delete();
    }
}
