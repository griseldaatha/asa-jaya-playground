<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksi;
use App\Models\KategoriProduk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProdukLaporanController extends Controller
{
    /**
     * Memvalidasi dan mengekstrak tanggal & filter dari request
     */
    private function resolveFilters(Request $request): array
    {
        $defaultDari = now()->startOfMonth()->format('Y-m-d');
        $defaultSampai = now()->format('Y-m-d');

        $dari = $request->query('dari', $defaultDari);
        $sampai = $request->query('sampai', $defaultSampai);
        $kategoriId = $request->query('kategori_id');
        $search = $request->query('search');

        try {
            $start = Carbon::createFromFormat('Y-m-d', $dari)->startOfDay();
            $end = Carbon::createFromFormat('Y-m-d', $sampai)->endOfDay();
        } catch (\Throwable $e) {
            return [
                'error' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.',
                'dari' => $defaultDari,
                'sampai' => $defaultSampai,
                'kategori_id' => null,
                'search' => null,
            ];
        }

        if ($dari > $sampai) {
            return [
                'error' => 'Tanggal "Sampai" tidak boleh lebih awal dari tanggal "Dari".',
                'dari' => $defaultDari,
                'sampai' => $defaultSampai,
                'kategori_id' => null,
                'search' => null,
            ];
        }

        if ($start->diffInDays($end) > 366) {
            return [
                'error' => 'Rentang filter maksimal adalah 366 hari (1 tahun).',
                'dari' => $defaultDari,
                'sampai' => $defaultSampai,
                'kategori_id' => null,
                'search' => null,
            ];
        }

        $kategoriFilter = (!empty($kategoriId) && is_numeric($kategoriId)) ? (int) $kategoriId : null;
        $searchFilter = !empty(trim((string)$search)) ? trim((string)$search) : null;

        return [
            'error' => null,
            'dari' => $dari,
            'sampai' => $sampai,
            'kategori_id' => $kategoriFilter,
            'search' => $searchFilter,
        ];
    }

    /**
     * Mengambil data rekap penjualan per produk
     */
    private function getReportData(string $dari, string $sampai, ?int $kategoriId, ?string $search): array
    {
        $query = DetailTransaksi::join('transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->join('kategori_produk', 'produk.kategori_produk_id', '=', 'kategori_produk.id')
            ->where('transaksi.status_pembayaran', 'lunas')
            ->whereRaw('DATE(COALESCE(transaksi.waktu_transaksi, transaksi.created_at)) BETWEEN ? AND ?', [$dari, $sampai]);

        if ($kategoriId) {
            $query->where('produk.kategori_produk_id', $kategoriId);
        }

        if ($search) {
            $query->where('produk.nama_produk', 'like', "%{$search}%");
        }

        $itemsRaw = (clone $query)
            ->select(
                'produk.id as produk_id',
                'produk.nama_produk',
                'produk.harga as harga_katalog',
                'produk.stok',
                'produk.foto_produk',
                'produk.is_active',
                'kategori_produk.nama_kategori',
                DB::raw('SUM(detail_transaksi.jumlah) as total_qty'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_omzet'),
                DB::raw('COUNT(DISTINCT transaksi.id) as total_transaksi')
            )
            ->groupBy(
                'produk.id',
                'produk.nama_produk',
                'produk.harga',
                'produk.stok',
                'produk.foto_produk',
                'produk.is_active',
                'kategori_produk.nama_kategori'
            )
            ->orderByDesc('total_qty')
            ->orderByDesc('total_omzet')
            ->get();

        $totalQty = (int) $itemsRaw->sum('total_qty');
        $totalOmzet = (int) $itemsRaw->sum('total_omzet');
        $totalItemJenis = $itemsRaw->count();

        // Tambahkan persentase omzet ke tiap item
        $items = $itemsRaw->map(function ($row) use ($totalOmzet) {
            $row->persentase_omzet = $totalOmzet > 0 ? round(($row->total_omzet / $totalOmzet) * 100, 1) : 0;
            return $row;
        });

        // Produk Terlaris berdasarkan Qty
        $topProdukQty = $items->sortByDesc('total_qty')->first();

        // Produk Kontributor Omzet Tertinggi
        $topProdukOmzet = $items->sortByDesc('total_omzet')->first();

        // Rekap Per Kategori Produk untuk Doughnut Chart
        $kategoriSales = (clone $query)
            ->select(
                'kategori_produk.nama_kategori',
                DB::raw('SUM(detail_transaksi.jumlah) as total_qty'),
                DB::raw('SUM(detail_transaksi.subtotal) as total_omzet')
            )
            ->groupBy('kategori_produk.nama_kategori')
            ->orderByDesc('total_omzet')
            ->get();

        $chartKategoriLabels = $kategoriSales->pluck('nama_kategori')->values()->all();
        $chartKategoriOmzet = $kategoriSales->pluck('total_omzet')->map(fn($v) => (int)$v)->values()->all();

        // Top 8 Produk untuk Bar Chart
        $top8 = $items->take(8);
        $chartTopLabels = $top8->pluck('nama_produk')->values()->all();
        $chartTopQty = $top8->pluck('total_qty')->map(fn($v) => (int)$v)->values()->all();
        $chartTopOmzet = $top8->pluck('total_omzet')->map(fn($v) => (int)$v)->values()->all();

        $semuaKategori = KategoriProduk::orderBy('nama_kategori', 'asc')->get();
        $namaKategoriAktif = null;
        if ($kategoriId) {
            $kat = $semuaKategori->firstWhere('id', $kategoriId);
            $namaKategoriAktif = $kat ? $kat->nama_kategori : 'Kategori #' . $kategoriId;
        }

        return [
            'items' => $items,
            'totalQty' => $totalQty,
            'totalOmzet' => $totalOmzet,
            'totalItemJenis' => $totalItemJenis,
            'topProdukQty' => $topProdukQty,
            'topProdukOmzet' => $topProdukOmzet,
            'kategoriSales' => $kategoriSales,
            'chartKategoriLabels' => $chartKategoriLabels,
            'chartKategoriOmzet' => $chartKategoriOmzet,
            'chartTopLabels' => $chartTopLabels,
            'chartTopQty' => $chartTopQty,
            'chartTopOmzet' => $chartTopOmzet,
            'semuaKategori' => $semuaKategori,
            'namaKategoriAktif' => $namaKategoriAktif,
        ];
    }

    /**
     * Halaman layar laporan penjualan per produk
     */
    public function index(Request $request)
    {
        $resolved = $this->resolveFilters($request);
        if ($resolved['error']) {
            return redirect()->route('owner.produk-laporan.index')->with('error', $resolved['error']);
        }

        $dari = $resolved['dari'];
        $sampai = $resolved['sampai'];
        $kategoriId = $resolved['kategori_id'];
        $search = $resolved['search'];

        $data = $this->getReportData($dari, $sampai, $kategoriId, $search);

        // Pagination manual 15 baris per halaman
        $currentPage = LengthAwarePaginator::resolveCurrentPage('page_produk');
        $perPage = 15;
        $currentItems = $data['items']->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedItems = new LengthAwarePaginator(
            $currentItems,
            $data['items']->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => 'page_produk',
                'query' => $request->query(),
            ]
        );

        return view('owner.laporan-penjualan-produk.index', array_merge($data, [
            'dari' => $dari,
            'sampai' => $sampai,
            'kategori_id' => $kategoriId,
            'search' => $search,
            'paginatedItems' => $paginatedItems,
        ]));
    }

    /**
     * Halaman cetak laporan penjualan per produk (A4 standalone)
     */
    public function cetak(Request $request)
    {
        $resolved = $this->resolveFilters($request);
        if ($resolved['error']) {
            return redirect()->route('owner.produk-laporan.index')->with('error', $resolved['error']);
        }

        $dari = $resolved['dari'];
        $sampai = $resolved['sampai'];
        $kategoriId = $resolved['kategori_id'];
        $search = $resolved['search'];

        $data = $this->getReportData($dari, $sampai, $kategoriId, $search);

        return view('owner.laporan-penjualan-produk.cetak', array_merge($data, [
            'dari' => $dari,
            'sampai' => $sampai,
            'kategori_id' => $kategoriId,
            'search' => $search,
        ]));
    }
}
