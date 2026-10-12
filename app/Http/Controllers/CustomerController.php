<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\KategoriProduk;
use App\Models\Meja;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Memproses saat pengunjung melakukan scan QR meja
     */
    public function scanMeja($qr_token)
    {
        // Cari meja berdasarkan qr_token dan pastikan statusnya aktif
        $meja = Meja::where('qr_token', $qr_token)
            ->where('is_active', true)
            ->first();

        if (! $meja) {
            abort(404, 'Meja tidak ditemukan atau sedang tidak aktif.');
        }

        // Simpan ID meja ke dalam session agar sistem tahu customer duduk di mana
        session(['meja_id' => $meja->id]);

        return redirect()->route('katalog');
    }

    /**
     * Menampilkan halaman katalog produk
     */
    public function katalog(Request $request)
    {
        if (! $request->session()->has('meja_id')) {
            return response('Silakan scan QR Code di meja Anda terlebih dahulu untuk melihat menu.', 403);
        }

        $meja_id = $request->session()->get('meja_id');
        $meja = Meja::find($meja_id);
        $nomor_meja = $meja ? ($meja->nomor_meja ?? $meja->id) : $meja_id;

        $kategori_produk = KategoriProduk::with(['produk' => function ($query) {
            $query->where('is_active', true)->where('stok', '>', 0);
        }])->get();

        // Ambil data keranjang untuk ditampilkan di sidebar kanan
        $keranjang_session = session()->get('keranjang', []);
        $total = 0;
        $items = [];
        foreach ($keranjang_session as $id => $item) {
            $produk = Produk::find($id);
            if ($produk && $produk->is_active) {
                $subtotal = $produk->harga * $item['jumlah'];
                $total += $subtotal;
                $item['harga'] = $produk->harga;
                $item['subtotal'] = $subtotal;
                $items[] = $item;
            }
        }

        return view('customer.katalog', compact('kategori_produk', 'meja_id', 'nomor_meja', 'items', 'total'));
    }

    /**
     * Menambah produk ke keranjang session
     */
    public function tambahKeranjang(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:255',
        ]);

        $produk = Produk::where('id', $request->produk_id)->where('is_active', true)->where('stok', '>=', $request->jumlah)->first();
        if (! $produk) {
            return back()->with('error', 'Produk tidak tersedia atau stok tidak mencukupi.');
        }

        $keranjang = session()->get('keranjang', []);

        $id = $request->produk_id;
        if (isset($keranjang[$id])) {
            // Cek apakah total stok masih mencukupi jika ditambah
            if ($keranjang[$id]['jumlah'] + $request->jumlah > $produk->stok) {
                return back()->with('error', 'Stok tidak mencukupi untuk jumlah tersebut.');
            }
            $keranjang[$id]['jumlah'] += $request->jumlah;
            if ($request->catatan) {
                $keranjang[$id]['catatan'] .= ' | '.$request->catatan;
            }
        } else {
            $keranjang[$id] = [
                'produk_id' => $id,
                'nama_produk' => $produk->nama_produk,
                'harga' => $produk->harga,
                'jumlah' => $request->jumlah,
                'catatan' => $request->catatan,
            ];
        }

        session(['keranjang' => $keranjang]);

        return back()->with('success', $produk->nama_produk.' ditambahkan ke keranjang!');
    }

    /**
     * Menampilkan halaman keranjang belanja
     */
    public function lihatKeranjang(Request $request)
    {
        if (! $request->session()->has('meja_id')) {
            return redirect('/');
        }
        $keranjang = session()->get('keranjang', []);

        // Hitung ulang dari database untuk tampilan (opsional, tapi aman)
        $total = 0;
        $items = [];
        foreach ($keranjang as $id => $item) {
            $produk = Produk::find($id);
            if ($produk && $produk->is_active) {
                $subtotal = $produk->harga * $item['jumlah'];
                $total += $subtotal;
                $item['harga'] = $produk->harga;
                $item['subtotal'] = $subtotal;
                $items[] = $item;
            }
        }

        $is_qris_available = MidtransService::isConfigured();

        return view('customer.keranjang', compact('items', 'total', 'is_qris_available'));
    }

    /**
     * Hapus item dari keranjang
     */
    public function hapusKeranjang(Request $request, $id)
    {
        $keranjang = session()->get('keranjang', []);
        if (isset($keranjang[$id])) {
            unset($keranjang[$id]);
            session(['keranjang' => $keranjang]);
        }

        return back();
    }

    /**
     * Sinkronisasi jumlah kuantitas produk di keranjang
     */
    public function updateKeranjang(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:0',
        ]);

        $keranjang = session()->get('keranjang', []);
        $jumlah = (int) $request->jumlah;

        if ($jumlah <= 0) {
            if (isset($keranjang[$id])) {
                unset($keranjang[$id]);
                session(['keranjang' => $keranjang]);
            }
            return back()->with('success', 'Item dihapus dari keranjang.');
        }

        $produk = Produk::where('id', $id)->where('is_active', true)->first();
        if (! $produk) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        if ($jumlah > $produk->stok) {
            return back()->with('error', 'Stok hanya tersisa ' . $produk->stok);
        }

        if (isset($keranjang[$id])) {
            $keranjang[$id]['jumlah'] = $jumlah;
        } else {
            $keranjang[$id] = [
                'produk_id' => $id,
                'nama_produk' => $produk->nama_produk,
                'harga' => $produk->harga,
                'jumlah' => $jumlah,
                'catatan' => null,
            ];
        }

        session(['keranjang' => $keranjang]);
        return back();
    }

    /**
     * Proses Checkout
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'nama_pemesan' => 'required|string|max:100',
            'metode_pembayaran' => 'required|in:tunai,payment_gateway',
        ]);

        $meja_id = $request->session()->get('meja_id');
        $keranjang = session()->get('keranjang', []);

        if (empty($keranjang) || ! $meja_id) {
            return redirect()->route('katalog')->with('error', 'Keranjang kosong atau meja tidak valid.');
        }

        // Jika memilih QRIS tapi belum dikonfigurasi, tolak ramah tanpa mengosongkan keranjang
        if ($request->metode_pembayaran === 'payment_gateway' && ! MidtransService::isConfigured()) {
            return back()->with('error', 'Metode pembayaran QRIS belum dikonfigurasi. Silakan pilih opsi Tunai.');
        }

        try {
            DB::beginTransaction();

            $total_harga = 0;
            $detail_inserts = [];
            $produk_updates = [];

            // 1. Validasi Stok & Harga dari Database
            foreach ($keranjang as $id => $item) {
                $produk = Produk::lockForUpdate()->find($id); // Kunci baris agar aman dari race condition

                if (! $produk || ! $produk->is_active) {
                    throw new \Exception("Produk {$item['nama_produk']} sudah tidak aktif.");
                }

                if ($produk->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi.");
                }

                $subtotal = $produk->harga * $item['jumlah'];
                $total_harga += $subtotal;

                $detail_inserts[] = [
                    'produk_id' => $produk->id,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $produk->harga, // Simpan harga riil saat ini
                    'subtotal' => $subtotal,
                    'catatan' => $item['catatan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $produk_updates[] = [
                    'produk' => $produk,
                    'pengurangan' => $item['jumlah'],
                ];
            }

            // 2. Buat Record Transaksi Utama
            $kode_transaksi = 'TRX-'.strtoupper(uniqid());
            $access_token = Str::random(32);

            $transaksi = Transaksi::create([
                'kode_transaksi' => $kode_transaksi,
                'access_token' => $access_token,
                'kasir_id' => null,
                'meja_id' => $meja_id,
                'nama_pemesan' => $request->nama_pemesan,
                'tipe_pesanan' => 'qr code',
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_harga' => $total_harga,
                'status_pembayaran' => 'pending',
                'status_pesanan' => 'menunggu',
                'waktu_transaksi' => now(),
            ]);

            // 3. Simpan Detail & Kurangi Stok
            foreach ($detail_inserts as &$detail) {
                $detail['transaksi_id'] = $transaksi->id;
            }
            DetailTransaksi::insert($detail_inserts);

            foreach ($produk_updates as $update) {
                $update['produk']->stok -= $update['pengurangan'];
                $update['produk']->save();
            }

            // 4. Integrasi Midtrans jika metode payment_gateway
            if ($request->metode_pembayaran === 'payment_gateway') {
                $midtransService = app(MidtransService::class);
                $transaksi->load('detailTransaksi.produk');
                $qrisData = $midtransService->chargeQris($transaksi);

                $transaksi->midtrans_transaction_id = $qrisData['transaction_id'];
                $transaksi->qris_url = $qrisData['qris_url'];
                $transaksi->qris_expired_at = $qrisData['qris_expired_at'];
                $transaksi->save();
            }

            DB::commit();

            // Kosongkan keranjang
            session()->forget('keranjang');

            // Redirect ke halaman status
            return redirect()->route('status.pesanan', [
                'kode_transaksi' => $kode_transaksi,
                'token' => $access_token,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Checkout Error: ' . $e->getMessage(), [
                'metode' => $request->metode_pembayaran,
                'meja_id' => $meja_id,
                'trace' => $e->getTraceAsString(),
            ]);

            $friendlyMessage = 'Checkout gagal: ' . $e->getMessage();
            if ($request->metode_pembayaran === 'payment_gateway' && !str_contains($e->getMessage(), 'Stok') && !str_contains($e->getMessage(), 'tidak aktif') && !str_contains($e->getMessage(), 'belum dikonfigurasi')) {
                $friendlyMessage = 'Gagal memproses pembayaran QRIS. Silakan coba beberapa saat lagi atau pilih metode Tunai.';
            }

            return redirect()->route('keranjang.lihat')->with('error', $friendlyMessage);
        }
    }

    /**
     * Menampilkan Halaman Status Pesanan
     */
    public function statusPesanan(Request $request, $kode_transaksi)
    {
        $token = $request->query('token');

        $transaksi = Transaksi::with(['detailTransaksi.produk', 'meja'])
            ->where('kode_transaksi', $kode_transaksi)
            ->where('access_token', $token)
            ->first();

        if (! $transaksi) {
            abort(404, 'Pesanan tidak ditemukan atau akses ditolak.');
        }

        $is_sandbox = ! (bool) config('midtrans.is_production', false);
        $sisa_detik = 0;
        if ($transaksi->qris_expired_at) {
            $sisa_detik = (int) max(0, now()->diffInSeconds(Carbon::parse($transaksi->qris_expired_at), false));
        }

        return view('customer.status', compact('transaksi', 'is_sandbox', 'sisa_detik'));
    }

    /**
     * Endpoint API untuk Polling Cek Status Pesanan Customer
     */
    public function cekStatusPesanan(Request $request, $kode_transaksi)
    {
        $token = $request->query('token');

        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)
            ->where('access_token', $token)
            ->first();

        if (! $transaksi) {
            abort(404, 'Pesanan tidak ditemukan atau akses ditolak.');
        }

        // Jika metode payment_gateway dan status masih pending, cek ke Midtrans dengan throttle cache 3 detik
        if ($transaksi->metode_pembayaran === 'payment_gateway' && $transaksi->status_pembayaran === 'pending') {
            $cacheKey = 'midtrans_check_' . $transaksi->kode_transaksi;
            $alreadyChecked = Cache::store('file')->get($cacheKey);

            if (! $alreadyChecked && MidtransService::isConfigured()) {
                // Set lock throttle 3 detik di file cache
                Cache::store('file')->put($cacheKey, true, 3);

                $midtrans = app(MidtransService::class);
                $statusData = $midtrans->getStatus($transaksi->kode_transaksi);

                if (! empty($statusData['transaction_status'])) {
                    $midtrans->applyStatus(
                        $transaksi,
                        $statusData['transaction_status'],
                        $statusData['fraud_status'] ?? null
                    );
                    $transaksi->refresh();
                }
            }
        }

        // Hitung sisa detik masa berlaku QRIS
        $sisaDetik = 0;
        if ($transaksi->qris_expired_at) {
            $expiredAt = Carbon::parse($transaksi->qris_expired_at);
            $sisaDetik = (int) max(0, now()->diffInSeconds($expiredAt, false));
            // Jika waktu sudah lewat dan masih pending, tandai expire
            if ($sisaDetik <= 0 && $transaksi->status_pembayaran === 'pending') {
                $midtrans = app(MidtransService::class);
                $midtrans->applyStatus($transaksi, 'expire');
                $transaksi->refresh();
            }
        }

        return response()->json([
            'kode_transaksi' => $transaksi->kode_transaksi,
            'status_pembayaran' => $transaksi->status_pembayaran,
            'status_pesanan' => $transaksi->status_pesanan,
            'sisa_detik' => $sisaDetik,
            'is_lunas' => $transaksi->status_pembayaran === 'lunas',
            'is_expired' => $transaksi->status_pembayaran === 'kadaluarsa',
            'is_batal' => in_array($transaksi->status_pembayaran, ['batal', 'kadaluarsa']),
        ]);
    }
}
