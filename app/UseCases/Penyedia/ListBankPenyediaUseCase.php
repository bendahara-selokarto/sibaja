<?php

namespace App\UseCases\Penyedia;

use App\Contracts\PenyediaRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ListBankPenyediaUseCase
{
    public function __construct(
        private readonly PenyediaRepositoryInterface $penyediaRepository
    ) {
    }

    public function execute(User $user): Collection
    {
        return $this->penyediaRepository->getBankForUser($user);
    }
}
