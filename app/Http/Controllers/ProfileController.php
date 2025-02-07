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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }


        // Simpan atribut tambahan
        $user->desa = $request->input('desa');
        $user->kecamatan = $request->input('kecamatan');
        $user->kepala_desa = $request->input('kepala_desa');
        $user->sekretaris_desa = $request->input('sekretaris_desa');
        $user->bendahara_desa = $request->input('bendahara_desa');
        $user->alamat_kantor = $request->input('alamat_kantor');
        $user->website = $request->input('website');
        $user->kode_desa = $request->input('kode_desa');
        $user->tahun_anggaran = $request->input('tahun_anggaran');

        $request->user()->save();
        // Display a success toast with no title
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
