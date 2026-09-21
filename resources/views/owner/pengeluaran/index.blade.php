@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Buku Pengeluaran Harian</h3>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fa-solid fa-plus"></i> Catat Pengeluaran</button>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Catat Pengeluaran Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.pengeluaran.store') }}" method="POST">
          <div class="modal-body">
              @csrf
              <div class="mb-3">
                  <label class="form-label fw-semibold">Tanggal</label>
                  <input type="date" name="tanggal_pengeluaran" class="form-control" value="{{ date('Y-m-d') }}" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Kategori</label>
                  <select name="kategori_pengeluaran_id" class="form-select" required>
                      @foreach($kategori as $kat) <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option> @endforeach
                  </select>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Nominal (Rp)</label>
                  <input type="number" name="nominal" class="form-control" required min="1">
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Keterangan</label>
                  <input type="text" name="keterangan" class="form-control" placeholder="Beli sapu dan pel lantai">
              </div>
          </div>
          <div class="modal-footer border-0">
            <button type="submit" class="btn btn-primary w-100">Simpan Catatan</button>
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
                        <th class="ps-3">Tanggal</th>
                        <th>Kategori</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengeluaran as $p)
                    <tr>
                        <td class="ps-3"><i class="fa-regular fa-calendar text-muted me-1"></i> {{ \Carbon\Carbon::parse($p->tanggal_pengeluaran)->format('d M Y') }}</td>
                        <td><span class="badge bg-secondary">{{ $p->kategoriPengeluaran->nama_kategori ?? '-' }}</span></td>
                        <td class="text-danger fw-bold">Rp {{ number_format($p->nominal, 0, ',', '.') }}</td>
                        <td>{{ $p->keterangan }}</td>
                        <td class="text-end pe-3">
                            <form action="{{ route('admin.pengeluaran.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus catatan?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
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