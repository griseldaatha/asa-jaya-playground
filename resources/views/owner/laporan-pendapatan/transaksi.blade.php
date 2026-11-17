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
        .quick-btn {
            font-size: 0.8rem;
            padding: 5px 12px;
            border-radius: 8px;
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
    </style>

    <!-- Header Action Button -->
    <div class="d-flex justify-content-end align-items-center mb-4">
        <a href="{{ route('owner.pendapatan.transaksi.cetak', array_filter(['dari' => $dari, 'sampai' => $sampai, 'metode' => $metode !== 'semua' ? $metode : null])) }}" target="_blank" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="fa-solid fa-print"></i>
            <span>Cetak / Simpan PDF</span>
        </a>
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
        <form method="GET" action="{{ route('owner.pendapatan.transaksi') }}" class="filter-bar-sleek" id="filterForm">
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
                        <option value="semua" {{ $metode === 'semua' ? 'selected' : '' }}>Semua Metode</option>
                        <option value="tunai" {{ $metode === 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="transfer" {{ $metode === 'transfer' ? 'selected' : '' }}>Transfer Bank (VA)</option>
                        <option value="ewallet" {{ $metode === 'ewallet' ? 'selected' : '' }}>E-Wallet (GoPay/Shopee)</option>
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
                @endphp
                <div class="filter-tag-chip" title="Hapus filter metode (kembali ke Semua Metode)" onclick="resetMetodeFilter()">
                    <span>Metode: {{ $metodeLabels[$metode] ?? ucfirst($metode) }}</span>
                    <span class="tag-close-btn">&times;</span>
                </div>
            </div>

            <div class="period-days-count">
                <strong>{{ $daysCount ?? (\Carbon\Carbon::parse($dari)->diffInDays(\Carbon\Carbon::parse($sampai)) + 1) }}</strong> hari dalam periode
            </div>
        </div>
    </div>

    <!-- 4 Kartu Ringkasan Penerimaan Uang -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Uang Masuk -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Total Uang Masuk</span>
                    <div class="icon-box bg-soft-green"><i class="fa-solid fa-sack-dollar"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <small class="text-muted"><i class="fa-solid fa-circle-check text-success me-1"></i>{{ number_format($countTransaksi) }} transaksi lunas</small>
            </div>
        </div>

        <!-- Card 2: Uang Tunai -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Uang Tunai (Cash)</span>
                    <div class="icon-box bg-soft-green"><i class="fa-solid fa-money-bill-wave"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalTunai, 0, ',', '.') }}</h3>
                <small class="text-muted">{{ number_format($countTunai) }} transaksi kasir</small>
            </div>
        </div>

        <!-- Card 3: Transfer Bank (VA) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Transfer Bank (VA)</span>
                    <div class="icon-box bg-soft-blue"><i class="fa-solid fa-building-columns"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-primary">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</h3>
                <small class="text-muted">{{ number_format($countTransfer) }} transaksi gateway</small>
            </div>
        </div>

        <!-- Card 4: E-Wallet -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">E-Wallet (GoPay/Shopee)</span>
                    <div class="icon-box bg-soft-gold"><i class="fa-solid fa-wallet"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-warning">Rp {{ number_format($totalEwallet, 0, ',', '.') }}</h3>
                <small class="text-muted">{{ number_format($countEwallet) }} transaksi scan QR</small>
            </div>
        </div>
    </div>

    <!-- TABEL UTAMA: Rincian Transaksi Penerimaan Uang -->
    <div class="report-card">
        <div class="report-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-cash-register text-primary me-2"></i>Rincian Transaksi Penerimaan Uang
                </h6>
                <small class="text-muted">Daftar transaksi penerimaan uang kas & non-tunai berstatus lunas pada periode terpilih</small>
            </div>
            <span class="badge bg-light text-muted border">Total: {{ number_format($rincianPendapatan->total()) }} Penerimaan</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-laporan mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th style="width: 140px;">Tanggal & Jam</th>
                        <th style="width: 160px;">Kode Transaksi</th>
                        <th>Pelanggan & Meja</th>
                        <th style="width: 170px;">Metode Penerimaan</th>
                        <th style="width: 130px;">Kasir</th>
                        <th class="text-end" style="width: 150px;">Uang Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rincianPendapatan as $index => $trx)
                        <tr>
                            <td class="text-center text-muted small">{{ $rincianPendapatan->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($trx->waktu_transaksi ?? $trx->created_at)->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($trx->waktu_transaksi ?? $trx->created_at)->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                @if(Route::has('admin.transaksi.show'))
                                    <a href="{{ route('admin.transaksi.show', $trx->id) }}" class="fw-bold text-decoration-none text-primary">
                                        {{ $trx->kode_transaksi }}
                                    </a>
                                @else
                                    <span class="fw-bold text-dark">{{ $trx->kode_transaksi }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $trx->nama_pemesan ?: 'Pelanggan Kasir' }}</div>
                                <small class="text-muted">
                                    @if($trx->meja)
                                        <i class="fa-solid fa-chair me-1 text-primary"></i>Meja #{{ $trx->meja->nomor_meja ?? $trx->meja->id }}
                                    @else
                                        <i class="fa-solid fa-store me-1 text-muted"></i>Kasir Langsung
                                    @endif
                                </small>
                            </td>
                            <td>
                                @if($trx->metode_pembayaran === 'tunai')
                                    <span class="badge bg-soft-green text-success">
                                        <i class="fa-solid fa-money-bill me-1"></i>Tunai
                                    </span>
                                @elseif($trx->metode_pembayaran === 'payment_gateway')
                                    <span class="badge bg-icy text-primary">
                                        <i class="fa-solid fa-credit-card me-1"></i>{{ $trx->metode_label }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($trx->metode_pembayaran ?? '-') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-dark small">{{ $trx->kasir->nama ?? $trx->kasir->username ?? '-' }}</span>
                            </td>
                            <td class="text-end fw-bold text-success">
                                Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state-icon mb-2">
                                    <i class="fa-solid fa-receipt text-muted"></i>
                                </div>
                                <h6 class="text-dark fw-bold mb-1">Tidak Ada Transaksi Penerimaan Uang</h6>
                                <p class="text-muted small mb-0">Belum ada transaksi penerimaan kas atau non-tunai pada periode dan filter metode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($rincianPendapatan->total() > 0)
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">TOTAL PENERIMAAN UANG (Periode Terpilih)</th>
                            <th class="text-end text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        @if($rincianPendapatan->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan {{ $rincianPendapatan->firstItem() }} - {{ $rincianPendapatan->lastItem() }} dari {{ $rincianPendapatan->total() }} baris
                </small>
                <div>
                    {{ $rincianPendapatan->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
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
