<?php

$dir = __DIR__;

// 1. Welcome / Landing Page
$welcome = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asa Jaya Playground</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .hero { background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); color: white; padding: 100px 20px; text-align: center; border-radius: 0 0 50px 50px; margin-bottom: 40px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .hero h1 { font-weight: 800; font-size: 3rem; }
        .icon-huge { font-size: 5rem; margin-bottom: 20px; color: #ffc107; text-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .feature-card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; text-align: center; padding: 30px 20px; }
        .feature-card:hover { transform: translateY(-10px); }
        .feature-card i { font-size: 3rem; color: #0d6efd; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="hero">
        <i class="fa-solid fa-shapes icon-huge"></i>
        <h1>Asa Jaya Playground</h1>
        <p class="lead mt-3">Wahana bermain anak terbaik, aman, dan menyenangkan!</p>
        <div class="mt-4">
            <a href="/login" class="btn btn-warning btn-lg fw-bold rounded-pill px-4 shadow"><i class="fa-solid fa-right-to-bracket"></i> Login Karyawan</a>
        </div>
    </div>

    <div class="container mb-5">
        <div class="text-center mb-5">
            <h3 class="fw-bold text-dark">Cara Memesan Tiket & Jajanan</h3>
            <p class="text-muted">Untuk pelanggan setia Asa Jaya Playground</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <i class="fa-solid fa-qrcode"></i>
                    <h5 class="fw-bold">1. Scan QR Code</h5>
                    <p class="text-muted small">Cari QR Code yang tertempel di meja tunggu atau loket, lalu scan menggunakan kamera HP Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h5 class="fw-bold">2. Pilih Menu & Tiket</h5>
                    <p class="text-muted small">Pilih tiket wahana atau jajanan yang Anda inginkan langsung dari layar HP Anda tanpa perlu antre panjang.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <i class="fa-solid fa-bell-concierge"></i>
                    <h5 class="fw-bold">3. Bayar di Kasir</h5>
                    <p class="text-muted small">Tunjukkan kode pesanan ke kasir kami, lakukan pembayaran, dan nikmati waktu bermain Anda!</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 text-muted bg-white border-top">
        <small>&copy; {{ date('Y') }} Asa Jaya Playground. All rights reserved.</small>
    </footer>
</body>
</html>
HTML;
file_put_contents($dir.'/resources/views/welcome.blade.php', $welcome);

// 2. Customer Katalog
$katalog = <<<HTML
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
            @php \$cart = session()->get('keranjang', []); \$count = array_sum(array_column(\$cart, 'jumlah')); @endphp
            @if(\$count > 0) <span class="badge-cart">{{ \$count }}</span> @endif
        </a>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-pill text-center py-2 shadow-sm" role="alert">
                <small><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</small>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" style="padding: 0.8rem;"></button>
            </div>
        @endif

        @foreach(\$kategori as \$kat)
            @if(\$kat->produk->where('is_active', true)->count() > 0)
                <h5 class="fw-bold mb-3 mt-4 text-dark border-bottom pb-2">{{ \$kat->nama_kategori }}</h5>
                <div class="row g-3">
                    @foreach(\$kat->produk->where('is_active', true) as \$p)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card product-card h-100">
                            @if(\$p->foto_produk)
                                <img src="{{ asset('storage/'.\$p->foto_produk) }}" class="product-img" alt="{{ \$p->nama_produk }}">
                            @else
                                <div class="product-img d-flex align-items-center justify-content-center text-muted fs-1"><i class="fa-solid fa-image opacity-25"></i></div>
                            @endif
                            <div class="card-body p-2 px-3 d-flex flex-column">
                                <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">{{ \$p->nama_produk }}</h6>
                                <p class="text-primary fw-bold mb-2 small">Rp {{ number_format(\$p->harga, 0, ',', '.') }}</p>
                                <div class="mt-auto">
                                    <form action="{{ route('keranjang.tambah') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="produk_id" value="{{ \$p->id }}">
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="number" name="jumlah" class="form-control text-center" value="1" min="1" max="{{ \$p->stok }}" required>
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

    @if(\$count > 0)
        @php \$total = 0; foreach(\$cart as \$c) { \$total += \$c['harga'] * \$c['jumlah']; } @endphp
        <a href="{{ route('keranjang.lihat') }}" class="cart-float">
            <span><i class="fa-solid fa-basket-shopping me-2"></i> {{ \$count }} Item</span>
            <span>Rp {{ number_format(\$total, 0, ',', '.') }} <i class="fa-solid fa-chevron-right ms-2"></i></span>
        </a>
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
file_put_contents($dir.'/resources/views/customer/katalog.blade.php', $katalog);

// 3. Customer Keranjang
$keranjang = <<<HTML
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

        @if(empty(\$keranjang))
            <div class="text-center mt-5 text-muted">
                <i class="fa-solid fa-basket-shopping fs-1 opacity-25 mb-3"></i>
                <p>Keranjang Anda masih kosong.<br>Yuk pilih tiket atau camilan dulu!</p>
                <a href="{{ route('katalog') }}" class="btn btn-primary rounded-pill mt-2">Lihat Katalog</a>
            </div>
        @else
            @php \$total_harga = 0; @endphp
            @foreach(\$keranjang as \$id => \$item)
                @php \$subtotal = \$item['harga'] * \$item['jumlah']; \$total_harga += \$subtotal; @endphp
                <div class="item-card d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">{{ \$item['nama'] }}</h6>
                        <div class="text-muted small">Rp {{ number_format(\$item['harga'], 0, ',', '.') }} &times; {{ \$item['jumlah'] }}</div>
                        <div class="fw-bold text-primary">Rp {{ number_format(\$subtotal, 0, ',', '.') }}</div>
                    </div>
                    <form action="{{ route('keranjang.hapus', \$id) }}" method="POST">
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

    @if(!empty(\$keranjang))
    <div class="bottom-bar">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted fw-bold">Total Pembayaran</span>
            <span class="fs-4 fw-bold text-dark">Rp {{ number_format(\$total_harga, 0, ',', '.') }}</span>
        </div>
        <button onclick="document.getElementById('checkoutForm').submit();" class="btn btn-primary w-100 py-3 rounded-pill fw-bold fs-6 shadow-sm">
            KIRIM PESANAN SEKARANG
        </button>
    </div>
    @endif
</body>
</html>
HTML;
file_put_contents($dir.'/resources/views/customer/keranjang.blade.php', $keranjang);

// 4. Customer Status
$status = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: sans-serif; }
        .ticket { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-top: 40px; }
        .ticket-header { background: #0d6efd; color: white; padding: 30px 20px; text-align: center; position: relative; }
        .ticket-header::after { content: ''; position: absolute; bottom: -15px; left: 0; right: 0; border-bottom: 30px dotted #f0f2f5; }
        .ticket-body { padding: 40px 30px 30px; }
        .status-badge { display: inline-block; padding: 8px 20px; border-radius: 30px; font-weight: bold; margin-bottom: 20px; }
        .bg-pending { background-color: #fff3cd; color: #856404; }
        .bg-lunas { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                @if(session('success'))
                    <div class="alert alert-success mt-4 mb-0 text-center rounded-pill"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
                @endif

                <div class="ticket">
                    <div class="ticket-header">
                        <p class="mb-1 opacity-75 small text-uppercase tracking-wider">Kode Pesanan</p>
                        <h1 class="fw-bold mb-0 tracking-widest">{{ \$transaksi->kode_transaksi }}</h1>
                    </div>
                    <div class="ticket-body text-center">
                        <h5 class="fw-bold mb-1">{{ \$transaksi->nama_pemesan }}</h5>
                        <p class="text-muted small mb-4"><i class="fa-solid fa-chair"></i> Meja/Spot: {{ \$transaksi->meja_id ?? '-' }}</p>
                        
                        <div class="status-badge {{ \$transaksi->status_pembayaran == 'lunas' ? 'bg-lunas' : 'bg-pending' }}">
                            <i class="fa-solid {{ \$transaksi->status_pembayaran == 'lunas' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            Pembayaran: {{ strtoupper(\$transaksi->status_pembayaran) }}
                        </div>

                        <div class="text-start bg-light p-3 rounded mb-4">
                            <ul class="list-unstyled mb-0 small">
                                @foreach(\$transaksi->detailTransaksi as \$dt)
                                    <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                        <span>{{ \$dt->jumlah }}x {{ \$dt->produk->nama_produk ?? 'Item Dihapus' }}</span>
                                        <span class="fw-semibold">Rp {{ number_format(\$dt->subtotal, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="d-flex justify-content-between mt-3">
                                <span class="fw-bold text-muted">TOTAL</span>
                                <span class="fw-bold fs-5 text-primary">Rp {{ number_format(\$transaksi->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if(\$transaksi->status_pembayaran == 'pending')
                            <div class="alert alert-warning small text-start">
                                <strong><i class="fa-solid fa-circle-info"></i> Tunjukkan kode ini!</strong><br>
                                Silakan bawa HP Anda ke kasir dan tunjukkan kode <b>#{{ \$transaksi->kode_transaksi }}</b> untuk melakukan pembayaran secara Tunai.
                            </div>
                        @else
                            <div class="alert alert-success small">
                                <i class="fa-solid fa-face-smile"></i> Pembayaran Lunas. Selamat bermain!
                            </div>
                        @endif

                        <a href="{{ route('katalog') }}" class="btn btn-outline-secondary w-100 rounded-pill fw-bold mt-2"><i class="fa-solid fa-arrow-left"></i> Kembali ke Menu</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
HTML;
file_put_contents($dir.'/resources/views/customer/status.blade.php', $status);

echo "Revamp UI Customer Selesai.";
