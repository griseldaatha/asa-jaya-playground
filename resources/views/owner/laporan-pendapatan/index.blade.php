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
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.05);
        }
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }
        .bg-icy { background-color: #D5E3E6; color: #5B6D92; }
        .bg-soft-green { background-color: #d1fae5; color: #059669; }
        .bg-soft-gold { background-color: #fef3c7; color: #d97706; }
        .bg-soft-red { background-color: #fee2e2; color: #dc2626; }
        .bg-soft-blue { background-color: #dbeafe; color: #2563eb; }
        
        .report-card {
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .report-card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 20px;
        }
        .table-laporan thead th {
            background-color: #D5E3E6 !important;
            color: #5B6D92 !important;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 2px solid #5B6D92 !important;
            vertical-align: middle;
        }
        .table-laporan tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
        }
        .table-laporan tfoot td, .table-laporan tfoot th {
            padding: 12px 16px;
            font-weight: 700;
            background-color: #f8fafc !important;
            border-top: 2px solid #cbd5e1 !important;
        }

        /* Sleek Filter Bar Pro (Image Matching) */
        .filter-wrapper-pro {
            margin-bottom: 24px;
        }
        .filter-bar-sleek {
            background: #1e2433;
            border-radius: 9999px;
            padding: 8px 12px 8px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        @media (max-width: 991px) {
            .filter-bar-sleek {
                border-radius: 20px;
                flex-direction: column;
                align-items: stretch;
                padding: 14px;
                gap: 12px;
            }
        }
        .filter-inputs-group {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .date-range-box {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #ffffff;
            font-size: 0.85rem;
        }
        .filter-icon {
            color: #94a3b8;
            font-size: 0.95rem;
        }
        .sleek-date-input {
            background: transparent !important;
            border: none !important;
            color: #ffffff !important;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 2px 4px;
            width: 125px;
            outline: none !important;
            box-shadow: none !important;
            cursor: pointer;
            color-scheme: dark;
        }
        .range-arrow {
            color: #94a3b8;
            font-weight: 600;
            margin: 0 4px;
            font-size: 0.95rem;
        }
        .filter-divider {
            width: 1px;
            height: 20px;
            background: rgba(255, 255, 255, 0.2);
            margin: 0 8px;
        }
        @media (max-width: 576px) {
            .filter-divider { display: none; }
        }
        .method-select-box {
            display: flex;
            align-items: center;
            gap: 6px;
            position: relative;
        }
        .sleek-select {
            background: transparent !important;
            border: none !important;
            color: #ffffff !important;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 2px 24px 2px 4px;
            outline: none !important;
            box-shadow: none !important;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
        }
        .sleek-select option {
            background-color: #1e2433;
            color: #ffffff;
        }
        .select-chevron {
            position: absolute;
            right: 4px;
            pointer-events: none;
            color: #94a3b8;
            font-size: 0.7rem;
        }
        .filter-actions-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .btn-quick-pill {
            background: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 9999px;
            padding: 6px 14px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-quick-pill:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.24);
        }
        .btn-quick-pill.active {
            background: #5B6D92;
            color: #ffffff;
            border-color: #7b8fae;
        }
        .btn-terapkan-pill {
            background: #9ab4f5;
            color: #0f172a;
            border: none;
            border-radius: 9999px;
            padding: 6px 18px;
            font-size: 0.825rem;
            font-weight: 700;
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .btn-terapkan-pill:hover {
            background: #b5c7f8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(154, 180, 245, 0.35);
        }

        /* Active Filter Tags Row */
        .filter-active-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            padding: 10px 8px 4px 8px;
        }
        .active-tags-list {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .filter-label-text {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.8px;
            color: #475569;
            text-transform: uppercase;
            margin-right: 2px;
        }
        .filter-tag-chip {
            background: #242c3f;
            border: 1px solid #384259;
            color: #e2e8f0;
            border-radius: 9999px;
            padding: 4px 12px;
            font-size: 0.78rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .filter-tag-chip:hover {
            background: #2d374e;
            color: #ffffff;
            border-color: #ef4444;
        }
        .tag-close-btn {
            font-size: 0.95rem;
            line-height: 1;
            color: #94a3b8;
            transition: color 0.2s ease;
        }
        .filter-tag-chip:hover .tag-close-btn {
            color: #ef4444;
        }
        .period-days-count {
            font-size: 0.825rem;
            color: #475569;
        }
        .period-days-count strong {
            color: #0f172a;
            font-weight: 700;
        }
        .table-pl th, .table-pl td {
            padding: 10px 16px;
        }
    </style>

    <!-- Header Action Button -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary px-3 py-2 rounded-pill fw-semibold">
                <i class="fa-solid fa-briefcase me-1"></i> Executive Financial Statement
            </span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('owner.pendapatan.transaksi') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-cash-register"></i>
                <span>Lihat Penerimaan Uang</span>
            </a>
            <a href="{{ route('owner.pendapatan.cetak', ['dari' => $dari, 'sampai' => $sampai]) }}" target="_blank" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Simpan PDF</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Flash Error -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Bar Filter Ramping Modern (Format Pill Sesuai Mockup) -->
    <div class="filter-wrapper-pro">
        <form method="GET" action="{{ route('owner.pendapatan.index') }}" class="filter-bar-sleek" id="filterForm">
            <div class="filter-inputs-group">
                <div class="date-range-box">
                    <i class="fa-regular fa-calendar filter-icon"></i>
                    <input type="date" class="sleek-date-input" id="inputDari" name="dari" value="{{ $dari }}" required title="Dari Tanggal">
                    <span class="range-arrow">&rarr;</span>
                    <input type="date" class="sleek-date-input" id="inputSampai" name="sampai" value="{{ $sampai }}" required title="Sampai Tanggal">
                </div>

                <div class="filter-divider"></div>

                <div class="method-select-box">
                    <i class="fa-solid fa-credit-card filter-icon"></i>
                    <select name="metode" id="inputMetode" class="sleek-select" title="Pilih Metode Pembayaran" onchange="document.getElementById('filterForm').submit()">
                        <option value="semua" {{ ($metode ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Metode</option>
                        <option value="tunai" {{ ($metode ?? '') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="transfer" {{ ($metode ?? '') === 'transfer' ? 'selected' : '' }}>Transfer Bank (VA)</option>
                        <option value="ewallet" {{ ($metode ?? '') === 'ewallet' ? 'selected' : '' }}>E-Wallet (GoPay/Shopee)</option>
                    </select>
                    <i class="fa-solid fa-chevron-down select-chevron"></i>
                </div>
            </div>

            <div class="filter-actions-group">
                @php
                    $todayStr = now()->toDateString();
                    $startThisMonthStr = now()->startOfMonth()->toDateString();
                @endphp
                <button type="button" 
                        class="btn-quick-pill {{ ($dari === $todayStr && $sampai === $todayStr) ? 'active' : '' }}" 
                        onclick="setQuickDate('{{ $todayStr }}', '{{ $todayStr }}')">
                    Hari Ini
                </button>
                <button type="button" 
                        class="btn-quick-pill {{ ($dari === $startThisMonthStr && $sampai === $todayStr) ? 'active' : '' }}" 
                        onclick="setQuickDate('{{ $startThisMonthStr }}', '{{ $todayStr }}')">
                    Bulan Ini
                </button>
                <button type="submit" class="btn-terapkan-pill">
                    <i class="fa-solid fa-check me-1"></i> Terapkan
                </button>
            </div>
        </form>

        <!-- Baris Bawah: FILTER AKTIF & Tag Chips (Bisa Dihapus Satu Per Satu) -->
        <div class="filter-active-bar">
            <div class="active-tags-list">
                <span class="filter-label-text">FILTER AKTIF</span>
                
                <!-- Tag Periode Tanggal -->
                <div class="filter-tag-chip" title="Hapus filter tanggal (kembali ke bulan ini)" onclick="resetDateFilter()">
                    <span>Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} &ndash; {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</span>
                    <span class="tag-close-btn">&times;</span>
                </div>

                <!-- Tag Metode Pembayaran -->
                @php
                    $metodeLabels = [
                        'semua' => 'Semua Metode',
                        'tunai' => 'Tunai',
                        'transfer' => 'Transfer Bank (VA)',
                        'ewallet' => 'E-Wallet',
                    ];
                    $currentMetode = $metode ?? 'semua';
                @endphp
                <div class="filter-tag-chip" title="Hapus filter metode (kembali ke Semua Metode)" onclick="resetMetodeFilter()">
                    <span>Metode: {{ $metodeLabels[$currentMetode] ?? ucfirst($currentMetode) }}</span>
                    <span class="tag-close-btn">&times;</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Kartu Metrik Laba Rugi & Finansial Eksekutif -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Pendapatan Kotor -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Pendapatan Kotor</span>
                    <div class="icon-box bg-soft-green"><i class="fa-solid fa-wallet"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <small class="text-muted d-block">
                    Dari <strong>{{ number_format($countTransaksi) }}</strong> transaksi lunas
                </small>
            </div>
        </div>

        <!-- Card 2: Beban Pengeluaran -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Beban Operasional</span>
                    <div class="icon-box bg-soft-red"><i class="fa-solid fa-money-bill-wave"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                <small class="text-muted d-block">
                    Dari <strong>{{ number_format($countPengeluaran) }}</strong> pos pengeluaran
                </small>
            </div>
        </div>

        <!-- Card 3: Laba Bersih Usaha -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro" style="border-left: 4px solid {{ $labaBersih >= 0 ? '#10b981' : '#ef4444' }};">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Laba Bersih (Net Profit)</span>
                    <div class="icon-box {{ $labaBersih >= 0 ? 'bg-soft-blue' : 'bg-soft-red' }}">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 {{ $labaBersih >= 0 ? 'text-primary' : 'text-danger' }}">
                    Rp {{ number_format($labaBersih, 0, ',', '.') }}
                </h3>
                <div class="d-flex align-items-center gap-1">
                    <span class="badge {{ $labaBersih >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fw-bold" style="font-size: 0.72rem;">
                        {{ $marginLaba >= 0 ? '+' : '' }}{{ $marginLaba }}% Margin Profit
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 4: Rasio Biaya terhadap Pendapatan -->
        @php
            $expenseRatio = $totalPendapatan > 0 ? round(($totalPengeluaran / $totalPendapatan) * 100, 1) : 0;
        @endphp
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Rasio Beban Usaha</span>
                    <div class="icon-box bg-icy"><i class="fa-solid fa-chart-pie"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ $expenseRatio }}%</h3>
                <small class="text-muted d-block">
                    Beban menghabiskan {{ $expenseRatio }}% dari total omzet
                </small>
            </div>
        </div>
    </div>

    <!-- TABEL 1: FORMAT LAPORAN LABA RUGI RESMI (PROFIT & LOSS STATEMENT) -->
    <div class="report-card mb-4">
        <div class="report-card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>Laporan Laba Rugi Usaha (Profit & Loss Statement)
                </h6>
                <small class="text-muted">Format ikhtisar keuangan standar pendapatan kotor dikurangi beban operasional</small>
            </div>
            <span class="badge bg-light text-muted border">Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} &ndash; {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-pl mb-0 align-middle">
                <tbody>
                    <tr class="table-light">
                        <th colspan="2" class="fw-bold text-uppercase text-secondary" style="font-size: 0.8rem;">
                            1. PENDAPATAN USAHA (REVENUE)
                        </th>
                        <th style="width: 200px;" class="text-end fw-bold text-secondary" style="font-size: 0.8rem;">NOMINAL</th>
                    </tr>
                    <tr>
                        <td style="width: 30px;" class="text-center text-muted">a.</td>
                        <td>Penerimaan Pembayaran Tunai (Cash di Kasir)</td>
                        <td class="text-end fw-semibold text-dark">Rp {{ number_format($totalTunai, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center text-muted">b.</td>
                        <td>Penerimaan Non-Tunai Transfer Bank (Virtual Account)</td>
                        <td class="text-end fw-semibold text-dark">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center text-muted">c.</td>
                        <td>Penerimaan Digital E-Wallet (GoPay / ShopeePay QRIS)</td>
                        <td class="text-end fw-semibold text-dark">Rp {{ number_format($totalEwallet, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-light fw-bold">
                        <td colspan="2" class="text-success ps-4">TOTAL PENDAPATAN KOTOR (A)</td>
                        <td class="text-end text-success fs-6">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>

                    <tr class="table-light">
                        <th colspan="2" class="fw-bold text-uppercase text-secondary pt-3" style="font-size: 0.8rem;">
                            2. BEBAN BIAYA OPERASIONAL (EXPENSES)
                        </th>
                        <th class="text-end fw-bold text-secondary pt-3" style="font-size: 0.8rem;">NOMINAL</th>
                    </tr>
                    <tr>
                        <td class="text-center text-muted">a.</td>
                        <td>Total Biaya & Pos Pengeluaran Toko ({{ number_format($countPengeluaran) }} Catatan)</td>
                        <td class="text-end fw-semibold text-danger">(Rp {{ number_format($totalPengeluaran, 0, ',', '.') }})</td>
                    </tr>
                    <tr class="bg-light fw-bold">
                        <td colspan="2" class="text-danger ps-4">TOTAL BEBAN OPERASIONAL (B)</td>
                        <td class="text-end text-danger fs-6">(Rp {{ number_format($totalPengeluaran, 0, ',', '.') }})</td>
                    </tr>

                    <tr class="{{ $labaBersih >= 0 ? 'table-success' : 'table-danger' }} fw-bold" style="border-top: 3px solid #cbd5e1;">
                        <td colspan="2" class="fs-6 py-3 ps-3">
                            <i class="fa-solid fa-scale-balanced me-2"></i>LABA BERSIH USAHA (NET PROFIT = A &minus; B)
                        </td>
                        <td class="text-end fs-5 py-3 {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($labaBersih, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL 2: REKAPITULASI KOMPARATIF HARIAN (LABA RUGI PER TANGGAL) -->
    <div class="report-card">
        <div class="report-card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-calendar-day text-primary me-2"></i>Rekapitulasi Finansial Harian (Pendapatan vs Pengeluaran & Laba)
                </h6>
                <small class="text-muted">Performa finansial harian menyandingkan pendapatan, beban, dan profitabilitas per tanggal</small>
            </div>
            <span class="badge bg-light text-muted border">{{ count($rekapHarian) }} Hari Aktif</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-laporan mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th style="width: 130px;">Tanggal</th>
                        <th class="text-end" style="width: 160px;">Pendapatan Kotor</th>
                        <th class="text-end" style="width: 150px;">Beban Pengeluaran</th>
                        <th class="text-end" style="width: 160px;">Laba Bersih</th>
                        <th class="text-center" style="width: 120px;">Margin Laba</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapHarian as $index => $row)
                        @php
                            $rowMargin = $row['total_pendapatan'] > 0 ? round(($row['laba_bersih'] / $row['total_pendapatan']) * 100) : ($row['laba_bersih'] < 0 ? -100 : 0);
                        @endphp
                        <tr>
                            <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $row['tanggal'] }}</td>
                            <td class="text-end text-success fw-bold">Rp {{ number_format($row['total_pendapatan'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger fw-semibold">
                                @if($row['total_pengeluaran'] > 0)
                                    Rp {{ number_format($row['total_pengeluaran'], 0, ',', '.') }}
                                @else
                                    <span class="text-muted">&ndash;</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold {{ $row['laba_bersih'] >= 0 ? 'text-primary' : 'text-danger' }}">
                                Rp {{ number_format($row['laba_bersih'], 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $row['laba_bersih'] >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fw-bold">
                                    {{ $rowMargin >= 0 ? '+' : '' }}{{ $rowMargin }}%
                                </span>
                            </td>
                            <td class="text-center">
                                @if($row['laba_bersih'] > 0)
                                    <span class="badge bg-soft-green text-success px-2 py-1"><i class="fa-solid fa-arrow-trend-up me-1"></i>Profit</span>
                                @elseif($row['laba_bersih'] < 0)
                                    <span class="badge bg-soft-red text-danger px-2 py-1"><i class="fa-solid fa-arrow-trend-down me-1"></i>Defisit</span>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-1">Impas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state-icon mb-2">
                                    <i class="fa-solid fa-calendar-xmark text-muted"></i>
                                </div>
                                <h6 class="text-dark fw-bold mb-1">Tidak Ada Data Finansial</h6>
                                <p class="text-muted small mb-0">Belum ada catatan pendapatan atau pengeluaran pada periode tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($rekapHarian) > 0)
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-center">TOTAL PERIODE</th>
                            <th class="text-end text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                            <th class="text-end text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
                            <th class="text-end {{ $labaBersih >= 0 ? 'text-primary' : 'text-danger' }}">
                                Rp {{ number_format($labaBersih, 0, ',', '.') }}
                            </th>
                            <th class="text-center {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">{{ $marginLaba }}%</th>
                            <th class="text-center">
                                <span class="badge {{ $labaBersih >= 0 ? 'bg-success text-white' : 'bg-danger text-white' }} px-2 py-1">
                                    {{ $labaBersih >= 0 ? 'PROFIT' : 'DEFISIT' }}
                                </span>
                            </th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function setQuickDate(dari, sampai) {
        document.getElementById('inputDari').value = dari;
        document.getElementById('inputSampai').value = sampai;
        document.getElementById('filterForm').submit();
    }

    function resetDateFilter() {
        const startMonth = '{{ now()->startOfMonth()->toDateString() }}';
        const today = '{{ now()->toDateString() }}';
        document.getElementById('inputDari').value = startMonth;
        document.getElementById('inputSampai').value = today;
        document.getElementById('filterForm').submit();
    }

    function resetMetodeFilter() {
        document.getElementById('inputMetode').value = 'semua';
        document.getElementById('filterForm').submit();
    }
</script>
@endsection
