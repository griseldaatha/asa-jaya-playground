<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #F0E2D2; font-family: sans-serif; }
        .ticket { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-top: 40px; }
        .ticket-header { background: #5B6D92; color: white; padding: 30px 20px; text-align: center; position: relative; }
        .ticket-header::after { content: ''; position: absolute; bottom: -15px; left: 0; right: 0; border-bottom: 30px dotted #F0E2D2; }
        .ticket-body { padding: 40px 30px 30px; }
        .status-badge { display: inline-block; padding: 8px 20px; border-radius: 30px; font-weight: bold; margin-bottom: 20px; }
        .bg-pending { background-color: #fff3cd; color: #856404; }
        .bg-lunas { background-color: #d4edda; color: #155724; }
    
        .btn-primary, .bg-primary { background-color: #5B6D92 !important; border-color: #5B6D92 !important; }
        .fs-5 { color: #5B6D92 !important; }
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
                        <h1 class="fw-bold mb-0 tracking-widest">{{ $transaksi->kode_transaksi }}</h1>
                    </div>
                    <div class="ticket-body text-center">
                        <h5 class="fw-bold mb-1">{{ $transaksi->nama_pemesan }}</h5>
                        <p class="text-muted small mb-4"><i class="fa-solid fa-chair"></i> Meja/Spot: {{ $transaksi->meja_id ?? '-' }}</p>
                        
                        <div class="status-badge {{ $transaksi->status_pembayaran == 'lunas' ? 'bg-lunas' : 'bg-pending' }}">
                            <i class="fa-solid {{ $transaksi->status_pembayaran == 'lunas' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            Pembayaran: {{ strtoupper($transaksi->status_pembayaran) }}
                        </div>

                        <div class="text-start bg-light p-3 rounded mb-4">
                            <ul class="list-unstyled mb-0 small">
                                @foreach($transaksi->detailTransaksi as $dt)
                                    <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                        <span>{{ $dt->jumlah }}x {{ $dt->produk->nama_produk ?? 'Item Dihapus' }}</span>
                                        <span class="fw-semibold">Rp {{ number_format($dt->subtotal, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="d-flex justify-content-between mt-3">
                                <span class="fw-bold text-muted">TOTAL</span>
                                <span class="fw-bold fs-5 ">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if($transaksi->status_pembayaran == 'pending')
                            <div class="alert alert-warning small text-start">
                                <strong><i class="fa-solid fa-circle-info"></i> Tunjukkan kode ini!</strong><br>
                                Silakan bawa HP Anda ke kasir dan tunjukkan kode <b>#{{ $transaksi->kode_transaksi }}</b> untuk melakukan pembayaran secara Tunai.
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