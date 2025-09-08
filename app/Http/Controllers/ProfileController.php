<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();
        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->desa = $validatedData['desa'] ?? $user->desa;
        $user->kecamatan = $validatedData['kecamatan'] ?? $user->kecamatan;
        $user->kepala_desa = $validatedData['kepala_desa'] ?? $user->kepala_desa;
        $user->sekretaris_desa = $validatedData['sekretaris_desa'] ?? $user->sekretaris_desa;
        $user->bendahara_desa = $validatedData['bendahara_desa'] ?? $user->bendahara_desa;
        $user->alamat_kantor = $validatedData['alamat_kantor'] ?? $user->alamat_kantor;
        $user->website = $validatedData['website'] ?? $user->website;
        $user->kode_desa = $validatedData['kode_desa'] ?? $user->kode_desa;
        $user->tahun_anggaran = $validatedData['tahun_anggaran'] ?? $user->tahun_anggaran;

        $user->save();
        flash()->success('profil berhasil diubah.');  

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
