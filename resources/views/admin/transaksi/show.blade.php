@extends('layouts.admin')

@section('content')
<div class="transaksi-detail-page">
    <!-- Actions Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.transaksi.struk', $transaksi->id) }}" target="_blank" class="btn btn-primary px-3 shadow-sm">
                <i class="fa-solid fa-print me-1.5"></i> Cetak Struk
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Informasi Transaksi & Pelanggan -->
        <div class="col-lg-4 col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-circle-info text-primary me-2"></i>Informasi Pesanan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">Kode Transaksi</small>
                        <span class="fw-bold text-primary fs-6">#{{ $transaksi->kode_transaksi }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">Waktu Transaksi</small>
                        <span class="text-dark fw-medium">
                            <i class="fa-regular fa-calendar me-1 text-muted"></i>
                            {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d F Y') }}
                            <span class="text-muted ms-1"><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i') }} WIB</span>
                        </span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">Nama Pemesan</small>
                        <span class="fw-bold text-dark"><i class="fa-solid fa-user text-secondary me-1.5"></i>{{ $transaksi->nama_pemesan }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">Meja / Tipe Pesanan</small>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-bold">
                                <i class="fa-solid {{ $transaksi->meja_id ? 'fa-chair text-warning' : 'fa-cash-register text-info' }} me-1"></i>
                                {{ $transaksi->meja ? ('Meja #' . ($transaksi->meja->nomor_meja ?? $transaksi->meja->id)) : ($transaksi->meja_id ? 'Meja #' . $transaksi->meja_id : 'Kasir / Langsung') }}
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 11px;">
                                {{ $transaksi->tipe_pesanan === 'kasir' ? 'Pesanan Kasir' : 'QR Code Meja' }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">Kasir yang Menangani</small>
                        <span class="text-dark fw-medium"><i class="fa-solid fa-user-gear text-primary me-1.5"></i>{{ $transaksi->kasir->nama ?? 'Sistem' }}</span>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <small class="text-muted d-block fw-semibold text-uppercase mb-1" style="font-size: 11px;">Status Pembayaran</small>
                        @if($transaksi->status_pembayaran === 'lunas')
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold">
                                <i class="fa-solid fa-circle-check me-1"></i>LUNAS ({{ strtoupper($transaksi->metode_label) }})
                            </span>
                        @elseif($transaksi->status_pembayaran === 'batal')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold">
                                <i class="fa-solid fa-circle-xmark me-1"></i>BATAL
                            </span>
                        @elseif($transaksi->status_pembayaran === 'kadaluarsa')
                            <span class="badge bg-dark-subtle text-dark border border-dark-subtle rounded-pill px-3 py-1.5 fw-bold">
                                <i class="fa-solid fa-hourglass-end me-1"></i>KADALUARSA
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold">
                                <i class="fa-solid fa-clock me-1"></i>PENDING ({{ strtoupper($transaksi->metode_label) }})
                            </span>
                        @endif
                    </div>

                    <div>
                        <small class="text-muted d-block fw-semibold text-uppercase mb-1" style="font-size: 11px;">Status Pesanan</small>
                        @if($transaksi->status_pesanan === 'menunggu')
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1.5 fw-bold">
                                <i class="fa-solid fa-hourglass-start me-1"></i>MENUNGGU
                            </span>
                        @elseif($transaksi->status_pesanan === 'diproses')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold">
                                <i class="fa-solid fa-fire-burner me-1"></i>DIPROSES
                            </span>
                        @elseif($transaksi->status_pesanan === 'selesai')
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-3 py-1.5 fw-bold">
                                <i class="fa-solid fa-check-double me-1"></i>SELESAI
                            </span>
                        @elseif($transaksi->status_pesanan === 'dibatalkan')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold">
                                <i class="fa-solid fa-ban me-1"></i>DIBATALKAN
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Rincian Item & Ringkasan Pembayaran -->
        <div class="col-lg-8 col-12">
            <!-- Tabel Item -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Daftar Item Pesanan</h6>
                    <span class="badge bg-primary rounded-pill px-2.5 py-1">{{ count($transaksi->detailTransaksi) }} Item</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 5%;">#</th>
                                    <th>Nama Produk</th>
                                    <th class="text-end" style="width: 20%;">Harga Satuan</th>
                                    <th class="text-center" style="width: 15%;">Jumlah</th>
                                    <th class="text-end pe-4" style="width: 25%;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksi->detailTransaksi as $index => $detail)
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</div>
                                            @if($detail->catatan)
                                                <small class="text-muted"><i class="fa-regular fa-note-sticky me-1"></i>Catatan: {{ $detail->catatan }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end text-muted">
                                            Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-dark-subtle text-dark rounded-pill px-2.5 py-1 fw-bold">{{ $detail->jumlah }}x</span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Tidak ada item dalam transaksi ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Rincian Keuangan / Pembayaran -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-wallet text-primary me-2"></i>Rincian Pembayaran</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="small text-muted fw-bold text-uppercase d-block mb-1">Metode Pembayaran</span>
                                <span class="fw-bold text-dark fs-5">
                                    @if($transaksi->metode_pembayaran === 'payment_gateway')
                                        <i class="fa-solid fa-credit-card text-primary me-1.5"></i>{{ strtoupper($transaksi->metode_label) }}
                                    @else
                                        <i class="fa-solid fa-money-bill-wave text-success me-1.5"></i>TUNAI
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="small text-muted fw-bold text-uppercase d-block mb-1">Total Tagihan</span>
                                <span class="fw-bold text-primary fs-4">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        @if($transaksi->metode_pembayaran === 'payment_gateway')
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="small text-muted fw-bold text-uppercase d-block mb-1">Channel Pembayaran</span>
                                    <span class="fw-bold text-dark fs-5">
                                        {{ $transaksi->metode_label }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="small text-muted fw-bold text-uppercase d-block mb-1">Tautan Pembayaran Snap</span>
                                    @if($transaksi->payment_url)
                                        <a href="{{ $transaksi->payment_url }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 mt-1">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Halaman Pembayaran
                                        </a>
                                    @else
                                        <span class="text-muted fs-6">-</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="small text-muted fw-bold text-uppercase d-block mb-1">Uang Diterima</span>
                                    <span class="fw-bold text-dark fs-5">
                                        {{ $transaksi->uang_diterima !== null ? 'Rp ' . number_format($transaksi->uang_diterima, 0, ',', '.') : '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <span class="small text-muted fw-bold text-uppercase d-block mb-1">Uang Kembalian</span>
                                    <span class="fw-bold text-success fs-5">
                                        {{ $transaksi->uang_kembalian !== null ? 'Rp ' . number_format($transaksi->uang_kembalian, 0, ',', '.') : '-' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
