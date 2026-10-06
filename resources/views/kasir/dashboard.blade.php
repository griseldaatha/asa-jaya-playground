@extends('layouts.admin')

@section('content')
<div class="kasir-page">
    <style>
        :root {
            --kasir-card-radius: 16px;
            --kasir-primary: #0d6efd;
            --kasir-success: #198754;
            --kasir-warning: #ffc107;
            --kasir-info: #0dcaf0;
            --kasir-danger: #dc3545;
        }

        .kasir-page {
            padding-bottom: 2rem;
        }

        /* Live Indicator Animation */
        .live-indicator {
            background: #ffffff;
            border: 1px solid #e3e8ef;
            padding: 6px 14px;
            border-radius: 50px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .live-dot {
            width: 9px;
            height: 9px;
            background-color: #198754;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
            animation: pulse-green 1.8s infinite;
        }

        @keyframes pulse-green {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 7px rgba(25, 135, 84, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
            }
        }

        /* Kasir Card Styling */
        .kasir-card {
            border: none;
            border-radius: var(--kasir-card-radius);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            background: #ffffff;
            overflow: hidden;
        }

        .kasir-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.1);
        }

        /* Status Accents */
        .kasir-card.card-pending {
            border-top: 5px solid #ffc107;
            background: linear-gradient(180deg, #fffdf2 0%, #ffffff 120px);
        }

        .kasir-card.card-lunas {
            border-top: 5px solid #198754;
        }

        .kasir-card.card-diproses {
            border-top: 5px solid #0d6efd;
        }

        /* Touch-friendly Buttons */
        .btn-kasir-action {
            min-height: 44px;
            font-size: 0.925rem;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-kasir-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Badge Styling */
        .badge-kasir {
            font-size: 0.725rem;
            padding: 5px 10px;
            border-radius: 30px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        /* Price Tag Block */
        .price-tag-container {
            background: rgba(13, 110, 253, 0.06);
            border-radius: 12px;
            padding: 10px 14px;
            border: 1px solid rgba(13, 110, 253, 0.12);
        }
    </style>

    <!-- Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-primary text-white rounded-3 p-2.5 d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-bell-concierge fs-5"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">Antrean Pesanan Aktif</h3>
                <span class="text-muted small">Kelola dan proses pesanan masuk secara real-time</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span id="order-count-badge" class="badge bg-primary rounded-pill px-3 py-2 fs-6 shadow-sm fw-bold">
                0 Pesanan
            </span>
        </div>
    </div>

    <!-- Cards Grid Container -->
    <div id="antrean-container" class="row g-3">
        <div class="col-12 py-5 text-center text-muted">
            <div class="spinner-border text-primary mb-3" role="status" style="width: 2.5rem; height: 2.5rem;">
                <span class="visually-hidden">Memuat pesanan...</span>
            </div>
            <div class="fw-bold text-dark fs-6">Memuat antrean pesanan...</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function getRelativeTime(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString.includes('T') ? dateString : dateString.replace(' ', 'T'));
        if (isNaN(date.getTime())) return '';
        const now = new Date();
        const diffSec = Math.floor((now - date) / 1000);
        if (diffSec < 30) return 'Baru saja';
        const diffMin = Math.floor(diffSec / 60);
        if (diffMin < 60) return `${diffMin} mnt lalu`;
        const diffHour = Math.floor(diffMin / 60);
        if (diffHour < 24) return `${diffHour} jam lalu`;
        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function fetchPesanan() {
        fetch('/kasir/api/pesanan-aktif')
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('antrean-container');
                const countBadge = document.getElementById('order-count-badge');
                if (countBadge) {
                    countBadge.textContent = `${data.length} Pesanan`;
                }

                container.innerHTML = '';
                if(data.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 py-5">
                            <div class="card border-0 shadow-sm text-center py-5 px-3 rounded-4 bg-white">
                                <div class="card-body">
                                    <div class="empty-state-icon mx-auto mb-3 text-success bg-success-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 76px; height: 76px;">
                                        <i class="fa-solid fa-mug-hot fs-2"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">Tidak Ada Pesanan Aktif</h5>
                                    <p class="text-muted small mb-0">Semua pesanan telah diproses atau belum ada pesanan baru yang masuk.</p>
                                </div>
                            </div>
                        </div>
                    `;
                    return;
                }
                data.forEach(p => {
                    let btnLunas = p.status_pembayaran === 'pending' ? `<button onclick="updateStatus(${p.id}, 'lunas')" class="btn btn-kasir-action btn-success w-100 mb-2"><i class="fa-solid fa-money-bill-wave me-1.5"></i> Terima Uang</button>` : '';
                    let btnProses = (p.status_pesanan === 'menunggu' && p.status_pembayaran === 'lunas') ? `<button onclick="updateStatus(${p.id}, 'diproses')" class="btn btn-kasir-action btn-primary w-100 mb-2"><i class="fa-solid fa-fire-burner me-1.5"></i> Proses Pesanan</button>` : '';
                    let btnSelesai = p.status_pesanan === 'diproses' ? `<button onclick="updateStatus(${p.id}, 'selesai')" class="btn btn-kasir-action btn-info text-white w-100 mb-2"><i class="fa-solid fa-check-double me-1.5"></i> Selesai (Serahkan)</button>` : '';
                    let btnBatal = p.status_pembayaran === 'pending' ? `<button onclick="updateStatus(${p.id}, 'batal')" class="btn btn-kasir-action btn-outline-danger w-100"><i class="fa-solid fa-xmark me-1.5"></i> Batal (Kembalikan Stok)</button>` : '';
                    
                    let bgBorder = p.status_pembayaran === 'lunas' ? (p.status_pesanan === 'diproses' ? 'card-diproses' : 'card-lunas') : 'card-pending';

                    let badgePembayaran = p.status_pembayaran === 'lunas' 
                        ? `<span class="badge bg-success-subtle text-success border border-success-subtle badge-kasir"><i class="fa-solid fa-circle-check me-1"></i>LUNAS</span>` 
                        : `<span class="badge bg-warning-subtle text-dark border border-warning-subtle badge-kasir"><i class="fa-solid fa-triangle-exclamation me-1"></i>BELUM BAYAR</span>`;

                    let badgePesanan = p.status_pesanan === 'diproses'
                        ? `<span class="badge bg-primary-subtle text-primary border border-primary-subtle badge-kasir"><i class="fa-solid fa-fire-burner me-1"></i>DIPROSES</span>`
                        : `<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle badge-kasir"><i class="fa-solid fa-hourglass-start me-1"></i>MENUNGGU</span>`;

                    let listItems = p.detail_transaksi.map(dt => `
                        <li class="list-group-item py-2 px-3 text-sm d-flex justify-content-between align-items-center bg-transparent border-bottom">
                            <span class="text-dark fw-medium small me-2 text-truncate" style="max-width: 160px;" title="${dt.produk ? dt.produk.nama_produk : 'Dihapus'}">
                                ${dt.produk ? dt.produk.nama_produk : 'Dihapus'}
                            </span>
                            <span class="badge bg-dark-subtle text-dark-emphasis rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">${dt.jumlah}x</span>
                        </li>
                    `).join('');

                    let waktuRelatif = getRelativeTime(p.created_at);
                    let namaMeja = p.meja ? p.meja.nama_meja : (p.meja_id ? `Meja ${p.meja_id}` : '-');

                    let html = `
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <div class="card h-100 kasir-card ${bgBorder}">
                                <div class="card-body p-4 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h5 class="fw-bold text-dark mb-0 fs-5">#${p.kode_transaksi}</h5>
                                                ${waktuRelatif ? `<small class="text-muted" style="font-size: 11px;"><i class="fa-regular fa-clock me-1"></i>${waktuRelatif}</small>` : ''}
                                            </div>
                                            <div class="d-flex flex-column align-items-end gap-1">
                                                ${badgePembayaran}
                                                ${badgePesanan}
                                            </div>
                                        </div>
                                        
                                        <div class="bg-light p-2.5 rounded-3 mb-3 d-flex justify-content-between align-items-center mt-2">
                                            <span class="small text-dark fw-semibold text-truncate me-2">
                                                <i class="fa-solid fa-user text-primary me-1.5"></i>${p.nama_pemesan}
                                            </span>
                                            <span class="badge bg-white text-dark border shadow-sm fw-bold small">
                                                <i class="fa-solid fa-chair text-warning me-1"></i>${namaMeja}
                                            </span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="text-uppercase text-muted fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.5px;">Item Pesanan:</div>
                                            <ul class="list-group list-group-flush border rounded-3 overflow-hidden">
                                                ${listItems}
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <div class="price-tag-container d-flex justify-content-between align-items-center mb-3">
                                            <span class="small text-muted fw-semibold">Total Bayar:</span>
                                            <h5 class="fw-bold text-primary mb-0">Rp ${new Intl.NumberFormat('id-ID').format(p.total_harga)}</h5>
                                        </div>
                                        
                                        <div class="actions">
                                            ${btnLunas} ${btnProses} ${btnSelesai} ${btnBatal}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += html;
                });
            });
    }

    function updateStatus(id, action, val) {
        let act = val || action;
        if (act === 'dibatalkan') act = 'batal';
        fetch(`/kasir/pesanan/${id}/update`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
            body: JSON.stringify({ action: act })
        }).then(() => fetchPesanan());
    }

    fetchPesanan(); 
    setInterval(fetchPesanan, 5000);
</script>
@endsection