<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDefaultKode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * Middleware untuk memeriksa apakah pengguna telah mengisi kode desa pada profilnya.
         * Jika pengguna sudah login (authenticated) namun field 'kode_desa' masih null,
         * maka pengguna akan diarahkan ke halaman edit profil dengan pesan error
         * untuk mengisi profil desa terlebih dahulu.
         *
         * @return \Illuminate\Http\RedirectResponse|null Redirect ke halaman edit profil jika 'kode_desa' belum diisi, null jika sudah.
         */
        if (Auth::check() && Auth::user()->kode_desa == null) {
            return redirect()->route('profile.edit')->with('error', 'Silakan isi profil desa terlebih dahulu!');
        }
        if (Auth::check() && !Auth::user()->canAccessDesaPanel()) {
            return redirect()->route('profile.edit')->with('error', 'Akses panel desa belum aktif untuk akun ini. Silakan hubungi admin untuk informasi lebih lanjut.');
        }

        return $next($request);
    }
    
}
