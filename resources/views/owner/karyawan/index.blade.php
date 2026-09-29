@extends('layouts.admin')

@section('content')
<div class="karyawan-page">
    <style>
        .karyawan-page {
            --primary-gradient: linear-gradient(135deg, #5B6D92 0%, #4A5978 100%);
            --radius: 16px;
            --soft-bg: #f8fafc;
            border-radius: 20px;
            padding: 0.5rem;
        }

        /* Hero Header Banner */
        .karyawan-hero {
            background: var(--primary-gradient);
            border-radius: var(--radius);
            padding: 1.75rem 2rem;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.2);
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .karyawan-hero::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .karyawan-hero-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #ffffff;
        }

        .btn-hero-add {
            background: #ffc107;
            color: #1e1b4b;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            box-shadow: 0 4px 14px rgba(255, 193, 7, 0.4);
            transition: all 0.25s ease;
        }

        .btn-hero-add:hover {
            background: #ffb300;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 179, 0, 0.5);
        }

        /* Stat Cards */
        .stat-card {
            border: none;
            border-radius: var(--radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            background: #ffffff;
            transition: all 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        /* Table Card Styling */
        .table-card {
            border: none;
            border-radius: var(--radius);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            background: #ffffff;
            overflow: hidden;
        }

        .table-karyawan thead th {
            background: #eef2ff !important;
            color: #312e81 !important;
            font-weight: 700;
            font-size: 0.775rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px 18px;
            border-bottom: 2px solid #e0e7ff;
        }

        .table-karyawan tbody tr {
            transition: all 0.2s ease;
        }

        .table-karyawan tbody tr:hover {
            background-color: #f0f3ff !important;
        }

        /* Round Action Buttons */
        .btn-action-round {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            transition: all 0.2s ease;
        }

        .btn-action-round:hover {
            transform: scale(1.1);
        }

        .modal-header-gradient {
            background: var(--primary-gradient);
            color: #ffffff;
            padding: 18px 24px;
        }

        /* Mobile Card Grid View */
        @media (max-width: 767.98px) {
            .table-responsive-desktop {
                display: none;
            }
            .mobile-karyawan-cards {
                display: block;
            }
        }
        @media (min-width: 768px) {
            .mobile-karyawan-cards {
                display: none;
            }
        }
    </style>

    <!-- Hero Header Banner -->
    <div class="karyawan-hero d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="karyawan-hero-icon">
                <i class="fa-solid fa-users-gear"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Kelola Akun Kasir</h3>
                <p class="mb-0 opacity-90 small">Atur akun karyawan yang bisa masuk dan bertransaksi di Portal Kasir</p>
            </div>
        </div>

        <button class="btn btn-hero-add d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa-solid fa-user-plus fs-6"></i>
            <span>Tambah Karyawan Baru</span>
        </button>
    </div>

    <!-- Summary & Tips Row -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Akun Kasir -->
        <div class="col-md-5 col-12">
            <div class="card stat-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 0.5px;">Total Akun Kasir</span>
                        <h3 class="fw-bold mb-0 text-primary mt-1">{{ $karyawan->count() }} Kasir</h3>
                    </div>
                    <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Tips Keamanan Password -->
        <div class="col-md-7 col-12">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4" style="background-color: #D5E3E6 !important; border-left: 5px solid #5B6D92 !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-dark fs-3">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Petunjuk Perubahan Password</h6>
                        <p class="mb-0 small text-secondary">
                            Kosongkan kolom password saat melakukan pengeditan jika Anda tidak ingin mengubah password akun kasir yang sudah ada.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Register Kasir Baru -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                <div class="modal-header modal-header-gradient">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-plus me-2"></i>Daftarkan Kasir Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('owner.karyawan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 mb-3 small">
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('nama') }}" required maxlength="100">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Username Login <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-at"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Contoh: budi_kasir" value="{{ old('username') }}" required maxlength="50">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Password Access <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 4 karakter" required minlength="4">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-light px-4 rounded-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold shadow-sm" style="background: var(--primary-gradient); border: none;">
                            <i class="fa-solid fa-floppy-disk me-1.5"></i> Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- HTML5 Form Attribute Binding Forms -->
    @foreach($karyawan as $k)
        <form action="{{ route('owner.karyawan.update', $k->id) }}" method="POST" id="form-karyawan-{{ $k->id }}">
            @csrf
            @method('PUT')
        </form>
        <form action="{{ route('owner.karyawan.destroy', $k->id) }}" method="POST" id="form-delete-karyawan-{{ $k->id }}">
            @csrf
            @method('DELETE')
        </form>

        <!-- Bootstrap Modal Confirmation Delete -->
        <div class="modal fade" id="modalDelete-{{ $k->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                    <div class="modal-body p-4 text-center">
                        <div class="text-danger bg-danger-subtle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                            <i class="fa-solid fa-user-xmark fs-2"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Hapus Akun Kasir?</h5>
                        <p class="text-muted small mb-3">Yakin ingin menghapus akun <strong>{{ $k->nama }}</strong> (@ {{ $k->username }})?</p>
                        <div class="alert alert-warning py-2 px-3 small text-start border-0 rounded-3 mb-4">
                            <i class="fa-solid fa-circle-info me-1"></i> Catatan: Akun yang sudah pernah memproses transaksi tidak bisa dihapus.
                        </div>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light px-4 rounded-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" form="form-delete-karyawan-{{ $k->id }}" class="btn btn-danger px-4 rounded-3 fw-bold shadow-sm">
                                <i class="fa-solid fa-trash me-1.5"></i> Hapus Akun
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Main Table Card (Desktop) -->
    <div class="card table-card table-responsive-desktop">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-users text-primary fs-5"></i>
                <h6 class="fw-bold text-dark mb-0">Daftar Akun Kasir Aktif</h6>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold">
                {{ $karyawan->count() }} Akun
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-karyawan table-hover align-middle mb-0" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 32%;">Nama Lengkap</th>
                            <th style="width: 25%;">Username Login</th>
                            <th style="width: 28%;">Ganti Password Baru</th>
                            <th class="text-end pe-4" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $avatarColors = [
                                ['bg' => 'bg-primary-subtle', 'text' => 'text-primary'],
                                ['bg' => 'bg-info-subtle', 'text' => 'text-dark'],
                                ['bg' => 'bg-success-subtle', 'text' => 'text-success'],
                                ['bg' => 'bg-warning-subtle', 'text' => 'text-warning-emphasis'],
                                ['bg' => 'bg-purple-subtle', 'text' => 'text-purple'],
                            ];
                        @endphp
                        @forelse($karyawan as $k)
                        @php
                            $colorIndex = $k->id % count($avatarColors);
                            $avatarColor = $avatarColors[$colorIndex];
                            $initial = strtoupper(substr($k->nama, 0, 1));
                        @endphp
                        <tr>
                            <!-- Column 1: Avatar & Nama Input -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle {{ $avatarColor['bg'] }} {{ $avatarColor['text'] }} shadow-2xs">
                                        {{ $initial }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <input type="text" name="nama" form="form-karyawan-{{ $k->id }}" class="form-control form-control-sm fw-bold" value="{{ $k->nama }}" required maxlength="100">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5" style="font-size: 10px;">
                                                <i class="fa-solid fa-user-tag me-1"></i>Kasir
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Column 2: Username Input Group -->
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-at"></i></span>
                                    <input type="text" name="username" form="form-karyawan-{{ $k->id }}" class="form-control form-control-sm fw-semibold" value="{{ $k->username }}" required maxlength="50">
                                </div>
                            </td>

                            <!-- Column 3: Password Input Group -->
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="password" form="form-karyawan-{{ $k->id }}" class="form-control form-control-sm" placeholder="Kosongi jika tidak diubah">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)" title="Lihat/Sembunyikan">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </td>

                            <!-- Column 4: Round Action Buttons -->
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1.5">
                                    <button type="submit" form="form-karyawan-{{ $k->id }}" class="btn btn-success btn-action-round shadow-sm" title="Simpan Perubahan">
                                        <i class="fa-solid fa-check fs-6"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-action-round shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDelete-{{ $k->id }}" title="Hapus Akun">
                                        <i class="fa-solid fa-user-xmark fs-6"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state py-5">
                                    <div class="empty-state-icon text-primary bg-primary-subtle mb-3">
                                        <i class="fa-solid fa-users-slash fs-2"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">Belum Ada Akun Kasir</h5>
                                    <p class="text-muted small mb-3">Klik tombol "Tambah Karyawan Baru" di atas untuk menambahkan akun kasir.</p>
                                    <button class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                        <i class="fa-solid fa-user-plus me-1"></i> Tambah Kasir Baru
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mobile Stacked Card View (< 768px) -->
    <div class="mobile-karyawan-cards">
        @forelse($karyawan as $k)
        @php
            $colorIndex = $k->id % count($avatarColors);
            $avatarColor = $avatarColors[$colorIndex];
            $initial = strtoupper(substr($k->nama, 0, 1));
        @endphp
        <div class="card border-0 shadow-sm rounded-4 mb-3 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-circle {{ $avatarColor['bg'] }} {{ $avatarColor['text'] }}">
                        {{ $initial }}
                    </div>
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-0.5" style="font-size: 10px;">Kasir</span>
                    </div>
                </div>
                <div class="d-flex gap-1">
                    <button type="submit" form="form-karyawan-{{ $k->id }}" class="btn btn-success btn-action-round shadow-sm" title="Simpan Perubahan">
                        <i class="fa-solid fa-check fs-6"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-action-round shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDelete-{{ $k->id }}" title="Hapus Akun">
                        <i class="fa-solid fa-user-xmark fs-6"></i>
                    </button>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label fw-semibold small text-muted mb-1">Nama Lengkap</label>
                <input type="text" name="nama" form="form-karyawan-{{ $k->id }}" class="form-control form-control-sm fw-bold" value="{{ $k->nama }}" required maxlength="100">
            </div>

            <div class="mb-2">
                <label class="form-label fw-semibold small text-muted mb-1">Username Login</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-at"></i></span>
                    <input type="text" name="username" form="form-karyawan-{{ $k->id }}" class="form-control form-control-sm fw-semibold" value="{{ $k->username }}" required maxlength="50">
                </div>
            </div>

            <div class="mb-1">
                <label class="form-label fw-semibold small text-muted mb-1">Ganti Password</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" form="form-karyawan-{{ $k->id }}" class="form-control form-control-sm" placeholder="Kosongi jika tidak diubah">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white">
            <div class="empty-state-icon text-primary bg-primary-subtle mx-auto mb-3">
                <i class="fa-solid fa-users-slash fs-2"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">Belum Ada Akun Kasir</h5>
            <p class="text-muted small mb-3">Klik tombol di bawah untuk menambahkan akun kasir.</p>
            <button class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fa-solid fa-user-plus me-1"></i> Tambah Kasir Baru
            </button>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(button) {
        const input = button.parentElement.querySelector('input');
        const icon = button.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

@if($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var modalElement = document.getElementById('modalTambah');
        if (modalElement) {
            var myModal = new bootstrap.Modal(modalElement);
            myModal.show();
        }
    });
</script>
@endif
@endsection