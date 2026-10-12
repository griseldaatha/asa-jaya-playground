<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaksi->kode_transaksi }}</title>
    <!-- FontAwesome for icons on preview screen -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f1f3f5;
            color: #000;
            font-size: 12px;
            line-height: 1.35;
        }

        /* Top control bar for preview screen */
        .no-print-bar {
            background: #212529;
            color: #fff;
            padding: 12px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: bold;
            font-family: system-ui, -apple-system, sans-serif;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-print {
            background-color: #0d6efd;
            color: #fff;
        }
        .btn-print:hover {
            background-color: #0b5ed7;
        }

        .btn-close-window {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-close-window:hover {
            background-color: #5c636a;
        }

        /* Receipt Card (Thermal 80mm) */
        .struk-card {
            max-width: 300px;
            width: 100%;
            margin: 24px auto 40px auto;
            background: #ffffff;
            padding: 18px 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .header-title {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .header-sub {
            font-size: 10px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .double-divider {
            border-top: 2px double #000;
            margin: 8px 0;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .item-row {
            margin-bottom: 6px;
        }

        .item-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-weight: bold;
        }

        .item-name {
            flex-grow: 1;
            padding-right: 8px;
            word-break: break-word;
        }

        .item-sub {
            font-size: 10px;
            color: #444;
            padding-left: 12px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .badge-status {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            padding: 4px 0;
            margin: 6px 0;
            border: 1px dashed #000;
        }

        .footer-text {
            font-size: 11px;
            text-align: center;
            margin-top: 8px;
        }

        /* Print Media Styles: Thermal POS Paper & Standard Paper */
        @page {
            size: 58mm auto; /* Default printer thermal 58mm / 80mm roll */
            margin: 0mm;
        }

        @media print {
            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                color: #000000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print, .no-print-bar {
                display: none !important;
            }

            .struk-card {
                width: 100% !important;
                max-width: 58mm !important; /* Pas di kertas struk mini/standar kasir */
                margin: 0 auto !important;
                padding: 3mm 2mm !important;
                box-shadow: none !important;
                border: none !important;
            }

            .header-title {
                font-size: 13px !important;
            }

            .meta-row, .item-main, .total-row {
                font-size: 11px !important;
            }
        }
    </style>
</head>
<body>
    <!-- Bar Kontrol Tombol Layar (Tidak Tercetak) -->
    <div class="no-print-bar no-print">
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="fa-solid fa-print"></i> Cetak Ulang
        </button>
        <button onclick="window.close()" class="btn-action btn-close-window">
            <i class="fa-solid fa-xmark"></i> Tutup
        </button>
    </div>

    <!-- Kontainer Struk Thermal 80mm -->
    <div class="struk-card">
        <!-- Header -->
        <div class="text-center">
            <div class="header-title">ASA JAYA PLAYGROUND</div>
            <div class="header-sub">Wahana Permainan Anak</div>
        </div>

        <div class="double-divider"></div>

        <!-- Meta Informasi Transaksi -->
        <div class="meta-row">
            <span>Kode:</span>
            <span class="bold">#{{ $transaksi->kode_transaksi }}</span>
        </div>
        <div class="meta-row">
            <span>Waktu:</span>
            <span>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y H:i') }} WIB</span>
        </div>
        <div class="meta-row">
            <span>Kasir:</span>
            <span>{{ $transaksi->kasir->nama ?? 'Sistem' }}</span>
        </div>
        <div class="meta-row">
            <span>Meja:</span>
            <span class="bold">{{ $transaksi->meja ? ('Meja #' . ($transaksi->meja->nomor_meja ?? $transaksi->meja->id)) : 'Kasir / Langsung' }}</span>
        </div>
        <div class="meta-row">
            <span>Pemesan:</span>
            <span>{{ $transaksi->nama_pemesan }}</span>
        </div>

        <div class="divider"></div>

        <!-- Daftar Item Pesanan -->
        <div class="items-container">
            @foreach($transaksi->detailTransaksi as $dt)
                <div class="item-row">
                    <div class="item-main">
                        <span class="item-name">{{ $dt->jumlah }}x {{ $dt->produk->nama_produk ?? 'Item' }}</span>
                        <span>{{ number_format($dt->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="item-sub">
                        @ {{ number_format($dt->harga_satuan, 0, ',', '.') }}
                        @if($dt->catatan)
                            ({{ $dt->catatan }})
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="divider"></div>

        @php
            $isQris = ($transaksi->metode_pembayaran === 'payment_gateway');
        @endphp

        <!-- Total Tagihan -->
        <div class="total-row">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
        </div>
        <div class="meta-row">
            <span>Metode:</span>
            <span class="bold">{{ $isQris ? 'QRIS' : 'TUNAI' }}</span>
        </div>

        @if($transaksi->status_pembayaran === 'lunas')
            @if($isQris)
                <div class="badge-status">
                    *** LUNAS ***
                </div>
            @else
                <div class="meta-row">
                    <span>TUNAI:</span>
                    <span>Rp {{ $transaksi->uang_diterima !== null ? number_format($transaksi->uang_diterima, 0, ',', '.') : '-' }}</span>
                </div>
                <div class="meta-row bold">
                    <span>KEMBALI:</span>
                    <span>Rp {{ $transaksi->uang_kembalian !== null ? number_format($transaksi->uang_kembalian, 0, ',', '.') : '-' }}</span>
                </div>
                <div class="badge-status">
                    *** LUNAS ***
                </div>
            @endif
        @else
            <div class="badge-status">
                *** BELUM LUNAS ***
            </div>
        @endif

        <div class="double-divider"></div>

        <!-- Footer -->
        <div class="footer-text">
            Terima kasih, selamat bermain!
        </div>
    </div>

    <script>
        // Otomatis memicu print dialog saat halaman selesai dimuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
