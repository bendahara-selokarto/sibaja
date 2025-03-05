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
        if (Auth::check() && Auth::user()->kode_desa == null) {
            return redirect()->route('profile.edit')->with('error', 'Silakan isi profil desa terlebih dahulu!');
        }

        return $next($request);
    }
    
}
