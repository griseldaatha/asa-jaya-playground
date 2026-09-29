@extends('layouts.admin')

@section('content')
<!-- Header Section -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="page-title"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Riwayat Seluruh Transaksi</h3>
        <p class="page-subtitle mb-0">Pantau seluruh catatan transaksi penjualan dan pembayaran playground</p>
    </div>
    
    <!-- Search & Filter Controls -->
    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="position-relative" style="min-width: 220px;">
            <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" id="searchTransaksi" class="form-control ps-5 shadow-sm" placeholder="Cari kode / pemesan..." onkeyup="filterTransaksi()">
        </div>
        
        <select id="filterPembayaran" class="form-select shadow-sm" onchange="filterTransaksi()" style="width: auto;">
            <option value="">-- Status Pembayaran --</option>
            <option value="lunas">Lunas</option>
            <option value="pending">Pending</option>
            <option value="batal">Batal</option>
        </select>
        
        <select id="filterPesanan" class="form-select shadow-sm" onchange="filterTransaksi()" style="width: auto;">
            <option value="">-- Status Pesanan --</option>
            <option value="menunggu">Menunggu</option>
            <option value="diproses">Diproses</option>
            <option value="selesai">Selesai</option>
            <option value="dibatalkan">Dibatalkan</option>
        </select>
    </div>
</div>

<!-- Main Table Card -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 900px;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 14%;">Waktu Transaksi</th>
                        <th style="width: 18%;">Kode & Meja</th>
                        <th style="width: 20%;">Pelanggan & Status</th>
                        <th style="width: 15%;">Kasir</th>
                        <th style="width: 20%;">Detail Item</th>
                        <th class="text-end pe-4" style="width: 13%;">Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $t)
                    <tr class="transaksi-row" 
                        data-search="{{ strtolower($t->kode_transaksi . ' ' . $t->nama_pemesan . ' ' . ($t->meja_id ?? '')) }}"
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
                                <i class="fa-solid fa-chair text-warning me-1"></i>{{ $t->meja ? $t->meja->nama_meja : ($t->meja_id ? 'Meja '.$t->meja_id : 'Meja -') }}
                            </span>
                        </td>

                        <!-- Column 3: Customer & Badges -->
                        <td>
                            <div class="fw-semibold text-dark mb-1"><i class="fa-solid fa-user text-secondary me-1.5"></i>{{ $t->nama_pemesan }}</div>
                            <div class="d-flex flex-wrap gap-1">
                                <!-- Payment Badge -->
                                @if($t->status_pembayaran == 'lunas')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                        <i class="fa-solid fa-circle-check me-1"></i>Lunas ({{ strtoupper($t->metode_pembayaran) }})
                                    </span>
                                @elseif($t->status_pembayaran == 'batal')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                        <i class="fa-solid fa-circle-xmark me-1"></i>Batal
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2 py-1" style="font-size: 11px;">
                                        <i class="fa-solid fa-clock me-1"></i>Pending
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
                        <td class="text-end pe-4 fw-bold text-primary fs-6">
                            Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
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
    </div>
</div>
@endsection

@section('scripts')
<script>
    function filterTransaksi() {
        const query = document.getElementById('searchTransaksi').value.toLowerCase().trim();
        const bayarFilter = document.getElementById('filterPembayaran').value.toLowerCase();
        const pesananFilter = document.getElementById('filterPesanan').value.toLowerCase();

        const rows = document.querySelectorAll('.transaksi-row');
        rows.forEach(row => {
            const searchData = row.getAttribute('data-search') || '';
            const bayarData = row.getAttribute('data-pembayaran') || '';
            const pesananData = row.getAttribute('data-pesanan') || '';

            const matchesQuery = !query || searchData.includes(query);
            const matchesBayar = !bayarFilter || bayarData === bayarFilter;
            const matchesPesanan = !pesananFilter || pesananData === pesananFilter;

            if (matchesQuery && matchesBayar && matchesPesanan) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection