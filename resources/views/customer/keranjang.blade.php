<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Anda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #EFE1D1;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="86" height="86" viewBox="0 0 86 86"><rect width="86" height="86" fill="%23EFE1D1"/><g transform="translate(6, 3) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(92, 3) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(6, 89) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(92, 89) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><circle cx="47" cy="6" r="3.8" fill="%2384A8E8"/><circle cx="47" cy="92" r="3.8" fill="%2384A8E8"/><circle cx="33" cy="45" r="3.5" fill="%23F3D063"/><rect x="-6" y="62.5" width="12" height="5" rx="2.5" fill="%23C58DE8"/><rect x="80" y="62.5" width="12" height="5" rx="2.5" fill="%23C58DE8"/></svg>');
            background-repeat: repeat;
            padding-bottom: 120px;
        }
        .header { background: white; padding: 15px 20px; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .item-card { background: white; border-radius: 12px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); border: 1px solid #f0f0f0; }
        .bottom-bar { position: fixed; bottom: 0; left: 0; width: 100%; background: white; padding: 15px 20px; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); border-radius: 20px 20px 0 0; }
        .payment-method-card {
            background-color: #ffffff;
            border-color: #cbd5e1 !important;
            user-select: none;
        }
        .payment-method-card:has(input:checked) {
            border-color: #5B6D92 !important;
            background-color: #f1f5f9;
            box-shadow: 0 0 0 1px #5B6D92;
        }
        .payment-method-card:hover {
            border-color: #94a3b8 !important;
        }
    </style>
</head>
<body>
    <div class="header d-flex align-items-center">
        <a href="{{ route('katalog') }}" class="text-dark fs-4 me-3"><i class="fa-solid fa-arrow-left"></i></a>
        <h5 class="mb-0 fw-bold">Keranjang Pesanan</h5>
    </div>

    <div class="container mt-3">
        @if(session('error'))
            <div class="alert alert-danger p-2 small rounded"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
        @endif

        @if(empty($items))
            <div class="text-center mt-5 text-muted">
                <i class="fa-solid fa-basket-shopping fs-1 opacity-25 mb-3"></i>
                <p>Keranjang Anda masih kosong.<br>Yuk pilih tiket atau camilan dulu!</p>
                <a href="{{ route('katalog') }}" class="btn btn-primary rounded-pill mt-2">Lihat Katalog</a>
            </div>
        @else
            @php $total_harga = 0; @endphp
            @foreach($items as $item)
                @php $subtotal = $item['harga'] * $item['jumlah']; $total_harga += $subtotal; @endphp
                <div class="item-card d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $item['nama_produk'] }}</h6>
                        <div class="text-muted small">Rp {{ number_format($item['harga'], 0, ',', '.') }} &times; {{ $item['jumlah'] }}</div>
                        <div class="fw-bold text-primary">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                    </div>
                    <form action="{{ route('keranjang.hapus', $item['produk_id']) }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm rounded-circle" style="width: 35px; height: 35px;"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            @endforeach
            
            <div class="item-card mt-4">
                <form action="{{ route('checkout') }}" method="POST" id="checkoutForm">
                    @csrf
                    <div class="mb-3">
                        <label class="fw-bold small text-muted mb-1">NAMA PEMESAN / ANAK</label>
                        <input type="text" name="nama_pemesan" class="form-control bg-light" placeholder="Contoh: Budi" required>
                    </div>
                    @if(\App\Services\MidtransService::isConfigured())
                        <div class="mb-3">
                            <label class="fw-bold small text-muted mb-1.5">METODE PEMBAYARAN</label>
                            <div class="d-flex flex-column gap-2">
                                <!-- QRIS -->
                                <label class="payment-method-card d-flex align-items-center justify-content-between p-3 rounded-3 border" for="pay_qris_keranjang" style="cursor: pointer; transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="radio" name="metode_pembayaran" id="pay_qris_keranjang" value="payment_gateway" class="form-check-input m-0" checked>
                                        <div>
                                            <div class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                                <i class="fa-solid fa-qrcode text-primary"></i>
                                                <span>QRIS (Scan & Bayar Otomatis)</span>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.725rem;">GoPay, OVO, DANA, BCA, ShopeePay, dll.</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">Otomatis</span>
                                </label>

                                <!-- Tunai -->
                                <label class="payment-method-card d-flex align-items-center justify-content-between p-3 rounded-3 border" for="pay_tunai_keranjang" style="cursor: pointer; transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="radio" name="metode_pembayaran" id="pay_tunai_keranjang" value="tunai" class="form-check-input m-0">
                                        <div>
                                            <div class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                                <i class="fa-solid fa-money-bill-wave text-success"></i>
                                                <span>Tunai</span>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.725rem;">Bayar langsung di meja kasir</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1">Di Kasir</span>
                                </label>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="metode_pembayaran" value="tunai">
                        <div class="mb-2">
                            <label class="fw-bold small text-muted mb-1">METODE PEMBAYARAN</label>
                            <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-secondary px-2.5 py-1.5 fw-bold" style="font-size: 0.8rem;">
                                        <i class="fa-solid fa-money-bill-wave me-1"></i>TUNAI
                                    </span>
                                    <span class="fw-bold text-dark small">Bayar di Kasir</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        @endif
    </div>

    @if(!empty($items))
    <div class="bottom-bar">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fw-bold">Total Pembayaran</span>
            <span class="fs-4 fw-bold text-dark">Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
        </div>
        <button onclick="document.getElementById('checkoutForm').submit();" class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-6 shadow-sm">
            KIRIM PESANAN SEKARANG
        </button>
    </div>
    @endif
</body>
</html>