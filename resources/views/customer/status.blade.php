<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan #{{ $transaksi->kode_transaksi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #EFE1D1;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="86" height="86" viewBox="0 0 86 86"><rect width="86" height="86" fill="%23EFE1D1"/><g transform="translate(6, 3) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(92, 3) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(6, 89) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(92, 89) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><circle cx="47" cy="6" r="3.8" fill="%2384A8E8"/><circle cx="47" cy="92" r="3.8" fill="%2384A8E8"/><circle cx="33" cy="45" r="3.5" fill="%23F3D063"/><rect x="-6" y="62.5" width="12" height="5" rx="2.5" fill="%23C58DE8"/><rect x="80" y="62.5" width="12" height="5" rx="2.5" fill="%23C58DE8"/></svg>');
            background-repeat: repeat;
            font-family: system-ui, -apple-system, sans-serif;
            padding-bottom: 50px;
        }
        .ticket { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-top: 40px; }
        .ticket-header { background: #5B6D92; color: white; padding: 30px 20px; text-align: center; position: relative; }
        .ticket-header::after { content: ''; position: absolute; bottom: -15px; left: 0; right: 0; border-bottom: 30px dotted #EFE1D1; }
        .ticket-body { padding: 40px 24px 30px; }
        .status-badge { display: inline-block; padding: 8px 20px; border-radius: 30px; font-weight: bold; margin-bottom: 20px; }
        .bg-pending { background-color: #fff3cd; color: #856404; }
        .bg-lunas { background-color: #d4edda; color: #155724; }
        .bg-kadaluarsa { background-color: #e2e3e5; color: #383d41; }
        .bg-batal, .bg-dibatalkan { background-color: #f8d7da; color: #721c24; }
    
        .btn-primary, .bg-primary { background-color: #5B6D92 !important; border-color: #5B6D92 !important; }
        .btn-primary:hover { background-color: #4A5978 !important; border-color: #4A5978 !important; }
        .text-primary { color: #5B6D92 !important; }

        .payment-box {
            background-color: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 14px;
            padding: 16px;
        }
    </style>
</head>
<body>
    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 col-xl-5">
                
                @if(session('success'))
                    <div class="alert alert-success mt-4 mb-0 text-center rounded-pill"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
                @endif

                <div class="ticket">
                    <div class="ticket-header">
                        <p class="mb-1 opacity-75 small text-uppercase tracking-wider">Kode Pesanan</p>
                        <h1 class="fw-bold mb-0 tracking-widest">{{ $transaksi->kode_transaksi }}</h1>
                    </div>
                    <div class="ticket-body text-center">
                        <h5 class="fw-bold mb-1 text-dark">{{ $transaksi->nama_pemesan }}</h5>
                        <p class="text-muted small mb-4"><i class="fa-solid fa-chair"></i> Meja/Spot: {{ $transaksi->meja ? ($transaksi->meja->nomor_meja ?? $transaksi->meja->id) : ($transaksi->meja_id ?? '-') }}</p>
                        
                        @php
                            $badgeClass = 'bg-pending';
                            $badgeIcon = 'fa-clock';
                            if ($transaksi->status_pembayaran === 'lunas') {
                                $badgeClass = 'bg-lunas';
                                $badgeIcon = 'fa-check-circle';
                            } elseif ($transaksi->status_pembayaran === 'kadaluarsa') {
                                $badgeClass = 'bg-kadaluarsa';
                                $badgeIcon = 'fa-clock-rotate-left';
                            } elseif (in_array($transaksi->status_pembayaran, ['batal', 'dibatalkan'])) {
                                $badgeClass = 'bg-batal';
                                $badgeIcon = 'fa-ban';
                            }
                        @endphp

                        <div id="statusBadge" class="status-badge {{ $badgeClass }}">
                            <i id="statusBadgeIcon" class="fa-solid {{ $badgeIcon }}"></i>
                            <span id="statusBadgeText">Pembayaran: {{ strtoupper($transaksi->status_pembayaran) }}</span>
                        </div>

                        <!-- Ringkasan Item Pesanan -->
                        <div class="text-start bg-light p-3 rounded-3 mb-4 border">
                            <ul class="list-unstyled mb-0 small">
                                @foreach($transaksi->detailTransaksi as $dt)
                                    <li class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                        <span>{{ $dt->jumlah }}x {{ $dt->produk->nama_produk ?? 'Item Dihapus' }}</span>
                                        <span class="fw-semibold">Rp {{ number_format($dt->subtotal, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-1">
                                <span class="fw-bold text-muted small">TOTAL TAGIHAN</span>
                                <span class="fw-bold fs-5 text-dark">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- KONTEN STATUS PEMBAYARAN -->
                        <div id="paymentStatusSection">
                            @if($transaksi->status_pembayaran === 'pending')
                                @if($transaksi->metode_pembayaran === 'payment_gateway')
                                    <!-- KARTU SELESAIKAN PEMBAYARAN SNAP -->
                                    <div class="card border-0 shadow-sm rounded-4 text-start mb-4 overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
                                        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white bg-primary" style="width: 34px; height: 34px; font-size: 0.95rem;">
                                                    <i class="fa-solid fa-credit-card"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-dark">Selesaikan Pembayaran</h6>
                                                    <small class="text-muted">{{ $transaksi->metode_label }}</small>
                                                </div>
                                            </div>
                                            <span class="badge bg-primary text-white px-2.5 py-1 fw-bold">{{ $transaksi->metode_label }}</span>
                                        </div>

                                        <div class="card-body p-3">
                                            <!-- Nominal yang Harus Dibayar -->
                                            <div class="payment-box mb-3" style="background-color: #f0fdf4; border-color: #bbf7d0;">
                                                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.725rem;">Total Tagihan Pembayaran</small>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span class="fs-4 fw-bold text-success">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">Online</span>
                                                </div>
                                            </div>

                                            <!-- Hitung Mundur Sisa Waktu Pembayaran -->
                                            <div class="text-danger small fw-bold mb-3 p-2.5 rounded-3 bg-danger-subtle border border-danger-subtle text-center" id="countdownWrapper">
                                                <i class="fa-regular fa-clock me-1"></i>Sisa Waktu Pembayaran: <span id="countdownTimer" class="fs-6 font-monospace">--:--</span>
                                            </div>

                                            <!-- Tombol Besar Bayar Sekarang -->
                                            <a href="{{ $transaksi->payment_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary rounded-pill w-100 py-3 fw-bold fs-6 shadow-sm mb-2 text-center text-white d-block text-decoration-none">
                                                <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Bayar Sekarang
                                            </a>

                                            <!-- Tombol Cek Status Manual -->
                                            <button type="button" id="btnCekStatus" onclick="checkStatus(true)" class="btn btn-outline-secondary rounded-pill w-100 py-2.5 fw-bold shadow-sm">
                                                <i class="fa-solid fa-arrows-rotate me-1"></i> Cek Status Pembayaran
                                            </button>
                                        </div>
                                    </div>

                                    @if($is_sandbox)
                                        @php
                                            $ch = strtolower($transaksi->payment_channel ?? '');
                                            $simulatorUrl = 'https://simulator.sandbox.midtrans.com';
                                            $channelName = $transaksi->metode_label;
                                            $testingSteps = [];

                                            if ($ch === 'bca_va') {
                                                $simulatorUrl = 'https://simulator.sandbox.midtrans.com/bca/va/index';
                                                $testingSteps = [
                                                    "Klik tombol 'Bayar Sekarang' di atas untuk membuka layar Midtrans Snap.",
                                                    "Salin nomor BCA Virtual Account yang tertera pada layar Midtrans.",
                                                    "Buka Simulator BCA di tautan bawah dan masukkan nomor VA.",
                                                    "Klik 'Inquire' lalu 'Pay' pada simulator; status pesanan akan otomatis terkonfirmasi Lunas."
                                                ];
                                            } elseif ($ch === 'bni_va') {
                                                $simulatorUrl = 'https://simulator.sandbox.midtrans.com/bni/va/index';
                                                $testingSteps = [
                                                    "Klik tombol 'Bayar Sekarang' di atas untuk membuka layar Midtrans Snap.",
                                                    "Salin nomor BNI Virtual Account yang tertera pada layar Midtrans.",
                                                    "Buka Simulator BNI di tautan bawah dan masukkan nomor VA.",
                                                    "Klik 'Inquire' lalu 'Pay' pada simulator; status pesanan akan otomatis terkonfirmasi Lunas."
                                                ];
                                            } elseif ($ch === 'bri_va') {
                                                $simulatorUrl = 'https://simulator.sandbox.midtrans.com/openapi/va/index?bank=bri';
                                                $testingSteps = [
                                                    "Klik tombol 'Bayar Sekarang' di atas untuk membuka layar Midtrans Snap.",
                                                    "Salin nomor BRI Virtual Account yang tertera pada layar Midtrans.",
                                                    "Buka Simulator BRI di tautan bawah dan masukkan nomor VA.",
                                                    "Klik 'Inquire' lalu 'Pay' pada simulator; status pesanan akan otomatis terkonfirmasi Lunas."
                                                ];
                                            } elseif ($ch === 'gopay') {
                                                $simulatorUrl = 'https://simulator.sandbox.midtrans.com/qris/index';
                                                $testingSteps = [
                                                    "Klik tombol 'Bayar Sekarang' di atas untuk membuka layar Midtrans Snap.",
                                                    "Pada layar Midtrans Snap Sandbox, Anda dapat langsung mengklik tombol simulasi pembayaran GoPay/QRIS atau menggunakan simulator Midtrans.",
                                                    "Setelah disimulasikan, status pesanan akan otomatis terkonfirmasi Lunas."
                                                ];
                                            } elseif ($ch === 'shopeepay') {
                                                $simulatorUrl = 'https://app.sandbox.midtrans.com';
                                                $testingSteps = [
                                                    "Klik tombol 'Bayar Sekarang' di atas untuk membuka layar Midtrans Snap.",
                                                    "Pada halaman ShopeePay Sandbox, klik tombol pengujian/simulasi bayar yang tersedia di layar Midtrans.",
                                                    "Setelah disetujui, kembali ke halaman ini dan status pesanan otomatis terkonfirmasi Lunas."
                                                ];
                                            } else {
                                                $testingSteps = [
                                                    "Klik tombol 'Bayar Sekarang' di atas.",
                                                    "Lakukan simulasi pembayaran melalui instruksi di halaman Midtrans Sandbox.",
                                                    "Kembali ke halaman ini untuk melihat status Lunas."
                                                ];
                                            }
                                        @endphp
                                        <!-- KOTAK MODE UJI SANDBOX -->
                                        <div id="sandboxBox" class="alert alert-info small text-start p-3 mb-3 border-info rounded-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge bg-warning text-dark me-2">Mode Uji</span>
                                                <strong>Panduan Simulasi Pembayaran Sandbox</strong>
                                            </div>
                                            <p class="mb-2 text-muted" style="font-size: 0.8rem;">
                                                Uji pembayaran {{ $channelName }} lewat simulator resmi Midtrans Sandbox:
                                            </p>
                                            <ol class="small text-muted ps-3 mb-2" style="font-size: 0.78rem;">
                                                @foreach($testingSteps as $step)
                                                    <li>{{ $step }}</li>
                                                @endforeach
                                            </ol>
                                            @if($simulatorUrl && in_array($ch, ['bca_va', 'bni_va', 'bri_va', 'gopay']))
                                                <a href="{{ $simulatorUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary w-100 fw-bold">
                                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Simulator {{ $channelName }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                @else
                                    <!-- TUNAI PENDING -->
                                    <div class="alert alert-warning small text-start p-3 rounded-3 mb-3">
                                        <strong><i class="fa-solid fa-circle-info me-1"></i> Tunjukkan kode ini!</strong><br>
                                        Silakan bawa HP Anda ke kasir dan tunjukkan kode <b>#{{ $transaksi->kode_transaksi }}</b> untuk melakukan pembayaran secara Tunai.
                                    </div>
                                    <button type="button" id="btnCekStatus" onclick="checkStatus(true)" class="btn btn-primary rounded-pill w-100 py-2.5 fw-bold shadow-sm mb-2">
                                        <i class="fa-solid fa-arrows-rotate me-1"></i> Cek Status Pembayaran
                                    </button>
                                @endif

                            @elseif($transaksi->status_pembayaran === 'lunas')
                                <div class="alert alert-success small text-center p-4 rounded-3">
                                    <i class="fa-solid fa-circle-check fs-1 text-success d-block mb-2"></i>
                                    <h6 class="fw-bold text-success mb-1">Pembayaran Berhasil!</h6>
                                    <p class="text-muted small mb-0">Pesanan Anda telah lunas dan sedang diproses petugas. Selamat bermain!</p>
                                </div>
                            @elseif($transaksi->status_pembayaran === 'kadaluarsa')
                                <div class="alert alert-secondary small text-center p-4 rounded-3 mb-3">
                                    <i class="fa-solid fa-clock-rotate-left fs-1 text-secondary d-block mb-2"></i>
                                    <h6 class="fw-bold text-dark mb-1">Batas Waktu Pembayaran Habis</h6>
                                    <p class="text-muted small mb-0">Waktu pembayaran pesanan ini telah habis. Silakan lakukan pemesanan ulang.</p>
                                </div>
                                <a href="{{ route('katalog') }}" class="btn btn-primary w-100 rounded-pill fw-bold mb-2 py-2.5">
                                    <i class="fa-solid fa-rotate-right me-1"></i> Pesan Ulang
                                </a>
                            @elseif(in_array($transaksi->status_pembayaran, ['batal', 'dibatalkan']))
                                <div class="alert alert-danger small text-center p-4 rounded-3 mb-3">
                                    <i class="fa-solid fa-ban fs-1 text-danger d-block mb-2"></i>
                                    <h6 class="fw-bold text-danger mb-1">Pesanan Dibatalkan</h6>
                                    <p class="text-muted small mb-0">Pesanan ini telah dibatalkan. Silakan lakukan pemesanan ulang.</p>
                                </div>
                                <a href="{{ route('katalog') }}" class="btn btn-primary w-100 rounded-pill fw-bold mb-2 py-2.5">
                                    <i class="fa-solid fa-rotate-right me-1"></i> Pesan Ulang
                                </a>
                            @endif
                        </div>

                        <a href="{{ route('katalog') }}" id="btnKembaliKatalog" class="btn btn-outline-secondary w-100 rounded-pill fw-bold mt-2">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Menu
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const checkUrl = "{{ route('status.pesanan.cek', $transaksi->kode_transaksi) }}?token={{ request('token') }}";
        const katalogUrl = "{{ route('katalog') }}";
        let remainingSeconds = {{ (int) $sisa_detik }};
        let currentStatus = "{{ $transaksi->status_pembayaran }}";
        let pollTimer = null;
        let countdownInterval = null;

        function formatTimer(sec) {
            if (sec <= 0) return '00:00';
            const m = Math.floor(sec / 60);
            const s = sec % 60;
            return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        }

        function updateCountdownDisplay() {
            const el = document.getElementById('countdownTimer');
            if (el) {
                el.textContent = formatTimer(remainingSeconds);
            }
        }

        if (currentStatus === 'pending') {
            updateCountdownDisplay();
            countdownInterval = setInterval(() => {
                if (remainingSeconds > 0) {
                    remainingSeconds--;
                    updateCountdownDisplay();
                } else {
                    clearInterval(countdownInterval);
                    updateCountdownDisplay();
                    checkStatus(false);
                }
            }, 1000);

            // Polling otomatis status setiap 4 detik
            pollTimer = setInterval(() => {
                checkStatus(false);
            }, 4000);
        }

        function handleStatusUpdate(data) {
            if (!data) return;

            if (typeof data.sisa_detik !== 'undefined') {
                remainingSeconds = parseInt(data.sisa_detik);
                updateCountdownDisplay();
            }

            if (data.status_pembayaran && data.status_pembayaran !== currentStatus) {
                currentStatus = data.status_pembayaran;
                renderStatusChange(data.status_pembayaran);
            }
        }

        function renderStatusChange(status) {
            const badge = document.getElementById('statusBadge');
            const badgeIcon = document.getElementById('statusBadgeIcon');
            const badgeText = document.getElementById('statusBadgeText');
            const section = document.getElementById('paymentStatusSection');

            // Hentikan interval polling & countdown jika bukan pending
            if (status !== 'pending') {
                if (pollTimer) clearInterval(pollTimer);
                if (countdownInterval) clearInterval(countdownInterval);
            }

            if (status === 'lunas') {
                if (badge) {
                    badge.className = 'status-badge bg-lunas';
                    badgeIcon.className = 'fa-solid fa-check-circle';
                    badgeText.textContent = 'Pembayaran: LUNAS';
                }
                if (section) {
                    section.innerHTML = `
                        <div class="alert alert-success small text-center p-4 rounded-3">
                            <i class="fa-solid fa-circle-check fs-1 text-success d-block mb-2"></i>
                            <h6 class="fw-bold text-success mb-1">Pembayaran Berhasil!</h6>
                            <p class="text-muted small mb-0">Pesanan Anda telah lunas dan sedang diproses petugas. Selamat bermain!</p>
                        </div>
                    `;
                }
            } else if (status === 'kadaluarsa') {
                if (badge) {
                    badge.className = 'status-badge bg-kadaluarsa';
                    badgeIcon.className = 'fa-solid fa-clock-rotate-left';
                    badgeText.textContent = 'Pembayaran: KADALUARSA';
                }
                if (section) {
                    section.innerHTML = `
                        <div class="alert alert-secondary small text-center p-4 rounded-3 mb-3">
                            <i class="fa-solid fa-clock-rotate-left fs-1 text-secondary d-block mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Batas Waktu Pembayaran Habis</h6>
                            <p class="text-muted small mb-0">Waktu pembayaran pesanan ini telah habis. Silakan lakukan pemesanan ulang.</p>
                        </div>
                        <a href="${katalogUrl}" class="btn btn-primary w-100 rounded-pill fw-bold mb-2 py-2.5">
                            <i class="fa-solid fa-rotate-right me-1"></i> Pesan Ulang
                        </a>
                    `;
                }
            } else if (status === 'batal' || status === 'dibatalkan') {
                if (badge) {
                    badge.className = 'status-badge bg-batal';
                    badgeIcon.className = 'fa-solid fa-ban';
                    badgeText.textContent = 'Pembayaran: ' + status.toUpperCase();
                }
                if (section) {
                    section.innerHTML = `
                        <div class="alert alert-danger small text-center p-4 rounded-3 mb-3">
                            <i class="fa-solid fa-ban fs-1 text-danger d-block mb-2"></i>
                            <h6 class="fw-bold text-danger mb-1">Pesanan Dibatalkan</h6>
                            <p class="text-muted small mb-0">Pesanan ini telah dibatalkan. Silakan lakukan pemesanan ulang.</p>
                        </div>
                        <a href="${katalogUrl}" class="btn btn-primary w-100 rounded-pill fw-bold mb-2 py-2.5">
                            <i class="fa-solid fa-rotate-right me-1"></i> Pesan Ulang
                        </a>
                    `;
                }
            }
        }

        function checkStatus(isManual = false) {
            const btn = document.getElementById('btnCekStatus');
            if (isManual && btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Mengecek...';
            }

            fetch(checkUrl)
                .then(response => {
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                })
                .then(data => {
                    handleStatusUpdate(data);
                })
                .catch(err => {
                    console.error('Error cek status:', err);
                })
                .finally(() => {
                    if (isManual && btn && currentStatus === 'pending') {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-arrows-rotate me-1"></i> Cek Status Pembayaran';
                    }
                });
        }
    </script>
</body>
</html>