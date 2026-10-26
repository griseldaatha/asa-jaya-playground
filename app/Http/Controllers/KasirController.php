<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Services\DashboardStatService;

class KasirController extends Controller
{
    /**
     * Menampilkan halaman statistik dashboard kasir
     */
    public function dashboard(DashboardStatService $statService)
    {
        $totalTransaksiHariIni = $statService->getTotalTransaksiHariIni();
        $totalAntrean = $statService->getTotalAntrean();
        $pesananDiproses = $statService->getPesananDiproses();
        $pendapatanHariIni = $statService->getPendapatanHariIni();
        $transaksiTerbaru = $statService->getTransaksiTerbaru(10);
        $produkTerlaris = $statService->getProdukTerlaris30Hari(5);
        $produkHampirHabis = $statService->getProdukHampirHabis();
        $lowStockThreshold = DashboardStatService::LOW_STOCK_THRESHOLD;

        return view('kasir.dashboard', compact(
            'totalTransaksiHariIni',
            'totalAntrean',
            'pesananDiproses',
            'pendapatanHariIni',
            'transaksiTerbaru',
            'produkTerlaris',
            'produkHampirHabis',
            'lowStockThreshold'
        ));
    }

    /**
     * Menampilkan halaman antrean aktif kasir (Live Stream)
     */
    public function antrean()
    {
        return view('kasir.antrean');
    }

    /**
     * API Internal untuk AJAX Polling: Mengambil pesanan yang masih aktif
     */
    public function getPesananAktif()
    {
        // Hanya ambil pesanan yang belum Selesai dan belum Dibatalkan
        $pesanan = Transaksi::with(['detailTransaksi.produk', 'meja', 'kasir'])
            ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($pesanan);
    }

    /**
     * Halaman Riwayat Transaksi (Untuk Kasir & Owner)
     */
    public function riwayat(Request $request)
    {
        $query = Transaksi::with(['detailTransaksi.produk', 'kasir', 'meja'])
            ->orderBy('created_at', 'desc');

        // Filter pencarian kode transaksi, nama pemesan, atau nomor meja
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('nama_pemesan', 'like', "%{$search}%")
                  ->orWhereHas('meja', function ($mq) use ($search) {
                      $mq->where('nomor_meja', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status pembayaran
        if ($request->filled('pembayaran')) {
            $query->where('status_pembayaran', $request->input('pembayaran'));
        }

        // Filter status pesanan
        if ($request->filled('pesanan')) {
            $query->where('status_pesanan', $request->input('pesanan'));
        }

        // Bagi 10 transaksi per halaman
        $transaksi = $query->paginate(10)->withQueryString();

        return view('admin.transaksi.index', compact('transaksi'));
    }

    /**
     * Halaman Detail Transaksi
     */
    public function detail($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'kasir', 'meja'])->findOrFail($id);

        return view('admin.transaksi.show', compact('transaksi'));
    }

    /**
     * Halaman Cetak Struk Thermal 80mm
     */
    public function struk($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'kasir', 'meja'])->findOrFail($id);

