<?php

namespace App\Services;

use App\Http\Requests\PenyediaRequest;
use App\Models\Penyedia;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PenyediaMediaService
{
    public function buildMediaPayload(PenyediaRequest $request, ?Penyedia $penyedia = null): array
    {
        return [
            'kop_surat' => $this->storeManagedMedia(
                $request,
                $request->user(),
                'kop_surat',
                'kop_surat',
                $penyedia?->kop_surat ?? ''
            ),
        ];
    }

    public function deleteManagedMedia(Penyedia $penyedia): void
    {
        foreach ([$penyedia->kop_surat] as $path) {
            if ($this->shouldDeleteManagedMedia($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function storeManagedMedia(
        PenyediaRequest $request,
        User $user,
        string $field,
        string $directory,
        string $currentPath = '',
        string $emptyPath = ''
    ): string {
        if ($request->hasFile($field)) {
            $extension = $request->file($field)->getClientOriginalExtension();
            $filename = sprintf(
                '%s-%s.%s',
                $user->kode_desa,
                (string) Str::uuid(),
                $extension
            );

            $newPath = $request->file($field)->storePubliclyAs(
                $directory,
                $filename,
                'public'
            );

            if ($currentPath !== '' && $currentPath !== $newPath && $this->shouldDeleteManagedMedia($currentPath)) {
                Storage::disk('public')->delete($currentPath);
            }

            return $newPath;
        }

        if ($request->boolean('clear_' . $field)) {
            if ($this->shouldDeleteManagedMedia($currentPath)) {
                Storage::disk('public')->delete($currentPath);
            }

            return $emptyPath;
        }

        return $currentPath;
    }

    private function shouldDeleteManagedMedia(?string $path): bool
    {
        return is_string($path)
            && $path !== ''
            && $path !== 'kop_surat/default.png'
            && $path !== '';
    }
}
