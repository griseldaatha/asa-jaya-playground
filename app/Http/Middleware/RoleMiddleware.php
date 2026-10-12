<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Mendukung multi-tab simultan: tiap tab dapat menjalankan role berbeda
     * (Owner, Kasir, Customer) di jendela browser yang sama tanpa bentrok session.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Dukungan multi-tab: Sesuaikan user aktif dengan role halaman yang diakses
        if (in_array('kasir', $roles) && !in_array('owner', $roles)) {
            // Halaman khusus Kasir (/kasir/* atau /admin/pesanan-baru) -> Set user sebagai Kasir
            $kasirUser = User::where('role', 'kasir')->first();
            if ($kasirUser) {
                Auth::setUser($kasirUser);
            }
        } elseif (in_array('owner', $roles) && !in_array('kasir', $roles)) {
            // Halaman khusus Owner (/owner/*) -> Set user sebagai Owner
            $ownerUser = User::where('role', 'owner')->first();
            if ($ownerUser) {
                Auth::setUser($ownerUser);
            }
        } elseif (in_array('owner', $roles) && in_array('kasir', $roles)) {
            // Halaman bersama (/admin/*): sesuaikan berdasarkan referer atau parameter
            if ($request->has('role') && $request->input('role') === 'owner') {
                $ownerUser = User::where('role', 'owner')->first();
                if ($ownerUser) Auth::setUser($ownerUser);
            } elseif ($request->has('role') && $request->input('role') === 'kasir') {
                $kasirUser = User::where('role', 'kasir')->first();
                if ($kasirUser) Auth::setUser($kasirUser);
            } elseif ($request->headers->get('referer') && str_contains($request->headers->get('referer'), '/owner')) {
                $ownerUser = User::where('role', 'owner')->first();
                if ($ownerUser) Auth::setUser($ownerUser);
            } elseif ($request->headers->get('referer') && str_contains($request->headers->get('referer'), '/kasir')) {
                $kasirUser = User::where('role', 'kasir')->first();
                if ($kasirUser) Auth::setUser($kasirUser);
            }
        }

        // 2. Pastikan user sudah login
        if (! Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->role;

        // 3. Cek apakah role user ada di dalam daftar yang diizinkan
        if (! in_array($userRole, $roles) && $userRole !== 'owner') {
            if ($userRole === 'kasir') {
                return redirect()->route('kasir.dashboard')->with('warning', 'Halaman tersebut hanya untuk Owner. Anda dialihkan ke Dashboard Kasir.');
            }

            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
