@extends('layouts.admin')

@section('content')
<style>
    /* Styling Pagination Modern & Elegan */
    .pagination {
        margin-bottom: 0;
        gap: 4px;
    }
    .page-item .page-link {
        color: #475569;
        border-radius: 8px !important;
        border: 1px solid #e2e8f0;
        padding: 6px 12px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }
    .page-item .page-link:hover {
        background-color: #FAF5EF;
        color: #5B6D92;
        border-color: #D5E3E6;
    }
    .page-item.active .page-link {
        background-color: #5B6D92 !important;
        border-color: #5B6D92 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(91, 109, 146, 0.25);
    }
    .page-item.disabled .page-link {
        color: #94a3b8;
        background-color: #f8fafc;
        border-color: #f1f5f9;
    }
</style>

<!-- Filter Controls Section -->
<form method="GET" action="{{ route('transaksi.index') }}" class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h5 class="fw-bold text-dark mb-0">Riwayat Transaksi</h5>
        <small class="text-muted">Total {{ number_format($transaksi->total()) }} transaksi tercatat di sistem</small>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="position-relative" style="min-width: 220px;">
            <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control ps-5 shadow-sm rounded-3" placeholder="Cari kode / pemesan...">
        </div>
        
        <select name="pembayaran" class="form-select shadow-sm rounded-3" style="width: auto;" onchange="this.form.submit()">
            <option value="">Semua Pembayaran</option>
            <option value="lunas" {{ request('pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="pending" {{ request('pembayaran') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="batal" {{ request('pembayaran') == 'batal' ? 'selected' : '' }}>Batal</option>
        </select>
        
        <select name="pesanan" class="form-select shadow-sm rounded-3" style="width: auto;" onchange="this.form.submit()">
            <option value="">Semua Status Pesanan</option>
            <option value="menunggu" {{ request('pesanan') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="diproses" {{ request('pesanan') == 'diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="selesai" {{ request('pesanan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('pesanan') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <button type="submit" class="btn text-white shadow-sm px-3 rounded-3 fw-semibold" style="background-color: #5B6D92; border-color: #5B6D92;">
            <i class="fa-solid fa-filter me-1"></i> Cari
        </button>

        @if(request('search') || request('pembayaran') || request('pesanan'))
            <a href="{{ route('transaksi.index') }}" class="btn btn-light border shadow-sm px-2.5 rounded-3 text-secondary" title="Reset Filter">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        @endif
    </div>
</form>

<!-- Main Table Card -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 13%;">Waktu Transaksi</th>
                        <th style="width: 16%;">Kode & Meja</th>
                        <th style="width: 18%;">Pelanggan & Status</th>
                        <th style="width: 13%;">Kasir</th>
                        <th style="width: 18%;">Detail Item</th>
                        <th class="text-end" style="width: 11%;">Total Bayar</th>
                        <th class="text-center pe-4" style="width: 11%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                    <tr class="transaksi-row" 
                        data-search="{{ strtolower($t->kode_transaksi . ' ' . $t->nama_pemesan . ' ' . ($t->meja ? ('meja ' . ($t->meja->nomor_meja ?? $t->meja->id)) : ($t->meja_id ? 'meja ' . $t->meja_id : 'kasir'))) }}"
                        data-pembayaran="{{ strtolower($t->status_pembayaran) }}"
                        data-pesanan="{{ strtolower($t->status_pesanan) }}">
                        
                        <!-- Column 1: Time -->
                        <td class="ps-4 text-muted small">
                            <div class="fw-medium text-dark">{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</div>
                            <div class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($t->created_at)->format('H:i') }} WIB</div>
                        </td>

                        <!-- Column 2: Code & Table -->
                        <td>
                            <div class="fw-bold text-primary mb-1">#{{ $t->kode_transaksi }}</div>
                            <span class="badge bg-light text-dark border fw-medium small">
                                <i class="fa-solid {{ $t->meja_id ? 'fa-chair text-warning' : 'fa-cash-register text-info' }} me-1"></i>{{ $t->meja ? ('Meja #' . ($t->meja->nomor_meja ?? $t->meja->id)) : ($t->meja_id ? 'Meja #' . $t->meja_id : 'Kasir / Langsung') }}
                            </span>
                        </td>

                        <!-- Column 3: Customer & Badges -->
                        <td>
                            <div class="fw-semibold text-dark mb-1"><i class="fa-solid fa-user text-secondary me-1.5"></i>{{ $t->nama_pemesan }}</div>
                            <div class="d-flex flex-wrap gap-1">
                                <!-- Payment Badge -->
                                @if($t->status_pembayaran == 'lunas')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                        <i class="fa-solid fa-circle-check me-1"></i>Lunas ({{ $t->metode_pembayaran === 'payment_gateway' ? 'QRIS' : 'Tunai' }})
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
                                        <i class="fa-solid fa-clock me-1"></i>Pending ({{ $t->metode_pembayaran === 'payment_gateway' ? 'QRIS' : 'Tunai' }})
                                    </span>
                                @endif

                                <!-- Order Status Badge -->
                                @if($t->status_pesanan == 'menunggu')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                        Menunggu
                                    </span>
                                @elseif($t->status_pesanan == 'diproses')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                        Diproses
                                    </span>
                                @elseif($t->status_pesanan == 'selesai')
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                        Selesai
                                    </span>
                                @elseif($t->status_pesanan == 'dibatalkan')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                        Dibatalkan
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Column 4: Cashier -->
                        <td class="text-secondary small">
                            <i class="fa-solid fa-user-gear text-primary me-1"></i>
                            <span class="fw-medium">{{ $t->kasir->nama ?? 'Sistem' }}</span>
                        </td>

                        <!-- Column 5: Item Details -->
                        <td>
                            <ul class="list-unstyled mb-0 small">
                                @foreach($t->detailTransaksi as $dt)
                                    <li class="d-flex align-items-center justify-content-between mb-0.5">
                                        <span class="text-dark text-truncate me-2" style="max-width: 140px;" title="{{ $dt->produk->nama_produk ?? 'Dihapus' }}">
                                            &bull; {{ $dt->produk->nama_produk ?? 'Dihapus' }}
                                        </span>
                                        <span class="badge bg-light text-dark border fw-bold" style="font-size: 10px;">{{ $dt->jumlah }}x</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>

                        <!-- Column 6: Total -->
                        <td class="text-end fw-bold text-primary fs-6">
                            Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                        </td>

                        <!-- Column 7: Aksi -->
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <a href="{{ route('admin.transaksi.show', $t->id) }}" class="btn btn-sm btn-outline-primary px-2.5" title="Lihat Detail Transaksi">
                                    <i class="fa-solid fa-eye me-1"></i>Detail
                                </a>
                                @if($t->status_pembayaran === 'lunas')
                                    <a href="{{ route('admin.transaksi.struk', $t->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary px-2" title="Cetak Struk">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">Belum Ada Riwayat Transaksi</h5>
                                <p class="text-muted small mb-0">Semua transaksi yang dilakukan kasir akan tercatat di halaman ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination -->
        <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white rounded-bottom">
            <small class="text-muted fw-medium">
                Menampilkan <span class="fw-bold text-dark">{{ $transaksi->firstItem() ?? 0 }}</span> - <span class="fw-bold text-dark">{{ $transaksi->lastItem() ?? 0 }}</span> dari <span class="fw-bold text-dark">{{ number_format($transaksi->total()) }}</span> transaksi
            </small>
            @if($transaksi->hasPages())
                <div>
                    {{ $transaksi->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection