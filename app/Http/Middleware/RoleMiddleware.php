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

        // 2. Cek apakah role user ada di dalam daftar yang diizinkan
        if (! in_array(Auth::user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
