<?php

namespace App\Contracts;

use App\Models\Penyedia;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface PenyediaRepositoryInterface
{
    public function getBankForUser(User $user): Collection;

    public function getForUser(User $user): Collection;

    public function store(array $attributes): Penyedia;

    public function update(Penyedia $penyedia, array $attributes): Penyedia;

    public function attachToUser(User $user, Penyedia $penyedia): void;

    public function detachFromUser(User $user, Penyedia $penyedia): void;

    public function delete(Penyedia $penyedia): void;
}
