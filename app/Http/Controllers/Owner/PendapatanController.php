<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PendapatanController extends Controller
{
    /**
     * Memvalidasi dan mengekstrak tanggal filter dari request
     */
    private function resolveDates(Request $request): array
    {
        $defaultDari = now()->startOfMonth()->format('Y-m-d');
        $defaultSampai = now()->format('Y-m-d');

        $dari = $request->query('dari', $defaultDari);
        $sampai = $request->query('sampai', $defaultSampai);

        try {
            $start = Carbon::createFromFormat('Y-m-d', $dari)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $sampai)->endOfDay();
        } catch (\Throwable $e) {
            return [
                'error' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.',
                'dari' => $defaultDari,
                'sampai' => $defaultSampai,
                'start' => now()->startOfMonth()->startOfDay(),
                'end' => now()->endOfDay(),
            ];
        }

        if ($dari > $sampai) {
            return [
                'error' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
                'dari' => $defaultDari,
                'sampai' => $defaultSampai,
                'start' => now()->startOfMonth()->startOfDay(),
                'end' => now()->endOfDay(),
            ];
        }

        if ($start->diffInDays($end) > 366) {
            return [
                'error' => 'Rentang filter maksimal adalah 366 hari (1 tahun).',
                'dari' => $defaultDari,
                'sampai' => $defaultSampai,
                'start' => now()->startOfMonth()->startOfDay(),
                'end' => now()->endOfDay(),
            ];
        }

        return [
            'error' => null,
            'dari' => $dari,
            'sampai' => $sampai,
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * Mengambil ringkasan & rekap harian pendapatan serta perbandingan pengeluaran / laba bersih
     */
    private function getReportData(Carbon $start, Carbon $end, string $dari, string $sampai): array
    {
        // Query Dasar: Transaksi Lunas dalam rentang tanggal
        $baseTransaksiQuery = Transaksi::where('status_pembayaran', 'lunas')
            ->whereRaw('COALESCE(waktu_transaksi, created_at) BETWEEN ? AND ?', [
                $start->toDateTimeString(),
                $end->toDateTimeString(),
            ]);

        // Ringkasan Pendapatan
        $totalPendapatan = (int) (clone $baseTransaksiQuery)->sum('total_harga');
        $countTransaksi = (int) (clone $baseTransaksiQuery)->count();
        $rataRataPerTransaksi = $countTransaksi > 0 ? (int) round($totalPendapatan / $countTransaksi) : 0;

        $totalTunai = (int) (clone $baseTransaksiQuery)->where('metode_pembayaran', 'tunai')->sum('total_harga');
        $countTunai = (int) (clone $baseTransaksiQuery)->where('metode_pembayaran', 'tunai')->count();

        $totalTransfer = (int) (clone $baseTransaksiQuery)
            ->where('metode_pembayaran', 'payment_gateway')
            ->where(function ($q) {
                $q->where('payment_channel', 'like', '%va%')
                  ->orWhereNull('payment_channel');
            })
            ->sum('total_harga');
        $countTransfer = (int) (clone $baseTransaksiQuery)
            ->where('metode_pembayaran', 'payment_gateway')
            ->where(function ($q) {
                $q->where('payment_channel', 'like', '%va%')
                  ->orWhereNull('payment_channel');
            })
            ->count();

        $totalEwallet = (int) (clone $baseTransaksiQuery)
            ->where('metode_pembayaran', 'payment_gateway')
            ->whereIn('payment_channel', ['gopay', 'shopeepay'])
            ->sum('total_harga');
        $countEwallet = (int) (clone $baseTransaksiQuery)
            ->where('metode_pembayaran', 'payment_gateway')
            ->whereIn('payment_channel', ['gopay', 'shopeepay'])
            ->count();

        // Ringkasan Pengeluaran & Laba Bersih di Periode yang Sama
        $totalPengeluaran = (int) Pengeluaran::whereBetween('tanggal_pengeluaran', [$dari, $sampai])->sum('nominal');
        $countPengeluaran = (int) Pengeluaran::whereBetween('tanggal_pengeluaran', [$dari, $sampai])->count();
        $labaBersih = $totalPendapatan - $totalPengeluaran;
        $marginLaba = $totalPendapatan > 0 ? round(($labaBersih / $totalPendapatan) * 100, 1) : 0;

        // Rekap Pendapatan Per Hari
        $pendapatanHarian = Transaksi::where('status_pembayaran', 'lunas')
            ->whereRaw('COALESCE(waktu_transaksi, created_at) BETWEEN ? AND ?', [
                $start->toDateTimeString(),
                $end->toDateTimeString(),
            ])
            ->selectRaw("
                DATE(COALESCE(waktu_transaksi, created_at)) as tgl,
                COUNT(*) as jml_transaksi,
                SUM(CASE WHEN metode_pembayaran = 'tunai' THEN total_harga ELSE 0 END) as total_tunai,
                SUM(CASE WHEN metode_pembayaran = 'payment_gateway' AND (payment_channel LIKE '%va%' OR payment_channel IS NULL) THEN total_harga ELSE 0 END) as total_transfer,
                SUM(CASE WHEN metode_pembayaran = 'payment_gateway' AND payment_channel IN ('gopay', 'shopeepay') THEN total_harga ELSE 0 END) as total_ewallet,
                SUM(total_harga) as total_pendapatan
            ")
            ->groupByRaw('DATE(COALESCE(waktu_transaksi, created_at))')
            ->get()
            ->keyBy('tgl');

        // Rekap Pengeluaran Per Hari
        $pengeluaranHarian = Pengeluaran::whereBetween('tanggal_pengeluaran', [$dari, $sampai])
            ->selectRaw('tanggal_pengeluaran as tgl, SUM(nominal) as total_pengeluaran, COUNT(*) as jml_pengeluaran')
            ->groupBy('tanggal_pengeluaran')
            ->get()
            ->keyBy('tgl');

        // Gabungan Tanggal Aktif (Pendapatan & Pengeluaran)
        $allDates = $pendapatanHarian->keys()
            ->merge($pengeluaranHarian->keys())
            ->unique()
            ->sort()
            ->values();

        $rekapHarian = $allDates->map(function ($dateKey) use ($pendapatanHarian, $pengeluaranHarian) {
            $pen = $pendapatanHarian->get($dateKey);
            $peng = $pengeluaranHarian->get($dateKey);

            $totPen = $pen ? (int) $pen->total_pendapatan : 0;
            $totPeng = $peng ? (int) $peng->total_pengeluaran : 0;

            return [
                'tanggal' => Carbon::parse($dateKey)->format('d/m/Y'),
                'tanggal_singkat' => Carbon::parse($dateKey)->format('d M'),
                'raw_date' => $dateKey,
                'jml_transaksi' => $pen ? (int) $pen->jml_transaksi : 0,
                'total_tunai' => $pen ? (int) $pen->total_tunai : 0,
                'total_transfer' => $pen ? (int) $pen->total_transfer : 0,
                'total_ewallet' => $pen ? (int) $pen->total_ewallet : 0,
                'total_pendapatan' => $totPen,
                'total_pengeluaran' => $totPeng,
                'laba_bersih' => $totPen - $totPeng,
            ];
        });

        // Data Siap Pakai untuk Chart.js
        $chartDates = $rekapHarian->pluck('tanggal_singkat')->values()->all();
        $chartPendapatan = $rekapHarian->pluck('total_pendapatan')->values()->all();
        $chartPengeluaran = $rekapHarian->pluck('total_pengeluaran')->values()->all();
        $chartLaba = $rekapHarian->pluck('laba_bersih')->values()->all();

        return [
            'baseTransaksiQuery' => $baseTransaksiQuery,
            'totalPendapatan' => $totalPendapatan,
            'countTransaksi' => $countTransaksi,
            'rataRataPerTransaksi' => $rataRataPerTransaksi,
            'totalTunai' => $totalTunai,
            'countTunai' => $countTunai,
            'totalTransfer' => $totalTransfer,
            'countTransfer' => $countTransfer,
            'totalEwallet' => $totalEwallet,
            'countEwallet' => $countEwallet,
            'totalPengeluaran' => $totalPengeluaran,
            'countPengeluaran' => $countPengeluaran,
            'labaBersih' => $labaBersih,
            'marginLaba' => $marginLaba,
            'rekapHarian' => $rekapHarian,
            'chartDates' => $chartDates,
            'chartPendapatan' => $chartPendapatan,
            'chartPengeluaran' => $chartPengeluaran,
            'chartLaba' => $chartLaba,
        ];
    }

    /**
     * Redirect default /owner/pendapatan ke sub bagian pertama (Pengunjung Harian)
     */
    public function index(Request $request)
    {
        return redirect()->route('owner.pendapatan.pengunjung', $request->query());
    }

    /**
     * Sub Bagian 1: Laporan Pengunjung Harian
     */
    public function pengunjung(Request $request)
    {
        $resolved = $this->resolveDates($request);
        if ($resolved['error']) {
            return redirect()->route('owner.pendapatan.pengunjung')->with('error', $resolved['error']);
        }

        $dari = $resolved['dari'];
        $sampai = $resolved['sampai'];
        $start = $resolved['start'];
        $end = $resolved['end'];

        $data = $this->getReportData($start, $end, $dari, $sampai);

        return view('owner.laporan-pendapatan.pengunjung', array_merge($data, [
            'dari' => $dari,
            'sampai' => $sampai,
        ]));
    }

    /**
     * Cetak Sub Bagian 1: Laporan Pengunjung Harian
     */
    public function cetakPengunjung(Request $request)
    {
        $resolved = $this->resolveDates($request);
        if ($resolved['error']) {
            return redirect()->route('owner.pendapatan.pengunjung')->with('error', $resolved['error']);
        }

        $dari = $resolved['dari'];
        $sampai = $resolved['sampai'];
        $start = $resolved['start'];
        $end = $resolved['end'];

        $data = $this->getReportData($start, $end, $dari, $sampai);

        return view('owner.laporan-pendapatan.cetak_pengunjung', array_merge($data, [
            'dari' => $dari,
            'sampai' => $sampai,
        ]));
    }

    /**
     * Sub Bagian 2: Laporan Transaksi Penerimaan Uang
     */
    public function transaksi(Request $request)
    {
        $resolved = $this->resolveDates($request);
        if ($resolved['error']) {
            return redirect()->route('owner.pendapatan.transaksi')->with('error', $resolved['error']);
        }

        $dari = $resolved['dari'];
        $sampai = $resolved['sampai'];
        $start = $resolved['start'];
        $end = $resolved['end'];
        $metode = $request->query('metode', 'semua');

        $data = $this->getReportData($start, $end, $dari, $sampai);

        $query = clone $data['baseTransaksiQuery'];

        if ($metode === 'tunai') {
            $query->where('metode_pembayaran', 'tunai');
        } elseif ($metode === 'transfer') {
            $query->where('metode_pembayaran', 'payment_gateway')
                ->where(function ($q) {
                    $q->where('payment_channel', 'like', '%va%')
                      ->orWhereNull('payment_channel');
                });
        } elseif ($metode === 'ewallet') {
            $query->where('metode_pembayaran', 'payment_gateway')
                ->whereIn('payment_channel', ['gopay', 'shopeepay']);
        }

        $rincianPendapatan = $query
            ->with(['meja', 'kasir'])
            ->orderByRaw('COALESCE(waktu_transaksi, created_at) DESC')
            ->paginate(15, ['*'], 'page_transaksi')
            ->withQueryString();

        return view('owner.laporan-pendapatan.transaksi', array_merge($data, [
            'dari' => $dari,
            'sampai' => $sampai,
            'metode' => $metode,
            'rincianPendapatan' => $rincianPendapatan,
        ]));
    }

    /**
     * Cetak Sub Bagian 2: Laporan Transaksi Penerimaan Uang
     */
    public function cetakTransaksi(Request $request)
    {
        $resolved = $this->resolveDates($request);
        if ($resolved['error']) {
            return redirect()->route('owner.pendapatan.transaksi')->with('error', $resolved['error']);
        }

        $dari = $resolved['dari'];
        $sampai = $resolved['sampai'];
        $start = $resolved['start'];
        $end = $resolved['end'];
        $metode = $request->query('metode', 'semua');

        $data = $this->getReportData($start, $end, $dari, $sampai);

        $query = clone $data['baseTransaksiQuery'];

        if ($metode === 'tunai') {
            $query->where('metode_pembayaran', 'tunai');
        } elseif ($metode === 'transfer') {
            $query->where('metode_pembayaran', 'payment_gateway')
                ->where(function ($q) {
                    $q->where('payment_channel', 'like', '%va%')
                      ->orWhereNull('payment_channel');
                });
        } elseif ($metode === 'ewallet') {
            $query->where('metode_pembayaran', 'payment_gateway')
                ->whereIn('payment_channel', ['gopay', 'shopeepay']);
        }

        $rincianPendapatan = $query
            ->with(['meja', 'kasir'])
            ->orderByRaw('COALESCE(waktu_transaksi, created_at) ASC')
            ->get();

        return view('owner.laporan-pendapatan.cetak_transaksi', array_merge($data, [
            'dari' => $dari,
            'sampai' => $sampai,
            'metode' => $metode,
            'rincianPendapatan' => $rincianPendapatan,
        ]));
    }

    /**
     * Backward-compatible cetak alias
     */
    public function cetak(Request $request)
    {
        return $this->cetakPengunjung($request);
    }
}
