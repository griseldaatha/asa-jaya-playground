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
        $meja = Meja::orderByRaw('COALESCE(nomor_meja, id) ASC')->get();

        return view('owner.meja.index', compact('meja'));
    }

    public function store(Request $request)
    {
        // QR Token di-generate otomatis
        $token = strtoupper(Str::random(5));

        // Cari nomor meja positif terkecil yang belum terpakai
        $usedNumbers = Meja::whereNotNull('nomor_meja')->pluck('nomor_meja')->toArray();
        $usedMap = array_flip($usedNumbers);

        $nomorMeja = 1;
        while (isset($usedMap[$nomorMeja])) {
            $nomorMeja++;
        }

        Meja::create([
            'nomor_meja' => $nomorMeja,
            'qr_token' => $token,
            'is_active' => true,
        ]);

        return back()->with('success', 'Meja #' . $nomorMeja . ' baru berhasil ditambahkan dengan Token: ' . $token);
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
            return back()->with('error', 'Meja #' . ($meja->nomor_meja ?? $meja->id) . ' tidak dapat dihapus karena sudah memiliki riwayat transaksi pelanggan. Silakan ubah statusnya menjadi Nonaktif.');
        }

        $meja->delete();

        return back()->with('success', 'Meja #' . ($meja->nomor_meja ?? $meja->id) . ' berhasil dihapus.');
    }
}
