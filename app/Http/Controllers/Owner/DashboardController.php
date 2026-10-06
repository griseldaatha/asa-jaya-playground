<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Meja;
use App\Models\DetailTransaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();

        // 1. Total Transaksi Hari Ini
        $totalTransaksi = Transaksi::whereDate('created_at', $hariIni)
            ->where('status_pesanan', '!=', 'dibatalkan')
            ->count();

        // 2. Pendapatan Hari Ini
        $pendapatan = Transaksi::whereDate('created_at', $hariIni)
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga');

        // 3. Pengeluaran Hari Ini
        $pengeluaran = Pengeluaran::whereDate('tanggal_pengeluaran', $hariIni)->sum('nominal');

        // 4. Pendapatan Bersih
        $pendapatanBersih = $pendapatan - $pengeluaran;

        // 5. Transaksi Terbaru (5 teratas)
        $transaksiTerbaru = Transaksi::with(['detailTransaksi.produk'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 6. Ringkasan Kasir & Meja
        $totalKasir = User::where('role', 'kasir')->count();
        $totalMeja = Meja::where('is_active', true)->count();

        // 7. Produk Terlaris
        $produkTerlaris = DetailTransaksi::select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
            ->with('produk')
            ->groupBy('produk_id')
            ->orderBy('total_terjual', 'desc')
            ->take(4)
            ->get();

        return view('owner.dashboard', compact(
            'totalTransaksi',
            'pendapatan',
            'pengeluaran',
            'pendapatanBersih',
            'transaksiTerbaru',
            'totalKasir',
            'totalMeja',
            'produkTerlaris'
        ));
    }
}