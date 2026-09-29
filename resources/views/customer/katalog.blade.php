<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Asa Jaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; padding-bottom: 80px; }
        .header { background: #0d6efd; color: white; padding: 15px 20px; border-radius: 0 0 20px 20px; margin-bottom: 20px; position: sticky; top: 0; z-index: 100; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .product-card { border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: 0.2s; }
        .product-card:active { transform: scale(0.98); }
        .product-img { width: 100%; height: 160px; object-fit: cover; background-color: #e9ecef; }
        .cart-float { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: 90%; max-width: 400px; background: #ffc107; color: #000; border-radius: 30px; padding: 12px 25px; box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4); display: flex; justify-content: space-between; align-items: center; z-index: 1000; text-decoration: none; font-weight: bold; }
        .badge-cart { background: #dc3545; color: white; border-radius: 50%; padding: 4px 8px; font-size: 0.8rem; position: absolute; top: -5px; right: -5px; }
    </style>
</head>
<body>
    <div class="header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-shapes text-warning"></i> Asa Jaya</h5>
            <small class="opacity-75">Meja/Spot: {{ Session::get('meja_id', '-') }}</small>
        </div>
        <a href="{{ route('keranjang.lihat') }}" class="text-white position-relative fs-4">
            <i class="fa-solid fa-basket-shopping"></i>
            @php $cart = session()->get('keranjang', []); $count = array_sum(array_column($cart, 'jumlah')); @endphp
            @if($count > 0) <span class="badge-cart">{{ $count }}</span> @endif
        </a>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-pill text-center py-2 shadow-sm" role="alert">
                <small><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</small>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" style="padding: 0.8rem;"></button>
            </div>
        @endif

        @foreach($kategori as $kat)
            @if($kat->produk->where('is_active', true)->count() > 0)
                <h5 class="fw-bold mb-3 mt-4 text-dark border-bottom pb-2">{{ $kat->nama_kategori }}</h5>
                <div class="row g-3">
                    @foreach($kat->produk->where('is_active', true) as $p)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card product-card h-100">
                            @if($p->foto_produk)
                                <img src="{{ asset('storage/'.$p->foto_produk) }}" class="product-img" alt="{{ $p->nama_produk }}">
                            @else
                                <div class="product-img d-flex align-items-center justify-content-center text-muted fs-1"><i class="fa-solid fa-image opacity-25"></i></div>
                            @endif
                            <div class="card-body p-2 px-3 d-flex flex-column">
                                <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">{{ $p->nama_produk }}</h6>
                                <p class="text-primary fw-bold mb-2 small">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                                <div class="mt-auto">
                                    <form action="{{ route('keranjang.tambah') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="produk_id" value="{{ $p->id }}">
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="number" name="jumlah" class="form-control text-center" value="1" min="1" max="{{ $p->stok }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold rounded-pill"><i class="fa-solid fa-plus"></i> Tambah</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>

    @if($count > 0)
        @php $total = 0; foreach($cart as $c) { $total += $c['harga'] * $c['jumlah']; } @endphp
        <a href="{{ route('keranjang.lihat') }}" class="cart-float">
            <span><i class="fa-solid fa-basket-shopping me-2"></i> {{ $count }} Item</span>
            <span>Rp {{ number_format($total, 0, ',', '.') }} <i class="fa-solid fa-chevron-right ms-2"></i></span>
        </a>
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>