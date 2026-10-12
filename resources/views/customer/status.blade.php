<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #EFE1D1;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="86" height="86" viewBox="0 0 86 86"><rect width="86" height="86" fill="%23EFE1D1"/><g transform="translate(6, 3) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(92, 3) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(6, 89) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(92, 89) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><circle cx="47" cy="6" r="3.8" fill="%2384A8E8"/><circle cx="47" cy="92" r="3.8" fill="%2384A8E8"/><circle cx="33" cy="45" r="3.5" fill="%23F3D063"/><rect x="-6" y="62.5" width="12" height="5" rx="2.5" fill="%23C58DE8"/><rect x="80" y="62.5" width="12" height="5" rx="2.5" fill="%23C58DE8"/></svg>');
            background-repeat: repeat;
            font-family: sans-serif;
        }
        .ticket { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-top: 40px; }
        .ticket-header { background: #5B6D92; color: white; padding: 30px 20px; text-align: center; position: relative; }
        .ticket-header::after { content: ''; position: absolute; bottom: -15px; left: 0; right: 0; border-bottom: 30px dotted #EFE1D1; }
        .ticket-body { padding: 40px 30px 30px; }
        .status-badge { display: inline-block; padding: 8px 20px; border-radius: 30px; font-weight: bold; margin-bottom: 20px; }
        .bg-pending { background-color: #fff3cd; color: #856404; }
        .bg-lunas { background-color: #d4edda; color: #155724; }
        .bg-kadaluarsa { background-color: #e2e3e5; color: #383d41; }
        .bg-batal, .bg-dibatalkan { background-color: #f8d7da; color: #721c24; }
    
        .btn-primary, .bg-primary { background-color: #5B6D92 !important; border-color: #5B6D92 !important; }
        .fs-5 { color: #5B6D92 !important; }
    </style>
</head>
<body>
    <div class="container pb-5">
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
                                <span class="fw-bold fs-5">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- KONTEN STATUS PEMBAYARAN -->
                        <div id="paymentStatusSection">
                            @if($transaksi->status_pembayaran === 'pending')
                                @if($transaksi->metode_pembayaran === 'payment_gateway')
                                    <!-- QRIS PENDING -->
                                    <div id="qrisContainer" class="text-center my-2">
                                        <div class="p-3 bg-white d-inline-block rounded-3 border shadow-sm mb-2 text-center" style="max-width: 280px;">
                                            @if($transaksi->qris_url)
                                                <img src="{{ $transaksi->qris_url }}" alt="QRIS Midtrans" id="qrisImage" class="img-fluid" style="width: 240px; max-width: 100%; height: auto; object-fit: contain;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center" style="width: 240px; height: 240px;">
                                                    <span class="text-muted small">QR Code tidak tersedia</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="fw-bold fs-5 text-dark mb-1">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                                        <div class="text-danger small fw-bold mb-2" id="countdownWrapper">
                                            <i class="fa-regular fa-clock me-1"></i>Sisa Waktu: <span id="countdownTimer">--:--</span>
                                        </div>
                                        <p class="small text-muted mb-3">Scan dengan GoPay, OVO, DANA, ShopeePay, LinkAja, atau m-banking</p>
                                        <button type="button" id="btnCekStatus" onclick="checkStatus(true)" class="btn btn-primary rounded-pill px-4 mb-3 btn-sm shadow-sm">
                                            <i class="fa-solid fa-arrows-rotate me-1"></i> Cek Status
                                        </button>
                                    </div>

                                    @if($is_sandbox && $transaksi->qris_url)
                                        <!-- MODE UJI SANDBOX -->
                                        <div id="sandboxBox" class="alert alert-info small text-start p-3 mb-3 border-info">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge bg-warning text-dark me-2">Mode Uji</span>
                                                <strong>Simulator QRIS Midtrans</strong>
                                            </div>
                                            <p class="mb-2 text-muted" style="font-size: 0.8rem;">Gunakan simulator resmi Midtrans untuk uji coba pembayaran tanpa e-wallet asli.</p>
                                            <div class="input-group input-group-sm mb-2">
                                                <input type="text" id="qrisUrlInput" class="form-control bg-white" value="{{ $transaksi->qris_url }}" readonly>
                                                <button class="btn btn-outline-secondary" type="button" onclick="copyQrisUrl()">
                                                    <i class="fa-regular fa-copy me-1"></i>Salin
                                                </button>
                                            </div>
                                            <a href="https://simulator.sandbox.midtrans.com/v2/qris/index" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary w-100">
                                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka Simulator QRIS
                                            </a>
                                        </div>
                                    @endif

                                @else
                                    <!-- TUNAI PENDING -->
                                    <div class="alert alert-warning small text-start">
                                        <strong><i class="fa-solid fa-circle-info"></i> Tunjukkan kode ini!</strong><br>
                                        Silakan bawa HP Anda ke kasir dan tunjukkan kode <b>#{{ $transaksi->kode_transaksi }}</b> untuk melakukan pembayaran secara Tunai.
                                    </div>
                                @endif

                            @elseif($transaksi->status_pembayaran === 'lunas')
                                <div class="alert alert-success small text-center p-3">
                                    <i class="fa-solid fa-circle-check fs-2 text-success d-block mb-2"></i>
                                    <strong>Pembayaran berhasil, pesanan Anda sedang diproses</strong><br>
                                    <span class="text-muted">Selamat bermain!</span>
                                </div>
                            @elseif($transaksi->status_pembayaran === 'kadaluarsa')
                                <div class="alert alert-secondary small text-center p-3 mb-3">
                                    <i class="fa-solid fa-clock-rotate-left fs-2 text-secondary d-block mb-2"></i>
                                    <strong>QR kedaluwarsa</strong><br>
                                    <span class="text-muted">Batas waktu pembayaran telah habis. Silakan pesan ulang.</span>
                                </div>
                                <a href="{{ route('katalog') }}" class="btn btn-primary w-100 rounded-pill fw-bold mb-2">
                                    <i class="fa-solid fa-rotate-right me-1"></i> Pesan Ulang
                                </a>
                            @elseif(in_array($transaksi->status_pembayaran, ['batal', 'dibatalkan']))
                                <div class="alert alert-danger small text-center p-3 mb-3">
                                    <i class="fa-solid fa-ban fs-2 text-danger d-block mb-2"></i>
                                    <strong>Pembayaran dibatalkan</strong><br>
                                    <span class="text-muted">Pesanan ini telah dibatalkan. Silakan pesan ulang.</span>
                                </div>
                                <a href="{{ route('katalog') }}" class="btn btn-primary w-100 rounded-pill fw-bold mb-2">
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
                    checkStatus();
                }
            }, 1000);

            // Polling status setiap 4 detik
            pollTimer = setInterval(() => {
                checkStatus(false);
            }, 4000);
        }

        function copyQrisUrl() {
            const input = document.getElementById('qrisUrlInput');
            if (input) {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(input.value).then(() => {
                        alert('URL QRIS berhasil disalin!');
                    }).catch(() => {
                        fallbackCopy(input);
                    });
                } else {
                    fallbackCopy(input);
                }
            }
        }

        function fallbackCopy(input) {
            input.select();
            input.setSelectionRange(0, 99999);
            try {
                document.execCommand('copy');
                alert('URL QRIS berhasil disalin!');
            } catch (err) {
                prompt('Salin URL QRIS secara manual:', input.value);
            }
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
                        <div class="alert alert-success small text-center p-3">
                            <i class="fa-solid fa-circle-check fs-2 text-success d-block mb-2"></i>
                            <strong>Pembayaran berhasil, pesanan Anda sedang diproses</strong><br>
                            <span class="text-muted">Selamat bermain!</span>
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
                        <div class="alert alert-secondary small text-center p-3 mb-3">
                            <i class="fa-solid fa-clock-rotate-left fs-2 text-secondary d-block mb-2"></i>
                            <strong>QR kedaluwarsa</strong><br>
                            <span class="text-muted">Batas waktu pembayaran telah habis. Silakan pesan ulang.</span>
                        </div>
                        <a href="${katalogUrl}" class="btn btn-primary w-100 rounded-pill fw-bold mb-2">
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
                        <div class="alert alert-danger small text-center p-3 mb-3">
                            <i class="fa-solid fa-ban fs-2 text-danger d-block mb-2"></i>
                            <strong>Pembayaran dibatalkan</strong><br>
                            <span class="text-muted">Pesanan ini telah dibatalkan. Silakan pesan ulang.</span>
                        </div>
                        <a href="${katalogUrl}" class="btn btn-primary w-100 rounded-pill fw-bold mb-2">
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
                        btn.innerHTML = '<i class="fa-solid fa-arrows-rotate me-1"></i> Cek Status';
                    }
                });
        }
    </script>
</body>
</html>