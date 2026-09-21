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
                <h5 class="mb-0 text-white fw-bold"><i class="fa-solid fa-shapes text-warning"></i> ASA JAYA</h5>
            </div>
            <div class="pt-3">
                @if(Auth::user()->role === 'owner')
                    <a href="{{ route('owner.dashboard') }}"><i class="fa-solid fa-chart-line"></i> Dashboard Owner</a>
                    <a href="{{ route('owner.karyawan.index') }}"><i class="fa-solid fa-users-gear"></i> Kasir & User</a>
                    <a href="{{ route('owner.meja.index') }}"><i class="fa-solid fa-qrcode"></i> Data Meja / QR</a>
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