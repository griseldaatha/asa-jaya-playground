<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama kasir
     */
    public function dashboard()
    {
        return view('kasir.dashboard');
    }

    /**
     * API Internal untuk AJAX Polling: Mengambil pesanan yang masih aktif
     */
    public function getPesananAktif()
    {
        // Hanya ambil pesanan yang belum Selesai dan belum Dibatalkan
        $pesanan = Transaksi::with(['detailTransaksi.produk', 'meja'])
            ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($pesanan);
    }

    /**
     * Halaman Riwayat Transaksi (Untuk Kasir & Owner)
     */
    public function riwayat()
    {
        // Tampilkan semua transaksi, diurutkan dari yang terbaru
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'kasir'])->orderBy('created_at', 'desc')->get();
        return view('admin.transaksi.index', compact('transaksi'));
    }

    /**
     * Mengubah status pesanan atau pembayaran
     */
    public function updateStatus(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $action = $request->input('action');

        // Kasir yang mengambil aksi ini akan dicatat ID-nya
        $transaksi->kasir_id = Auth::id();

        if ($action === 'lunas') {
            $transaksi->status_pembayaran = 'lunas';
        } elseif ($action === 'diproses') {
            $transaksi->status_pesanan = 'diproses';
        } elseif ($action === 'selesai') {
            $transaksi->status_pesanan = 'selesai';
        } elseif ($action === 'batal') {
            $transaksi->status_pembayaran = 'batal';
            $transaksi->status_pesanan = 'dibatalkan';
            
            // Kembalikan stok produk jika pesanan dibatalkan
            foreach ($transaksi->detailTransaksi as $detail) {
                $produk = $detail->produk;
                if ($produk) {
                    $produk->stok += $detail->jumlah;
                    $produk->save();
                }
            }
        }

        $transaksi->save();

        return response()->json(['success' => true, 'message' => 'Status berhasil diubah!']);
    }
}
