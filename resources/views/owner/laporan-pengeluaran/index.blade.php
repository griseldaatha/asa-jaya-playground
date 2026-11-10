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
        .bg-soft-red { background-color: #fee2e2; color: #dc2626; }
        .bg-soft-orange { background-color: #ffedd5; color: #ea580c; }
        .bg-soft-green { background-color: #d1fae5; color: #059669; }
        .bg-soft-blue { background-color: #dbeafe; color: #2563eb; }
        .bg-icy { background-color: #D5E3E6; color: #5B6D92; }
        
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
    <div class="d-flex justify-content-end align-items-center mb-4 gap-2">
        @if(Route::has('admin.pengeluaran.index'))
            <a href="{{ route('admin.pengeluaran.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>Catat Pengeluaran</span>
            </a>
        @endif
        <a href="{{ route('owner.pengeluaran.cetak', array_filter(['dari' => $dari, 'sampai' => $sampai, 'kategori_id' => $kategori_id])) }}" target="_blank" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="fa-solid fa-print"></i>
            <span>Cetak / Simpan PDF</span>
        </a>
    </div>

    <!-- Alert Flash Error -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Bar Filter -->
    <div class="report-card p-3 p-md-4 mb-4">
        <form method="GET" action="{{ route('owner.pengeluaran.index') }}" class="row g-3 align-items-end" id="filterForm">
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
            <div class="col-12 col-sm-6 col-md-3">
                <label for="selectKategori" class="form-label small fw-bold text-muted mb-1">
                    <i class="fa-solid fa-tags me-1"></i>Kategori
                </label>
                <select class="form-select form-select-sm" id="selectKategori" name="kategori_id">
                    <option value="">Semua Kategori</option>
                    @foreach($semuaKategori as $kat)
                        <option value="{{ $kat->id }}" {{ $kategori_id == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fa-solid fa-filter me-1"></i>Terapkan
                </button>
            </div>
            <div class="col-12 mt-3 pt-2 border-top">
                <div class="d-flex flex-wrap gap-1 justify-content-md-end align-items-center">
                    <span class="small text-muted me-2">Pilihan Cepat:</span>
                    @php
                        $todayStr = now()->toDateString();
                        $sevenDaysAgoStr = now()->subDays(6)->toDateString();
                        $startThisMonthStr = now()->startOfMonth()->toDateString();
                        $startLastMonthStr = now()->subMonthNoOverflow()->startOfMonth()->toDateString();
                        $endLastMonthStr = now()->subMonthNoOverflow()->endOfMonth()->toDateString();
                    @endphp
                    <a href="{{ route('owner.pengeluaran.index', array_filter(['dari' => $todayStr, 'sampai' => $todayStr, 'kategori_id' => $kategori_id])) }}"
                       class="btn btn-sm quick-btn {{ ($dari === $todayStr && $sampai === $todayStr) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('owner.pengeluaran.index', array_filter(['dari' => $sevenDaysAgoStr, 'sampai' => $todayStr, 'kategori_id' => $kategori_id])) }}"
                       class="btn btn-sm quick-btn {{ ($dari === $sevenDaysAgoStr && $sampai === $todayStr) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        7 Hari
                    </a>
                    <a href="{{ route('owner.pengeluaran.index', array_filter(['dari' => $startThisMonthStr, 'sampai' => $todayStr, 'kategori_id' => $kategori_id])) }}"
                       class="btn btn-sm quick-btn {{ ($dari === $startThisMonthStr && $sampai === $todayStr) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Bulan Ini
                    </a>
                    <a href="{{ route('owner.pengeluaran.index', array_filter(['dari' => $startLastMonthStr, 'sampai' => $endLastMonthStr, 'kategori_id' => $kategori_id])) }}"
                       class="btn btn-sm quick-btn {{ ($dari === $startLastMonthStr && $sampai === $endLastMonthStr) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Bulan Lalu
                    </a>
                </div>
            </div>
        </form>
        <div class="mt-2 text-muted small">
            <i class="fa-solid fa-circle-info text-primary me-1"></i>
            Menampilkan periode: <strong>{{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</strong>
            @if($namaKategoriAktif)
                | Kategori: <strong class="text-dark">{{ $namaKategoriAktif }}</strong>
            @endif
        </div>
    </div>

    <!-- 4 Ringkasan Finansial Pengeluaran -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Pengeluaran -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Total Pengeluaran</span>
                    <div class="icon-box bg-soft-red"><i class="fa-solid fa-money-bill-wave"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                <small class="text-muted"><i class="fa-solid fa-circle-minus text-danger me-1"></i>Akumulasi Seluruh Biaya</small>
            </div>
        </div>

        <!-- Card 2: Rasio Terhadap Pendapatan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Rasio Beban / Omzet</span>
                    <div class="icon-box {{ $rasioPengeluaran > 70 ? 'bg-soft-red' : ($rasioPengeluaran > 40 ? 'bg-soft-orange' : 'bg-soft-green') }}">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 {{ $rasioPengeluaran > 70 ? 'text-danger' : ($rasioPengeluaran > 40 ? 'text-warning' : 'text-success') }}">
                    {{ $rasioPengeluaran }}%
                </h3>
                <small class="text-muted text-truncate d-block">
                    Dari Pendapatan: <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                </small>
            </div>
        </div>

        <!-- Card 3: Jumlah Catatan & Rata-rata -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Frekuensi Catatan</span>
                    <div class="icon-box bg-icy"><i class="fa-solid fa-file-invoice"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($countPengeluaran) }} <span class="fs-6 text-muted fw-normal">Item</span></h3>
                <small class="text-muted">Rata-rata: <strong>Rp {{ number_format($rataRataPengeluaran, 0, ',', '.') }}</strong> / item</small>
            </div>
        </div>

        <!-- Card 4: Pengeluaran Terbesar -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Biaya Terbesar</span>
                    <div class="icon-box bg-soft-orange"><i class="fa-solid fa-arrow-trend-up"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-danger">
                    @if($pengeluaranTerbesar['nominal'] > 0)
                        Rp {{ number_format($pengeluaranTerbesar['nominal'], 0, ',', '.') }}
                    @else
                        Rp 0
                    @endif
                </h3>
                <small class="text-muted text-truncate d-block" title="{{ $pengeluaranTerbesar['nama'] }}">
                    @if($pengeluaranTerbesar['nominal'] > 0)
                        <span class="badge bg-light text-dark border me-1">{{ $pengeluaranTerbesar['kategori'] }}</span>
                        {{ $pengeluaranTerbesar['nama'] }}
                    @else
                        Tidak ada data
                    @endif
                </small>
            </div>
        </div>
    </div>

    <!-- SECTION GRAFIK VISUAL (Chart.js) -->
    <div class="row g-4 mb-4">
        <!-- Grafik 1: Tren Harian Pengeluaran -->
        <div class="col-12 col-lg-7">
            <div class="report-card mb-0 h-100">
                <div class="report-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-chart-line text-primary me-2"></i>Tren Pengeluaran Harian
                        </h6>
                        <small class="text-muted">Fluktuasi beban pengeluaran berdasarkan tanggal pencatatan</small>
                    </div>
                    <span class="badge bg-light text-primary border px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                        Grafik Interaktif
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <div class="chart-container-wrapper">
                        <canvas id="chartTrenPengeluaran"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik 2: Komposisi Per Kategori Biaya -->
        <div class="col-12 col-lg-5">
            <div class="report-card mb-0 h-100">
                <div class="report-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-chart-pie text-primary me-2"></i>Distribusi Kategori Biaya
                        </h6>
                        <small class="text-muted">Proporsi alokasi pengeluaran</small>
                    </div>
                    <span class="badge bg-light text-muted border">{{ count($rekapKategori) }} Kategori</span>
                </div>
                <div class="p-3 p-md-4 d-flex flex-column justify-content-between">
                    <div class="chart-container-wrapper" style="height: 230px;">
                        <canvas id="chartKategoriPengeluaran"></canvas>
                    </div>
                    @if(count($rekapKategori) > 0)
                        <div class="mt-3 pt-3 border-top small">
                            <div class="d-flex justify-content-between align-items-center text-muted mb-1" style="font-size: 0.75rem;">
                                <span>Pos Terbesar:</span>
                                <strong class="text-danger">{{ $rekapKategori->first()['kategori'] }} ({{ $rekapKategori->first()['persentase'] }}%)</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL 1: Rekap Per Kategori -->
    <div class="report-card">
        <div class="report-card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-chart-pie text-primary me-2"></i>Rekap Per Kategori
                </h6>
                <small class="text-muted">Distribusi pengeluaran berdasarkan pos kategori biaya</small>
            </div>
            <span class="badge bg-light text-muted border">{{ count($rekapKategori) }} Kategori</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-laporan mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th class="text-center" style="width: 170px;">Jumlah Catatan</th>
                        <th class="text-end" style="width: 220px;">Total</th>
                        <th class="text-end" style="width: 160px;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapKategori as $row)
                        <tr>
                            <td class="fw-semibold text-dark">
                                <span class="badge bg-light text-dark border me-2">{{ $row['kategori'] }}</span>
                            </td>
                            <td class="text-center">{{ number_format($row['jml_catatan']) }}</td>
                            <td class="text-end fw-semibold text-danger">Rp {{ number_format($row['total_nominal'], 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-dark">{{ number_format($row['persentase'], 1) }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="empty-state-icon mb-2">
                                    <i class="fa-solid fa-tags text-muted"></i>
                                </div>
                                <h6 class="text-dark fw-bold mb-1">Tidak Ada Data Kategori</h6>
                                <p class="text-muted small mb-0">Belum ada pengeluaran yang tercatat pada filter ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($rekapKategori) > 0)
                    <tfoot>
                        <tr>
                            <th>TOTAL</th>
                            <th class="text-center">{{ number_format($countPengeluaran) }}</th>
                            <th class="text-end text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
                            <th class="text-end">100.0%</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- TABEL 2: Rincian Pengeluaran -->
    <div class="report-card">
        <div class="report-card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-list-ul text-primary me-2"></i>Rincian Catatan Pengeluaran
                </h6>
                <small class="text-muted">Daftar pencatatan pengeluaran operasional pada periode terpilih</small>
            </div>
            <span class="badge bg-light text-muted border">Total: {{ number_format($countPengeluaran) }} Catatan</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-laporan mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th style="width: 130px;">Tanggal</th>
                        <th style="width: 170px;">Kategori</th>
                        <th>Keterangan</th>
                        <th style="width: 160px;">Dicatat Oleh</th>
                        <th class="text-end" style="width: 160px;">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rincianPengeluaran as $index => $peng)
                        <tr>
                            <td>{{ $rincianPengeluaran->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($peng->tanggal_pengeluaran)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $peng->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $peng->nama_pengeluaran }}</div>
                                @if(!empty($peng->deskripsi))
                                    <div class="small text-muted">{{ $peng->deskripsi }}</div>
                                @endif
                            </td>
                            <td>{{ $peng->user->nama ?? $peng->user->username ?? '-' }}</td>
                            <td class="text-end fw-semibold text-danger">Rp {{ number_format($peng->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state-icon mb-2">
                                    <i class="fa-solid fa-file-invoice text-muted"></i>
                                </div>
                                <h6 class="text-dark fw-bold mb-1">Tidak Ada Catatan Pengeluaran</h6>
                                <p class="text-muted small mb-0">Belum ada pengeluaran yang dicatat pada periode tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($countPengeluaran > 0)
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">TOTAL (Seluruh Periode: {{ number_format($countPengeluaran) }} Catatan)</th>
                            <th class="text-end text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        @if($rincianPengeluaran->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan {{ $rincianPengeluaran->firstItem() }} - {{ $rincianPengeluaran->lastItem() }} dari {{ $rincianPengeluaran->total() }} baris
                </small>
                <div>
                    {{ $rincianPengeluaran->links('pagination::bootstrap-5') }}
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

        // 1. Chart Tren Harian Pengeluaran (Bar / Area Chart)
        const ctxTren = document.getElementById('chartTrenPengeluaran');
        if (ctxTren) {
            const labelsHarian = @json($chartHarianDates);
            const dataHarian = @json($chartHarianData);

            new Chart(ctxTren, {
                type: 'bar',
                data: {
                    labels: labelsHarian.length > 0 ? labelsHarian : ['Tidak Ada Data'],
                    datasets: [{
                        label: 'Pengeluaran Harian',
                        data: dataHarian.length > 0 ? dataHarian : [0],
                        backgroundColor: 'rgba(239, 68, 68, 0.85)',
                        hoverBackgroundColor: '#ef4444',
                        borderRadius: 6,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Pengeluaran: ' + formatRupiah(context.raw);
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

        // 2. Chart Distribusi Kategori (Doughnut)
        const ctxKat = document.getElementById('chartKategoriPengeluaran');
        if (ctxKat) {
            const labelsKat = @json($chartKategoriLabels);
            const dataKat = @json($chartKategoriData);
            const hasData = dataKat.length > 0;

            const paletteColors = [
                '#ef4444', '#f59e0b', '#3b82f6', '#10b981', 
                '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'
            ];

            new Chart(ctxKat, {
                type: 'doughnut',
                data: {
                    labels: hasData ? labelsKat : ['Tidak Ada Data'],
                    datasets: [{
                        data: hasData ? dataKat : [1],
                        backgroundColor: hasData 
                            ? paletteColors.slice(0, dataKat.length)
                            : ['#e2e8f0'],
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
