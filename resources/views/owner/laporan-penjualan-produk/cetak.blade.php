<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Per Produk - ASA JAYA PLAYGROUND ({{ \Carbon\Carbon::parse($dari)->format('d-m-Y') }} s.d. {{ \Carbon\Carbon::parse($sampai)->format('d-m-Y') }})</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* CSS Reset & Base */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background-color: #f1f5f9;
            line-height: 1.4;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Toolbar Non-Cetak */
        .no-print-bar {
            background-color: #1e293b;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 9999;
        }
        .no-print-bar .title {
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .no-print-bar .actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-print {
            background-color: #0d6efd;
            color: #ffffff;
        }
        .btn-print:hover {
            background-color: #0b5ed7;
        }
        .btn-close-window {
            background-color: #475569;
            color: #ffffff;
        }
        .btn-close-window:hover {
            background-color: #334155;
        }
        .hint-text {
            font-size: 11px;
            color: #94a3b8;
            margin-right: 12px;
        }

        /* Container Dokumen A4 */
        .print-container {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20mm 18mm 20mm 18mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
        }

        /* KOP & Identitas */
        .header-kop {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header-kop .brand {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #0f172a;
        }
        .header-kop .doc-title {
            font-size: 14px;
            font-weight: 800;
            color: #334155;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }
        .header-kop .period {
            font-size: 10.5px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Section Titles */
        .section-title {
            font-size: 11.5px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 14px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10px;
        }
        thead {
            display: table-header-group;
        }
        tr {
            page-break-inside: avoid;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            vertical-align: middle;
        }
        th {
            background-color: #e2e8f0 !important;
            color: #1e293b;
            font-weight: 700;
            text-align: left;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        tfoot th, tfoot td {
            background-color: #f1f5f9 !important;
            font-weight: 700;
            border-top: 1.5px solid #64748b;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-start { text-align: left; }
        .fw-bold { font-weight: 700; }
        .fw-semibold { font-weight: 600; }
        .text-muted { color: #64748b; }
        .text-success { color: #15803d !important; }
        .text-primary { color: #0284c7 !important; }

        /* Tabel Ringkasan Eksekutif */
        .table-summary {
            margin-bottom: 12px;
        }
        .table-summary th {
            width: 25%;
            background-color: #f8fafc !important;
        }
        .table-summary td {
            width: 25%;
            font-size: 10.5px;
        }

        /* Footer & Tanda Tangan */
        .footer-section {
            margin-top: 24px;
            page-break-inside: avoid;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .print-meta {
            font-size: 9.5px;
            color: #64748b;
            max-width: 60%;
            line-height: 1.5;
        }
        .signature-box {
            text-align: center;
            width: 180px;
        }
        .signature-box .role-label {
            font-size: 10.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 50px;
        }
        .signature-box .name-line {
            border-top: 1px solid #1e293b;
            padding-top: 4px;
            font-weight: 700;
            font-size: 10.5px;
            color: #0f172a;
        }

        /* Cetak Stylesheet Media */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: none;
                margin: 0;
                padding: 0;
            }
            .print-container {
                width: 100%;
                margin: 0;
                padding: 10mm 12mm;
                box-shadow: none;
            }
            @page {
                size: A4 portrait;
                margin: 8mm 10mm;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Kontrol Non-Cetak -->
    <div class="no-print-bar no-print">
        <div class="title">
            <i class="fa-solid fa-file-pdf text-danger"></i>
            <span>Pratinjau Cetak / Ekspor PDF - Laporan Penjualan Per Produk</span>
        </div>
        <div class="actions">
            <span class="hint-text">
                <i class="fa-solid fa-circle-info me-1"></i>Pilih opsi <strong>"Save as PDF"</strong> pada dialog printer browser
            </span>
            <button type="button" class="btn-action btn-print" onclick="window.print()">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Simpan PDF</span>
            </button>
            <button type="button" class="btn-action btn-close-window" onclick="window.close()">
                <i class="fa-solid fa-xmark"></i>
                <span>Tutup</span>
            </button>
        </div>
    </div>

    <!-- Halaman Dokumen A4 -->
    <div class="print-container">
        <!-- KOP LAPORAN -->
        <div class="header-kop">
            <div class="brand">ASA JAYA PLAYGROUND</div>
            <div class="doc-title">LAPORAN PENJUALAN PER PRODUK</div>
            <div class="period">
                Periode: <strong>{{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</strong>
                @if($namaKategoriAktif)
                    | Filter Kategori: <strong>{{ $namaKategoriAktif }}</strong>
                @endif
                @if($search)
                    | Pencarian: <strong>"{{ $search }}"</strong>
                @endif
            </div>
        </div>

        <!-- 1. RINGKASAN EKSEKUTIF -->
        <div class="section-title">
            <i class="fa-solid fa-calculator"></i>
            <span>1. Ringkasan Eksekutif</span>
        </div>
        <table class="table-summary">
            <tbody>
                <tr>
                    <th>Total Unit Terjual</th>
                    <td class="fw-bold">{{ number_format($totalQty) }} Unit</td>
                    <th>Total Omzet Produk</th>
                    <td class="fw-bold text-success">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Produk Terlaris (Qty)</th>
                    <td class="fw-semibold">
                        {{ $topProdukQty->nama_produk ?? '-' }}
                        @if($topProdukQty)
                            <span class="text-muted" style="font-size: 8.5px;">({{ number_format($topProdukQty->total_qty) }}x)</span>
                        @endif
                    </td>
                    <th>Top Kontributor Omzet</th>
                    <td class="fw-semibold text-primary">
                        {{ $topProdukOmzet->nama_produk ?? '-' }}
                        @if($topProdukOmzet)
                            <span class="text-muted" style="font-size: 8.5px;">(Rp {{ number_format($topProdukOmzet->total_omzet, 0, ',', '.') }})</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Jumlah Jenis Produk Terjual</th>
                    <td colspan="3" class="fw-semibold">{{ $totalItemJenis }} Jenis Produk</td>
                </tr>
            </tbody>
        </table>

        <!-- 2. REKAP KATEGORI PRODUK -->
        @if($kategoriSales->count() > 0)
        <div class="section-title">
            <i class="fa-solid fa-chart-pie"></i>
            <span>2. Rekap Per Kategori Produk</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">No</th>
                    <th>Kategori Produk</th>
                    <th class="text-center" style="width: 120px;">Unit Terjual</th>
                    <th class="text-end" style="width: 150px;">Total Omzet</th>
                    <th class="text-end" style="width: 110px;">Kontribusi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategoriSales as $idx => $kat)
                    @php
                        $persen = $totalOmzet > 0 ? round(($kat->total_omzet / $totalOmzet) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="fw-semibold">{{ $kat->nama_kategori }}</td>
                        <td class="text-center">{{ number_format($kat->total_qty) }}</td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($kat->total_omzet, 0, ',', '.') }}</td>
                        <td class="text-end">{{ $persen }}%</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-center">TOTAL</th>
                    <th class="text-center">{{ number_format($totalQty) }}</th>
                    <th class="text-end text-success">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</th>
                    <th class="text-end">100.0%</th>
                </tr>
            </tfoot>
        </table>
        @endif

        <!-- 3. RINCIAN PENJUALAN PER PRODUK -->
        <div class="section-title">
            <i class="fa-solid fa-list-check"></i>
            <span>3. Rincian Penjualan Per Produk</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;" class="text-center">No</th>
                    <th>Nama Produk</th>
                    <th style="width: 120px;">Kategori</th>
                    <th class="text-end" style="width: 90px;">Harga</th>
                    <th class="text-center" style="width: 70px;">Terjual</th>
                    <th class="text-end" style="width: 110px;">Total Omzet</th>
                    <th class="text-end" style="width: 75px;">Kontribusi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $row)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $row->nama_produk }}</td>
                        <td>{{ $row->nama_kategori }}</td>
                        <td class="text-end">Rp {{ number_format($row->harga_katalog, 0, ',', '.') }}</td>
                        <td class="text-center fw-bold">{{ number_format($row->total_qty) }}</td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($row->total_omzet, 0, ',', '.') }}</td>
                        <td class="text-end">{{ $row->persentase_omzet }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 10px;">
                            Tidak ada transaksi penjualan produk pada periode terpilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($items->count() > 0)
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-center">TOTAL KESELURUHAN ({{ $totalItemJenis }} Produk)</th>
                        <th class="text-center">{{ number_format($totalQty) }}</th>
                        <th class="text-end text-success">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</th>
                        <th class="text-end">100.0%</th>
                    </tr>
                </tfoot>
            @endif
        </table>

        <!-- 4. CATATAN & TANDA TANGAN -->
        <div class="footer-section">
            <div class="print-meta">
                <p><strong>Catatan Laporan:</strong></p>
                <p>&bull; Data hanya mengakumulasi pesanan dengan status pembayaran Lunas.</p>
                <p>&bull; Dicetak pada: {{ now()->translatedFormat('d F Y, H:i:s') }} WIB</p>
                <p>&bull; Sistem Manajemen ASA JAYA PLAYGROUND (POS & Reporting)</p>
            </div>
            <div class="signature-box">
                <div class="role-label">Mengetahui,<br>Pemilik / Owner</div>
                <div class="name-line">{{ Auth::user()->nama ?? Auth::user()->username ?? 'Owner' }}</div>
            </div>
        </div>
    </div>

</body>
</html>
