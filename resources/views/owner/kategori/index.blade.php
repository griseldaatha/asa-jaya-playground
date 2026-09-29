@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="page-title"><i class="fa-solid fa-tags text-primary me-2"></i>Kelola Kategori Produk</h3>
        <p class="page-subtitle mb-0">Kelompokkan produk dan barang penjualan ke dalam kategori</p>
    </div>
</div>

<!-- Forms Update & Delete (HTML5 form attribute binding) -->
@foreach($kategori as $k)
    <form action="{{ route('admin.kategori.update', $k->id) }}" method="POST" id="form-kategori-{{ $k->id }}">
        @csrf
        @method('PUT')
    </form>
    <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?');" id="form-delete-kategori-{{ $k->id }}">
        @csrf
        @method('DELETE')
    </form>
@endforeach

<div class="row g-4">
    <!-- Left Column: Add Category Form -->
    <div class="col-lg-4 col-md-5 col-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <div class="d-flex align-items-center gap-2 text-primary fw-bold">
                    <i class="fa-solid fa-folder-plus fs-5"></i>
                    <span>Tambah Kategori Baru</span>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Tiket / Makanan" required maxlength="25">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-secondary">Deskripsi (Opsional)</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan singkat kategori..." maxlength="100"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2.5 d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Kategori</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Categories Table -->
    <div class="col-lg-8 col-md-7 col-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="fw-bold text-dark">
                    <i class="fa-solid fa-list-ul text-primary me-2"></i>Daftar Kategori
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                    {{ $kategori->count() }} Kategori
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 500px;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 35%;">Nama Kategori</th>
                                <th style="width: 35%;">Deskripsi</th>
                                <th style="width: 15%;">Jumlah Produk</th>
                                <th class="text-end pe-4" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategori as $k)
                            <tr>
                                <td class="ps-4">
                                    <input type="text" name="nama_kategori" form="form-kategori-{{ $k->id }}" class="form-control form-control-sm fw-bold" value="{{ $k->nama_kategori }}" required maxlength="25">
                                </td>
                                <td>
                                    <input type="text" name="deskripsi" form="form-kategori-{{ $k->id }}" class="form-control form-control-sm text-muted" value="{{ $k->deskripsi }}" placeholder="Belum ada deskripsi" maxlength="100">
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1.5 fw-bold">
                                        <i class="fa-solid fa-box me-1"></i>{{ $k->produk_count }} item
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <button type="submit" form="form-kategori-{{ $k->id }}" class="btn btn-sm btn-success" title="Simpan Perubahan">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button type="submit" form="form-delete-kategori-{{ $k->id }}" class="btn btn-sm btn-outline-danger" title="Hapus Kategori">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fa-solid fa-tags"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-1">Belum Ada Kategori</h5>
                                        <p class="text-muted small mb-0">Gunakan formulir di sebelah kiri untuk menambah kategori baru.</p>
                                    </div>
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
@endsection