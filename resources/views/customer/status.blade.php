<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan</title>
    <style>
        body { font-family: sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .header { background-color: #ff9800; color: white; padding: 15px; text-align: center; }
        .container { max-width: 600px; margin: 0 auto; padding: 15px; }
        .card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 15px; color: white; font-size: 14px; font-weight: bold; }
        .badge-pending { background-color: #ff9800; }
        .badge-lunas { background-color: #4CAF50; }
        .badge-menunggu { background-color: #9e9e9e; }
        .badge-diproses { background-color: #2196F3; }
        .badge-selesai { background-color: #4CAF50; }
        .list-item { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding: 10px 0; }
        .list-item:last-child { border-bottom: none; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Detail Pesanan Anda</h2>
    </div>

    <div class="container">
        <div class="card" style="text-align: center;">
            <p style="color: #777; margin: 0;">Kode Transaksi</p>
            <h2 style="margin: 5px 0;">{{ $transaksi->kode_transaksi }}</h2>
            <p><strong>Pemesan:</strong> {{ $transaksi->nama_pemesan }} | <strong>Meja:</strong> {{ $transaksi->meja->id ?? '-' }}</p>
        </div>

        <div class="card">
            <h3>Status Saat Ini</h3>
            <p>
                Status Pembayaran: 
                <span class="badge badge-{{ $transaksi->status_pembayaran }}">{{ strtoupper($transaksi->status_pembayaran) }}</span>
            </p>
            <p>
                Status Pesanan: 
                <span class="badge badge-{{ $transaksi->status_pesanan }}">{{ strtoupper($transaksi->status_pesanan) }}</span>
            </p>
            <hr>
            <p style="font-size: 14px; color: #555;">
                *Jika pembayaran Tunai, silakan menuju ke Kasir dan sebutkan Kode Transaksi Anda.<br>
                *Simpan halaman ini (jangan ditutup) untuk memantau status pesanan Anda.
            </p>
        </div>

        <div class="card">
            <h3>Rincian Pesanan</h3>
            @foreach($transaksi->detailTransaksi as $detail)
                <div class="list-item">
                    <span>{{ $detail->jumlah }}x {{ $detail->produk->nama_produk ?? 'Produk' }}</span>
                    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="list-item" style="font-weight: bold; font-size: 18px; margin-top: 10px;">
                <span>Total Belanja:</span>
                <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

    </div>

</body>
</html>
