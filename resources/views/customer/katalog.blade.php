<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Menu Playground</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .header { background-color: #ff9800; color: white; padding: 15px; text-align: center; }
        .container { max-width: 600px; margin: 0 auto; padding: 15px; }
        .kategori-title { border-bottom: 2px solid #ff9800; margin-top: 30px; padding-bottom: 5px; }
        .produk-card { background: white; border-radius: 8px; padding: 15px; margin-bottom: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .produk-info h3 { margin: 0 0 5px 0; font-size: 18px; }
        .produk-info p { margin: 0; color: #555; }
        .produk-harga { font-weight: bold; color: #d32f2f; }
        .empty-state { text-align: center; padding: 50px 20px; color: #777; }
        .meja-info { background: #fff3e0; padding: 10px; text-align: center; font-weight: bold; margin-bottom: 20px; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Menu Playground</h2>
    </div>

    <div class="container">
        <div class="meja-info">
            Anda berada di Meja #{{ $meja_id }}
        </div>

        @php $ada_produk = false; @endphp

        @foreach($kategori_produk as $kategori)
            @if($kategori->produk->count() > 0)
                @php $ada_produk = true; @endphp
                <h3 class="kategori-title">{{ $kategori->nama_kategori }}</h3>
                
                @foreach($kategori->produk as $item)
                    <div class="produk-card">
                        <div class="produk-info">
                            <h3>{{ $item->nama_produk }}</h3>
                            <p>Stok: {{ $item->stok }}</p>
                        </div>
                        <div class="produk-harga">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                            
                            <form action="{{ route('keranjang.tambah') }}" method="POST" style="margin-top: 10px;">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $item->id }}">
                                <input type="number" name="jumlah" value="1" min="1" max="{{ $item->stok }}" style="width: 50px;">
                                <button type="submit" style="background:#4CAF50; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">Tambah</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach

        @if(!$ada_produk)
            <div class="empty-state">
                <h3>Belum ada produk tersedia saat ini.</h3>
                <p>Silakan tanyakan kepada kasir atau kembali lagi nanti.</p>
            </div>
        @else
            <!-- Tombol Lihat Keranjang Melayang -->
            <a href="{{ route('keranjang.lihat') }}" style="display:block; position:fixed; bottom:20px; left:50%; transform:translateX(-50%); background:#ff9800; color:white; text-decoration:none; padding:15px 30px; border-radius:30px; font-weight:bold; box-shadow:0 4px 6px rgba(0,0,0,0.2);">
                Lihat Keranjang ({{ count(session('keranjang', [])) }})
            </a>
            <br><br><br>
        @endif

    </div>

</body>
</html>
