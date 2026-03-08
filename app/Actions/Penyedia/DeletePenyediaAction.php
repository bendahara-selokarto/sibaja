<?php

namespace App\Actions\Penyedia;

use App\Contracts\PenyediaRepositoryInterface;
use App\Models\Penyedia;
use App\Services\PenyediaMediaService;

class DeletePenyediaAction
{
    public function __construct(
        private readonly PenyediaRepositoryInterface $penyediaRepository,
        private readonly PenyediaMediaService $penyediaMediaService
    ) {
    }

    public function execute(Penyedia $penyedia): bool
    {
        if ($penyedia->isReferencedInProcurement()) {
            return false;
        }

        $this->penyediaMediaService->deleteManagedMedia($penyedia);
        $penyedia->users()->detach();
        $this->penyediaRepository->delete($penyedia);

        return true;
    }
}
