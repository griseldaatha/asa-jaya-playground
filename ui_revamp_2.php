<?php

$dir = __DIR__;

// 1. Kasir Dashboard (Live Queue)
$kasir_dash = <<<HTML
@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-fire"></i> Antrean Pesanan Aktif</h3>
</div>
<div id="antrean-container" class="row g-3">
    <div class="col-12 text-center text-muted"><i class="fa-solid fa-circle-notch fa-spin fs-1 mt-5"></i><br>Memuat pesanan...</div>
</div>
@endsection
@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    function fetchPesanan() {
        fetch('/kasir/api/pesanan-aktif')
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('antrean-container');
                container.innerHTML = '';
                if(data.length === 0) {
                    container.innerHTML = '<div class="col-12 text-center mt-5 text-muted"><i class="fa-solid fa-mug-hot fs-1 mb-2 opacity-50"></i><br>Tidak ada pesanan aktif saat ini.</div>';
                    return;
                }
                data.forEach(p => {
                    let btnLunas = p.status_pembayaran === 'pending' ? `<button onclick="updateStatus(\${p.id}, 'pembayaran', 'lunas')" class="btn btn-sm btn-success w-100 mb-2 fw-bold"><i class="fa-solid fa-money-bill-wave"></i> Terima Uang</button>` : '';
                    let btnProses = (p.status_pesanan === 'menunggu' && p.status_pembayaran === 'lunas') ? `<button onclick="updateStatus(\${p.id}, 'pesanan', 'diproses')" class="btn btn-sm btn-primary w-100 mb-2 fw-bold"><i class="fa-solid fa-fire-burner"></i> Proses Pesanan</button>` : '';
                    let btnSelesai = p.status_pesanan === 'diproses' ? `<button onclick="updateStatus(\${p.id}, 'pesanan', 'selesai')" class="btn btn-sm btn-info text-white w-100 mb-2 fw-bold"><i class="fa-solid fa-check-double"></i> Selesai (Serahkan)</button>` : '';
                    let btnBatal = p.status_pembayaran === 'pending' ? `<button onclick="updateStatus(\${p.id}, 'pesanan', 'dibatalkan')" class="btn btn-sm btn-outline-danger w-100 fw-bold">Batal (Kembalikan Stok)</button>` : '';
                    
                    let bgBorder = p.status_pembayaran === 'lunas' ? 'border-success' : 'border-warning';
                    let listItems = p.detail_transaksi.map(dt => `<li class="list-group-item py-1 px-2 text-sm d-flex justify-content-between"><span>\${dt.produk ? dt.produk.nama_produk : 'Dihapus'}</span> <span class="fw-bold">\${dt.jumlah}x</span></li>`).join('');
                    
                    let html = `
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card h-100 shadow-sm border-0 border-top border-4 \${bgBorder}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h5 class="fw-bold mb-0">#\${p.kode_transaksi}</h5>
                                        <span class="badge \${p.status_pembayaran === 'lunas' ? 'bg-success' : 'bg-warning text-dark'}">\${p.status_pembayaran.toUpperCase()}</span>
                                    </div>
                                    <p class="mb-1 text-muted small"><i class="fa-solid fa-user"></i> \${p.nama_pemesan}</p>
                                    <p class="mb-3 text-muted small"><i class="fa-solid fa-chair"></i> Meja: \${p.meja_id || '-'}</p>
                                    
                                    <ul class="list-group list-group-flush mb-3 border rounded">
                                        \${listItems}
                                    </ul>
                                    <h5 class="fw-bold text-end mb-3">Rp \${new Intl.NumberFormat('id-ID').format(p.total_harga)}</h5>
                                    
                                    <div class="actions">
                                        \${btnLunas} \${btnProses} \${btnSelesai} \${btnBatal}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += html;
                });
            });
    }
    function updateStatus(id, type, val) {
        fetch(`/kasir/pesanan/\${id}/update`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
            body: JSON.stringify({ type: type, value: val })
        }).then(() => fetchPesanan());
    }
    fetchPesanan(); setInterval(fetchPesanan, 5000);
</script>
@endsection
HTML;
file_put_contents($dir.'/resources/views/kasir/dashboard.blade.php', $kasir_dash);

// 2. Admin Produk
$produk = <<<HTML
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
                          @foreach(\$kategori as \$k) <option value="{{ \$k->id }}">{{ \$k->nama_kategori }}</option> @endforeach
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
                    @foreach(\$produk as \$p)
                    <tr>
                        <form action="{{ route('admin.produk.update', \$p->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <td class="ps-3 d-flex align-items-center">
                                @if(\$p->foto_produk)
                                    <img src="{{ asset('storage/'.\$p->foto_produk) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;"><i class="fa-solid fa-image"></i></div>
                                @endif
                                <div class="d-flex flex-column">
                                    <input type="text" name="nama_produk" class="form-control form-control-sm mb-1 fw-bold" value="{{ \$p->nama_produk }}" required>
                                    <input type="file" name="foto" class="form-control form-control-sm" style="font-size:0.7rem;">
                                </div>
                            </td>
                            <td>
                                <select name="kategori_produk_id" class="form-select form-select-sm" required>
                                    @foreach(\$kategori as \$k)
                                        <option value="{{ \$k->id }}" {{ \$p->kategori_produk_id == \$k->id ? 'selected' : '' }}>{{ \$k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="harga" class="form-control form-control-sm" value="{{ \$p->harga }}" required></td>
                            <td><input type="number" name="stok" class="form-control form-control-sm" value="{{ \$p->stok }}" required style="width: 70px;"></td>
                            <td>
                                <select name="is_active" class="form-select form-select-sm {{ \$p->is_active ? 'text-success' : 'text-danger' }}">
                                    <option value="1" {{ \$p->is_active ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ !\$p->is_active ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </td>
                            <td class="text-end pe-3">
                                <button type="submit" class="btn btn-sm btn-success" title="Simpan Perubahan"><i class="fa-solid fa-check"></i></button>
                        </form>
                                <form action="{{ route('admin.produk.destroy', \$p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Nonaktifkan produk ini?');">
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
HTML;
file_put_contents($dir.'/resources/views/owner/produk/index.blade.php', $produk);

// 3. Admin Pengeluaran
$pengeluaran = <<<HTML
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
                      @foreach(\$kategori as \$kat) <option value="{{ \$kat->id }}">{{ \$kat->nama_kategori }}</option> @endforeach
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
                    @foreach(\$pengeluaran as \$p)
                    <tr>
                        <td class="ps-3"><i class="fa-regular fa-calendar text-muted me-1"></i> {{ \Carbon\Carbon::parse(\$p->tanggal_pengeluaran)->format('d M Y') }}</td>
                        <td><span class="badge bg-secondary">{{ \$p->kategoriPengeluaran->nama_kategori ?? '-' }}</span></td>
                        <td class="text-danger fw-bold">Rp {{ number_format(\$p->nominal, 0, ',', '.') }}</td>
                        <td>{{ \$p->keterangan }}</td>
                        <td class="text-end pe-3">
                            <form action="{{ route('admin.pengeluaran.destroy', \$p->id) }}" method="POST" onsubmit="return confirm('Hapus catatan?');">
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
HTML;
file_put_contents($dir.'/resources/views/owner/pengeluaran/index.blade.php', $pengeluaran);

// 4. Admin Transaksi (Riwayat)
$transaksi = <<<HTML
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
                    @foreach(\$transaksi as \$t)
                    <tr>
                        <td class="ps-3 text-muted small">{{ \Carbon\Carbon::parse(\$t->created_at)->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="fw-bold text-primary">#{{ \$t->kode_transaksi }}</div>
                            <small class="text-muted"><i class="fa-solid fa-chair"></i> Meja: {{ \$t->meja_id ?? '-' }}</small>
                        </td>
                        <td>
                            <div class="fw-medium">{{ \$t->nama_pemesan }}</div>
                            @if(\$t->status_pembayaran == 'lunas')
                                <span class="badge bg-success">Lunas ({{ \$t->metode_pembayaran }})</span>
                            @elseif(\$t->status_pembayaran == 'batal')
                                <span class="badge bg-danger">Batal</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td><i class="fa-solid fa-user-tag text-muted"></i> {{ \$t->kasir->nama ?? '-' }}</td>
                        <td>
                            <ul class="list-unstyled mb-0 small text-muted">
                                @foreach(\$t->detailTransaksi as \$dt)
                                    <li>&bull; {{ \$dt->jumlah }}x {{ \$dt->produk->nama_produk ?? 'Dihapus' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-end pe-4 fw-bold">Rp {{ number_format(\$t->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
HTML;
file_put_contents($dir.'/resources/views/admin/transaksi/index.blade.php', $transaksi);

echo "Tahap 2 selesai.";
