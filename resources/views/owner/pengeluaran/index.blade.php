@extends('layouts.admin')

@section('content')
<!-- Header Action -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm rounded-3 px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fa-solid fa-plus-circle fs-6"></i>
        <span>Catat Pengeluaran</span>
    </button>
</div>

<!-- Summary Card & Modal -->
<div class="row g-4 mb-4">
    <div class="col-md-4 col-12">
        <div class="card border-0 shadow-sm border-start border-danger border-5 h-100">
            <div class="card-body p-4">
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
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahPengeluaranTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg" style="background-color: #ffffff;">
            <!-- Header Modal Bersih & Modern -->
            <div class="modal-header border-0 pb-0 pt-4 px-4 bg-white d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; background: linear-gradient(135deg, #5B6D92 0%, #43557A 100%); color: #ffffff;">
                        <i class="fa-solid fa-file-circle-plus fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalTambahPengeluaranTitle" style="font-size: 1.2rem; letter-spacing: -0.3px;">
                            Catat Pengeluaran Baru
                        </h5>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.85rem;"></button>
            </div>

            <form action="{{ route('admin.pengeluaran.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 pt-3 bg-white">
                    <div class="row g-3">
                        <!-- Tanggal Pengeluaran -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark mb-1" style="font-size: 0.82rem;">
                                Tanggal Pengeluaran <span class="text-danger">*</span>
                            </label>
                            <div class="input-group shadow-sm rounded-3 overflow-hidden border" style="border-color: #e2e8f0 !important;">
                                <span class="input-group-text border-0 bg-light text-secondary px-3">
                                    <i class="fa-regular fa-calendar"></i>
                                </span>
                                <input type="date" name="tanggal_pengeluaran" class="form-control border-0 ps-2 fw-semibold text-dark bg-white" value="{{ date('Y-m-d') }}" required style="font-size: 0.92rem; box-shadow: none;">
                            </div>
                        </div>

                        <!-- Kategori Pengeluaran -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark mb-1" style="font-size: 0.82rem;">
                                Kategori Pengeluaran <span class="text-danger">*</span>
                            </label>
                            <div class="input-group shadow-sm rounded-3 overflow-hidden border" style="border-color: #e2e8f0 !important;">
                                <span class="input-group-text border-0 bg-light text-secondary px-3">
                                    <i class="fa-solid fa-shapes"></i>
                                </span>
                                <select name="kategori_pengeluaran_id" class="form-select border-0 ps-2 fw-semibold text-dark bg-white" required style="font-size: 0.92rem; box-shadow: none;">
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Nominal -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark mb-1" style="font-size: 0.82rem;">
                                Nominal (Rp) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group shadow-sm rounded-3 overflow-hidden border" style="border-color: #e2e8f0 !important;">
                                <span class="input-group-text border-0 bg-light text-secondary px-3 fw-bold" style="font-size: 0.88rem;">
                                    Rp
                                </span>
                                <input type="number" name="nominal" class="form-control border-0 ps-2 fw-semibold text-dark bg-white" placeholder="0" required min="1" style="font-size: 0.92rem; box-shadow: none;">
                            </div>
                        </div>

                        <!-- Keterangan / Rincian -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark mb-1" style="font-size: 0.82rem;">
                                Keterangan / Rincian <span class="text-muted fw-normal">(Opsional)</span>
                            </label>
                            <div class="input-group shadow-sm rounded-3 overflow-hidden border" style="border-color: #e2e8f0 !important;">
                                <span class="input-group-text border-0 bg-light text-secondary px-3">
                                    <i class="fa-regular fa-comment-dots"></i>
                                </span>
                                <input type="text" name="keterangan" class="form-control border-0 ps-2 fw-semibold text-dark bg-white" placeholder="Contoh: Beli sapu dan pel lantai" maxlength="255" style="font-size: 0.92rem; box-shadow: none;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Tombol Aksi -->
                <div class="modal-footer border-top-0 pt-0 pb-4 px-4 bg-white d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light text-secondary px-3 py-2 rounded-3 border fw-semibold" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn text-white px-4 py-2 rounded-3 fw-bold shadow-sm d-flex align-items-center gap-2" style="background-color: #43557A; border-color: #43557A;">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Catatan</span>
                    </button>
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