<?php

namespace App\Actions\Penyedia;

use App\Contracts\PenyediaRepositoryInterface;
use App\Models\Penyedia;
use App\Models\User;

class DetachPenyediaAction
{
    public function __construct(
        private readonly PenyediaRepositoryInterface $penyediaRepository
    ) {
    }

    public function execute(User $user, Penyedia $penyedia): void
    {
        $this->penyediaRepository->detachFromUser($user, $penyedia);
    }
}
