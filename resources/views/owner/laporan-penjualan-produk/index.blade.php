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
        .bg-soft-green { background-color: #d1fae5; color: #059669; }
        .bg-soft-blue { background-color: #dbeafe; color: #2563eb; }
        .bg-soft-gold { background-color: #fef3c7; color: #d97706; }
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
        .search-input-box {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .sleek-text-input {
            background: transparent !important;
            border: none !important;
            color: #ffffff !important;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 2px 4px;
            width: 130px;
            outline: none !important;
            box-shadow: none !important;
        }
        .sleek-text-input::placeholder {
            color: #94a3b8;
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
        .product-thumb-sm {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }
        .product-thumb-placeholder-sm {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid #e0e7ff;
            font-size: 0.9rem;
        }
        .rank-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .rank-1 { background-color: #fef08a; color: #854d0e; }
        .rank-2 { background-color: #f1f5f9; color: #475569; }
        .rank-3 { background-color: #fed7aa; color: #9a3412; }
        .rank-other { background-color: #f8fafc; color: #64748b; }
    </style>

    <!-- Header Action Button -->
    <div class="d-flex justify-content-end align-items-center mb-4">
        <a href="{{ route('owner.produk-laporan.cetak', array_filter(['dari' => $dari, 'sampai' => $sampai, 'kategori_id' => $kategori_id, 'search' => $search])) }}" target="_blank" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
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

    <!-- Bar Filter Ramping Modern (Format Pill Sesuai Mockup) -->
    <div class="filter-wrapper-pro">
        <form method="GET" action="{{ route('owner.produk-laporan.index') }}" class="filter-bar-sleek" id="filterForm">
            <div class="filter-inputs-group">
                <div class="date-range-box">
                    <i class="fa-regular fa-calendar filter-icon"></i>
                    <input type="date" class="sleek-date-input" id="inputDari" name="dari" value="{{ $dari }}" required title="Dari Tanggal">
                    <span class="range-arrow">&rarr;</span>
                    <input type="date" class="sleek-date-input" id="inputSampai" name="sampai" value="{{ $sampai }}" required title="Sampai Tanggal">
                </div>

                <div class="filter-divider"></div>

                <div class="method-select-box">
                    <i class="fa-solid fa-tags filter-icon"></i>
                    <select name="kategori_id" id="inputKategori" class="sleek-select" title="Pilih Kategori" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($semuaKategori as $kat)
                            <option value="{{ $kat->id }}" {{ $kategori_id == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down select-chevron"></i>
                </div>

                <div class="filter-divider"></div>

                <div class="search-input-box">
                    <i class="fa-solid fa-magnifying-glass filter-icon"></i>
                    <input type="text" name="search" id="inputSearch" class="sleek-text-input" placeholder="Cari produk..." value="{{ $search }}">
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

                <!-- Tag Kategori -->
                <div class="filter-tag-chip" title="Hapus filter kategori (kembali ke Semua Kategori)" onclick="resetKategoriFilter()">
                    <span>Kategori: {{ $namaKategoriAktif ?: 'Semua Kategori' }}</span>
                    <span class="tag-close-btn">&times;</span>
                </div>

                <!-- Tag Pencarian Produk jika ada -->
                @if($search)
                    <div class="filter-tag-chip" title="Hapus filter pencarian" onclick="resetSearchFilter()">
                        <span>Produk: "{{ $search }}"</span>
                        <span class="tag-close-btn">&times;</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 4 Ringkasan Finansial Penjualan Produk -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Unit Terjual -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Total Unit Terjual</span>
                    <div class="icon-box bg-icy"><i class="fa-solid fa-box-open"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($totalQty) }} <span class="fs-6 text-muted fw-normal">Unit</span></h3>
                <small class="text-muted"><i class="fa-solid fa-check text-success me-1"></i>Dari {{ $totalItemJenis }} jenis produk laku</small>
            </div>
        </div>

        <!-- Card 2: Total Omzet Penjualan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Total Omzet Produk</span>
                    <div class="icon-box bg-soft-green"><i class="fa-solid fa-wallet"></i></div>
                </div>
                <h3 class="fw-bold mb-1 text-success">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h3>
                <small class="text-muted"><i class="fa-solid fa-arrow-trend-up text-success me-1"></i>Akumulasi Subtotal Penjualan</small>
            </div>
        </div>

        <!-- Card 3: Produk Terlaris (Qty) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Produk Terlaris</span>
                    <div class="icon-box bg-soft-gold"><i class="fa-solid fa-fire"></i></div>
                </div>
                <h4 class="fw-bold mb-1 text-dark text-truncate" title="{{ $topProdukQty->nama_produk ?? '-' }}">
                    {{ $topProdukQty->nama_produk ?? '-' }}
                </h4>
                <small class="text-muted d-block text-truncate">
                    @if($topProdukQty)
                        <strong class="text-primary">{{ number_format($topProdukQty->total_qty) }} unit</strong> terjual ({{ $topProdukQty->persentase_omzet }}% omzet)
                    @else
                        Belum ada data
                    @endif
                </small>
            </div>
        </div>

        <!-- Card 4: Top Kontributor Omzet -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-pro">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-muted small">Top Nilai Omzet</span>
                    <div class="icon-box bg-soft-blue"><i class="fa-solid fa-sack-dollar"></i></div>
                </div>
                <h4 class="fw-bold mb-1 text-primary text-truncate" title="{{ $topProdukOmzet ? 'Rp ' . number_format($topProdukOmzet->total_omzet, 0, ',', '.') : '-' }}">
                    @if($topProdukOmzet)
                        Rp {{ number_format($topProdukOmzet->total_omzet, 0, ',', '.') }}
                    @else
                        Rp 0
                    @endif
                </h4>
                <small class="text-muted d-block text-truncate">
                    @if($topProdukOmzet)
                        {{ $topProdukOmzet->nama_produk }} ({{ number_format($topProdukOmzet->total_qty) }}x)
                    @else
                        Belum ada data
                    @endif
                </small>
            </div>
        </div>
    </div>

    <!-- TABEL UTAMA: Rekap Penjualan Per Produk -->
    <div class="report-card">
        <div class="report-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fa-solid fa-table-list text-primary me-2"></i>Daftar Penjualan Per Produk
            </h6>
            <span class="badge bg-light text-muted border">Total: {{ $totalItemJenis }} Jenis Produk</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-laporan mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">#</th>
                        <th>Produk</th>
                        <th style="width: 160px;">Kategori</th>
                        <th class="text-end" style="width: 140px;">Harga Satuan</th>
                        <th class="text-center" style="width: 130px;">Terjual</th>
                        <th class="text-end" style="width: 160px;">Total Omzet</th>
                        <th class="text-end" style="width: 130px;">Kontribusi</th>
                        <th class="text-center" style="width: 110px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginatedItems as $index => $row)
                        @php
                            $rank = $paginatedItems->firstItem() + $index;
                            $rankClass = match($rank) {
                                1 => 'rank-1',
                                2 => 'rank-2',
                                3 => 'rank-3',
                                default => 'rank-other',
                            };
                        @endphp
                        <tr>
                            <td class="text-center">
                                <span class="rank-circle {{ $rankClass }}">{{ $rank }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    @if($row->foto_produk)
                                        <img src="{{ asset('storage/' . $row->foto_produk) }}" alt="{{ $row->nama_produk }}" class="product-thumb-sm">
                                    @else
                                        <div class="product-thumb-placeholder-sm">
                                            <i class="fa-solid fa-box"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark">{{ $row->nama_produk }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">Stok saat ini: {{ $row->stok }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $row->nama_kategori }}
                                </span>
                            </td>
                            <td class="text-end text-dark">
                                Rp {{ number_format($row->harga_katalog, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1.5" style="font-size: 0.85rem;">
                                    {{ number_format($row->total_qty) }}
                                </span>
                            </td>
                            <td class="text-end fw-bold text-success">
                                Rp {{ number_format($row->total_omzet, 0, ',', '.') }}
                            </td>
                            <td class="text-end fw-semibold text-dark">
                                {{ $row->persentase_omzet }}%
                            </td>
                            <td class="text-center">
                                @if($row->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;">Aktif</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.72rem;">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state-icon mb-2">
                                    <i class="fa-solid fa-box-open text-muted"></i>
                                </div>
                                <h6 class="text-dark fw-bold mb-1">Tidak Ada Data Penjualan Produk</h6>
                                <p class="text-muted small mb-0">Belum ada transaksi produk berstatus lunas pada filter tanggal atau kriteria ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($paginatedItems->count() > 0)
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">TOTAL KESELURUHAN ({{ $totalItemJenis }} Produk)</th>
                            <th class="text-center text-primary">{{ number_format($totalQty) }} Unit</th>
                            <th class="text-end text-success">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</th>
                            <th class="text-end">100.0%</th>
                            <th></th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        @if($paginatedItems->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Menampilkan {{ $paginatedItems->firstItem() }} - {{ $paginatedItems->lastItem() }} dari {{ $paginatedItems->total() }} produk
                </small>
                <div>
                    {{ $paginatedItems->links('pagination::bootstrap-5') }}
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

    function resetKategoriFilter() {
        document.getElementById('inputKategori').value = '';
        document.getElementById('filterForm').submit();
    }

    function resetSearchFilter() {
        document.getElementById('inputSearch').value = '';
        document.getElementById('filterForm').submit();
    }
</script>
@endsection
