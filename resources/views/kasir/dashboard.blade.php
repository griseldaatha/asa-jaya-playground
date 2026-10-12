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
            border: 1px solid #edf2f7;
            border-radius: 24px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            background: #ffffff;
            overflow: hidden;
        }

        .kasir-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
        }

        /* Avatar Pemesan */
        .kasir-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #fde047;
            color: #854d0e;
            font-weight: 800;
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Stepper Wizard Mini */
        .stepper-mini {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            margin: 1rem 0;
            padding: 0 6px;
        }

        .step-mini-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .step-mini-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            background-color: #e2e8f0;
            color: #64748b;
            transition: all 0.2s ease;
        }

        .step-mini-item.active .step-mini-circle {
            background-color: #f59e0b;
            color: #ffffff;
        }

        .step-mini-item.completed .step-mini-circle {
            background-color: #16a34a;
            color: #ffffff;
        }

        .step-mini-label {
            font-size: 0.725rem;
            font-weight: 600;
            margin-top: 5px;
            color: #64748b;
        }

        .step-mini-item.active .step-mini-label {
            color: #b45309;
            font-weight: 700;
        }

        .step-mini-item.completed .step-mini-label {
            color: #15803d;
            font-weight: 700;
        }

        .step-mini-line {
            flex-grow: 1;
            height: 4px;
            background-color: #e2e8f0;
            border-radius: 4px;
            margin: 0 8px;
            margin-bottom: 22px;
            z-index: 1;
        }

        .step-mini-line.active {
            background-color: #16a34a;
        }

        /* Item Pesanan Box */
        .item-pesanan-box {
            background-color: #fef7ee;
            border: 1px solid #fdebd2;
            border-radius: 16px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .btn-more-items {
            transition: all 0.2s ease;
        }

        .btn-more-items:hover {
            background-color: #f5ebe0 !important;
            border-color: #5B6D92 !important;
        }

        .btn-more-items:hover span.text-muted {
            color: #43557A !important;
        }

        .btn-more-items:hover i {
            transform: translateX(2px);
            color: #5B6D92 !important;
        }

        .item-pesanan-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background-color: #fed7aa;
            color: #c2410c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            margin-right: 12px;
            flex-shrink: 0;
        }

        /* Action Buttons */
        .btn-action-main {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            color: #ffffff !important;
            border-radius: 14px;
            font-weight: 700;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
        }

        .btn-action-main:hover {
            background-color: #15803d !important;
            border-color: #15803d !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.3);
        }

        .btn-action-secondary {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            border-radius: 12px;
            font-weight: 600;
            padding: 9px 14px;
            font-size: 0.84rem;
            letter-spacing: 0.2px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-action-secondary:hover {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border-color: #cbd5e1 !important;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }

        .btn-action-secondary:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-action-secondary i {
            font-size: 0.92rem;
            transition: transform 0.2s ease;
        }

        .btn-action-secondary:hover i {
            transform: scale(1.1);
        }

        .btn-action-cancel {
            color: #dc2626;
            text-decoration: underline;
            font-size: 0.875rem;
            font-weight: 600;
            background: none;
            border: none;
            padding: 6px 0;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .btn-action-cancel:hover {
            color: #991b1b;
        }
    </style>

    <!-- Header Badge Section -->
    <div class="d-flex justify-content-end align-items-center mb-4">
        <span id="order-count-badge" class="badge bg-primary rounded-pill px-3 py-2 fs-6 shadow-sm fw-bold">
            0 Pesanan
        </span>
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

    <!-- Modal Pembayaran Tunai (Fitur 1) -->
    <div class="modal fade" id="modalTerimaUang" tabindex="-1" aria-labelledby="modalBayarTitle" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background-color: #ffffff;">
                <div class="modal-header border-0 pb-0 pt-4 px-4 bg-white d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge rounded-pill px-2.5 py-1 text-uppercase fw-bold" style="background-color: #D5E3E6; color: #5B6D92; font-size: 0.72rem; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-money-bill-wave me-1"></i> Pembayaran Kasir
                        </span>
                        <h5 class="modal-title fw-bold text-dark mt-1 mb-0" id="modalBayarTitle" style="font-size: 1.15rem;">
                            Terima Uang <span class="text-muted fw-normal" style="font-size: 0.9rem;">#<span id="bayarKodeTrx" class="fw-semibold text-primary"></span></span>
                        </h5>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.8rem;"></button>
                </div>
                
                <!-- Form Input Bayar -->
                <div id="formBayarContainer" class="modal-body p-4 pt-3 bg-white">
                    <!-- Card Total Tagihan -->
                    <div class="p-3.5 rounded-4 text-center mb-3" style="background: linear-gradient(135deg, #F0E2D2 0%, #FAF5EF 100%); border: 1px solid rgba(91, 109, 146, 0.15);">
                        <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.8px; color: #64748b !important;">Total Tagihan</div>
                        <div class="fw-bold mb-0" id="bayarTotalText" style="font-size: 2rem; color: #5B6D92; letter-spacing: -0.5px;">Rp 0</div>
                    </div>

                    <!-- Input Uang Diterima -->
                    <div class="mb-3">
                        <label for="inputUangDiterima" class="form-label fw-bold text-dark small text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Uang Diterima (Rp)</label>
                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden border" style="border-color: #D5E3E6 !important;">
                            <span class="input-group-text border-0 fw-bold px-3" style="background-color: #FAF5EF; color: #5B6D92; font-size: 1.05rem;">Rp</span>
                            <input type="text" id="inputUangDiterima" class="form-control border-0 fw-bold fs-3 text-dark bg-white ps-2" placeholder="0" autocomplete="off" oninput="onInputUangDiterima()" style="box-shadow: none;">
                        </div>
                    </div>

                    <!-- Tombol Cepat -->
                    <div class="mb-3">
                        <div class="text-muted small fw-bold text-uppercase mb-1.5" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b !important;">Pilihan Nominal Cepat:</div>
                        <div class="row g-2" id="containerTombolCepat">
                            <!-- Diisi dinamis oleh JS berdasarkan total tagihan -->
                        </div>
                    </div>

                    <!-- Box Status Kembalian Live -->
                    <div id="boxKembalianLive" class="alert p-3 mb-0 rounded-3 border d-flex align-items-center transition-all" style="background-color: #f8fafc; border-color: #e2e8f0; color: #475569;">
                        <div class="d-flex align-items-center w-100">
                            <div id="kembalianIconBox" class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 38px; height: 38px; background-color: #e2e8f0;">
                                <i id="kembalianIcon" class="fa-solid fa-circle-info fs-5 text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small fw-semibold text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;" id="kembalianStatusLabel">Status Pembayaran</div>
                                <div class="fw-bold fs-6 text-dark" id="kembalianStatusValue">Masukkan uang diterima</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer untuk Form -->
                <div class="modal-footer border-top-0 pt-0 pb-4 px-4 bg-white d-flex justify-content-end gap-2" id="modalBayarFooter">
                    <button type="button" class="btn btn-light text-secondary fw-semibold px-3 py-2 rounded-3 border" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnKonfirmasiBayar" class="btn text-white fw-bold px-4 py-2 rounded-3 shadow-sm" style="background-color: #198754; border-color: #198754;" disabled onclick="submitKonfirmasiBayar()">
                        <i class="fa-solid fa-check me-1.5"></i> Konfirmasi Bayar
                    </button>
                </div>

                <!-- View Sukses (Tampil Setelah Berhasil) -->
                <div id="suksesBayarContainer" class="modal-body p-4 text-center bg-white" style="display: none;">
                    <div class="mx-auto mb-3 text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px; background-color: #198754;">
                        <i class="fa-solid fa-check fs-2"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Pembayaran Berhasil!</h4>
                    <p class="text-muted small mb-4">Pesanan #<span id="suksesKodeTrx"></span> telah lunas tercatat.</p>

                    <div class="p-3.5 rounded-4 border mb-4 text-start shadow-sm" style="background-color: #FAF5EF; border-color: #D5E3E6 !important;">
                        <div class="d-flex justify-content-between text-muted small mb-1.5">
                            <span>Total Tagihan:</span>
                            <span class="fw-bold text-dark" id="suksesTotalTagihan">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>Uang Diterima:</span>
                            <span class="fw-bold text-dark" id="suksesUangDiterima">Rp 0</span>
                        </div>
                        <hr class="my-2" style="border-color: #D5E3E6;">
                        <div class="d-flex justify-content-between align-items-center pt-1">
                            <span class="fw-bold text-dark fs-6">Kembalian:</span>
                            <span class="fs-2 fw-bold text-success" id="suksesKembalianText">Rp 0</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a id="btnCetakStrukModal" href="#" target="_blank" class="btn btn-primary py-2.5 fw-bold rounded-3 shadow-sm" style="background-color: #5B6D92 !important; border-color: #5B6D92 !important;">
                            <i class="fa-solid fa-print me-2"></i> Cetak Struk Transaksi
                        </a>
                        <button type="button" class="btn btn-light border py-2 text-secondary fw-semibold rounded-3" data-bs-dismiss="modal">
                            Selesai & Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Transaksi (Antrean Aktif) -->
    <div class="modal fade" id="modalDetailTransaksi" tabindex="-1" aria-labelledby="modalDetailTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 520px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background-color: #ffffff;">
                <div class="modal-header border-0 pb-0 pt-4 px-4 bg-white d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge rounded-pill px-2.5 py-1 text-uppercase fw-bold" style="background-color: #D5E3E6; color: #5B6D92; font-size: 0.72rem; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-receipt me-1"></i> Rincian Pesanan
                        </span>
                        <h5 class="modal-title fw-bold text-dark mt-1 mb-0" id="modalDetailTitle" style="font-size: 1.15rem;">
                            Detail Transaksi <span class="text-primary fw-bold" id="detailKodeTrx">#</span>
                        </h5>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.8rem;"></button>
                </div>

                <div class="modal-body p-4 pt-3 bg-white">
                    <!-- Info Pelanggan & Meja -->
                    <div class="p-3 rounded-4 mb-3" style="background-color: #FAF5EF; border: 1px solid #D5E3E6;">
                        <div class="row g-2">
                            <div class="col-6">
                                <span class="text-muted small d-block" style="font-size: 0.72rem;">Nama Pemesan</span>
                                <span class="fw-bold text-dark fs-6" id="detailNamaPemesan">-</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted small d-block" style="font-size: 0.72rem;">Meja / Lokasi</span>
                                <span class="fw-bold text-dark fs-6" id="detailMeja">-</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted small d-block" style="font-size: 0.72rem;">Waktu Pesan</span>
                                <span class="text-secondary small fw-medium" id="detailWaktu">-</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted small d-block" style="font-size: 0.72rem;">Metode Bayar</span>
                                <span id="detailMetodeBayar">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badges -->
                    <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                        <div>
                            <span class="text-muted small me-1">Status Bayar:</span>
                            <span id="detailStatusBayar">-</span>
                        </div>
                        <div>
                            <span class="text-muted small me-1">Status Pesanan:</span>
                            <span id="detailStatusPesanan">-</span>
                        </div>
                    </div>

                    <!-- Daftar Item Pesanan -->
                    <div class="mb-3">
                        <div class="text-muted small fw-bold text-uppercase mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            Item Pesanan (<span id="detailTotalItemCount">0</span>)
                        </div>
                        <div id="detailListItems" class="d-flex flex-column gap-2" style="max-height: 200px; overflow-y: auto;">
                            <!-- Injected by JS -->
                        </div>
                    </div>

                    <!-- Rincian Finansial -->
                    <div class="p-3 rounded-4 border bg-white shadow-sm" style="border-color: #e2e8f0 !important;">
                        <div class="d-flex justify-content-between text-muted small mb-1.5">
                            <span>Total Tagihan:</span>
                            <span class="fw-bold text-dark fs-6" id="detailTotalHarga">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-1.5">
                            <span>Uang Diterima:</span>
                            <span class="fw-bold text-dark" id="detailUangDiterima">-</span>
                        </div>
                        <hr class="my-2" style="border-color: #e2e8f0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Uang Kembalian:</span>
                            <span class="fw-bold text-success fs-5" id="detailUangKembalian">-</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0 pb-4 px-4 bg-white d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light text-secondary fw-semibold px-3 py-2 rounded-3 border" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <div class="d-flex align-items-center gap-2">
                        <span id="detailActionBtnContainer"></span>
                        <a id="btnCetakStrukDetail" href="#" target="_blank" class="btn btn-primary px-3 py-2 rounded-3 fw-bold shadow-sm d-flex align-items-center gap-2" style="background-color: #5B6D92 !important; border-color: #5B6D92 !important;">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak Struk</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Selesai & Cetak Struk -->
    <div class="modal fade" id="modalSelesaiPesanan" tabindex="-1" aria-labelledby="modalSelesaiTitle" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background-color: #ffffff;">
                <div class="modal-header border-0 pb-0 pt-4 px-4 bg-white d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge rounded-pill px-2.5 py-1 text-uppercase fw-bold" style="background-color: #dcfce7; color: #15803d; font-size: 0.72rem; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-circle-check me-1"></i> Pesanan Selesai
                        </span>
                        <h5 class="modal-title fw-bold text-dark mt-1 mb-0" id="modalSelesaiTitle" style="font-size: 1.15rem;">
                            Pesanan Telah Diserahkan
                        </h5>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.8rem;"></button>
                </div>

                <div class="modal-body p-4 pt-3 bg-white text-center">
                    <div class="mx-auto mb-3 text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 68px; height: 68px; background-color: #0284c7;">
                        <i class="fa-solid fa-check-double fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Pesanan #<span id="selesaiKodeTrx"></span></h5>
                    <p class="text-muted small mb-3">Pesanan telah berhasil diselesaikan dan diserahkan ke pelanggan.</p>

                    <div class="p-3 rounded-4 border text-start mb-3" style="background-color: #FAF5EF; border-color: #D5E3E6 !important;">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Nama Pemesan:</span>
                            <span class="fw-bold text-dark" id="selesaiNamaPemesan">-</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Meja / Lokasi:</span>
                            <span class="fw-bold text-dark" id="selesaiMeja">-</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Total Tagihan:</span>
                            <span class="fw-bold text-primary fs-6" id="selesaiTotalHarga">Rp 0</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a id="btnCetakStrukSelesai" href="#" target="_blank" class="btn btn-primary py-2.5 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: #5B6D92 !important; border-color: #5B6D92 !important;">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak Struk Sekarang</span>
                        </a>
                        <button type="button" class="btn btn-light border py-2 text-secondary fw-semibold rounded-3" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let modalBayarInstance = null;
    let modalDetailInstance = null;
    let modalSelesaiInstance = null;
    let bayarData = { id: null, kode: '', total: 0 };
    let activePesananList = [];

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('modalTerimaUang');
        if (modalEl) {
            modalBayarInstance = new bootstrap.Modal(modalEl);
            modalEl.addEventListener('hidden.bs.modal', function () {
                fetchPesanan();
            });
        }

        const modalDetailEl = document.getElementById('modalDetailTransaksi');
        if (modalDetailEl) {
            modalDetailInstance = new bootstrap.Modal(modalDetailEl);
        }

        const modalSelesaiEl = document.getElementById('modalSelesaiPesanan');
        if (modalSelesaiEl) {
            modalSelesaiInstance = new bootstrap.Modal(modalSelesaiEl);
            modalSelesaiEl.addEventListener('hidden.bs.modal', function () {
                fetchPesanan();
            });
        }
    });

    function bukaModalSelesai(pesananObj, transaksiId, strukUrl) {
        const p = pesananObj || activePesananList.find(item => item.id === transaksiId);
        if (p) {
            document.getElementById('selesaiKodeTrx').textContent = p.kode_transaksi;
            document.getElementById('selesaiNamaPemesan').textContent = p.nama_pemesan || 'Pelanggan';
            let namaMeja = p.meja ? `Meja #${p.meja.nomor_meja ?? p.meja.id}` : (p.meja_id ? `Meja #${p.meja_id}` : 'Kasir / Langsung');
            document.getElementById('selesaiMeja').textContent = namaMeja;
            document.getElementById('selesaiTotalHarga').textContent = 'Rp ' + formatRupiah(p.total_harga);
        }
        
        const btnCetak = document.getElementById('btnCetakStrukSelesai');
        btnCetak.href = strukUrl || `/admin/transaksi/${transaksiId}/struk`;

        if (modalSelesaiInstance) {
            modalSelesaiInstance.show();
        }
    }

    function bukaModalDetail(id) {
        const p = activePesananList.find(item => item.id === id);
        if (!p) return;

        document.getElementById('detailKodeTrx').textContent = '#' + p.kode_transaksi;
        document.getElementById('detailNamaPemesan').textContent = p.nama_pemesan || 'Pelanggan';
        
        let namaMeja = p.meja ? `Meja #${p.meja.nomor_meja ?? p.meja.id}` : (p.meja_id ? `Meja #${p.meja_id}` : 'Kasir / Langsung');
        document.getElementById('detailMeja').textContent = namaMeja;

        let waktuStr = '-';
        if (p.created_at) {
            const dateObj = new Date(p.created_at.includes('T') ? p.created_at : p.created_at.replace(' ', 'T'));
            if (!isNaN(dateObj.getTime())) {
                waktuStr = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) + ' ' + 
                           dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace(':', '.') + ' WIB';
            }
        }
        document.getElementById('detailWaktu').textContent = waktuStr;

        let isQris = (p.metode_pembayaran === 'payment_gateway');
        document.getElementById('detailMetodeBayar').innerHTML = isQris
            ? '<span class="badge bg-light text-dark border px-2 py-1"><i class="fa-solid fa-qrcode text-primary me-1"></i>QRIS</span>'
            : '<span class="badge bg-light text-dark border px-2 py-1"><i class="fa-solid fa-money-bill-wave text-success me-1"></i>TUNAI</span>';

        let badgeBayar = '';
        if (p.status_pembayaran === 'lunas') {
            badgeBayar = '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i>LUNAS</span>';
        } else if (p.status_pembayaran === 'batal') {
            badgeBayar = '<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i>BATAL</span>';
        } else {
            badgeBayar = '<span class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2.5 py-1 fw-bold"><i class="fa-solid fa-clock me-1"></i>MENUNGGU</span>';
        }
        document.getElementById('detailStatusBayar').innerHTML = badgeBayar;

        let badgePesanan = '';
        if (p.status_pesanan === 'menunggu') {
            badgePesanan = '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1 fw-bold"><i class="fa-solid fa-hourglass-start me-1"></i>MENUNGGU</span>';
        } else if (p.status_pesanan === 'diproses') {
            badgePesanan = '<span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fw-bold"><i class="fa-solid fa-fire-burner me-1"></i>DIPROSES</span>';
        } else if (p.status_pesanan === 'selesai') {
            badgePesanan = '<span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-2.5 py-1 fw-bold"><i class="fa-solid fa-check-double me-1"></i>SELESAI</span>';
        } else {
            badgePesanan = '<span class="badge bg-dark-subtle text-dark rounded-pill px-2.5 py-1 fw-bold">' + (p.status_pesanan || '-') + '</span>';
        }
        document.getElementById('detailStatusPesanan').innerHTML = badgePesanan;

        // Render item list
        const items = p.detail_transaksi || [];
        document.getElementById('detailTotalItemCount').textContent = items.length;
        const containerItems = document.getElementById('detailListItems');
        if (items.length === 0) {
            containerItems.innerHTML = '<div class="text-muted small text-center py-2">Tidak ada item pesanan</div>';
        } else {
            containerItems.innerHTML = items.map((dt, idx) => `
                <div class="p-2.5 rounded-3 border d-flex justify-content-between align-items-center" style="background-color: #f8fafc;">
                    <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                        <span class="badge bg-white text-dark border rounded-circle" style="width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;">${idx + 1}</span>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">${dt.produk ? dt.produk.nama_produk : 'Produk'}</div>
                            ${dt.catatan ? `<div class="text-muted small" style="font-size: 0.75rem;"><i class="fa-regular fa-note-sticky me-1"></i>${dt.catatan}</div>` : ''}
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold text-dark small">Rp ${formatRupiah(dt.subtotal)}</div>
                        <div class="text-muted" style="font-size: 0.72rem;">${dt.jumlah}x @ Rp ${formatRupiah(dt.harga_satuan)}</div>
                    </div>
                </div>
            `).join('');
        }

        // Finansial
        document.getElementById('detailTotalHarga').textContent = 'Rp ' + formatRupiah(p.total_harga);
        if (isQris) {
            document.getElementById('detailUangDiterima').innerHTML = '<span class="text-primary small fw-semibold"><i class="fa-solid fa-qrcode me-1"></i>QRIS Otomatis</span>';
            document.getElementById('detailUangKembalian').innerHTML = '<span class="text-muted small fw-normal">Rp 0 (Uang Pas)</span>';
        } else if (p.uang_diterima !== null && p.uang_diterima !== undefined) {
            document.getElementById('detailUangDiterima').textContent = 'Rp ' + formatRupiah(p.uang_diterima);
            document.getElementById('detailUangKembalian').textContent = 'Rp ' + formatRupiah(p.uang_kembalian || 0);
        } else {
            document.getElementById('detailUangDiterima').innerHTML = '<span class="text-muted fst-italic">Belum dibayar</span>';
            document.getElementById('detailUangKembalian').innerHTML = '<span class="text-muted fst-italic">-</span>';
        }

        // Link cetak struk modal
        document.getElementById('btnCetakStrukDetail').href = `/admin/transaksi/${p.id}/struk`;

        // Tombol Terima Uang dari Detail jika masih pending Tunai
        const actionBtnContainer = document.getElementById('detailActionBtnContainer');
        if (p.status_pembayaran === 'pending' && !isQris) {
            actionBtnContainer.innerHTML = `
                <button type="button" onclick="modalDetailInstance.hide(); bukaModalBayar(${p.id}, '${p.kode_transaksi}', ${p.total_harga})" class="btn text-white px-3 py-2 rounded-3 fw-bold shadow-sm d-flex align-items-center gap-1.5" style="background-color: #198754; border-color: #198754;">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <span>Terima Uang</span>
                </button>
            `;
        } else {
            actionBtnContainer.innerHTML = '';
        }

        if (modalDetailInstance) {
            modalDetailInstance.show();
        }
    }

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID').format(Math.max(0, num || 0));
    }

    function parseNominal(str) {
        if (!str) return 0;
        const cleaned = str.toString().replace(/[^0-9]/g, '');
        return cleaned ? parseInt(cleaned, 10) : 0;
    }

    function renderTombolCepat(total) {
        const container = document.getElementById('containerTombolCepat');
        if (!container) return;

        let options = ['pas'];
        const denominations = [10000, 20000, 50000, 100000, 150000, 200000];
        
        const round10k = Math.ceil(total / 10000) * 10000;
        if (round10k > total && !denominations.includes(round10k)) {
            denominations.push(round10k);
        }
        const round50k = Math.ceil(total / 50000) * 50000;
        if (round50k > total && !denominations.includes(round50k)) {
            denominations.push(round50k);
        }
        denominations.sort((a, b) => a - b);

        for (const d of denominations) {
            if (d >= total && !options.includes(d) && options.length < 4) {
                options.push(d);
            }
        }
        const fallback = [50000, 100000, 200000];
        for (const fb of fallback) {
            if (!options.includes(fb) && options.length < 4) {
                options.push(fb);
            }
        }

        container.innerHTML = options.map(opt => {
            if (opt === 'pas') {
                return `
                    <div class="col-6">
                        <button type="button" class="btn btn-sm w-100 fw-bold py-2 rounded-3 border transition-all text-truncate" style="background-color: #FAF5EF; color: #5B6D92; border-color: #D5E3E6;" onclick="setNominalCepat('pas')" title="Uang Pas">
                            <i class="fa-solid fa-equals me-1 text-primary"></i> Uang Pas
                        </button>
                    </div>
                `;
            } else {
                return `
                    <div class="col-6">
                        <button type="button" class="btn btn-sm w-100 fw-bold py-2 rounded-3 bg-white text-dark border transition-all" style="border-color: #e2e8f0;" onclick="setNominalCepat(${opt})">
                            Rp ${formatRupiah(opt)}
                        </button>
                    </div>
                `;
            }
        }).join('');
    }

    function bukaModalBayar(id, kode, total) {
        bayarData = { id: id, kode: kode, total: parseInt(total, 10) };
        
        // Reset tampilan modal
        document.getElementById('formBayarContainer').style.display = 'block';
        document.getElementById('modalBayarFooter').style.display = 'flex';
        document.getElementById('suksesBayarContainer').style.display = 'none';
        
        document.getElementById('bayarKodeTrx').textContent = kode;
        document.getElementById('bayarTotalText').textContent = 'Rp ' + formatRupiah(bayarData.total);
        
        const inputUang = document.getElementById('inputUangDiterima');
        inputUang.value = '';
        
        renderTombolCepat(bayarData.total);
        updateKembalianUI(0);

        if (modalBayarInstance) {
            modalBayarInstance.show();
            setTimeout(() => inputUang.focus(), 400);
        }
    }

    function setNominalCepat(val) {
        let nominal = 0;
        if (val === 'pas') {
            nominal = bayarData.total;
        } else {
            nominal = parseInt(val, 10);
        }
        const inputUang = document.getElementById('inputUangDiterima');
        inputUang.value = formatRupiah(nominal);
        updateKembalianUI(nominal);
    }

    function onInputUangDiterima() {
        const inputUang = document.getElementById('inputUangDiterima');
        const nominal = parseNominal(inputUang.value);
        if (nominal > 0) {
            inputUang.value = formatRupiah(nominal);
        } else {
            inputUang.value = '';
        }
        updateKembalianUI(nominal);
    }

    function updateKembalianUI(uangDiterima) {
        const box = document.getElementById('boxKembalianLive');
        const iconBox = document.getElementById('kembalianIconBox');
        const icon = document.getElementById('kembalianIcon');
        const label = document.getElementById('kembalianStatusLabel');
        const val = document.getElementById('kembalianStatusValue');
        const btn = document.getElementById('btnKonfirmasiBayar');

        if (!uangDiterima || uangDiterima === 0) {
            box.className = 'alert p-3 mb-0 rounded-3 border d-flex align-items-center transition-all';
            box.style.backgroundColor = '#f8fafc';
            box.style.borderColor = '#e2e8f0';
            box.style.color = '#475569';
            if (iconBox) {
                iconBox.style.backgroundColor = '#e2e8f0';
                iconBox.style.color = '#64748b';
            }
            icon.className = 'fa-solid fa-circle-info fs-5 text-secondary';
            label.textContent = 'Status Pembayaran';
            val.className = 'fw-bold fs-6 text-dark';
            val.textContent = 'Masukkan uang diterima';
            btn.disabled = true;
            btn.style.opacity = '0.6';
            return;
        }

        if (uangDiterima < bayarData.total) {
            const kurang = bayarData.total - uangDiterima;
            box.className = 'alert p-3 mb-0 rounded-3 border d-flex align-items-center transition-all';
            box.style.backgroundColor = '#fef2f2';
            box.style.borderColor = '#fecaca';
            box.style.color = '#991b1b';
            if (iconBox) {
                iconBox.style.backgroundColor = '#fee2e2';
                iconBox.style.color = '#dc2626';
            }
            icon.className = 'fa-solid fa-circle-xmark fs-5 text-danger';
            label.textContent = 'Uang Belum Cukup';
            val.className = 'fw-bold fs-6 text-danger';
            val.textContent = 'Kurang Rp ' + formatRupiah(kurang);
            btn.disabled = true;
            btn.style.opacity = '0.6';
        } else {
            const kembalian = uangDiterima - bayarData.total;
            box.className = 'alert p-3 mb-0 rounded-3 border d-flex align-items-center transition-all';
            box.style.backgroundColor = '#f0fdf4';
            box.style.borderColor = '#bbf7d0';
            box.style.color = '#166534';
            if (iconBox) {
                iconBox.style.backgroundColor = '#dcfce7';
                iconBox.style.color = '#16a34a';
            }
            icon.className = 'fa-solid fa-circle-check fs-5 text-success';
            label.textContent = kembalian === 0 ? 'Uang Pas' : 'Kembalian';
            val.className = 'fw-bold fs-5 text-success';
            val.textContent = kembalian === 0 ? 'Tidak ada kembalian' : 'Rp ' + formatRupiah(kembalian);
            btn.disabled = false;
            btn.style.opacity = '1';
        }
    }

    function submitKonfirmasiBayar() {
        const inputUang = document.getElementById('inputUangDiterima');
        const uangDiterima = parseNominal(inputUang.value);

        if (uangDiterima < bayarData.total) {
            alert('Uang diterima kurang dari total tagihan!');
            return;
        }

        const btn = document.getElementById('btnKonfirmasiBayar');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';

        updateStatus(bayarData.id, 'lunas', null, uangDiterima)
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                
                if (data && data.success) {
                    // Tampilkan view sukses di modal
                    document.getElementById('formBayarContainer').style.display = 'none';
                    document.getElementById('modalBayarFooter').style.display = 'none';
                    document.getElementById('suksesBayarContainer').style.display = 'block';

                    document.getElementById('suksesKodeTrx').textContent = bayarData.kode;
                    document.getElementById('suksesTotalTagihan').textContent = 'Rp ' + formatRupiah(bayarData.total);
                    document.getElementById('suksesUangDiterima').textContent = 'Rp ' + formatRupiah(uangDiterima);
                    document.getElementById('suksesKembalianText').textContent = data.kembalian_formatted || ('Rp ' + formatRupiah(uangDiterima - bayarData.total));
                    
                    const btnStruk = document.getElementById('btnCetakStrukModal');
                    btnStruk.href = data.struk_url || `/admin/transaksi/${bayarData.id}/struk`;
                } else {
                    alert((data && data.message) ? data.message : 'Gagal memperbarui status transaksi.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                alert('Terjadi kesalahan koneksi.');
            });
    }

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
                activePesananList = data;
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
                    let isQris = (p.metode_pembayaran === 'payment_gateway');
                    let badgeMetode = isQris 
                        ? `<span class="badge rounded-pill px-2.5 py-1 fw-bold" style="background-color: #f1f5f9; color: #475569; font-size: 0.75rem;"><i class="fa-solid fa-qrcode me-1"></i>QRIS</span>` 
                        : `<span class="badge rounded-pill px-2.5 py-1 fw-bold" style="background-color: #e2e8f0; color: #475569; font-size: 0.75rem;"><i class="fa-solid fa-money-bill-wave me-1"></i>Tunai</span>`;

                    // Inisial avatar
                    let inisial = (p.nama_pemesan && p.nama_pemesan.trim().length > 0) ? p.nama_pemesan.trim().charAt(0).toUpperCase() : 'P';
                    
                    // Format waktu jam:menit
                    let waktuStr = '';
                    if (p.created_at) {
                        const dateObj = new Date(p.created_at.includes('T') ? p.created_at : p.created_at.replace(' ', 'T'));
                        if (!isNaN(dateObj.getTime())) {
                            waktuStr = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace(':', '.');
                        }
                    }
                    let namaMeja = p.meja ? `Meja #${p.meja.nomor_meja ?? p.meja.id}` : (p.meja_id ? `Meja #${p.meja_id}` : 'Kasir');
                    let infoSub = `${namaMeja}${waktuStr ? ' · masuk ' + waktuStr : ''}`;

                    // Stepper: 1: Pesan, 2: Bayar, 3: Selesai
                    let step1Class = 'completed'; // selalu selesai karena sudah masuk antrean
                    let step2Class = '';
                    let step3Class = '';
                    let line1Class = '';
                    let line2Class = '';

                    if (p.status_pembayaran === 'pending') {
                        // Sedang menunggu bayar
                        step2Class = 'active';
                        line1Class = 'active';
                    } else if (p.status_pembayaran === 'lunas') {
                        step2Class = 'completed';
                        line1Class = 'active';
                        if (p.status_pesanan === 'selesai') {
                            step3Class = 'completed';
                            line2Class = 'active';
                        } else {
                            step3Class = 'active';
                        }
                    }

                    let stepperHtml = `
                        <div class="stepper-mini">
                            <div class="step-mini-item ${step1Class}">
                                <div class="step-mini-circle"><i class="fa-solid fa-check"></i></div>
                                <div class="step-mini-label">Pesan</div>
                            </div>
                            <div class="step-mini-line ${line1Class}"></div>
                            <div class="step-mini-item ${step2Class}">
                                <div class="step-mini-circle">
                                    ${step2Class === 'completed' ? '<i class="fa-solid fa-check"></i>' : '2'}
                                </div>
                                <div class="step-mini-label">Bayar</div>
                            </div>
                            <div class="step-mini-line ${line2Class}"></div>
                            <div class="step-mini-item ${step3Class}">
                                <div class="step-mini-circle">
                                    ${step3Class === 'completed' ? '<i class="fa-solid fa-check"></i>' : '3'}
                                </div>
                                <div class="step-mini-label">Selesai</div>
                            </div>
                        </div>
                    `;

                    // Item pesanan card (tampilkan maksimal 2 item agar kartu rapi dan proporsional)
                    const maxDisplayItems = 2;
                    const itemsToDisplay = p.detail_transaksi ? p.detail_transaksi.slice(0, maxDisplayItems) : [];
                    const sisaItemCount = (p.detail_transaksi ? p.detail_transaksi.length : 0) - maxDisplayItems;

                    let listItemsHtml = itemsToDisplay.map(dt => `
                        <div class="item-pesanan-box">
                            <div class="d-flex align-items-center flex-grow-1 overflow-hidden me-2">
                                <div class="item-pesanan-icon">
                                    <i class="fa-solid fa-ticket"></i>
                                </div>
                                <span class="fw-bold text-dark text-truncate" style="font-size: 0.95rem;">
                                    ${dt.produk ? dt.produk.nama_produk : 'Produk'}
                                </span>
                            </div>
                            <span class="text-secondary fw-semibold" style="font-size: 0.9rem;">${dt.jumlah}x</span>
                        </div>
                    `).join('');

                    if (sisaItemCount > 0) {
                        listItemsHtml += `
                            <div role="button" onclick="bukaModalDetail(${p.id})" class="btn-more-items py-2 px-3 rounded-3 d-flex align-items-center justify-content-between mb-2 text-decoration-none shadow-2xs" style="background-color: #FAF5EF; border: 1px dashed #D5E3E6; cursor: pointer;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-pill fw-bold px-2 py-1" style="background-color: #D5E3E6; color: #5B6D92; font-size: 0.72rem;">+${sisaItemCount} item lainnya</span>
                                    <span class="fw-semibold text-muted" style="font-size: 0.8rem;">Lihat detail lengkap</span>
                                </div>
                                <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                            </div>
                        `;
                    }

                    // Tombol Aksi Utama
                    let qrisPendingNotice = '';
                    let actionBtnHtml = '';

                    if (p.status_pembayaran === 'pending') {
                        if (isQris) {
                            qrisPendingNotice = `
                                <div class="alert alert-warning py-2.5 px-3 small d-flex align-items-center mb-3 rounded-4 border-warning-subtle text-dark">
                                    <div class="spinner-border spinner-border-sm text-warning me-2" role="status"></div>
                                    <span class="fw-semibold" style="font-size: 0.85rem;">Menunggu pembayaran QRIS...</span>
                                </div>
                            `;
                        } else {
                            actionBtnHtml = `
                                <button onclick="bukaModalBayar(${p.id}, '${p.kode_transaksi}', ${p.total_harga})" class="btn btn-action-main w-100 mb-2">
                                    <i class="fa-solid fa-money-bill-wave me-2"></i> Terima uang
                                </button>
                            `;
                        }
                    } else if (p.status_pesanan === 'menunggu' && p.status_pembayaran === 'lunas') {
                        actionBtnHtml = `
                            <button onclick="updateStatus(${p.id}, 'diproses')" class="btn btn-action-main w-100 mb-2" style="background-color: #5B6D92 !important; border-color: #5B6D92 !important;">
                                <i class="fa-solid fa-fire-burner me-2"></i> Proses Pesanan
                            </button>
                        `;
                    } else if (p.status_pesanan === 'diproses') {
                        actionBtnHtml = `
                            <button onclick="prosesSelesaiDanCetak(${p.id})" class="btn btn-action-main w-100 mb-2" style="background-color: #0284c7 !important; border-color: #0284c7 !important;">
                                <i class="fa-solid fa-check-double me-2"></i> Selesai (Serahkan)
                            </button>
                        `;
                    }

                    let btnBatalHtml = p.status_pembayaran === 'pending' ? `
                        <div class="text-center mt-1">
                            <button onclick="updateStatus(${p.id}, 'batal')" class="btn-action-cancel">
                                Batal (Kembalikan stok)
                            </button>
                        </div>
                    ` : '';

                    let html = `
                        <div class="col-xl-4 col-lg-6 col-12">
                            <div class="card h-100 kasir-card p-4">
                                <!-- Top Bar: Avatar, Nama, Meja, Badge Tunai/QRIS -->
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-3 overflow-hidden me-2">
                                        <div class="kasir-avatar">
                                            ${inisial}
                                        </div>
                                        <div class="overflow-hidden">
                                            <h5 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 1.2rem; letter-spacing: -0.3px;">
                                                ${p.nama_pemesan || 'Pelanggan'}
                                            </h5>
                                            <div class="text-muted small" style="font-size: 0.85rem;">
                                                ${infoSub}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        ${badgeMetode}
                                    </div>
                                </div>

                                <!-- Stepper Status (Pesan -> Bayar -> Selesai) -->
                                ${stepperHtml}

                                <!-- Daftar Item Pesanan -->
                                <div class="my-2 flex-grow-1">
                                    ${listItemsHtml}
                                </div>

                                <!-- Total Bayar -->
                                <div class="d-flex justify-content-between align-items-baseline my-3">
                                    <span class="text-secondary fw-normal fs-6">Total bayar</span>
                                    <div class="fw-bold text-dark" style="font-size: 1.85rem; letter-spacing: -0.5px;">
                                        Rp ${new Intl.NumberFormat('id-ID').format(p.total_harga)}
                                    </div>
                                </div>

                                <!-- Tombol Aksi Utama -->
                                <div class="mt-auto">
                                    ${qrisPendingNotice}
                                    ${actionBtnHtml}

                                    <!-- Tombol Detail Transaksi -->
                                    <div class="mb-2">
                                        <button type="button" onclick="bukaModalDetail(${p.id})" class="btn-action-secondary w-100" title="Lihat Detail Transaksi">
                                            <i class="fa-solid fa-receipt text-primary"></i>
                                            <span>Detail Transaksi</span>
                                        </button>
                                    </div>

                                    ${btnBatalHtml}
                                    <div class="text-center mt-2">
                                        <span class="text-muted fw-bold" style="font-size: 0.8rem; letter-spacing: 0.3px;">#${p.kode_transaksi}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += html;
                });
            });
    }

    function updateStatus(id, action, val, uangDiterima = null) {
        let act = val || action;
        if (act === 'dibatalkan') act = 'batal';
        const payload = { action: act };
        if (uangDiterima !== null) {
            payload.uang_diterima = uangDiterima;
        }
        return fetch(`/kasir/pesanan/${id}/update`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
            body: JSON.stringify(payload)
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(errData => {
                    alert(errData.message || 'Gagal mengubah status');
                    throw new Error(errData.message);
                });
            }
            return res.json();
        })
        .then(data => {
            fetchPesanan();
            return data;
        });
    }

    function prosesSelesaiDanCetak(id) {
        const pesanan = activePesananList.find(item => item.id === id);
        updateStatus(id, 'selesai')
            .then(data => {
                if (data && data.success) {
                    bukaModalSelesai(pesanan, id, data.struk_url);
                }
            })
            .catch(err => {
                // error handled in updateStatus
            });
    }

    fetchPesanan(); 
    setInterval(fetchPesanan, 5000);
</script>
@endsection