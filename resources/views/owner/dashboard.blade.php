@extends('layouts.admin')

@section('content')
<div class="container-fluid px-0">
    <style>
        .stat-card-pro {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            height: 100%;
        }
        .stat-card-pro:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }
        .bg-icy { background-color: #D5E3E6; color: #5B6D92; }
        .bg-soft-green { background-color: #d1fae5; color: #059669; }
        .bg-soft-red { background-color: #fee2e2; color: #dc2626; }
        .bg-soft-gold { background-color: #fef3c7; color: #d97706; }

        /* Produk Terlaris Card Styles */
        .top-product-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }
        .top-product-item {
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
            background: #ffffff;
            transition: all 0.2s ease;
        }
        .top-product-item:hover {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }
        .rank-badge {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.72rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .rank-badge-1 {
            background: #fef9c3;
            color: #a16207;
            border: 1px solid #fde047;
        }
        .rank-badge-2 {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .rank-badge-3 {
            background: #ffedd5;
            color: #c2410c;
            border: 1px solid #fed7aa;
        }
        .rank-badge-other {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        .product-thumb {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }
        .product-thumb-placeholder {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid #e0e7ff;
        }
        .sales-pill {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }
    </style>

    <!-- Date Only Header -->
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-0"><i class="fa-regular fa-calendar-check text-primary me-2"></i>{{ date('d F Y') }}</h4>
    </div>

    <!-- 4 Main Financial Stat Cards -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Transaksi -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Total Transaksi</span>
                    <div class="icon-box bg-icy"><i class="fa-solid fa-receipt"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($totalTransaksi) }} <span class="fs-6 text-muted fw-normal">Pesanan</span></h3>
                <small class="text-muted"><i class="fa-solid fa-circle-check text-success me-1"></i>Status Aktif Hari Ini</small>
            </div>
        </div>

        <!-- Card 2: Pendapatan Kotor -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Pendapatan Kotor</span>
                    <div class="icon-box bg-soft-green"><i class="fa-solid fa-wallet"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-success">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                <small class="text-muted"><i class="fa-solid fa-arrow-trend-up text-success me-1"></i>Pembayaran Lunas</small>
            </div>
        </div>

        <!-- Card 3: Total Pengeluaran -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Total Pengeluaran</span>
                    <div class="icon-box bg-soft-red"><i class="fa-solid fa-money-bill-wave"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-danger">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</h3>
                <small class="text-muted"><i class="fa-solid fa-circle-minus text-danger me-1"></i>Biaya Operasional</small>
            </div>
        </div>

        <!-- Card 4: Pendapatan Bersih -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro" style="border-left: 4px solid #5B6D92;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Pendapatan Bersih</span>
                    <div class="icon-box bg-soft-gold"><i class="fa-solid fa-sack-dollar"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-primary">Rp {{ number_format($pendapatanBersih, 0, ',', '.') }}</h3>
                <small class="text-muted"><i class="fa-solid fa-chart-line text-primary me-1"></i>Margin Profit</small>
            </div>
        </div>
    </div>

    <!-- Main Content Section: Recent Transactions & Quick Insights -->
    <div class="row g-4">
        <!-- Left Side: Recent Transactions Table (Live Stream) -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Transaksi Terbaru</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>KODE</th>
                                    <th>PEMESAN</th>
                                    <th>METODE</th>
                                    <th>TOTAL</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksiTerbaru as $t)
                                <tr>
                                    <td class="fw-bold text-primary">#{{ $t->kode_transaksi }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $t->nama_pemesan }}</div>
                                        <small class="text-muted">Meja/Spot: {{ $t->meja ? ('Meja #' . ($t->meja->nomor_meja ?? $t->meja->id)) : ($t->meja_id ? 'Meja #' . $t->meja_id : '-') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border text-uppercase">{{ $t->metode_pembayaran }}</span>
                                    </td>
                                    <td class="fw-bold">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if($t->status_pembayaran == 'lunas')
                                            <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i>Lunas</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i>Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-inbox fs-2 opacity-25 mb-2 d-block"></i>
                                        Belum ada transaksi tercatat hari ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Quick Stats & Popular Items -->
        <div class="col-12 col-lg-4">
            <!-- Operational Summary Box -->
            <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-users-gear me-2 text-primary"></i>Ringkasan Operasional</h6>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded-3" style="background-color: #F0E2D2;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-white text-primary rounded-circle"><i class="fa-solid fa-user-shield"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0">Kasir Aktif</h6>
                                <small class="text-muted">Petugas Operasional</small>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-0 text-primary">{{ $totalKasir }}</h4>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background-color: #D5E3E6;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-white text-primary rounded-circle"><i class="fa-solid fa-qrcode"></i></div>
                            <div>
                                <h6 class="fw-bold mb-0">Spot Meja QR</h6>
                                <small class="text-muted">Titik Scan Aktif</small>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-0 text-primary">{{ $totalMeja }}</h4>
                    </div>
                </div>
            </div>

            <!-- Top Wahana / Produk Card -->
            <div class="top-product-card">
                <div class="card-header bg-white py-3 px-3 d-flex align-items-center justify-content-between border-bottom">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="fa-solid fa-chart-line text-primary"></i>
                            Produk / Wahana Terlaris
                        </h6>
                        <small class="text-muted" style="font-size: 0.72rem;">Berdasarkan total unit terjual</small>
                    </div>
                    <span class="badge bg-light text-primary border px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.7rem;">Top 5</span>
                </div>
                <div class="card-body p-3">
                    @php
                        $maxTerjual = $produkTerlaris->first()->total_terjual ?? 1;
                        $maxTerjual = $maxTerjual > 0 ? $maxTerjual : 1;
                    @endphp

                    <div class="d-flex flex-column gap-2">
                        @forelse($produkTerlaris as $index => $pt)
                            @php
                                $rank = $index + 1;
                                $rankClass = match($rank) {
                                    1 => 'rank-badge-1',
                                    2 => 'rank-badge-2',
                                    3 => 'rank-badge-3',
                                    default => 'rank-badge-other',
                                };
                                $percentage = min(100, max(8, round(($pt->total_terjual / $maxTerjual) * 100)));
                            @endphp
                            <div class="top-product-item">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-2 min-w-0" style="overflow: hidden;">
                                        <!-- Rank Number Badge -->
                                        <span class="rank-badge {{ $rankClass }}">#{{ $rank }}</span>

                                        <!-- Product Thumbnail -->
                                        @if($pt->produk && $pt->produk->foto_produk)
                                            <img src="{{ asset('storage/' . $pt->produk->foto_produk) }}" alt="{{ $pt->produk->nama_produk }}" class="product-thumb">
                                        @else
                                            <div class="product-thumb-placeholder">
                                                <i class="fa-solid fa-box"></i>
                                            </div>
                                        @endif

                                        <!-- Product Info -->
                                        <div class="text-truncate">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.86rem;" title="{{ $pt->produk->nama_produk ?? 'Produk' }}">
                                                {{ $pt->produk->nama_produk ?? 'Produk' }}
                                            </div>
                                            <div class="text-muted text-truncate" style="font-size: 0.72rem;">
                                                {{ $pt->produk->kategori->nama_kategori ?? 'Umum' }} &bull; Rp {{ number_format($pt->produk->harga ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity Sold -->
                                    <div class="text-end flex-shrink-0">
                                        <span class="sales-pill">
                                            {{ number_format($pt->total_terjual) }} <span class="fw-normal text-muted" style="font-size: 0.72rem;">terjual</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="progress mt-2" style="height: 4px; background-color: #f1f5f9; border-radius: 4px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: {{ $rank === 1 ? '#eab308' : '#5B6D92' }}; border-radius: 4px;"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fa-solid fa-inbox fs-2 opacity-25 mb-2 d-block"></i>
                                <span class="small">Belum ada data penjualan produk.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection