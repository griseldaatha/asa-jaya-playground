<?php

namespace App\Services;

use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardStatService
{
    /**
     * Ambang batas stok hampir habis (disimpan di satu tempat agar mudah disesuaikan)
     */
    public const LOW_STOCK_THRESHOLD = 10;

    /**
     * Jumlah transaksi berstatus_pembayaran 'lunas' hari ini
     */
    public function getTotalTransaksiHariIni(?Carbon $date = null): int
    {
        $targetDate = ($date ?? Carbon::today())->toDateString();

        return (int) Transaksi::where('status_pembayaran', 'lunas')
            ->whereDate(DB::raw('COALESCE(waktu_transaksi, created_at)'), $targetDate)
            ->count();
    }

    /**
     * Jumlah pesanan aktif saat ini (status_pesanan 'menunggu' atau 'diproses', di luar batal/kadaluarsa/dibatalkan)
     */
    public function getTotalAntrean(): int
    {
        return (int) Transaksi::whereIn('status_pesanan', ['menunggu', 'diproses'])
            ->whereNotIn('status_pembayaran', ['batal', 'kadaluarsa'])
            ->count();
    }

    /**
     * Jumlah transaksi dengan status_pesanan 'diproses' saat ini
     */
    public function getPesananDiproses(): int
    {
        return (int) Transaksi::where('status_pesanan', 'diproses')
            ->whereNotIn('status_pembayaran', ['batal', 'kadaluarsa'])
            ->count();
    }

    /**
     * Total pendapatan transaksi lunas hari ini
     */
    public function getPendapatanHariIni(?Carbon $date = null): int
    {
        $targetDate = ($date ?? Carbon::today())->toDateString();

        return (int) Transaksi::where('status_pembayaran', 'lunas')
            ->whereDate(DB::raw('COALESCE(waktu_transaksi, created_at)'), $targetDate)
            ->sum('total_harga');
    }

    /**
     * 10 transaksi terakhir (semua status) dengan eager loading
     */
    public function getTransaksiTerbaru(int $limit = 10)
    {
        return Transaksi::with(['meja', 'kasir'])
            ->orderBy(DB::raw('COALESCE(waktu_transaksi, created_at)'), 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * 5 produk/wahana terlaris berdasarkan jumlah terjual dalam 30 hari terakhir (hanya transaksi lunas)
     */
    public function getProdukTerlaris30Hari(int $limit = 5)
    {
        $since = Carbon::now()->subDays(30)->startOfDay();

        return DetailTransaksi::join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->where('transaksi.status_pembayaran', 'lunas')
            ->where(DB::raw('COALESCE(transaksi.waktu_transaksi, transaksi.created_at)'), '>=', $since)
            ->select(
                'detail_transaksi.produk_id',
                DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_pendapatan')
            )
            ->with(['produk.kategori'])
            ->groupBy('detail_transaksi.produk_id')
            ->orderByDesc('total_terjual')
            ->take($limit)
            ->get();
    }

    /**
     * Daftar produk aktif dengan stok <= threshold, urut stok naik
     */
    public function getProdukHampirHabis(int $threshold = self::LOW_STOCK_THRESHOLD)
    {
        return Produk::with('kategori')
            ->where('is_active', 1)
            ->where('stok', '<=', $threshold)
            ->orderBy('stok', 'asc')
            ->get();
    }

    /**
     * Data grafik pendapatan 7 hari terakhir (hari per hari)
     */
    public function getWeeklyRevenue7Days(): array
    {
        $daysName = [
            0 => 'Min',
            1 => 'Sen',
            2 => 'Sel',
            3 => 'Rab',
            4 => 'Kam',
            5 => 'Jum',
            6 => 'Sab',
        ];

        $today = Carbon::today();
        $startDate = $today->copy()->subDays(6)->startOfDay();
        $endDate = $today->copy()->endOfDay();

        // Ambil data agregat per hari
        $rows = Transaksi::where('status_pembayaran', 'lunas')
            ->whereBetween(DB::raw('COALESCE(waktu_transaksi, created_at)'), [
                $startDate->toDateTimeString(),
                $endDate->toDateTimeString(),
            ])
            ->select(
                DB::raw('DATE(COALESCE(waktu_transaksi, created_at)) as tgl'),
                DB::raw('SUM(total_harga) as total')
            )
            ->groupBy('tgl')
            ->pluck('total', 'tgl')
            ->toArray();

        $labels = [];
        $data = [];
        $totalSum = 0;

        for ($i = 6; $i >= 0; $i--) {
            $current = $today->copy()->subDays($i);
            $key = $current->toDateString();
            $val = isset($rows[$key]) ? (int) $rows[$key] : 0;

            $dayOfWeek = (int) $current->dayOfWeek;
            $labels[] = $current->format('d/m') . ' (' . ($daysName[$dayOfWeek] ?? '') . ')';
            $data[] = $val;
            $totalSum += $val;
        }

        $average = (int) round($totalSum / 7);

        return [
            'labels' => $labels,
            'data' => $data,
            'total' => $totalSum,
            'average' => $average,
            'all_zero' => $totalSum === 0,
        ];
    }

    /**
     * Data grafik pendapatan 8 minggu terakhir (Senin - Minggu per minggu)
     */
    public function getWeeklyRevenue8Weeks(): array
    {
        $labels = [];
        $data = [];
        $totalSum = 0;

        // Minggu ini: mulai Senin minggu ini sampai Minggu minggu ini
        $now = Carbon::now();
        $currentWeekMonday = $now->copy()->startOfWeek(Carbon::MONDAY);

        // Kumpulkan 8 rentang minggu dari yang terlama ke terbaru
        $weeks = [];
        for ($w = 7; $w >= 0; $w--) {
            $mon = $currentWeekMonday->copy()->subWeeks($w)->startOfDay();
            $sun = $mon->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
            $weeks[] = [
                'start' => $mon,
                'end' => $sun,
                'label' => $mon->format('d/m') . ' - ' . $sun->format('d/m'),
            ];
        }

        $earliest = $weeks[0]['start']->toDateTimeString();
        $latest = $weeks[count($weeks) - 1]['end']->toDateTimeString();

        // Query transaksi lunas dalam rentang 8 minggu
        $transaksis = Transaksi::where('status_pembayaran', 'lunas')
            ->whereBetween(DB::raw('COALESCE(waktu_transaksi, created_at)'), [$earliest, $latest])
            ->select('total_harga', DB::raw('COALESCE(waktu_transaksi, created_at) as tgl'))
            ->get();

        foreach ($weeks as $wk) {
            $wStart = $wk['start']->toDateTimeString();
            $wEnd = $wk['end']->toDateTimeString();

            $weekTotal = (int) $transaksis->filter(function ($trx) use ($wStart, $wEnd) {
                return $trx->tgl >= $wStart && $trx->tgl <= $wEnd;
            })->sum('total_harga');

            $labels[] = $wk['label'];
            $data[] = $weekTotal;
            $totalSum += $weekTotal;
        }

        $average = (int) round($totalSum / 8);

        return [
            'labels' => $labels,
            'data' => $data,
            'total' => $totalSum,
            'average' => $average,
            'all_zero' => $totalSum === 0,
        ];
    }
}
