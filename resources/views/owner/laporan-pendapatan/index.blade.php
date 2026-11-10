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
        .chart-container-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
        }
    </style>

    <!-- Header Action Button -->
    <div class="d-flex justify-content-end align-items-center mb-4">
        <a href="{{ route('owner.pendapatan.cetak', ['dari' => $dari, 'sampai' => $sampai]) }}" target="_blank" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
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
        <form method="GET" action="{{ route('owner.pendapatan.index') }}" class="row g-3 align-items-end" id="filterForm">
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
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fa-solid fa-filter me-1"></i>Terapkan
                </button>
            </div>
            <div class="col-12 col-md-4">
                <div class="d-flex flex-wrap gap-1 justify-content-md-end">
                    @php
                        $todayStr = now()->toDateString();
                        $sevenDaysAgoStr = now()->subDays(6)->toDateString();
                        $startThisMonthStr = now()->startOfMonth()->toDateString();
                        $startLastMonthStr = now()->subMonthNoOverflow()->startOfMonth()->toDateString();
                        $endLastMonthStr = now()->subMonthNoOverflow()->endOfMonth()->toDateString();
                    @endphp
                    <a href="{{ route('owner.pendapatan.index', ['dari' => $todayStr, 'sampai' => $todayStr]) }}"
                       class="btn btn-sm quick-btn {{ ($dari === $todayStr && $sampai === $todayStr) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('owner.pendapatan.index', ['dari' => $sevenDaysAgoStr, 'sampai' => $todayStr]) }}"
                       class="btn btn-sm quick-btn {{ ($dari === $sevenDaysAgoStr && $sampai === $todayStr) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        7 Hari
                    </a>
                    <a href="{{ route('owner.pendapatan.index', ['dari' => $startThisMonthStr, 'sampai' => $todayStr]) }}"
                       class="btn btn-sm quick-btn {{ ($dari === $startThisMonthStr && $sampai === $todayStr) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Bulan Ini
                    </a>
                    <a href="{{ route('owner.pendapatan.index', ['dari' => $startLastMonthStr, 'sampai' => $endLastMonthStr]) }}"
                       class="btn btn-sm quick-btn {{ ($dari === $startLastMonthStr && $sampai === $endLastMonthStr) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Bulan Lalu
                    </a>
                </div>
            </div>
        </form>
        <div class="mt-2 text-muted small">
            <i class="fa-solid fa-circle-info text-primary me-1"></i>
            Menampilkan periode: <strong>{{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</strong>
        </div>
    </div>

    <!-- 4 Kartu Finansial Utama (Termasuk Laba Bersih Gabungan) -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Pendapatan Kotor -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Pendapatan Kotor</span>
                    <div class="icon-box bg-soft-green"><i class="fa-solid fa-wallet"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <small class="text-muted"><i class="fa-solid fa-circle-check text-success me-1"></i>Dari {{ number_format($countTransaksi) }} pesanan lunas</small>
            </div>
        </div>

        <!-- Card 2: Total Pengeluaran Operasional -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Beban Pengeluaran</span>
                    <div class="icon-box bg-soft-red"><i class="fa-solid fa-money-bill-wave"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                <small class="text-muted"><i class="fa-solid fa-circle-minus text-danger me-1"></i>Dari {{ number_format($countPengeluaran) }} pos pengeluaran</small>
            </div>
        </div>

        <!-- Card 3: Laba Bersih (Net Profit) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro" style="border-left: 4px solid {{ $labaBersih >= 0 ? '#10b981' : '#ef4444' }};">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Laba Bersih (Profit)</span>
                    <div class="icon-box {{ $labaBersih >= 0 ? 'bg-soft-blue' : 'bg-soft-red' }}">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 {{ $labaBersih >= 0 ? 'text-primary' : 'text-danger' }}">
                    Rp {{ number_format($labaBersih, 0, ',', '.') }}
                </h3>
                <div class="d-flex align-items-center gap-1">
                    <span class="badge {{ $labaBersih >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fw-bold" style="font-size: 0.72rem;">
                        {{ $marginLaba >= 0 ? '+' : '' }}{{ $marginLaba }}% Margin
                    </span>
                    <small class="text-muted" style="font-size: 0.72rem;">(Pendapatan - Beban)</small>
                </div>
            </div>
        </div>

        <!-- Card 4: Ringkasan & Metode Bayar -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Metode Pembayaran</span>
                    <div class="icon-box bg-icy"><i class="fa-solid fa-credit-card"></i></div>
                </div>
                <div class="small">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><i class="fa-solid fa-money-bill text-success me-1"></i>Tunai:</span>
                        <strong class="text-dark">Rp {{ number_format($totalTunai, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><i class="fa-solid fa-building-columns text-primary me-1"></i>Transfer:</span>
                        <strong class="text-dark">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-wallet text-warning me-1"></i>E-Wallet:</span>
                        <strong class="text-dark">Rp {{ number_format($totalEwallet, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION GRAFIK VISUAL (Chart.js) -->
    <div class="row g-4 mb-4">
        <!-- Grafik 1: Tren Finansial Harian (Pendapatan vs Pengeluaran & Laba) -->
        <div class="col-12 col-lg-8">
            <div class="report-card mb-0 h-100">
                <div class="report-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-chart-column text-primary me-2"></i>Tren Pendapatan, Pengeluaran & Laba Bersih
                        </h6>
                        <small class="text-muted">Grafik perbandingan performa keuangan harian pada periode terpilih</small>
                    </div>
                    <span class="badge bg-light text-primary border px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                        Grafik Interaktif
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <div class="chart-container-wrapper">
                        <canvas id="chartTrenFinansial"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik 2: Komposisi Metode Pembayaran -->
        <div class="col-12 col-lg-4">
            <div class="report-card mb-0 h-100">
                <div class="report-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-chart-pie text-primary me-2"></i>Metode Pembayaran
                        </h6>
                        <small class="text-muted">Distribusi transaksi lunas</small>
                    </div>
                </div>
                <div class="p-3 p-md-4 d-flex flex-column justify-content-between">
                    <div class="chart-container-wrapper" style="height: 230px;">
                        <canvas id="chartMetodeBayar"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-top small">
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <span class="d-block text-muted" style="font-size: 0.7rem;">Tunai</span>
                                <strong class="text-success">{{ $totalPendapatan > 0 ? round(($totalTunai / $totalPendapatan) * 100) : 0 }}%</strong>
                            </div>
                            <div class="col-4">
                                <span class="d-block text-muted" style="font-size: 0.7rem;">Transfer</span>
                                <strong class="text-primary">{{ $totalPendapatan > 0 ? round(($totalTransfer / $totalPendapatan) * 100) : 0 }}%</strong>
                            </div>
                            <div class="col-4">
                                <span class="d-block text-muted" style="font-size: 0.7rem;">E-Wallet</span>
                                <strong class="text-warning">{{ $totalPendapatan > 0 ? round(($totalEwallet / $totalPendapatan) * 100) : 0 }}%</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL 1: Rekap Per Hari (Laba Rugi Harian) -->
    <div class="report-card">
        <div class="report-card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-calendar-day text-primary me-2"></i>Rekap Finansial Harian (Laba Rugi)
                </h6>
                <small class="text-muted">Rincian pendapatan kotor, beban pengeluaran, serta laba bersih harian</small>
            </div>
            <span class="badge bg-light text-muted border">{{ count($rekapHarian) }} Hari Aktif</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-laporan mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 130px;">Tanggal</th>
                        <th class="text-center" style="width: 100px;">Pesanan</th>
                        <th class="text-end" style="width: 130px;">Tunai</th>
                        <th class="text-end" style="width: 130px;">Transfer</th>
                        <th class="text-end" style="width: 130px;">E-Wallet</th>
                        <th class="text-end" style="width: 150px;">Pendapatan Kotor</th>
                        <th class="text-end" style="width: 140px;">Pengeluaran</th>
                        <th class="text-end" style="width: 150px;">Laba Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapHarian as $row)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $row['tanggal'] }}</td>
                            <td class="text-center">{{ number_format($row['jml_transaksi']) }}</td>
                            <td class="text-end text-dark">Rp {{ number_format($row['total_tunai'], 0, ',', '.') }}</td>
                            <td class="text-end text-dark">Rp {{ number_format($row['total_transfer'], 0, ',', '.') }}</td>
                            <td class="text-end text-dark">Rp {{ number_format($row['total_ewallet'], 0, ',', '.') }}</td>
                            <td class="text-end text-success fw-bold">Rp {{ number_format($row['total_pendapatan'], 0, ',', '.') }}</td>
                            <td class="text-end text-danger fw-semibold">
                                @if($row['total_pengeluaran'] > 0)
                                    Rp {{ number_format($row['total_pengeluaran'], 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end fw-bold {{ $row['laba_bersih'] >= 0 ? 'text-primary' : 'text-danger' }}">
                                Rp {{ number_format($row['laba_bersih'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state-icon mb-2">
                                    <i class="fa-solid fa-calendar-xmark text-muted"></i>
                                </div>
                                <h6 class="text-dark fw-bold mb-1">Tidak Ada Transaksi Finansial</h6>
                                <p class="text-muted small mb-0">Belum ada pendapatan atau pengeluaran tercatat pada periode tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($rekapHarian) > 0)
                    <tfoot>
                        <tr>
                            <th>TOTAL</th>
                            <th class="text-center">{{ number_format($countTransaksi) }}</th>
                            <th class="text-end">Rp {{ number_format($totalTunai, 0, ',', '.') }}</th>
                            <th class="text-end">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</th>
                            <th class="text-end">Rp {{ number_format($totalEwallet, 0, ',', '.') }}</th>
                            <th class="text-end text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                            <th class="text-end text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
                            <th class="text-end {{ $labaBersih >= 0 ? 'text-primary' : 'text-danger' }}">
                                Rp {{ number_format($labaBersih, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- TABEL 2: Rincian Pendapatan (Transaksi Lunas) -->
    <div class="report-card">
        <div class="report-card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-list-check text-primary me-2"></i>Rincian Transaksi Pendapatan
                </h6>
                <small class="text-muted">Daftar transaksi berstatus lunas pada periode terpilih</small>
            </div>
            <span class="badge bg-light text-muted border">Total: {{ number_format($countTransaksi) }} Transaksi</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-laporan mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th style="width: 150px;">Tanggal & Jam</th>
                        <th style="width: 170px;">Kode Transaksi</th>
                        <th style="width: 160px;">Meja / Tipe</th>
                        <th style="width: 130px;">Metode</th>
                        <th>Kasir</th>
                        <th class="text-end" style="width: 160px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rincianPendapatan as $index => $trx)
                        <tr>
                            <td>{{ $rincianPendapatan->firstItem() + $index }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($trx->waktu_transaksi ?? $trx->created_at)->format('d/m/Y H:i') }}
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
                                @if($trx->meja)
                                    <span class="badge bg-light text-dark border">
                                        <i class="fa-solid fa-chair text-muted me-1"></i>Meja #{{ $trx->meja->nomor_meja ?? $trx->meja->id }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border">Kasir / Langsung</span>
                                @endif
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
                            <td>{{ $trx->kasir->nama ?? $trx->kasir->username ?? '-' }}</td>
                            <td class="text-end fw-semibold text-dark">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state-icon mb-2">
                                    <i class="fa-solid fa-receipt text-muted"></i>
                                </div>
                                <h6 class="text-dark fw-bold mb-1">Tidak Ada Transaksi Pendapatan</h6>
                                <p class="text-muted small mb-0">Belum ada transaksi berstatus lunas pada periode tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($countTransaksi > 0)
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">TOTAL (Seluruh Periode: {{ number_format($countTransaksi) }} Transaksi)</th>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formatRupiah = (val) => 'Rp ' + new Intl.NumberFormat('id-ID').format(val);

        // 1. Chart Tren Finansial Harian (Pendapatan vs Pengeluaran & Laba)
        const ctxTren = document.getElementById('chartTrenFinansial');
        if (ctxTren) {
            const labels = @json($chartDates);
            const dataPendapatan = @json($chartPendapatan);
            const dataPengeluaran = @json($chartPengeluaran);
            const dataLaba = @json($chartLaba);

            new Chart(ctxTren, {
                data: {
                    labels: labels.length > 0 ? labels : ['Tidak Ada Data'],
                    datasets: [
                        {
                            type: 'line',
                            label: 'Laba Bersih',
                            data: dataLaba.length > 0 ? dataLaba : [0],
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            borderWidth: 3,
                            tension: 0.35,
                            fill: false,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#2563eb',
                            order: 1
                        },
                        {
                            type: 'bar',
                            label: 'Pendapatan Kotor',
                            data: dataPendapatan.length > 0 ? dataPendapatan : [0],
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            hoverBackgroundColor: '#10b981',
                            borderRadius: 6,
                            order: 2
                        },
                        {
                            type: 'bar',
                            label: 'Beban Pengeluaran',
                            data: dataPengeluaran.length > 0 ? dataPengeluaran : [0],
                            backgroundColor: 'rgba(239, 68, 68, 0.85)',
                            hoverBackgroundColor: '#ef4444',
                            borderRadius: 6,
                            order: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                font: { size: 12, weight: 600 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + formatRupiah(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    if (value >= 1000000) return (value / 1000000) + ' Jt';
                                    if (value >= 1000) return (value / 1000) + ' Rb';
                                    return value;
                                },
                                font: { size: 11 }
                            },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }

        // 2. Chart Komposisi Metode Pembayaran (Doughnut)
        const ctxMetode = document.getElementById('chartMetodeBayar');
        if (ctxMetode) {
            const totalTunai = {{ (int) $totalTunai }};
            const totalTransfer = {{ (int) $totalTransfer }};
            const totalEwallet = {{ (int) $totalEwallet }};
            const hasData = (totalTunai + totalTransfer + totalEwallet) > 0;

            new Chart(ctxMetode, {
                type: 'doughnut',
                data: {
                    labels: ['Tunai', 'Transfer VA', 'E-Wallet'],
                    datasets: [{
                        data: hasData ? [totalTunai, totalTransfer, totalEwallet] : [1, 0, 0],
                        backgroundColor: hasData 
                            ? ['#10b981', '#3b82f6', '#f59e0b']
                            : ['#e2e8f0', '#e2e8f0', '#e2e8f0'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                font: { size: 11, weight: 600 }
                            }
                        },
                        tooltip: {
                            enabled: hasData,
                            callbacks: {
                                label: function (context) {
                                    return context.label + ': ' + formatRupiah(context.raw);
                                }
                            }
                        }
                    },
                    cutout: '68%'
                }
            });
        }
    });
</script>
@endsection
