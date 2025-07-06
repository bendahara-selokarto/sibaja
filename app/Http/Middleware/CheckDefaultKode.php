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
        // Daftar pengguna yang diijinkan
        $allowedUsers = [
            'selokarto',
            'gemuh',
            'randu',
            'pretek',
            'siguci',
            'Selokarto',
            'Gemuh',
            'Randu',
            'Pretek',
            'SELOKARTO',
            'GEMUH',
            'RANDU',
            'PRETEK'
            // Tambahkan email pengguna lain yang diijinkan di sini
        ];

        if (Auth::check() && !in_array(Auth::user()->desa, $allowedUsers)) {
            return redirect()->route('profile.edit')->with('error', 'Hubungi admin untuk penggunaan sibaja!');
        }

        return $next($request);
    }
    
}
