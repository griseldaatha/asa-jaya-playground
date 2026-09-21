<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();

        // 1. Total Transaksi Hari Ini (Semua pesanan yang tidak batal)
        $totalTransaksi = Transaksi::whereDate('created_at', $hariIni)
            ->where('status_pesanan', '!=', 'dibatalkan')
            ->count();

        // 2. Pendapatan Hari Ini (Hanya yang status lunas)
        $pendapatan = Transaksi::whereDate('created_at', $hariIni)
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga');

        // 3. Pengeluaran Hari Ini
        $pengeluaran = Pengeluaran::whereDate('tanggal_pengeluaran', $hariIni)->sum('nominal');

        // 4. Pendapatan Bersih
        $pendapatanBersih = $pendapatan - $pengeluaran;

        return view('owner.dashboard', compact('totalTransaksi', 'pendapatan', 'pengeluaran', 'pendapatanBersih'));
    }
}
