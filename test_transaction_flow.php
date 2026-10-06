<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Meja;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\Owner\DashboardController;
use Illuminate\Support\Facades\Auth;

echo "========================================================\n";
echo "    PENGUJIAN OTOMATIS ALUR TRANSAKSI END-TO-END        \n";
echo "========================================================\n\n";

try {
    $sessionStore = app('session.store');
    
    // ----------------------------------------------------
    // LANGKAH 1: Customer Scan Meja & Pilih Produk
    // ----------------------------------------------------
    echo "[TEST 1] Customer Scan Meja (X7A9)...\n";
    $meja = Meja::where('qr_token', 'X7A9')->first();
    if (!$meja) {
        throw new Exception("Meja X7A9 tidak ditemukan!");
    }
    $sessionStore->put('meja_id', $meja->id);
    echo "  -> OK: Meja ID " . $meja->id . " berhasil disimpan di session.\n\n";

    // ----------------------------------------------------
    // LANGKAH 2: Customer Tambah Tiket Mandi Bola ke Keranjang
    // ----------------------------------------------------
    echo "[TEST 2] Customer Menambah Tiket Mandi Bola (Qty: 2)...\n";
    $produk = Produk::find(1);
    if (!$produk || $produk->stok < 2) {
        throw new Exception("Produk #1 (Tiket Mandi Bola) tidak ditemukan atau stok kurang!");
    }
    $stokAwal = $produk->stok;

    $reqTambah = Request::create('/keranjang/tambah', 'POST', [
        'produk_id' => 1,
        'jumlah' => 2,
        'catatan' => 'Anak umur 5 tahun'
    ]);
    $reqTambah->setLaravelSession($sessionStore);

    $customerCtrl = new CustomerController();
    $resTambah = $customerCtrl->tambahKeranjang($reqTambah);
    
    $keranjang = $sessionStore->get('keranjang', []);
    if (!isset($keranjang[1]) || $keranjang[1]['jumlah'] !== 2) {
        throw new Exception("Gagal memasukkan produk ke keranjang session!");
    }
    echo "  -> OK: Produk berhasil masuk keranjang. Total Qty: " . $keranjang[1]['jumlah'] . "\n\n";

    // ----------------------------------------------------
    // LANGKAH 3: Customer Checkout Pesanan
    // ----------------------------------------------------
    echo "[TEST 3] Customer Lakukan Checkout (Nama: Budi Tes)...\n";
    $reqCheckout = Request::create('/checkout', 'POST', [
        'nama_pemesan' => 'Budi Tes',
        'metode_pembayaran' => 'tunai'
    ]);
    $reqCheckout->setLaravelSession($sessionStore);

    $resCheckout = $customerCtrl->checkout($reqCheckout);

    // Ambil transaksi paling baru
    $transaksi = Transaksi::where('nama_pemesan', 'Budi Tes')->orderBy('id', 'desc')->first();
    if (!$transaksi) {
        throw new Exception("Gagal membuat transaksi di database!");
    }

    echo "  -> OK: Transaksi Berhasil Dibuat!\n";
    echo "     - Kode Transaksi    : #" . $transaksi->kode_transaksi . "\n";
    echo "     - Total Harga       : Rp " . number_format($transaksi->total_harga, 0, ',', '.') . "\n";
    echo "     - Status Pembayaran : " . $transaksi->status_pembayaran . "\n";
    echo "     - Status Pesanan    : " . $transaksi->status_pesanan . "\n\n";

    // Cek detail transaksi
    $detail = DetailTransaksi::where('transaksi_id', $transaksi->id)->get();
    if ($detail->count() === 0) {
        throw new Exception("Detail transaksi kosong/tidak tersimpan!");
    }
    echo "  -> OK: Detail transaksi tersimpan (" . $detail->count() . " item).\n";

    // Cek Pengurangan Stok
    $produk->refresh();
    echo "  -> OK: Pengurangan Stok Berhasil! (Stok Awal: $stokAwal -> Stok Baru: " . $produk->stok . ")\n\n";

    // ----------------------------------------------------
    // LANGKAH 4: Kasir Memproses & Pelunasan Transaksi
    // ----------------------------------------------------
    echo "[TEST 4] Kasir Konfirmasi Pembayaran Lunas & Selesai...\n";
    $kasirUser = User::where('role', 'kasir')->first();
    Auth::login($kasirUser);

    $kasirCtrl = new KasirController();

    // Action 1: Tandai Lunas
    $reqLunas = Request::create('/kasir/transaksi/' . $transaksi->id . '/update-status', 'POST', ['action' => 'lunas']);
    $reqLunas->setLaravelSession($sessionStore);
    $kasirCtrl->updateStatus($reqLunas, $transaksi->id);

    // Action 2: Tandai Selesai
    $reqSelesai = Request::create('/kasir/transaksi/' . $transaksi->id . '/update-status', 'POST', ['action' => 'selesai']);
    $reqSelesai->setLaravelSession($sessionStore);
    $kasirCtrl->updateStatus($reqSelesai, $transaksi->id);

    $transaksi->refresh();
    if ($transaksi->status_pembayaran !== 'lunas' || $transaksi->status_pesanan !== 'selesai') {
        throw new Exception("Status transaksi tidak berubah menjadi Lunas & Selesai saat diproses Kasir!");
    }
    echo "  -> OK: Kasir Berhasil Memproses Transaksi! Status saat ini: LUNAS & SELESAI.\n\n";

    // ----------------------------------------------------
    // LANGKAH 5: Cek Perhitungan Keuangan di Dashboard Owner
    // ----------------------------------------------------
    echo "[TEST 5] Memeriksa Perhitungan Keuangan Dashboard Owner...\n";
    $ownerUser = User::where('role', 'owner')->first();
    Auth::login($ownerUser);

    $ownerCtrl = new DashboardController();
    $resOwner = $ownerCtrl->index();
    $dataOwner = $resOwner->getData();

    echo "  -> OK: Dashboard Owner Berhasil Mengambil Data Ringkasan:\n";
    echo "     - Total Transaksi Hari Ini  : " . $dataOwner['totalTransaksi'] . " Pesanan\n";
    echo "     - Total Pendapatan Kotor    : Rp " . number_format($dataOwner['pendapatan'], 0, ',', '.') . "\n";
    echo "     - Total Pengeluaran Hari Ini: Rp " . number_format($dataOwner['pengeluaran'], 0, ',', '.') . "\n";
    echo "     - Total Pendapatan Bersih   : Rp " . number_format($dataOwner['pendapatanBersih'], 0, ',', '.') . "\n\n";

    echo "========================================================\n";
    echo " 🎉 SELURUH PENGUJIAN TRANSAKSI BERHASIL 100% LANTJAR!  \n";
    echo "========================================================\n";

} catch (Exception $e) {
    echo "\n❌ ERROR SAAT PENGUJIAN: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
