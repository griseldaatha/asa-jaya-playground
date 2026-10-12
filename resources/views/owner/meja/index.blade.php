@extends('layouts.admin')
@section('content')
<style>
    /* Header Area */
    .meja-header-icon {
        width: 44px;
        height: 44px;
        background-color: #5B6D92;
        color: #ffffff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .btn-generate-meja {
        background-color: #43557A;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 10px 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(67, 85, 122, 0.25);
        transition: all 0.2s ease;
    }

    .btn-generate-meja:hover {
        background-color: #354463;
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* Search & Stats Bar */
    .search-input-box {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 30px;
        padding: 9px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .search-input-box input {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        font-size: 0.9rem;
        color: #334155;
    }

    .badge-stat-pill {
        background-color: #ffffff;
        color: #334155;
        border: 1px solid #e2e8f0;
        border-radius: 30px;
        padding: 8px 16px;
        font-weight: 700;
        font-size: 0.85rem;
        white-space: nowrap;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .badge-stat-pill.active-pill {
        background-color: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    /* Table Styling Sesuai Gambar */
    .table-meja-card {
        background-color: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #e9ecef;
    }

    .table-meja-card table {
        margin-bottom: 0;
    }

    .table-meja-card thead th {
        background-color: #E2ECF3 !important;
        color: #5B6D92 !important;
        font-size: 0.82rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        padding: 16px 20px;
        border-bottom: none;
        text-transform: uppercase;
    }

    .table-meja-card tbody td {
        padding: 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        background-color: #ffffff !important;
    }

    /* Badge No Meja */
    .meja-number-card {
        background-color: #f1f5f9;
        border-radius: 10px;
        padding: 6px 12px;
        display: inline-block;
        text-align: left;
        line-height: 1.15;
    }

    .meja-number-card .label {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 700;
        display: block;
    }

    .meja-number-card .number {
        font-size: 1.1rem;
        color: #1e293b;
        font-weight: 800;
    }

    /* QR Code Display */
    .qr-thumb-box {
        width: 74px;
        height: 74px;
        border-radius: 8px;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
    }

    .qr-thumb-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Token Badge */
    .token-badge {
        background-color: #dbeafe;
        color: #2563eb;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-family: monospace;
    }

    /* Toggle Switch Hijau Sesuai Gambar */
    .form-switch .form-check-input {
        width: 44px;
        height: 24px;
        cursor: pointer;
        background-color: #cbd5e1;
        border-color: #cbd5e1;
    }

    .form-switch .form-check-input:checked {
        background-color: #16a34a;
        border-color: #16a34a;
    }

    .status-text-aktif {
        font-size: 0.85rem;
        font-weight: 700;
        color: #16a34a;
        margin-top: 4px;
    }

    .status-text-nonaktif {
        font-size: 0.85rem;
        font-weight: 700;
        color: #dc2626;
        margin-top: 4px;
    }

    /* Action Buttons */
    .btn-action-cetak {
        background-color: #ffffff;
        color: #2563eb;
        border: 1.5px solid #60a5fa;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-action-cetak:hover {
        background-color: #eff6ff;
        color: #1d4ed8;
    }

    .btn-action-delete {
        background-color: #ffffff;
        color: #dc2626;
        border: 1.5px solid #f87171;
        border-radius: 10px;
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-action-delete:hover {
        background-color: #fef2f2;
        color: #b91c1c;
    }

    .copy-link-btn {
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 0 4px;
        font-size: 0.85rem;
    }
    .copy-link-btn:hover {
        color: #1e293b;
    }
</style>

<!-- Top Header Section -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="meja-header-icon shadow-sm">
            <i class="fa-solid fa-qrcode"></i>
        </div>
        <div>
            <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.35rem; letter-spacing: -0.3px;">Kelola Data Meja & QR Code</h3>
            <p class="text-muted small mb-0">Generate Meja baru, tampilkan QR Code, dan cetak stiker QR untuk ditempel di meja.</p>
        </div>
    </div>
    
    <form action="{{ route('owner.meja.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn-generate-meja">
            <i class="fa-solid fa-circle-plus fs-5"></i>
            <span>Generate Meja Baru</span>
        </button>
    </form>
</div>

<!-- Search Input & Status Pills Bar -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div class="flex-grow-1" style="max-width: 620px;">
        <div class="search-input-box">
            <i class="fa-solid fa-magnifying-glass text-secondary"></i>
            <input type="text" id="cariMejaInput" placeholder="Cari meja atau token" onkeyup="filterTabelMeja()">
        </div>
    </div>

    @php
        $totalMejaCount = $meja->count();
        $aktifMejaCount = $meja->where('is_active', true)->count();
    @endphp
    <div class="d-flex align-items-center gap-2">
        <span class="badge-stat-pill">
            {{ $totalMejaCount }} meja
        </span>
        <span class="badge-stat-pill active-pill">
            {{ $aktifMejaCount }} aktif
        </span>
    </div>
</div>

<!-- Tabel Data Meja Sesuai Mockup -->
<div class="table-meja-card">
    <div class="table-responsive">
        <table class="table align-middle" id="tabelMeja">
            <thead>
                <tr>
                    <th style="width: 110px;">NO MEJA</th>
                    <th style="width: 100px;">QR CODE</th>
                    <th>TOKEN DAN LINK KATALOG</th>
                    <th style="width: 140px;">STATUS</th>
                    <th class="text-end pe-4" style="width: 180px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($meja as $m)
                @php
                    $nomorDisplay = $m->nomor_meja ?? $m->id;
                    $qrUrl = url('/meja/'.$m->qr_token);
                    $qrImageApi = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($qrUrl);
                @endphp
                <tr class="baris-meja">
                    <!-- NO MEJA -->
                    <td>
                        <div class="meja-number-card">
                            <span class="label">Meja</span>
                            <span class="number">#{{ $nomorDisplay }}</span>
                        </div>
                    </td>

                    <!-- QR CODE -->
                    <td>
                        <div class="qr-thumb-box">
                            <img src="{{ $qrImageApi }}" alt="QR #{{ $nomorDisplay }}">
                        </div>
                    </td>

                    <!-- TOKEN DAN LINK KATALOG -->
                    <td>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="text-secondary small fw-semibold">Token</span>
                            <span class="token-badge">{{ $m->qr_token }}</span>
                        </div>
                        <div class="small text-secondary d-flex align-items-center gap-1 mb-1">
                            <i class="fa-solid fa-link" style="font-size: 0.8rem;"></i>
                            <span class="text-dark fw-medium text-truncate" style="max-width: 320px;">{{ $qrUrl }}</span>
                            <button type="button" class="copy-link-btn" title="Salin Link" onclick="salinLink('{{ $qrUrl }}')">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                        <div>
                            <a href="{{ $qrUrl }}" target="_blank" class="small fw-semibold text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.75rem;"></i>
                                <span>Buka katalog customer</span>
                            </a>
                        </div>
                    </td>

                    <!-- STATUS (TOGGLE SWITCH & LABEL "Aktif") -->
                    <td>
                        <form action="{{ route('owner.meja.update', $m->id) }}" method="POST" id="form-status-{{ $m->id }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="is_active" id="input-status-{{ $m->id }}" value="{{ $m->is_active ? 1 : 0 }}">
                            <div class="form-check form-switch p-0 m-0 d-flex flex-column align-items-start">
                                <input class="form-check-input m-0" type="checkbox" role="switch" id="switch-{{ $m->id }}" {{ $m->is_active ? 'checked' : '' }} onchange="toggleStatusMeja({{ $m->id }}, this)">
                                <span class="{{ $m->is_active ? 'status-text-aktif' : 'status-text-nonaktif' }}" id="status-label-{{ $m->id }}">
                                    {{ $m->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </form>
                    </td>

                    <!-- AKSI (CETAK QR & HAPUS) -->
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <button type="button" class="btn-action-cetak" onclick="cetakStikerQR('{{ $nomorDisplay }}', '{{ $m->qr_token }}', '{{ $qrImageApi }}', '{{ $qrUrl }}')">
                                <i class="fa-solid fa-print"></i>
                                <span>Cetak QR</span>
                            </button>

                            <form action="{{ route('owner.meja.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus Meja #{{ $nomorDisplay }} secara permanen?');" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action-delete" title="Hapus Meja">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-qrcode fs-1 opacity-25 mb-3 d-block"></i>
                        Belum ada meja yang terdaftar. Klik tombol <strong>"Generate Meja Baru"</strong> di atas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal / Window Template untuk Cetak Stiker QR Meja -->
<script>
function filterTabelMeja() {
    const input = document.getElementById('cariMejaInput').value.toLowerCase();
    const rows = document.querySelectorAll('.baris-meja');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
}

function toggleStatusMeja(id, checkbox) {
    const hiddenInput = document.getElementById('input-status-' + id);
    const form = document.getElementById('form-status-' + id);
    hiddenInput.value = checkbox.checked ? '1' : '0';
    form.submit();
}

function salinLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('Link katalog berhasil disalin: ' + url);
    }).catch(() => {
        prompt('Salin link katalog berikut:', url);
    });
}

function cetakStikerQR(idMeja, token, qrImgSrc, fullUrl) {
    var printWindow = window.open('', '_blank', 'width=600,height=700');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Cetak QR Stiker - Meja #${idMeja}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
            <style>
                @media print {
                    .no-print { display: none !important; }
                    body { background: white !important; padding: 0 !important; }
                    .stiker-card { border: 3px solid #5B6D92 !important; box-shadow: none !important; }
                }
                body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
                .stiker-card { background: white; width: 340px; border-radius: 20px; padding: 25px; text-align: center; border: 3px solid #5B6D92; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
                .brand-title { color: #5B6D92; font-weight: 800; font-size: 1.2rem; margin-bottom: 2px; }
                .table-title { font-size: 2rem; font-weight: 900; color: #1e293b; letter-spacing: -1px; margin-bottom: 15px; }
                .qr-box { background: #ffffff; padding: 15px; border-radius: 15px; border: 2px dashed #D5E3E6; display: inline-block; margin-bottom: 15px; }
                .qr-box img { width: 200px; height: 200px; }
                .instruction { font-size: 0.85rem; font-weight: 600; color: #475569; line-height: 1.3; }
                .btn-print-act { background: #5B6D92; color: white; border: none; padding: 10px 25px; border-radius: 50px; font-weight: bold; margin-bottom: 20px; cursor: pointer; box-shadow: 0 4px 12px rgba(91, 109, 146, 0.3); }
                .btn-print-act:hover { background: #4A5978; }
            </style>
        </head>
        <body>
            <div class="no-print">
                <button onclick="window.print()" class="btn-print-act"><i class="fa-solid fa-print me-2"></i> Cetak Stiker Sekarang</button>
            </div>
            
            <div class="stiker-card">
                <div class="brand-title"><i class="fa-solid fa-shapes me-1"></i> ASA JAYA PLAYGROUND</div>
                <div class="table-title">MEJA #${idMeja}</div>
                
                <div class="qr-box">
                    <img src="${qrImgSrc}" alt="QR Code Meja #${idMeja}">
                </div>
                
                <div class="instruction">
                    <i class="fa-solid fa-camera text-primary me-1"></i> <strong>SCAN QR CODE INI</strong><br>
                    Gunakan Kamera HP Anda untuk memilih Tiket & Jajanan langsung dari meja!
                </div>
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
}
</script>
@endsection