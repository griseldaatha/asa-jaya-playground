<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Middleware sederhana untuk membatasi akses berdasarkan role (owner/kasir).
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Pastikan user sudah login
        if (! Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role;

        // Owner memiliki super-access ke semua fitur (owner, kasir, admin)
        if ($userRole === 'owner') {
            return $next($request);
        }

        // 2. Cek apakah role user ada di dalam daftar yang diizinkan
        if (! in_array($userRole, $roles)) {
            // Jika kasir mencoba mengakses halaman owner, alihkan dengan ramah ke dashboard kasir
            if ($userRole === 'kasir') {
                return redirect()->route('kasir.dashboard')->with('warning', 'Halaman tersebut hanya untuk Owner. Anda dialihkan ke Dashboard Kasir.');
            }

            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
