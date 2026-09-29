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