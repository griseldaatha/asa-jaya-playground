@extends('layouts.admin')

@section('content')
<div class="produk-page">
    <style>
        .produk-page {
            --primary: #4f46e5;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --secondary-gradient: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            --radius: 16px;
            --soft-bg: #f8fafc;
            border-radius: 20px;
            padding: 0.5rem;
        }

        /* Hero Header Banner */
        .produk-hero {
            background: var(--primary-gradient);
            border-radius: var(--radius);
            padding: 1.75rem 2rem;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.2);
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .produk-hero::before {
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

        .produk-hero-icon {
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
            background: #f59e0b;
            color: #ffffff;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);
            transition: all 0.25s ease;
        }

        .btn-hero-add:hover {
            background: #d97706;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.5);
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

        .stat-card-blue .stat-icon-wrapper { background: rgba(59, 130, 246, 0.12); color: #2563eb; }
        .stat-card-green .stat-icon-wrapper { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .stat-card-danger .stat-icon-wrapper { background: rgba(239, 68, 68, 0.12); color: #dc2626; }
        .stat-card-warning .stat-icon-wrapper { background: rgba(245, 158, 11, 0.12); color: #d97706; }

        /* Main Table Card */
        .table-card {
            border: none;
            border-radius: var(--radius);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            background: #ffffff;
            overflow: hidden;
        }

        .table-header-bar {
            background: #ffffff;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-produk thead th {
            background: #eef2ff !important;
            color: #312e81 !important;
            font-weight: 700;
            font-size: 0.775rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 18px;
            border-bottom: 2px solid #e0e7ff;
        }

        .table-produk tbody tr {
            transition: all 0.2s ease;
        }

        .table-produk tbody tr:nth-child(even) {
            background-color: #fafbfc;
        }

        .table-produk tbody tr:hover {
            background-color: #f0f3ff !important;
        }

        .produk-row.non-aktif {
            background-color: #fff5f5 !important;
            border-left: 4px solid #ef4444 !important;
        }

        /* Action Buttons Round */
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

        /* Stock Status Classes */
        .stock-high { border-color: #10b981 !important; color: #065f46; font-weight: 700; }
        .stock-medium { border-color: #f59e0b !important; color: #92400e; font-weight: 700; }
        .stock-low { border-color: #ef4444 !important; color: #991b1b; font-weight: 700; background-color: #fef2f2 !important; }

        /* Modal Header Gradient */
        .modal-header-gradient {
            background: var(--primary-gradient);
            color: #ffffff;
            padding: 18px 24px;
        }

        .upload-area-dashed {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 1rem;
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        .upload-area-dashed:hover {
            border-color: #4f46e5;
            background: #f0f3ff;
        }
    </style>

    <!-- Hero Header Banner -->
    <div class="produk-hero d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="produk-hero-icon">
                <i class="fa-solid fa-shapes"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Kelola Barang Penjualan</h3>
                <p class="mb-0 opacity-90 small">Kelola katalog produk, harga jual, stok barang, dan foto untuk kasir</p>
            </div>
        </div>

        <button class="btn btn-hero-add d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa-solid fa-plus-circle fs-6"></i>
            <span>Tambah Produk Baru</span>
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Produk -->
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card stat-card stat-card-blue p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 0.5px;">Total Produk</span>
                        <h3 class="fw-bold mb-0 text-dark mt-1">{{ $produk->count() }}</h3>
                    </div>
                    <div class="stat-icon-wrapper">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Aktif -->
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card stat-card stat-card-green p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 0.5px;">Produk Aktif</span>
                        <h3 class="fw-bold mb-0 text-success mt-1">{{ $produk->where('is_active', 1)->count() }}</h3>
                    </div>
                    <div class="stat-icon-wrapper">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Nonaktif -->
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card stat-card stat-card-danger p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 0.5px;">Produk Nonaktif</span>
                        <h3 class="fw-bold mb-0 text-danger mt-1">{{ $produk->where('is_active', 0)->count() }}</h3>
                    </div>
                    <div class="stat-icon-wrapper">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card stat-card stat-card-warning p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-bold text-muted" style="font-size: 11px; letter-spacing: 0.5px;">Stok Menipis (≤5)</span>
                        <h3 class="fw-bold mb-0 text-warning mt-1">{{ $produk->where('stok', '<=', 5)->count() }}</h3>
                    </div>
                    <div class="stat-icon-wrapper">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                <div class="modal-header modal-header-gradient">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-cart-plus me-2"></i>Tambah Produk Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Kategori Produk <span class="text-danger">*</span></label>
                                <select name="kategori_produk_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Tiket Wahana Playland" required maxlength="100">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Harga Jual (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-success">Rp</span>
                                    <input type="number" name="harga" class="form-control" placeholder="0" required min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" name="stok" class="form-control" placeholder="0" required min="0">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-secondary">Foto Produk (Opsional)</label>
                                <div class="upload-area-dashed d-flex align-items-center gap-3">
                                    <div id="previewContainerAdd" class="rounded-3 border d-flex align-items-center justify-content-center bg-white text-muted overflow-hidden shadow-sm" style="width: 70px; height: 70px; flex-shrink: 0;">
                                        <i class="fa-solid fa-cloud-arrow-up fs-3 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" name="foto" class="form-control" accept="image/*" onchange="previewImageAdd(event)">
                                        <div class="form-text small text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-light px-4 rounded-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold shadow-sm" style="background: var(--primary-gradient); border: none;">
                            <i class="fa-solid fa-floppy-disk me-1.5"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- HTML5 Form Attribute Binding Forms -->
    @foreach($produk as $p)
        <form action="{{ route('admin.produk.update', $p->id) }}" method="POST" enctype="multipart/form-data" id="form-produk-{{ $p->id }}">
            @csrf
            @method('PUT')
        </form>
        <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" id="form-delete-{{ $p->id }}" onsubmit="return confirm('Nonaktifkan produk ini?');">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    <!-- Main Table Card -->
    <div class="card table-card">
        <div class="table-header-bar d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-list-check text-primary fs-5"></i>
                <h6 class="fw-bold text-dark mb-0">Daftar Produk Penjualan</h6>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1">
                    {{ $produk->count() }} Item
                </span>
            </div>
            
            <div class="position-relative" style="min-width: 260px;">
                <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" id="searchProduk" class="form-control form-control-sm ps-5 rounded-pill shadow-sm" placeholder="Cari nama produk..." onkeyup="filterProduk()">
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-produk table-hover align-middle mb-0" style="min-width: 880px;">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 32%;">Informasi Produk & Foto</th>
                            <th style="width: 20%;">Kategori</th>
                            <th style="width: 18%;">Harga Jual</th>
                            <th style="width: 12%;">Stok</th>
                            <th style="width: 10%;">Status</th>
                            <th class="text-end pe-4" style="width: 8%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produk as $p)
                        @php
                            $badgeStyles = [
                                'bg-primary-subtle text-primary border-primary-subtle',
                                'bg-info-subtle text-info-emphasis border-info-subtle',
                                'bg-warning-subtle text-warning-emphasis border-warning-subtle',
                                'bg-success-subtle text-success border-success-subtle',
                                'bg-danger-subtle text-danger border-danger-subtle',
                                'bg-secondary-subtle text-secondary-emphasis border-secondary-subtle',
                            ];
                            $badgeClass = $badgeStyles[$p->kategori_produk_id % count($badgeStyles)];

                            $stockClass = 'stock-high';
                            if ($p->stok <= 0) {
                                $stockClass = 'stock-low';
                            } elseif ($p->stok <= 10) {
                                $stockClass = 'stock-medium';
                            }
                        @endphp
                        <tr class="produk-row {{ !$p->is_active ? 'non-aktif' : '' }}" data-nama="{{ $p->nama_produk }}">
                            <!-- Column 1: Foto & Nama Produk -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative flex-shrink-0" style="width: 48px; height: 48px;">
                                        @if($p->foto_produk)
                                            <img src="{{ asset('storage/'.$p->foto_produk) }}" class="rounded-3 shadow-sm w-100 h-100 border" style="object-fit: cover; border-color: #cbd5e1 !important;">
                                        @else
                                            <div class="rounded-3 w-100 h-100 d-flex align-items-center justify-content-center text-primary fw-bold shadow-sm" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);">
                                                <i class="fa-solid fa-image fs-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="text" name="nama_produk" form="form-produk-{{ $p->id }}" class="form-control form-control-sm fw-bold mb-1 shadow-2xs" value="{{ $p->nama_produk }}" required maxlength="100">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="text-muted" style="font-size: 0.7rem;">Ganti foto:</span>
                                            <input type="file" name="foto" form="form-produk-{{ $p->id }}" class="form-control form-control-sm py-0 px-1 text-muted" style="font-size: 0.68rem; max-width: 175px;" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Column 2: Category Badge & Select -->
                            <td>
                                <select name="kategori_produk_id" form="form-produk-{{ $p->id }}" class="form-select form-select-sm fw-semibold" required>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id }}" {{ $p->kategori_produk_id == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <!-- Column 3: Price -->
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-success-subtle text-success fw-bold" style="font-size: 0.75rem;">Rp</span>
                                    <input type="number" name="harga" form="form-produk-{{ $p->id }}" class="form-control form-control-sm fw-bold text-success" value="{{ $p->harga }}" required min="0">
                                </div>
                            </td>

                            <!-- Column 4: Stock -->
                            <td>
                                <input type="number" name="stok" form="form-produk-{{ $p->id }}" class="form-control form-control-sm text-center {{ $stockClass }}" value="{{ $p->stok }}" required min="0" style="max-width: 85px;">
                                @if($p->stok <= 0)
                                    <small class="text-danger fw-bold d-block text-center mt-0.5" style="font-size: 10px;">Habis</small>
                                @endif
                            </td>

                            <!-- Column 5: Status Select -->
                            <td>
                                <select name="is_active" form="form-produk-{{ $p->id }}" class="form-select form-select-sm fw-bold {{ $p->is_active ? 'text-success bg-success-subtle border-success-subtle' : 'text-danger bg-danger-subtle border-danger-subtle' }}">
                                    <option value="1" {{ $p->is_active ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ !$p->is_active ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </td>

                            <!-- Column 6: Round Action Buttons -->
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1.5">
                                    <button type="submit" form="form-produk-{{ $p->id }}" class="btn btn-success btn-action-round shadow-sm" title="Simpan Perubahan">
                                        <i class="fa-solid fa-check fs-6"></i>
                                    </button>
                                    <button type="submit" form="form-delete-{{ $p->id }}" class="btn btn-outline-danger btn-action-round shadow-sm" title="Nonaktifkan Produk">
                                        <i class="fa-solid fa-ban fs-6"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state py-5">
                                    <div class="empty-state-icon text-primary bg-primary-subtle mb-3">
                                        <i class="fa-solid fa-box-open fs-2"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">Belum Ada Barang Penjualan</h5>
                                    <p class="text-muted small mb-3">Klik tombol "Tambah Produk Baru" di atas untuk menambahkan produk ke katalog.</p>
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
@endsection

@section('scripts')
<script>
    function previewImageAdd(event) {
        const file = event.target.files[0];
        const container = document.getElementById('previewContainerAdd');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                container.innerHTML = `<img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover;">`;
            }
            reader.readAsDataURL(file);
        } else {
            container.innerHTML = `<i class="fa-solid fa-cloud-arrow-up fs-3 text-primary"></i>`;
        }
    }

    function filterProduk() {
        const query = document.getElementById('searchProduk').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.produk-row');
        rows.forEach(row => {
            const nama = row.getAttribute('data-nama') || '';
            row.style.display = nama.toLowerCase().includes(query) ? '' : 'none';
        });
    }
</script>
@endsection