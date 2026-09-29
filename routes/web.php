<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// JALUR CUSTOMER (GUEST SCAN QR)
// ==========================================
use App\Http\Controllers\CustomerController;

Route::get('/meja/{qr_token}', [CustomerController::class, 'scanMeja'])->name('scan.meja');
Route::get('/katalog', [CustomerController::class, 'katalog'])->name('katalog');

// Keranjang & Checkout
Route::post('/keranjang/tambah', [CustomerController::class, 'tambahKeranjang'])->name('keranjang.tambah');
Route::get('/keranjang', [CustomerController::class, 'lihatKeranjang'])->name('keranjang.lihat');
Route::post('/keranjang/hapus/{id}', [CustomerController::class, 'hapusKeranjang'])->name('keranjang.hapus');
Route::post('/checkout', [CustomerController::class, 'checkout'])->name('checkout');

// Status Pesanan (Pakai token di URL query)
Route::get('/pesanan/{kode_transaksi}', [CustomerController::class, 'statusPesanan'])->name('status.pesanan');

// ==========================================
// JALUR AUTENTIKASI (LOGIN & LOGOUT)
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// JALUR OWNER (Dilindungi Middleware Auth & Role:Owner)
// ==========================================
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\KaryawanController;
use App\Http\Controllers\Owner\KategoriController;
use App\Http\Controllers\Owner\MejaController;
use App\Http\Controllers\Owner\PengeluaranController;
use App\Http\Controllers\Owner\ProdukController;

Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Meja
    Route::get('/meja', [MejaController::class, 'index'])->name('meja.index');
    Route::post('/meja', [MejaController::class, 'store'])->name('meja.store');
    Route::put('/meja/{id}', [MejaController::class, 'update'])->name('meja.update');
    Route::delete('/meja/{id}', [MejaController::class, 'destroy'])->name('meja.destroy');

    // CRUD Karyawan (Kasir)
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
});

// ==========================================
// JALUR ADMIN (BISA DIAKSES OWNER & KASIR)
// ==========================================
use App\Http\Controllers\KasirController;

Route::middleware(['auth', 'role:owner,kasir'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // CRUD Produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');

    // CRUD Pengeluaran
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::post('/pengeluaran', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

    // Kelola Riwayat Transaksi
    Route::get('/transaksi', [KasirController::class, 'riwayat'])->name('transaksi.index');
});

// ==========================================
// JALUR KASIR (Dilindungi Middleware Auth & Role:Kasir)
// ==========================================

Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('kasir.dashboard');
    Route::get('/api/pesanan-aktif', [KasirController::class, 'getPesananAktif']);
    Route::post('/pesanan/{id}/update', [KasirController::class, 'updateStatus'])->name('kasir.pesanan.update');
});
