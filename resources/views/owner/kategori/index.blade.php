@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Kategori Produk</h3>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><i class="fa-solid fa-plus text-primary me-2"></i> Tambah Kategori</div>
            <div class="card-body">
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" required maxlength="25">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2" maxlength="100"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-save"></i> Simpan Kategori</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Produk</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategori as $k)
                            <tr>
                                <form action="{{ route('admin.kategori.update', $k->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <td class="ps-3"><input type="text" name="nama_kategori" class="form-control form-control-sm" value="{{ $k->nama_kategori }}" required></td>
                                    <td><input type="text" name="deskripsi" class="form-control form-control-sm" value="{{ $k->deskripsi }}"></td>
                                    <td><span class="badge bg-secondary">{{ $k->produk_count }} item</span></td>
                                    <td class="text-end pe-3">
                                        <button type="submit" class="btn btn-sm btn-success" title="Update"><i class="fa-solid fa-check"></i></button>
                                </form>
                                        <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection