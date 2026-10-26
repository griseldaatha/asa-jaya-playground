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

use App\Services\DashboardStatService;

class DashboardController extends Controller
{
    public function index(DashboardStatService $statService)
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

        // 5. Data Grafik Pendapatan Mingguan (Chart.js)
        $chart7Days = $statService->getWeeklyRevenue7Days();
        $chart8Weeks = $statService->getWeeklyRevenue8Weeks();

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

        // 8. Produk Hampir Habis (Service Bersama)
        $produkHampirHabis = $statService->getProdukHampirHabis();
        $lowStockThreshold = DashboardStatService::LOW_STOCK_THRESHOLD;

        return view('owner.dashboard', compact(
            'totalTransaksi',
            'pendapatan',
            'pengeluaran',
            'pendapatanBersih',
            'chart7Days',
            'chart8Weeks',
            'totalKasir',
            'totalMeja',
            'produkTerlaris',
            'produkHampirHabis',
            'lowStockThreshold'
        ));
    }
}