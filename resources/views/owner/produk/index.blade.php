@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Barang Penjualan</h3>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fa-solid fa-plus"></i> Tambah Produk</button>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Tambah Produk Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
              @csrf
              <div class="row g-3">
                  <div class="col-md-6">
                      <label class="form-label fw-semibold">Kategori</label>
                      <select name="kategori_produk_id" class="form-select" required>
                          <option value="">-- Pilih --</option>
                          @foreach($kategori as $k) <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option> @endforeach
                      </select>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label fw-semibold">Nama Produk</label>
                      <input type="text" name="nama_produk" class="form-control" required maxlength="100">
                  </div>
                  <div class="col-md-4">
                      <label class="form-label fw-semibold">Harga (Rp)</label>
                      <input type="number" name="harga" class="form-control" required min="0">
                  </div>
                  <div class="col-md-4">
                      <label class="form-label fw-semibold">Stok</label>
                      <input type="number" name="stok" class="form-control" required min="0">
                  </div>
                  <div class="col-md-4">
                      <label class="form-label fw-semibold">Foto</label>
                      <input type="file" name="foto" class="form-control" accept="image/*">
                  </div>
              </div>
          </div>
          <div class="modal-footer border-0">
            <button type="submit" class="btn btn-primary px-4">Simpan</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produk as $p)
                    <tr>
                        <form action="{{ route('admin.produk.update', $p->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <td class="ps-3 d-flex align-items-center">
                                @if($p->foto_produk)
                                    <img src="{{ asset('storage/'.$p->foto_produk) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;"><i class="fa-solid fa-image"></i></div>
                                @endif
                                <div class="d-flex flex-column">
                                    <input type="text" name="nama_produk" class="form-control form-control-sm mb-1 fw-bold" value="{{ $p->nama_produk }}" required>
                                    <input type="file" name="foto" class="form-control form-control-sm" style="font-size:0.7rem;">
                                </div>
                            </td>
                            <td>
                                <select name="kategori_produk_id" class="form-select form-select-sm" required>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}" {{ $p->kategori_produk_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="harga" class="form-control form-control-sm" value="{{ $p->harga }}" required></td>
                            <td><input type="number" name="stok" class="form-control form-control-sm" value="{{ $p->stok }}" required style="width: 70px;"></td>
                            <td>
                                <select name="is_active" class="form-select form-select-sm {{ $p->is_active ? 'text-success' : 'text-danger' }}">
                                    <option value="1" {{ $p->is_active ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ !$p->is_active ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </td>
                            <td class="text-end pe-3">
                                <button type="submit" class="btn btn-sm btn-success" title="Simpan Perubahan"><i class="fa-solid fa-check"></i></button>
                        </form>
                                <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Nonaktifkan produk ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Nonaktifkan (Soft Delete)"><i class="fa-solid fa-ban"></i></button>
                                </form>
                            </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection