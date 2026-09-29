<?php

$dir = __DIR__;
$controller_file = $dir . '/app/Http/Controllers/CustomerController.php';
$controller_content = file_get_contents($controller_file);

// 1. Modify CustomerController@katalog
$new_katalog_method = <<<PHP
    public function katalog(Request \$request)
    {
        if (! \$request->session()->has('meja_id')) {
            return response('Silakan scan QR Code di meja Anda terlebih dahulu untuk melihat menu.', 403);
        }

        \$meja_id = \$request->session()->get('meja_id');
        \$kategori_produk = KategoriProduk::with(['produk' => function (\$query) {
            \$query->where('is_active', true)->where('stok', '>', 0);
        }])->get();

        // Ambil data keranjang untuk ditampilkan di sidebar kanan
        \$keranjang_session = session()->get('keranjang', []);
        \$total = 0;
        \$items = [];
        foreach (\$keranjang_session as \$id => \$item) {
            \$produk = Produk::find(\$id);
            if (\$produk && \$produk->is_active) {
                \$subtotal = \$produk->harga * \$item['jumlah'];
                \$total += \$subtotal;
                \$item['harga'] = \$produk->harga;
                \$item['subtotal'] = \$subtotal;
                \$items[] = \$item;
            }
        }

        return view('customer.katalog', compact('kategori_produk', 'meja_id', 'items', 'total'));
    }
PHP;

// Find and replace the katalog method
$pattern = '/public function katalog\(Request \$request\).*?return view\(\'customer\.katalog\', compact\(\'kategori_produk\', \'meja_id\'\)\);\s*}/s';
$controller_content = preg_replace($pattern, $new_katalog_method, $controller_content);
file_put_contents($controller_file, $controller_content);


// 2. Rewrite customer/katalog.blade.php
$katalog_view = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Asa Jaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f1ebd9; /* Warna beige seperti di foto */ font-family: 'Segoe UI', sans-serif; height: 100vh; overflow: hidden; }
        
        .layout-container { display: flex; height: 100vh; }
        
        /* LEFT SIDE - MENU */
        .left-side { flex: 1; padding: 20px; overflow-y: auto; }
        
        /* Horizontal Category Pills */
        .category-scroll { display: flex; overflow-x: auto; gap: 10px; padding-bottom: 15px; scrollbar-width: none; }
        .category-scroll::-webkit-scrollbar { display: none; }
        .cat-pill { background: white; border: none; border-radius: 12px; padding: 10px 20px; font-weight: bold; color: #555; white-space: nowrap; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-decoration: none; transition: 0.2s; }
        .cat-pill:hover, .cat-pill.active { background: white; color: #2e7d32; border: 2px solid #2e7d32; }
        
        /* Product Cards */
        .product-card { background: white; border-radius: 15px; overflow: hidden; padding: 12px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.05); height: 100%; display: flex; flex-direction: column; position: relative; }
        .badge-new { position: absolute; top: 12px; left: 12px; background: #2e7d32; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: bold; z-index: 2; }
        .product-img { width: 100%; height: 140px; object-fit: cover; border-radius: 10px; margin-bottom: 12px; background-color: #eee; }
        .product-title { font-weight: bold; font-size: 0.95rem; line-height: 1.2; margin-bottom: 5px; color: #333; }
        .product-price { font-weight: bold; font-size: 0.85rem; color: #000; margin-bottom: 15px; }
        .btn-tambah { background: #388e3c; color: white; border: none; width: 100%; padding: 8px; border-radius: 8px; font-size: 0.85rem; font-weight: bold; transition: 0.2s; }
        .btn-tambah:hover { background: #2e7d32; }

        /* RIGHT SIDE - CART */
        .right-side { width: 350px; background: white; box-shadow: -5px 0 15px rgba(0,0,0,0.05); border-radius: 20px 0 0 20px; display: flex; flex-direction: column; }
        @media (max-width: 991px) {
            .layout-container { flex-direction: column; overflow-y: auto; height: auto; }
            .left-side { overflow-y: visible; padding-bottom: 0; }
            .right-side { width: 100%; border-radius: 20px 20px 0 0; margin-top: 20px; min-height: 400px; }
        }

        .cart-header { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .cart-body { flex: 1; overflow-y: auto; padding: 20px; }
        .cart-footer { padding: 20px; border-top: 1px solid #eee; background: #fff; border-radius: 0 0 0 20px; }
        
        .empty-cart { text-align: center; color: #777; margin-top: 50px; }
        .empty-cart i { font-size: 4rem; color: #c8e6c9; margin-bottom: 15px; }
        
        .cart-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #eee; }
        .cart-item-info h6 { font-weight: bold; font-size: 0.9rem; margin-bottom: 2px; }
        .cart-item-info small { color: #888; font-size: 0.8rem; }
    </style>
</head>
<body>
    <div class="layout-container">
        
        <!-- BAGIAN KIRI: MENU & KATALOG -->
        <div class="left-side">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold m-0"><i class="fa-solid fa-shapes text-warning"></i> Asa Jaya Playground</h4>
                <span class="badge bg-secondary">Meja/Spot: {{ \$meja_id }}</span>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible py-2 small" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible py-2 small" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Kategori Horizontal Pills -->
            <div class="category-scroll">
                @foreach(\$kategori_produk as \$kat)
                    @if(\$kat->produk->where('is_active', true)->count() > 0)
                        <a href="#kat-{{ \$kat->id }}" class="cat-pill">
                            <i class="fa-solid fa-tag text-success"></i> {{ \$kat->nama_kategori }}
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Daftar Produk -->
            <div class="mt-3 pb-5">
                @foreach(\$kategori_produk as \$kat)
                    @if(\$kat->produk->where('is_active', true)->count() > 0)
                        <h5 id="kat-{{ \$kat->id }}" class="fw-bold mb-3 mt-4" style="color: #444;">{{ \$kat->nama_kategori }}</h5>
                        <div class="row g-3">
                            @foreach(\$kat->produk->where('is_active', true) as \$p)
                            <div class="col-6 col-md-4 col-xl-3">
                                <div class="product-card">
                                    <span class="badge-new">Tersedia</span>
                                    @if(\$p->foto_produk)
                                        <img src="{{ asset('storage/'.\$p->foto_produk) }}" class="product-img" alt="{{ \$p->nama_produk }}">
                                    @else
                                        <div class="product-img d-flex align-items-center justify-content-center text-muted fs-3"><i class="fa-solid fa-image opacity-25"></i></div>
                                    @endif
                                    
                                    <div class="product-title">{{ \$p->nama_produk }}</div>
                                    <div class="product-price">Rp {{ number_format(\$p->harga, 0, ',', '.') }}</div>
                                    
                                    <div class="mt-auto">
                                        <form action="{{ route('keranjang.tambah') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="produk_id" value="{{ \$p->id }}">
                                            <input type="hidden" name="jumlah" value="1">
                                            <button type="submit" class="btn-tambah">Tambah</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- BAGIAN KANAN: KERANJANG (SIDEBAR) -->
        <div class="right-side">
            <div class="cart-header">
                <h5 class="fw-bold m-0">Pesanan Anda</h5>
                <span class="badge bg-success rounded-pill">{{ count(\$items) }} Item</span>
            </div>
            
            <div class="cart-body">
                @if(empty(\$items))
                    <div class="empty-cart">
                        <i class="fa-solid fa-bell-concierge"></i>
                        <h6 class="fw-bold text-dark mt-3">Belum ada menu yang dipilih</h6>
                        <p class="small">Silakan tambahkan menu dari katalog di sebelah kiri.</p>
                    </div>
                @else
                    @foreach(\$items as \$item)
                        <div class="cart-item">
                            <div class="cart-item-info">
                                <h6>{{ \$item['nama_produk'] }}</h6>
                                <small>Rp {{ number_format(\$item['harga'], 0, ',', '.') }} &times; {{ \$item['jumlah'] }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold mb-1">Rp {{ number_format(\$item['subtotal'], 0, ',', '.') }}</div>
                                <form action="{{ route('keranjang.hapus', \$item['produk_id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-sm text-danger p-0"><i class="fa-solid fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            @if(!empty(\$items))
            <div class="cart-footer">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-bold">Total Pembayaran</span>
                    <span class="fs-4 fw-bold text-success">Rp {{ number_format(\$total, 0, ',', '.') }}</span>
                </div>
                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <input type="text" name="nama_pemesan" class="form-control mb-2" placeholder="Nama Pemesan (Wajib)" required>
                    <input type="hidden" name="metode_pembayaran" value="tunai">
                    <button type="submit" class="btn-tambah" style="font-size: 1rem; padding: 12px;">Pesan Sekarang</button>
                </form>
            </div>
            @endif
        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
file_put_contents($dir.'/resources/views/customer/katalog.blade.php', $katalog_view);

echo "Layout split screen (Katalog + Keranjang) berhasil dibuat!";
