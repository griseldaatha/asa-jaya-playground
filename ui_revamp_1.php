<?php

$dir = __DIR__;

// 1. Layout Admin
$admin_layout = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Playground Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; overflow-x: hidden; }
        #sidebar {
            min-width: 250px; max-width: 250px; min-height: 100vh; background: #212529; color: #fff; transition: all 0.3s;
        }
        #sidebar .sidebar-header { padding: 20px; background: #1a1e21; text-align: center; border-bottom: 1px solid #343a40; }
        #sidebar a { padding: 12px 20px; font-size: 1.05em; display: block; color: #adb5bd; text-decoration: none; transition: 0.2s; border-bottom: 1px solid #343a40; }
        #sidebar a:hover { color: #fff; background: #343a40; border-left: 4px solid #0d6efd; }
        #sidebar a i { margin-right: 10px; width: 20px; text-align: center; }
        #content { width: 100%; padding: 0; min-height: 100vh; }
        .topbar { background: #fff; padding: 15px 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); margin-bottom: 20px; }
        .card-header { background-color: #fff; border-bottom: 1px solid #eff2f7; padding: 15px 20px; font-weight: bold; border-radius: 10px 10px 0 0 !important; }
        .table th { background-color: #f8f9fa; font-weight: 600; }
        .table-responsive { border-radius: 8px; overflow: hidden; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4 class="mb-0 text-white"><i class="fa-solid fa-gamepad text-primary"></i> Playground</h4>
            </div>
            <div class="pt-3">
                @if(Auth::user()->role === 'owner')
                    <a href="{{ route('owner.dashboard') }}"><i class="fa-solid fa-chart-line"></i> Dashboard Owner</a>
                    <a href="{{ route('admin.karyawan.index') }}"><i class="fa-solid fa-users-gear"></i> Kasir & User</a>
                    <a href="{{ route('admin.meja.index') }}"><i class="fa-solid fa-qrcode"></i> Data Meja / QR</a>
                @elseif(Auth::user()->role === 'kasir')
                    <a href="{{ route('kasir.dashboard') }}"><i class="fa-solid fa-bell-concierge"></i> Antrean Aktif (Live)</a>
                    <a href="{{ route('admin.transaksi.index') }}"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a>
                    <a href="{{ route('admin.kategori.index') }}"><i class="fa-solid fa-tags"></i> Kategori Produk</a>
                    <a href="{{ route('admin.produk.index') }}"><i class="fa-solid fa-box-open"></i> Barang Penjualan</a>
                    <a href="{{ route('admin.pengeluaran.index') }}"><i class="fa-solid fa-money-bill-trend-up"></i> Pengeluaran</a>
                @endif
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <div class="topbar">
                <h5 class="m-0 text-secondary">
                    @if(Auth::user()->role === 'owner') Portal Owner @else Portal Kasir @endif
                </h5>
                <div class="d-flex align-items-center">
                    <span class="me-3 fw-medium"><i class="fa-regular fa-circle-user"></i> Halo, {{ Auth::user()->nama }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-right-from-bracket"></i> Keluar</button>
                    </form>
                </div>
            </div>

            <div class="container-fluid px-4 pb-5">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger shadow-sm">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
HTML;
file_put_contents($dir.'/resources/views/layouts/admin.blade.php', $admin_layout);

// 2. Auth Login
$login = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: none; }
        .login-icon { font-size: 3rem; color: #0d6efd; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="card login-card text-center bg-white">
        <div class="card-body">
            <i class="fa-solid fa-gamepad login-icon"></i>
            <h3 class="card-title fw-bold text-dark mb-4">Playground POS</h3>
            
            @if ($errors->any())
                <div class="alert alert-danger text-start py-2" role="alert">
                    <small><i class="fa-solid fa-circle-exclamation"></i> Username atau password salah.</small>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold"><i class="fa-solid fa-right-to-bracket"></i> MASUK</button>
            </form>
            <p class="text-muted mt-4 mb-0" style="font-size: 0.85rem;">Hanya untuk karyawan & manajemen</p>
        </div>
    </div>
</body>
</html>
HTML;
@mkdir($dir.'/resources/views/auth', 0777, true);
file_put_contents($dir.'/resources/views/auth/login.blade.php', $login);

// 3. Owner Dashboard
$owner_dashboard = <<<HTML
@extends('layouts.admin')
@section('content')
    <h3 class="mb-4 fw-bold">Ringkasan Keuangan ({{ \Carbon\Carbon::now()->translatedFormat('d F Y') }})</h3>
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 border-start border-primary border-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Total Transaksi</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ \$totalTransaksi }}</h3>
                        </div>
                        <i class="fa-solid fa-receipt fs-2 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 border-start border-success border-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Pendapatan Kotor</p>
                            <h3 class="fw-bold mb-0 text-success">Rp {{ number_format(\$pendapatan, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fa-solid fa-money-bill-wave fs-2 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 border-start border-danger border-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Total Pengeluaran</p>
                            <h3 class="fw-bold mb-0 text-danger">Rp {{ number_format(\$pengeluaran, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fa-solid fa-cart-shopping fs-2 text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 border-start border-warning border-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Pendapatan Bersih</p>
                            <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format(\$pendapatanBersih, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fa-solid fa-vault fs-2 text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
HTML;
file_put_contents($dir.'/resources/views/owner/dashboard.blade.php', $owner_dashboard);

// 4. Kategori
$kategori = <<<HTML
@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Kategori Produk</h3>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><i class="fa-solid fa-plus text-primary me-2"></i> Tambah Kategori</div>
            <div class="card-body">
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" required maxlength="25">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2" maxlength="100"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-save"></i> Simpan Kategori</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Produk</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\$kategori as \$k)
                            <tr>
                                <form action="{{ route('admin.kategori.update', \$k->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <td class="ps-3"><input type="text" name="nama_kategori" class="form-control form-control-sm" value="{{ \$k->nama_kategori }}" required></td>
                                    <td><input type="text" name="deskripsi" class="form-control form-control-sm" value="{{ \$k->deskripsi }}"></td>
                                    <td><span class="badge bg-secondary">{{ \$k->produk_count }} item</span></td>
                                    <td class="text-end pe-3">
                                        <button type="submit" class="btn btn-sm btn-success" title="Update"><i class="fa-solid fa-check"></i></button>
                                </form>
                                        <form action="{{ route('admin.kategori.destroy', \$k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
HTML;
file_put_contents($dir.'/resources/views/owner/kategori/index.blade.php', $kategori);

echo "Tahap 1 selesai.";
