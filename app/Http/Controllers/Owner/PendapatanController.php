<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
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
     * Mengambil ringkasan & rekap harian pendapatan
     */
    private function getReportData(Carbon $start, Carbon $end): array
    {
        // Query Dasar: Transaksi Lunas dalam rentang tanggal
        $baseTransaksiQuery = Transaksi::where('status_pembayaran', 'lunas')
            ->whereRaw('COALESCE(waktu_transaksi, created_at) BETWEEN ? AND ?', [
                $start->toDateTimeString(),
                $end->toDateTimeString(),
            ]);

        // Ringkasan
        $totalPendapatan = (int) (clone $baseTransaksiQuery)->sum('total_harga');
        $countTransaksi = (int) (clone $baseTransaksiQuery)->count();
        $rataRataPerTransaksi = $countTransaksi > 0 ? (int) round($totalPendapatan / $countTransaksi) : 0;

        $totalTunai = (int) (clone $baseTransaksiQuery)->where('metode_pembayaran', 'tunai')->sum('total_harga');
        $countTunai = (int) (clone $baseTransaksiQuery)->where('metode_pembayaran', 'tunai')->count();

        $totalQris = (int) (clone $baseTransaksiQuery)->where('metode_pembayaran', 'payment_gateway')->sum('total_harga');
        $countQris = (int) (clone $baseTransaksiQuery)->where('metode_pembayaran', 'payment_gateway')->count();

        // Rekap Per Hari (Tanggal | Jumlah Transaksi | Tunai | QRIS | Total Pendapatan)
        $dailyData = Transaksi::where('status_pembayaran', 'lunas')
            ->whereRaw('COALESCE(waktu_transaksi, created_at) BETWEEN ? AND ?', [
                $start->toDateTimeString(),
                $end->toDateTimeString(),
            ])
            ->selectRaw("
                DATE(COALESCE(waktu_transaksi, created_at)) as tgl,
                COUNT(*) as jml_transaksi,
                SUM(CASE WHEN metode_pembayaran = 'tunai' THEN total_harga ELSE 0 END) as total_tunai,
                SUM(CASE WHEN metode_pembayaran = 'payment_gateway' THEN total_harga ELSE 0 END) as total_qris,
                SUM(total_harga) as total_pendapatan
            ")
            ->groupByRaw('DATE(COALESCE(waktu_transaksi, created_at))')
            ->orderByRaw('DATE(COALESCE(waktu_transaksi, created_at)) ASC')
            ->get();

        $rekapHarian = $dailyData->map(function ($row) {
            return [
                'tanggal' => Carbon::parse($row->tgl)->format('d/m/Y'),
                'raw_date' => $row->tgl,
                'jml_transaksi' => (int) $row->jml_transaksi,
                'total_tunai' => (int) $row->total_tunai,
                'total_qris' => (int) $row->total_qris,
                'total_pendapatan' => (int) $row->total_pendapatan,
            ];
        });

        return [
            'baseTransaksiQuery' => $baseTransaksiQuery,
            'totalPendapatan' => $totalPendapatan,
            'countTransaksi' => $countTransaksi,
            'rataRataPerTransaksi' => $rataRataPerTransaksi,
            'totalTunai' => $totalTunai,
            'countTunai' => $countTunai,
            'totalQris' => $totalQris,
            'countQris' => $countQris,
            'rekapHarian' => $rekapHarian,
        ];
    }

    /**
     * Halaman layar pendapatan (dengan pagination 15 baris)
     */
    public function index(Request $request)
    {
        $resolved = $this->resolveDates($request);
        if ($resolved['error']) {
            return redirect()->route('owner.pendapatan.index')->with('error', $resolved['error']);
        }

        $dari = $resolved['dari'];
        $sampai = $resolved['sampai'];
        $start = $resolved['start'];
        $end = $resolved['end'];

        $data = $this->getReportData($start, $end);

        // Pagination untuk tabel layar (15 baris per halaman)
        $rincianPendapatan = (clone $data['baseTransaksiQuery'])
            ->with(['meja', 'kasir'])
            ->orderByRaw('COALESCE(waktu_transaksi, created_at) DESC')
            ->paginate(15, ['*'], 'page_pendapatan')
            ->withQueryString();

        return view('owner.laporan-pendapatan.index', array_merge($data, [
            'dari' => $dari,
            'sampai' => $sampai,
            'rincianPendapatan' => $rincianPendapatan,
        ]));
    }

    /**
     * Halaman cetak pendapatan (A4 standalone, tanpa pagination)
     */
    public function cetak(Request $request)
    {
        $resolved = $this->resolveDates($request);
        if ($resolved['error']) {
            return redirect()->route('owner.pendapatan.index')->with('error', $resolved['error']);
        }

        $dari = $resolved['dari'];
        $sampai = $resolved['sampai'];
        $start = $resolved['start'];
        $end = $resolved['end'];

        $data = $this->getReportData($start, $end);

        // Tanpa pagination: seluruh baris ditampilkan untuk cetak
        $rincianPendapatan = (clone $data['baseTransaksiQuery'])
            ->with(['meja', 'kasir'])
            ->orderByRaw('COALESCE(waktu_transaksi, created_at) ASC')
            ->get();

        return view('owner.laporan-pendapatan.cetak', array_merge($data, [
            'dari' => $dari,
            'sampai' => $sampai,
            'rincianPendapatan' => $rincianPendapatan,
        ]));
    }
}
