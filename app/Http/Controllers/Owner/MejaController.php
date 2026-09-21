<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MejaController extends Controller
{
    public function index()
    {
        $meja = Meja::all();
        return view('owner.meja.index', compact('meja'));
    }

    public function store(Request $request)
    {
        // QR Token di-generate otomatis
        $token = strtoupper(Str::random(5));
        
        Meja::create([
            'qr_token' => $token,
            'is_active' => true
        ]);

        return back()->with('success', 'Meja baru berhasil ditambahkan dengan Token: ' . $token);
    }

    public function update(Request $request, $id)
    {
        $meja = Meja::findOrFail($id);
        $meja->update(['is_active' => $request->is_active]);
        return back()->with('success', 'Status meja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $meja = Meja::findOrFail($id);
        
        // Cek apakah meja sudah pernah dipakai transaksi
        $adaTransaksi = Transaksi::where('meja_id', $id)->exists();
        if ($adaTransaksi) {
            return back()->with('error', 'Meja tidak dapat dihapus karena sudah memiliki riwayat transaksi pelanggan. Silakan ubah statusnya menjadi Nonaktif.');
        }

        $meja->delete();
        return back()->with('success', 'Meja berhasil dihapus.');
    }
}
