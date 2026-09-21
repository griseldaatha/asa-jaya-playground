<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .header { background-color: #ff9800; color: white; padding: 15px; text-align: center; }
        .container { max-width: 600px; margin: 0 auto; padding: 15px; }
        .item-card { background: white; border-radius: 8px; padding: 15px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .item-header { display: flex; justify-content: space-between; font-weight: bold; }
        .item-sub { color: #555; font-size: 14px; margin-top: 5px; }
        .btn-hapus { background: #f44336; color: white; border: none; padding: 3px 8px; border-radius: 4px; cursor: pointer; }
        .checkout-box { background: white; border-radius: 8px; padding: 20px; margin-top: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-checkout { background: #4CAF50; color: white; border: none; padding: 15px; width: 100%; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Keranjang Anda</h2>
    </div>

    <div class="container">
        <a href="{{ route('katalog') }}" style="color: #ff9800; text-decoration: none;">&larr; Kembali ke Menu</a>
        <br><br>

        @if(session('error'))
            <div style="background: #ffcdd2; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($items))
            <div style="text-align:center; padding: 40px; color:#777;">
                Keranjang masih kosong.
            </div>
        @else
            <!-- Daftar Pesanan -->
            @foreach($items as $id => $item)
                <div class="item-card">
                    <div class="item-header">
                        <span>{{ $item['jumlah'] }}x {{ $item['nama_produk'] }}</span>
                        <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="item-sub">
                        @if($item['catatan']) Catatan: {{ $item['catatan'] }} <br> @endif
                        <form action="{{ route('keranjang.hapus', $item['produk_id']) }}" method="POST" style="display:inline; margin-top:5px;">
                            @csrf
                            <button type="submit" class="btn-hapus">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <!-- Kotak Checkout -->
            <div class="checkout-box">
                <h3 style="margin-top: 0;">Total Pembayaran: Rp {{ number_format($total, 0, ',', '.') }}</h3>
                <hr>
                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Pemesan</label>
                        <input type="text" name="nama_pemesan" required placeholder="Contoh: Budi">
                    </div>
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select name="metode_pembayaran" required>
                            <option value="tunai">Tunai (Bayar di Kasir)</option>
                            <option value="payment_gateway" disabled>Transfer / QRIS (Midtrans - Belum Tersedia)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-checkout">Pesan Sekarang</button>
                </form>
            </div>
        @endif

    </div>

</body>
</html>
