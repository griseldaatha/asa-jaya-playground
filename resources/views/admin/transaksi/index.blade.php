@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Riwayat Seluruh Transaksi</h3>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Waktu</th>
                        <th>Info Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Kasir</th>
                        <th>Detail Item</th>
                        <th class="text-end pe-4">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $t)
                    <tr>
                        <td class="ps-3 text-muted small">{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="fw-bold text-primary">#{{ $t->kode_transaksi }}</div>
                            <small class="text-muted"><i class="fa-solid fa-chair"></i> Meja: {{ $t->meja_id ?? '-' }}</small>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $t->nama_pemesan }}</div>
                            @if($t->status_pembayaran == 'lunas')
                                <span class="badge bg-success">Lunas ({{ $t->metode_pembayaran }})</span>
                            @elseif($t->status_pembayaran == 'batal')
                                <span class="badge bg-danger">Batal</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td><i class="fa-solid fa-user-tag text-muted"></i> {{ $t->kasir->nama ?? '-' }}</td>
                        <td>
                            <ul class="list-unstyled mb-0 small text-muted">
                                @foreach($t->detailTransaksi as $dt)
                                    <li>&bull; {{ $dt->jumlah }}x {{ $dt->produk->nama_produk ?? 'Dihapus' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-end pe-4 fw-bold">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection