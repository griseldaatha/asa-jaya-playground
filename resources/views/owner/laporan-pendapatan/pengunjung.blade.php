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
        <a href="{{ route('owner.pendapatan.pengunjung.cetak', ['dari' => $dari, 'sampai' => $sampai]) }}" target="_blank" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
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
        <form method="GET" action="{{ route('owner.pendapatan.pengunjung') }}" class="filter-bar-sleek" id="filterForm">
            <div class="filter-inputs-group">
                <div class="date-range-box">
                    <i class="fa-regular fa-calendar filter-icon"></i>
                    <input type="date" class="sleek-date-input" id="inputDari" name="dari" value="{{ $dari }}" required title="Dari Tanggal">
                    <span class="range-arrow">&rarr;</span>
                    <input type="date" class="sleek-date-input" id="inputSampai" name="sampai" value="{{ $sampai }}" required title="Sampai Tanggal">
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
            </div>
        </div>
    </div>

    <!-- 4 Kartu Ringkasan Finansial & Pengunjung -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Pengunjung -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Total Pengunjung</span>
                    <div class="icon-box bg-icy"><i class="fa-solid fa-users"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($countTransaksi) }} <span class="fs-6 text-muted fw-normal">Orang</span></h3>
                <small class="text-muted d-block">
                    Rata-rata: <strong>Rp {{ number_format($rataRataPerTransaksi, 0, ',', '.') }}</strong> / orang
                </small>
            </div>
        </div>

        <!-- Card 2: Pendapatan Kotor -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Pendapatan Kotor</span>
                    <div class="icon-box bg-soft-green"><i class="fa-solid fa-wallet"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <small class="text-muted d-block">
                    Tunai: <strong>Rp {{ number_format($totalTunai, 0, ',', '.') }}</strong>
                </small>
                <small class="text-muted d-block">
                    Non-Tunai: <strong>Rp {{ number_format($totalTransfer + $totalEwallet, 0, ',', '.') }}</strong>
                </small>
            </div>
        </div>

        <!-- Card 3: Beban Pengeluaran -->
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

        <!-- Card 4: Laba Bersih Usaha -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro" style="border-left: 4px solid {{ $labaBersih >= 0 ? '#10b981' : '#ef4444' }};">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Laba Bersih Usaha</span>
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
    </div>

    <!-- TABEL UTAMA: Rekap Finansial & Pengunjung Harian -->
    <div class="report-card">
        <div class="report-card-header d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fa-solid fa-calendar-day text-primary me-2"></i>Rekap Finansial & Pengunjung Harian
            </h6>
            <span class="badge bg-light text-muted border">{{ count($rekapHarian) }} Hari Aktif</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-laporan mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th style="width: 120px;">Tanggal</th>
                        <th class="text-center" style="width: 140px;">
                            <i class="fa-solid fa-users text-primary me-1"></i>Pengunjung
                        </th>
                        <th class="text-end" style="width: 120px;">Tunai</th>
                        <th class="text-end" style="width: 120px;">Transfer</th>
                        <th class="text-end" style="width: 120px;">E-Wallet</th>
                        <th class="text-end" style="width: 140px;">Pendapatan Kotor</th>
                        <th class="text-end" style="width: 130px;">Pengeluaran</th>
                        <th class="text-end" style="width: 140px;">Laba Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapHarian as $index => $row)
                        <tr>
                            <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark">{{ $row['tanggal'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-semibold">
                                    <i class="fa-solid fa-user me-1 text-primary"></i>{{ number_format($row['jml_transaksi']) }} orang
                                </span>
                            </td>
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
                            <td colspan="9" class="text-center py-5">
                                <div class="empty-state-icon mb-2">
                                    <i class="fa-solid fa-calendar-xmark text-muted"></i>
                                </div>
                                <h6 class="text-dark fw-bold mb-1">Tidak Ada Data Pengunjung</h6>
                                <p class="text-muted small mb-0">Belum ada catatan transaksi pengunjung atau finansial pada periode tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($rekapHarian) > 0)
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-center">TOTAL</th>
                            <th class="text-center fw-bold">{{ number_format($countTransaksi) }} Pengunjung</th>
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
</script>
@endsection
