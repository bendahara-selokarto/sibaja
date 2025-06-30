<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        /**
         * Memeriksa apakah peran (role) pengguna yang terautentikasi tidak sesuai dengan peran yang dibutuhkan.
         * Jika peran tidak sesuai, mengembalikan respons JSON dengan pesan 'Unauthorized' dan status 403.
         *
         * @param string $role Peran pengguna yang dibutuhkan untuk akses.
         * @return \Illuminate\Http\JsonResponse|null Mengembalikan respons error JSON jika tidak diizinkan, jika diizinkan mengembalikan null.
         */
        if(Auth::user() && Auth::user()->role !== $role) {
            return redirect()->back()->with('error', 'tindakan ini hanya dapat lakukan oleh ' . $role);
        }
        return $next($request);
    }
}
