<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Playground POS & Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --bs-primary: #0d6efd;
            --bs-primary-rgb: 13, 110, 253;
            --bs-body-bg: #f4f6f9;
            --bs-body-font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            --card-border-radius: 14px;
            --card-box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: var(--bs-body-bg);
            color: #2b303a;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: #1e2227;
            color: #fff;
            transition: all 0.3s ease;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #14171a;
            text-align: center;
            border-bottom: 1px solid #2d3238;
        }
        #sidebar a {
            padding: 12px 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            color: #a0aec0;
            text-decoration: none;
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }
        #sidebar a:hover, #sidebar a.active {
            color: #ffffff;
            background: #282e36;
            border-left: 4px solid #0d6efd;
            font-weight: 600;
        }
        #sidebar a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        /* Content & Topbar */
        #content {
            width: 100%;
            padding: 0;
            min-height: 100vh;
        }
        .topbar {
            background: #ffffff;
            padding: 14px 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            border-bottom: 1px solid #edf2f7;
        }

        /* Card Components */
        .card {
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: var(--card-box-shadow);
            background: #ffffff;
            margin-bottom: 24px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f0f3f8;
            padding: 16px 20px;
            font-weight: 700;
            border-radius: var(--card-border-radius) var(--card-border-radius) 0 0 !important;
        }

        /* Table Styling */
        .table-responsive {
            border-radius: var(--card-border-radius);
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Button System */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.825rem;
            border-radius: 7px;
        }

        /* Form Control Tweaks */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            font-size: 0.9rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }
        .form-control-sm, .form-select-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
            border-radius: 6px;
        }

        /* Modals */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }
        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 18px 24px;
        }
        .modal-body {
            padding: 24px;
        }
        .modal-footer {
            border-top: 1px solid #f1f5f9;
            padding: 16px 24px;
        }

        /* Empty State Wrapper */
        .empty-state {
            padding: 48px 24px;
            text-align: center;
        }
        .empty-state-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: rgba(13, 110, 253, 0.08);
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px auto;
            font-size: 1.75rem;
        }

        /* Page Titles */
        .page-title {
            font-weight: 800;
            font-size: 1.35rem;
            color: #1e293b;
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }
        .page-subtitle {
            color: #64748b;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h5 class="mb-0 text-white fw-bold"><i class="fa-solid fa-shapes text-warning me-2"></i>ASA JAYA</h5>
            </div>
            <div class="pt-3">
                @if(Auth::user()->role === 'owner')
                    <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Dashboard Owner</a>
                    <a href="{{ route('owner.karyawan.index') }}" class="{{ request()->routeIs('owner.karyawan.*') ? 'active' : '' }}"><i class="fa-solid fa-users-gear"></i> Kasir & User</a>
                    <a href="{{ route('owner.meja.index') }}" class="{{ request()->routeIs('owner.meja.*') ? 'active' : '' }}"><i class="fa-solid fa-qrcode"></i> Data Meja / QR</a>
                    <a href="{{ route('admin.transaksi.index') }}" class="{{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a>
                    <a href="{{ route('admin.kategori.index') }}" class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}"><i class="fa-solid fa-tags"></i> Kategori Produk</a>
                    <a href="{{ route('admin.produk.index') }}" class="{{ request()->routeIs('admin.produk.*') ? 'active' : '' }}"><i class="fa-solid fa-box-open"></i> Barang Penjualan</a>
                    <a href="{{ route('admin.pengeluaran.index') }}" class="{{ request()->routeIs('admin.pengeluaran.*') ? 'active' : '' }}"><i class="fa-solid fa-money-bill-trend-up"></i> Pengeluaran</a>
                @elseif(Auth::user()->role === 'kasir')
                    <a href="{{ route('kasir.dashboard') }}" class="{{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-bell-concierge"></i> Antrean Aktif (Live)</a>
                    <a href="{{ route('admin.transaksi.index') }}" class="{{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi</a>
                    <a href="{{ route('admin.kategori.index') }}" class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}"><i class="fa-solid fa-tags"></i> Kategori Produk</a>
                    <a href="{{ route('admin.produk.index') }}" class="{{ request()->routeIs('admin.produk.*') ? 'active' : '' }}"><i class="fa-solid fa-box-open"></i> Barang Penjualan</a>
                    <a href="{{ route('admin.pengeluaran.index') }}" class="{{ request()->routeIs('admin.pengeluaran.*') ? 'active' : '' }}"><i class="fa-solid fa-money-bill-trend-up"></i> Pengeluaran</a>
                @endif
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <div class="topbar">
                <h6 class="m-0 text-secondary fw-semibold">
                    <i class="fa-solid fa-shield-halved me-1 text-primary"></i>
                    @if(Auth::user()->role === 'owner') Portal Owner @else Portal Kasir @endif
                </h6>
                <div class="d-flex align-items-center">
                    <span class="me-3 fw-medium text-dark small"><i class="fa-regular fa-circle-user text-primary me-1"></i> Halo, {{ Auth::user()->nama }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="fa-solid fa-right-from-bracket me-1"></i> Keluar</button>
                    </form>
                </div>
            </div>

            <div class="container-fluid px-4 pb-5">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
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