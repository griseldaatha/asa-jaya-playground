<?php

$dir = __DIR__;
$view_file = $dir . '/resources/views/owner/meja/index.blade.php';

$blade_code = <<<'BLADE'
@extends('layouts.admin')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="page-title"><i class="fa-solid fa-qrcode text-primary me-2"></i>Kelola Data Meja & QR Code</h3>
        <p class="page-subtitle mb-0">Generate Meja baru, tampilkan QR Code, dan cetak stiker QR untuk ditempel di meja.</p>
    </div>
    <form action="{{ route('owner.meja.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary shadow-sm px-3 py-2 fw-bold d-flex align-items-center gap-2">
            <i class="fa-solid fa-plus-circle fs-5"></i>
            <span>Generate Meja Baru</span>
        </button>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-center">
                <thead>
                    <tr>
                        <th style="width: 100px;">NO MEJA</th>
                        <th style="width: 130px;">GAMBAR QR CODE</th>
                        <th>TOKEN & LINK KATALOG</th>
                        <th>STATUS LAYANAN</th>
                        <th class="text-end pe-4" style="width: 220px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meja as $m)
                    @php
                        $qrUrl = url('/meja/'.$m->qr_token);
                        $qrImageApi = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($qrUrl);
                    @endphp
                    <tr>
                        <td class="fw-bold fs-5 text-dark">
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-3">Meja #{{ $m->id }}</span>
                        </td>
                        <td>
                            <div class="p-1 bg-white border rounded-3 d-inline-block shadow-sm">
                                <img src="{{ $qrImageApi }}" alt="QR Meja #{{ $m->id }}" width="80" height="80" class="rounded-2">
                            </div>
                        </td>
                        <td class="text-start">
                            <div class="fw-bold text-primary fs-6 mb-1">Token: <span class="badge bg-primary-subtle text-primary border font-monospace px-2">{{ $m->qr_token }}</span></div>
                            <small class="text-muted d-block text-truncate" style="max-width: 250px;">
                                <i class="fa-solid fa-link me-1"></i> {{ $qrUrl }}
                            </small>
                            <a href="{{ $qrUrl }}" target="_blank" class="btn btn-link btn-sm p-0 text-decoration-none small text-primary mt-1">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Katalog Customer
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('owner.meja.update', $m->id) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="is_active" class="form-select form-select-sm w-auto mx-auto fw-bold {{ $m->is_active ? 'border-success text-success bg-success-subtle' : 'border-danger text-danger bg-danger-subtle' }}" onchange="this.form.submit()">
                                    <option value="1" {{ $m->is_active ? 'selected' : '' }}>✓ Aktif Melayani</option>
                                    <option value="0" {{ !$m->is_active ? 'selected' : '' }}>✕ Nonaktif / Rusak</option>
                                </select>
                            </form>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold d-flex align-items-center gap-1" onclick="cetakStikerQR('{{ $m->id }}', '{{ $m->qr_token }}', '{{ $qrImageApi }}', '{{ $qrUrl }}')">
                                    <i class="fa-solid fa-print"></i> Cetak QR
                                </button>
                                <form action="{{ route('owner.meja.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus Meja #{{ $m->id }} secara permanen?');" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
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
</div>

<!-- Modal / Window Template untuk Cetak Stiker QR Meja -->
<script>
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
BLADE;

file_put_contents($view_file, $blade_code);
echo "Meja page updated with QR Code column and instant printing modal!";
