<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = User::where('role', 'kasir')->get();

        return view('owner.karyawan.index', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|unique:users,username|max:50',
            'password' => 'required|string|min:4',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
        ]);

        return back()->with('success', 'Akun Kasir berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'kasir')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,'.$id,
        ]);

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Akun Kasir berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'kasir')->findOrFail($id);

        $adaTransaksi = Transaksi::where('kasir_id', $id)->exists();
        if ($adaTransaksi) {
            return back()->with('error', 'Akun ini tidak dapat dihapus karena sudah memiliki riwayat memproses transaksi. Silakan ubah passwordnya agar tidak bisa diakses.');
        }

        $user->delete();

        return back()->with('success', 'Akun Kasir berhasil dihapus.');
    }
}
