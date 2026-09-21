<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        // Jika sudah login, langsung arahkan ke dashboard masing-masing
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }
        
        return view('auth.login');
    }

    // Memproses data login
    public function login(Request $request)
    {
        // 1. Validasi input sederhana
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // 2. Coba proses login bawaan Laravel (Session)
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk keamanan (mencegah session fixation)
            $request->session()->regenerate();
            
            // Arahkan sesuai jabatan
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        // 3. Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    // Fungsi bantuan untuk mengarahkan pengguna sesuai role
    private function redirectBasedOnRole($role)
    {
        if ($role === 'owner') {
            return redirect('/owner/dashboard');
        } elseif ($role === 'kasir') {
            return redirect('/kasir/dashboard');
        }
        
        return redirect('/');
    }
}
