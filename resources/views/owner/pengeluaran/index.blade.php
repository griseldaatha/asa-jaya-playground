@extends('layouts.admin')

@section('content')
<!-- Header Section -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="page-title"><i class="fa-solid fa-money-bill-trend-up text-primary me-2"></i>Buku Pengeluaran Harian</h3>
        <p class="page-subtitle mb-0">Catat dan pantau seluruh biaya operasional playground</p>
    </div>
    <button class="btn btn-primary shadow-sm px-3 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fa-solid fa-plus"></i>
        <span>Catat Pengeluaran</span>
    </button>
</div>

<!-- Summary Card & Modal -->
<div class="row g-4 mb-4">
    <div class="col-md-4 col-12">
        <div class="card border-0 shadow-sm border-start border-danger border-5 h-100">
            <div class="card-body p-3.5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Pengeluaran Recorded</p>
                        <h3 class="fw-bold mb-0 text-danger">Rp {{ number_format($pengeluaran->sum('nominal'), 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-danger-subtle text-danger rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="fa-solid fa-receipt fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Catat Pengeluaran -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-file-circle-plus text-primary me-2"></i>Catat Pengeluaran Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.pengeluaran.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pengeluaran" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Kategori Pengeluaran <span class="text-danger">*</span></label>
                        <select name="kategori_pengeluaran_id" class="form-select" required>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nominal (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                            <input type="number" name="nominal" class="form-control" placeholder="0" required min="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Keterangan / Rincian</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Beli sapu dan pel lantai">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 650px;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 20%;">Tanggal</th>
                        <th style="width: 20%;">Kategori</th>
                        <th class="text-end" style="width: 20%;">Nominal</th>
                        <th style="width: 30%;">Keterangan</th>
                        <th class="text-end pe-4" style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengeluaran as $p)
                    <tr>
                        <td class="ps-4 text-secondary">
                            <i class="fa-regular fa-calendar me-1.5 text-primary"></i>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($p->tanggal_pengeluaran)->format('d M Y') }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle rounded-pill px-2.5 py-1 fw-bold">
                                {{ $p->kategori->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        <td class="text-end text-danger fw-bold fs-6">
                            Rp {{ number_format($p->nominal, 0, ',', '.') }}
                        </td>
                        <td class="text-dark">
                            {{ $p->deskripsi ?: '-' }}
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('admin.pengeluaran.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus catatan pengeluaran ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Catatan">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">Belum Ada Catatan Pengeluaran</h5>
                                <p class="text-muted small mb-0">Klik tombol "Catat Pengeluaran" di atas untuk menambahkan data pengeluaran baru.</p>
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