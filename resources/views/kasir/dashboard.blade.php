@extends('layouts.admin')

@section('content')
<div class="container-fluid px-0">
    <style>
        .stat-card-kasir {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .stat-card-kasir:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }
        .icon-box-kasir {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }
        .bg-card-blue { background-color: #D5E3E6; color: #5B6D92; }
        .bg-card-amber { background-color: #fef3c7; color: #d97706; }
        .bg-card-purple { background-color: #ede9fe; color: #7c3aed; }
        .bg-card-green { background-color: #d1fae5; color: #059669; }

        .progress-bar-custom {
            height: 6px;
            border-radius: 10px;
            background-color: #5B6D92;
        }
        .table-thumb {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .table-thumb-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background-color: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
    </style>

    <!-- Header Ringkasan & Waktu -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-chart-pie text-primary me-2"></i>Dashboard Statistik Kasir
            </h4>
            <p class="text-muted small mb-0">Pantau performa transaksi harian, antrean pelayanan, dan ketersediaan stok produk.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                <i class="fa-regular fa-calendar-check text-primary me-1.5"></i>{{ date('d/m/Y') }}
            </span>
            <a href="{{ route('kasir.antrean') }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-bell-concierge me-1"></i> Antrean Aktif (Live)
            </a>
        </div>
    </div>

    <!-- 1. Empat Kartu Statistik Berwarna -->
    <div class="row g-3 mb-4">
        <!-- (a) Total Transaksi Hari Ini -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-kasir">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-uppercase fw-bold text-muted small">Transaksi Hari Ini</span>
                        <div class="icon-box-kasir bg-card-blue"><i class="fa-solid fa-receipt"></i></div>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">{{ number_format($totalTransaksiHariIni) }} <span class="fs-6 text-muted fw-normal">Trx</span></h3>
                </div>
                <div class="pt-2 border-top mt-2">
                    <small class="text-muted" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i>Jumlah transaksi berstatus pembayaran lunas hari ini
                    </small>
                </div>
            </div>
        </div>

        <!-- (b) Total Antrean -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-kasir">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-uppercase fw-bold text-muted small">Total Antrean</span>
                        <div class="icon-box-kasir bg-card-amber"><i class="fa-solid fa-bell-concierge"></i></div>
                    </div>
                    <h3 class="fw-bold mb-1 text-warning">{{ number_format($totalAntrean) }} <span class="fs-6 text-muted fw-normal">Pesanan</span></h3>
                </div>
                <div class="pt-2 border-top mt-2 d-flex flex-column gap-1">
                    <small class="text-muted" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-circle-info text-warning me-1"></i>Pesanan aktif saat ini (status 'menunggu' atau 'diproses')
                    </small>
                    <a href="{{ route('kasir.antrean') }}" class="btn btn-sm btn-outline-warning py-1 mt-1 text-start d-flex justify-content-between align-items-center">
                        <span style="font-size: 0.8rem;">Buka Antrean</span>
                        <i class="fa-solid fa-arrow-right fs-xs"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- (c) Pesanan Diproses -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-kasir">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-uppercase fw-bold text-muted small">Pesanan Diproses</span>
                        <div class="icon-box-kasir bg-card-purple"><i class="fa-solid fa-fire-burner"></i></div>
                    </div>
                    <h3 class="fw-bold mb-1" style="color: #7c3aed;">{{ number_format($pesananDiproses) }} <span class="fs-6 text-muted fw-normal">Pesanan</span></h3>
                </div>
                <div class="pt-2 border-top mt-2">
                    <small class="text-muted" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-circle-info text-purple me-1"></i>Jumlah pesanan yang sedang diproses kasir saat ini
                    </small>
                </div>
            </div>
        </div>

        <!-- (d) Pendapatan Hari Ini -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card-kasir">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-uppercase fw-bold text-muted small">Pendapatan Hari Ini</span>
                        <div class="icon-box-kasir bg-card-green"><i class="fa-solid fa-wallet"></i></div>
                    </div>
                    <h3 class="fw-bold mb-1 text-success">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                </div>
                <div class="pt-2 border-top mt-2">
                    <small class="text-muted" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-circle-info text-success me-1"></i>Total akumulasi dari transaksi lunas hari ini
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Tabel Transaksi Terbaru (10 Terakhir, Semua Status) -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center border-bottom gap-2">
            <div>
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>Transaksi Terbaru
                    <span class="badge bg-light text-muted border ms-2 fw-normal">10 Transaksi Terakhir</span>
                </h6>
            </div>
            <div>
                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    <i class="fa-solid fa-list-ul me-1"></i> Lihat Semua Riwayat
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="min-width: 100px;">WAKTU</th>
                            <th style="min-width: 150px;">KODE TRANSAKSI</th>
                            <th style="min-width: 140px;">PEMESAN</th>
                            <th style="min-width: 130px;">MEJA / TIPE</th>
                            <th style="min-width: 100px;">METODE</th>
                            <th style="min-width: 120px;">TOTAL</th>
                            <th style="min-width: 220px;">STATUS PEMBAYARAN & PESANAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $t)
                        @php
                            $waktuTrx = \Carbon\Carbon::parse($t->waktu_transaksi ?? $t->created_at);
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-bold text-dark">{{ $waktuTrx->format('H:i') }}</span>
                                <small class="text-muted d-block" style="font-size: 11px;">{{ $waktuTrx->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.transaksi.show', $t->id) }}" class="fw-bold text-primary text-decoration-none">
                                    #{{ $t->kode_transaksi }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $t->nama_pemesan }}</div>
                                @if($t->kasir)
                                    <small class="text-muted" style="font-size: 11px;"><i class="fa-solid fa-user-gear me-1"></i>{{ $t->kasir->nama }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-medium small">
                                    <i class="fa-solid {{ $t->meja_id ? 'fa-chair text-warning' : 'fa-cash-register text-info' }} me-1"></i>{{ $t->meja ? ('Meja #' . ($t->meja->nomor_meja ?? $t->meja->id)) : ($t->meja_id ? 'Meja #' . $t->meja_id : 'Kasir / Langsung') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase">
                                    {{ $t->metode_pembayaran === 'payment_gateway' ? 'Transfer' : 'Tunai' }}
                                </span>
                            </td>
                            <td class="fw-bold text-dark">
                                Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                    <!-- Status Pembayaran -->
                                    @if($t->status_pembayaran == 'lunas')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                            <i class="fa-solid fa-circle-check me-1"></i>Lunas
                                        </span>
                                    @elseif($t->status_pembayaran == 'batal')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                            <i class="fa-solid fa-circle-xmark me-1"></i>Batal
                                        </span>
                                    @elseif($t->status_pembayaran == 'kadaluarsa')
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                            <i class="fa-solid fa-clock-rotate-left me-1"></i>Kadaluarsa
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                            <i class="fa-solid fa-clock me-1"></i>Pending
                                        </span>
                                    @endif

                                    <!-- Status Pesanan -->
                                    @if($t->status_pesanan == 'menunggu')
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                            Menunggu
                                        </span>
                                    @elseif($t->status_pesanan == 'diproses')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                            Diproses
                                        </span>
                                    @elseif($t->status_pesanan == 'selesai')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                            Selesai
                                        </span>
                                    @elseif($t->status_pesanan == 'dibatalkan')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-2 opacity-25 mb-2 d-block"></i>
                                Belum ada data transaksi yang tercatat di sistem.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3 & 4. Produk Terlaris & Produk Hampir Habis -->
    <div class="row g-4">
        <!-- 3. Produk / Wahana Terlaris (30 Hari Terakhir, Lunas) -->
        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-trophy text-warning me-2"></i>Produk / Wahana Terlaris
                        </h6>
                        <small class="text-muted">Top 5 penjualan tertinggi dalam 30 hari terakhir (transaksi lunas)</small>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 60px;" class="text-center">RANK</th>
                                    <th style="width: 50px;">FOTO</th>
                                    <th>NAMA PRODUK</th>
                                    <th>KATEGORI</th>
                                    <th style="min-width: 130px;">TERJUAL</th>
                                    <th class="text-end" style="min-width: 120px;">PENDAPATAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maxTerjual = $produkTerlaris->max('total_terjual') ?: 1;
                                @endphp
                                @forelse($produkTerlaris as $idx => $pt)
                                @php
                                    $persen = ($pt->total_terjual / $maxTerjual) * 100;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        @if($idx === 0)
                                            <span class="badge rounded-circle p-2 bg-warning text-dark"><i class="fa-solid fa-crown"></i></span>
                                        @elseif($idx === 1)
                                            <span class="badge rounded-circle p-2 bg-secondary text-white">#2</span>
                                        @elseif($idx === 2)
                                            <span class="badge rounded-circle p-2 bg-bronze text-white" style="background-color: #cd7f32;">#3</span>
                                        @else
                                            <span class="badge rounded-circle p-2 bg-light text-dark border">#{{ $idx + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pt->produk && $pt->produk->foto_produk)
                                            <img src="{{ asset('storage/' . $pt->produk->foto_produk) }}" alt="{{ $pt->produk->nama_produk }}" class="table-thumb">
                                        @else
                                            <div class="table-thumb-placeholder">
                                                <i class="fa-solid fa-shapes"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $pt->produk->nama_produk ?? 'Produk Dihapus' }}</div>
                                        <small class="text-muted">Rp {{ number_format($pt->produk->harga ?? 0, 0, ',', '.') }} / unit</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $pt->produk->kategori->nama_kategori ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fw-bold text-primary">{{ number_format($pt->total_terjual) }}</span>
                                            <small class="text-muted" style="font-size: 11px;">terjual</small>
                                        </div>
                                        <div class="progress" style="height: 6px; background-color: #e2e8f0; border-radius: 10px;">
                                            <div class="progress-bar progress-bar-custom" role="progressbar" style="width: {{ $persen }}%;" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        Rp {{ number_format($pt->total_pendapatan, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-chart-line fs-3 opacity-25 mb-2 d-block"></i>
                                        Belum ada penjualan lunas dalam 30 hari terakhir.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Produk Hampir Habis (Stok <= 10) -->
        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>Produk Hampir Habis
                        </h6>
                        <small class="text-muted">Stok ≤ {{ $lowStockThreshold }} unit (urut stok tersedikit)</small>
                    </div>
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5">
                        <i class="fa-solid fa-boxes-stacked me-1"></i> Kelola
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>NAMA PRODUK</th>
                                    <th>KATEGORI</th>
                                    <th class="text-center">SISA STOK</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produkHampirHabis as $ph)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $ph->nama_produk }}</div>
                                        <small class="text-muted">Rp {{ number_format($ph->harga, 0, ',', '.') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $ph->kategori->nama_kategori ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($ph->stok == 0)
                                            <span class="badge bg-danger rounded-pill px-2.5 py-1">
                                                <i class="fa-solid fa-circle-xmark me-1"></i>Habis
                                            </span>
                                        @elseif($ph->stok <= 5)
                                            <span class="badge rounded-pill px-2.5 py-1 text-white" style="background-color: #ea580c;">
                                                <i class="fa-solid fa-triangle-exclamation me-1"></i>Sisa {{ $ph->stok }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1">
                                                <i class="fa-solid fa-clock-rotate-left me-1"></i>Sisa {{ $ph->stok }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-circle-check text-success fs-3 opacity-50 mb-2 d-block"></i>
                                        Semua stok produk dalam kondisi aman (> {{ $lowStockThreshold }} unit).
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection