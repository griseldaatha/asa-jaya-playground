<?php

$dir = __DIR__;

// 1. Admin Meja
$meja = <<<HTML
@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Data Meja / Spot</h3>
    <form action="{{ route('admin.meja.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary shadow-sm"><i class="fa-solid fa-qrcode"></i> Generate Meja Baru</button>
    </form>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID Meja</th>
                        <th>QR Token (Link Katalog)</th>
                        <th>Status Layanan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\$meja as \$m)
                    <tr>
                        <td class="fw-bold">Meja #{{ \$m->id }}</td>
                        <td>
                            <div class="fs-5 fw-bold text-primary tracking-widest">{{ \$m->qr_token }}</div>
                            <a href="{{ url('/meja/'.\$m->qr_token) }}" target="_blank" class="text-decoration-none small"><i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Katalog</a>
                        </td>
                        <td>
                            <form action="{{ route('admin.meja.update', \$m->id) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="is_active" class="form-select form-select-sm w-auto mx-auto {{ \$m->is_active ? 'border-success text-success' : 'border-danger text-danger' }}" onchange="this.form.submit()">
                                    <option value="1" {{ \$m->is_active ? 'selected' : '' }}>🟢 Aktif Melayani</option>
                                    <option value="0" {{ !\$m->is_active ? 'selected' : '' }}>🔴 Nonaktif / Rusak</option>
                                </select>
                            </form>
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('admin.meja.destroy', \$m->id) }}" method="POST" onsubmit="return confirm('Hapus meja ini secara permanen?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
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
file_put_contents($dir.'/resources/views/owner/meja/index.blade.php', $meja);

// 2. Admin Karyawan
$karyawan = <<<HTML
@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Akun Kasir</h3>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fa-solid fa-user-plus"></i> Tambah Karyawan</button>
</div>

<!-- Modal -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Daftarkan Kasir Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.karyawan.store') }}" method="POST">
          <div class="modal-body">
              @csrf
              <div class="mb-3">
                  <label class="form-label fw-semibold">Nama Lengkap</label>
                  <input type="text" name="nama" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Username Login</label>
                  <input type="text" name="username" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Password</label>
                  <input type="password" name="password" class="form-control" required>
              </div>
          </div>
          <div class="modal-footer border-0">
            <button type="submit" class="btn btn-primary w-100">Simpan Akun</button>
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
                        <th class="ps-4">Nama Lengkap</th>
                        <th>Username Login</th>
                        <th>Ganti Password Baru</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\$karyawan as \$k)
                    <tr>
                        <form action="{{ route('admin.karyawan.update', \$k->id) }}" method="POST">
                            @csrf @method('PUT')
                            <td class="ps-4"><input type="text" name="nama" class="form-control form-control-sm" value="{{ \$k->nama }}" required></td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                                    <input type="text" name="username" class="form-control form-control-sm" value="{{ \$k->username }}" required>
                                </div>
                            </td>
                            <td><input type="password" name="password" class="form-control form-control-sm" placeholder="*** Kosongi jika tidak diubah ***"></td>
                            <td class="text-end pe-4">
                                <button type="submit" class="btn btn-sm btn-success"><i class="fa-solid fa-check"></i></button>
                        </form>
                                <form action="{{ route('admin.karyawan.destroy', \$k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus akun ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-user-xmark"></i></button>
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
file_put_contents($dir.'/resources/views/owner/karyawan/index.blade.php', $karyawan);
