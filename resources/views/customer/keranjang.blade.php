<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Anda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; padding-bottom: 120px; }
        .header { background: white; padding: 15px 20px; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .item-card { background: white; border-radius: 12px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); border: 1px solid #f0f0f0; }
        .bottom-bar { position: fixed; bottom: 0; left: 0; width: 100%; background: white; padding: 15px 20px; box-shadow: 0 -4px 15px rgba(0,0,0,0.05); border-radius: 20px 20px 0 0; }
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
                    <div class="mb-2">
                        <label class="fw-bold small text-muted mb-1">METODE PEMBAYARAN</label>
                        <select name="metode_pembayaran" class="form-select bg-light">
                            <option value="tunai">Tunai di Kasir</option>
                        </select>
                    </div>
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