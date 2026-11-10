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

    <!-- Bar Filter -->
    <div class="report-card p-3 p-md-4 mb-4">
        <form method="GET" action="{{ route('owner.pendapatan.transaksi') }}" class="row g-3 align-items-end" id="filterForm">
            <div class="col-12 col-sm-6 col-md-3">
                <label for="inputDari" class="form-label small fw-bold text-muted mb-1">
                    <i class="fa-regular fa-calendar me-1"></i>Dari Tanggal
                </label>
                <input type="date" class="form-control form-control-sm" id="inputDari" name="dari" value="{{ $dari }}" required>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label for="inputSampai" class="form-label small fw-bold text-muted mb-1">
                    <i class="fa-regular fa-calendar me-1"></i>Sampai Tanggal
                </label>
                <input type="date" class="form-control form-control-sm" id="inputSampai" name="sampai" value="{{ $sampai }}" required>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <label for="inputMetode" class="form-label small fw-bold text-muted mb-1">
                    <i class="fa-solid fa-credit-card me-1"></i>Metode Bayar
                </label>
                <select name="metode" id="inputMetode" class="form-select form-select-sm">
                    <option value="semua" {{ $metode === 'semua' ? 'selected' : '' }}>Semua Metode</option>
                    <option value="tunai" {{ $metode === 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="transfer" {{ $metode === 'transfer' ? 'selected' : '' }}>Transfer Bank (VA)</option>
                    <option value="ewallet" {{ $metode === 'ewallet' ? 'selected' : '' }}>E-Wallet (GoPay/Shopee)</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fa-solid fa-filter me-1"></i>Terapkan
                </button>
            </div>
            <div class="col-12 col-md-2">
                @php
                    $todayStr = now()->toDateString();
                    $startThisMonthStr = now()->startOfMonth()->toDateString();
                @endphp
                <div class="d-flex gap-1 justify-content-md-end">
                    <a href="{{ route('owner.pendapatan.transaksi', ['dari' => $todayStr, 'sampai' => $todayStr, 'metode' => $metode]) }}"
                       class="btn btn-sm quick-btn w-50 text-center {{ ($dari === $todayStr && $sampai === $todayStr) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('owner.pendapatan.transaksi', ['dari' => $startThisMonthStr, 'sampai' => $todayStr, 'metode' => $metode]) }}"
                       class="btn btn-sm quick-btn w-50 text-center {{ ($dari === $startThisMonthStr && $sampai === $todayStr) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Bulan Ini
                    </a>
                </div>
            </div>
        </form>
        <div class="mt-2 text-muted small">
            <i class="fa-solid fa-circle-info text-primary me-1"></i>
            Menampilkan periode: <strong>{{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</strong>
            @if($metode !== 'semua')
                | Filter Metode: <span class="badge bg-light text-primary border">{{ ucfirst($metode) }}</span>
            @endif
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