        return view('admin.transaksi.struk', compact('transaksi'));
    }

    /**
     * Mengubah status pesanan atau pembayaran
     */
    public function updateStatus(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $action = $request->input('action') ?? $request->input('value');

        // Kasir yang mengambil aksi ini akan dicatat ID-nya
        $transaksi->kasir_id = Auth::id();

        if ($action === 'lunas') {
            if ($transaksi->metode_pembayaran === 'payment_gateway') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran non-tunai dikonfirmasi otomatis melalui payment gateway.',
                ], 422);
            }

            if ($request->has('uang_diterima')) {
                $uangDiterima = (int) $request->input('uang_diterima');
                if ($uangDiterima < $transaksi->total_harga) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uang diterima kurang dari total tagihan (Rp ' . number_format($transaksi->total_harga, 0, ',', '.') . ').'
                    ], 422);
                }
                $transaksi->uang_diterima = $uangDiterima;
                $transaksi->uang_kembalian = $uangDiterima - $transaksi->total_harga;
            }
            $transaksi->status_pembayaran = 'lunas';
        } elseif ($action === 'diproses') {
            $transaksi->status_pesanan = 'diproses';
        } elseif ($action === 'selesai') {
            $transaksi->status_pesanan = 'selesai';
        } elseif ($action === 'batal' || $action === 'dibatalkan') {
            if ($transaksi->metode_pembayaran === 'payment_gateway' && $transaksi->status_pembayaran === 'pending') {
                try {
                    app(MidtransService::class)->cancel($transaksi->kode_transaksi);
                } catch (\Exception $e) {
                    Log::warning('Gagal cancel transaksi Midtrans saat kasir membatalkan: ' . $e->getMessage());
                }
            }

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

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah!',
            'transaksi_id' => $transaksi->id,
            'total_harga' => $transaksi->total_harga,
            'uang_diterima' => $transaksi->uang_diterima,
            'uang_kembalian' => $transaksi->uang_kembalian,
            'kembalian_formatted' => $transaksi->uang_kembalian !== null ? 'Rp ' . number_format($transaksi->uang_kembalian, 0, ',', '.') : null,
            'struk_url' => route('admin.transaksi.struk', $transaksi->id),
        ]);
    }

    /**
     * Tampilan Input Pesanan Langsung Kasir (POS)
     */
    public function createPesananBaru()
    {
        $kategori_produk = KategoriProduk::with(['produk' => function ($q) {
            $q->where('is_active', true)->where('stok', '>', 0);
        }])->get();

        return view('admin.pesanan_baru.index', compact('kategori_produk'));
    }

    /**
     * Simpan Pesanan Langsung oleh Kasir
     */
    public function storePesananBaru(Request $request)
    {
        $request->validate([
            'nama_pemesan' => 'nullable|string|max:100',
            'uang_diterima' => 'required|integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.catatan' => 'nullable|string|max:255',
        ]);

        $namaPemesan = trim($request->input('nama_pemesan')) ?: 'Pelanggan';
        $uangDiterima = (int) $request->input('uang_diterima');
        $items = $request->input('items', []);

        DB::beginTransaction();
        try {
            $total_harga = 0;
            $detail_inserts = [];
            $produk_updates = [];

            foreach ($items as $item) {
                $produk = Produk::lockForUpdate()->find($item['produk_id']);

                if (! $produk || ! $produk->is_active) {
                    $nama = $produk ? $produk->nama_produk : 'Produk ID ' . $item['produk_id'];
                    throw new \Exception("Produk {$nama} sudah tidak aktif.");
                }

                if ($produk->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi (tersisa {$produk->stok}).");
                }

                $subtotal = $produk->harga * $item['jumlah'];
                $total_harga += $subtotal;

                $detail_inserts[] = [
                    'produk_id' => $produk->id,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $produk->harga,
                    'subtotal' => $subtotal,
                    'catatan' => $item['catatan'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $produk_updates[] = [
                    'produk' => $produk,
                    'pengurangan' => $item['jumlah'],
                ];
            }

            if ($uangDiterima < $total_harga) {
                throw new \Exception("Uang diterima (Rp " . number_format($uangDiterima, 0, ',', '.') . ") kurang dari total tagihan (Rp " . number_format($total_harga, 0, ',', '.') . ").");
            }

            $uangKembalian = $uangDiterima - $total_harga;
            $kode_transaksi = 'TRX-' . strtoupper(uniqid());
            $access_token = Str::random(32);

            $transaksi = Transaksi::create([
                'kode_transaksi' => $kode_transaksi,
                'access_token' => $access_token,
                'kasir_id' => Auth::id(),
                'meja_id' => null,
                'nama_pemesan' => $namaPemesan,
                'tipe_pesanan' => 'kasir',
                'metode_pembayaran' => 'tunai',
                'total_harga' => $total_harga,
                'uang_diterima' => $uangDiterima,
                'uang_kembalian' => $uangKembalian,
                'status_pembayaran' => 'lunas',
                'status_pesanan' => 'diproses',
                'waktu_transaksi' => now(),
            ]);

            foreach ($detail_inserts as &$detail) {
                $detail['transaksi_id'] = $transaksi->id;
            }
            DetailTransaksi::insert($detail_inserts);

            foreach ($produk_updates as $update) {
                $update['produk']->stok -= $update['pengurangan'];
                $update['produk']->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'transaksi_id' => $transaksi->id,
                'kode_transaksi' => $transaksi->kode_transaksi,
                'total_harga' => $total_harga,
                'total_harga_formatted' => 'Rp ' . number_format($total_harga, 0, ',', '.'),
                'uang_diterima' => $uangDiterima,
                'uang_diterima_formatted' => 'Rp ' . number_format($uangDiterima, 0, ',', '.'),
                'uang_kembalian' => $uangKembalian,
                'kembalian_formatted' => 'Rp ' . number_format($uangKembalian, 0, ',', '.'),
                'struk_url' => route('admin.transaksi.struk', $transaksi->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
