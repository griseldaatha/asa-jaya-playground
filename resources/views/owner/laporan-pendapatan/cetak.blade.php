<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - ASA JAYA PLAYGROUND ({{ \Carbon\Carbon::parse($dari)->format('d-m-Y') }} s.d. {{ \Carbon\Carbon::parse($sampai)->format('d-m-Y') }})</title>
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
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-print {
            background-color: #2563eb;
            color: #ffffff;
        }
        .btn-print:hover {
            background-color: #1d4ed8;
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
            color: #cbd5e1;
            margin-right: 12px;
        }

        /* Container Kertas A4 */
        .print-container {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 16mm 14mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        /* Kop Laporan */
        .header-kop {
            text-align: center;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header-kop .brand {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 1.5px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header-kop .doc-title {
            font-size: 14px;
            font-weight: 700;
            color: #334155;
            margin-top: 2px;
            text-transform: uppercase;
        }
        .header-kop .period {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
            font-weight: 500;
        }

        /* Section Title */
        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1e293b;
            margin-top: 14px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Tabel Standar Cetak */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 12px;
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
        .text-danger { color: #b91c1c !important; }

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

        /* Page Media Rules */
        @page {
            size: A4 portrait;
            margin: 10mm 10mm 12mm 10mm;
        }

        @media print {
            body {
                background-color: #ffffff;
                color: #000000;
            }
            .no-print, .no-print-bar {
                display: none !important;
            }
            .print-container {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Kontrol Non-Cetak -->
    <div class="no-print-bar no-print">
        <div class="title">
            <i class="fa-solid fa-file-pdf text-danger"></i>
            <span>Pratinjau Cetak / Ekspor PDF - Laporan Pendapatan</span>
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
            <div class="doc-title">LAPORAN PENDAPATAN</div>
            <div class="period">
                Periode: <strong>{{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</strong>
            </div>
        </div>

        <!-- 1. RINGKASAN EKSEKUTIF -->
        <div class="section-title">
            <i class="fa-solid fa-calculator"></i>
            <span>1. Ringkasan Pendapatan</span>
        </div>
        <table class="table-summary">
            <tbody>
                <tr>
                    <th>Total Pendapatan</th>
                    <td class="fw-bold text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    <th>Jumlah Transaksi Lunas</th>
                    <td class="fw-semibold">{{ number_format($countTransaksi) }} Pesanan</td>
                </tr>
                <tr>
                    <th>Rincian Tunai</th>
                    <td>Rp {{ number_format($totalTunai, 0, ',', '.') }} ({{ $countTunai }} trx)</td>
                    <th>Rata-rata per Transaksi</th>
                    <td class="fw-semibold">Rp {{ number_format($rataRataPerTransaksi, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Rincian Transfer</th>
                    <td>Rp {{ number_format($totalTransfer, 0, ',', '.') }} ({{ $countTransfer }} trx)</td>
                    <th>Rincian E-Wallet</th>
                    <td>Rp {{ number_format($totalEwallet, 0, ',', '.') }} ({{ $countEwallet }} trx)</td>
                </tr>
                <tr>
                    <th>Status Pembayaran</th>
                    <td class="fw-semibold text-success" colspan="3">Lunas (100%)</td>
                </tr>
            </tbody>
        </table>

        <!-- 2. REKAP PER HARI -->
        <div class="section-title">
            <i class="fa-solid fa-calendar-day"></i>
            <span>2. Rekap Per Hari</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">No</th>
                    <th style="width: 90px;">Tanggal</th>
                    <th class="text-center" style="width: 100px;">Jumlah Transaksi</th>
                    <th class="text-end" style="width: 110px;">Tunai</th>
                    <th class="text-end" style="width: 110px;">Transfer</th>
                    <th class="text-end" style="width: 110px;">E-Wallet</th>
                    <th class="text-end" style="width: 125px;">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapHarian as $index => $row)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $row['tanggal'] }}</td>
                        <td class="text-center">{{ number_format($row['jml_transaksi']) }}</td>
                        <td class="text-end">Rp {{ number_format($row['total_tunai'], 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($row['total_transfer'], 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($row['total_ewallet'], 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($row['total_pendapatan'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 10px;">
                             Tidak ada transaksi pendapatan lunas pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-center">TOTAL</th>
                    <th class="text-center">{{ number_format($countTransaksi) }}</th>
                    <th class="text-end">Rp {{ number_format($totalTunai, 0, ',', '.') }}</th>
                    <th class="text-end">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</th>
                    <th class="text-end">Rp {{ number_format($totalEwallet, 0, ',', '.') }}</th>
                    <th class="text-end text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <!-- 3. RINCIAN PENDAPATAN -->
        <div class="section-title">
            <i class="fa-solid fa-list-check"></i>
            <span>3. Rincian Pendapatan (Transaksi Lunas)</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;" class="text-center">No</th>
                    <th style="width: 85px;">Tanggal & Jam</th>
                    <th style="width: 110px;">Kode Transaksi</th>
                    <th style="width: 90px;">Meja / Tipe</th>
                    <th style="width: 70px;" class="text-center">Metode</th>
                    <th>Kasir</th>
                    <th class="text-end" style="width: 100px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rincianPendapatan as $index => $trx)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($trx->waktu_transaksi ?? $trx->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="fw-semibold">{{ $trx->kode_transaksi }}</td>
                        <td>
                            @if($trx->meja)
                                Meja #{{ $trx->meja->nomor_meja ?? $trx->meja->id }}
                            @else
                                Kasir / Langsung
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $trx->metode_label }}
                        </td>
                        <td>{{ $trx->kasir->nama ?? $trx->kasir->username ?? '-' }}</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 10px;">
                            Tidak ada data transaksi pendapatan lunas pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" class="text-end">TOTAL PENDAPATAN</th>
                    <th class="text-end text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <!-- KETERANGAN CETAK & TANDA TANGAN -->
        <div class="footer-section">
            <div class="print-meta">
                <div>Dicetak oleh: <strong>{{ Auth::user()->nama ?? Auth::user()->username ?? 'Owner' }}</strong></div>
                <div>Pada: <strong>{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</strong></div>
                <div class="text-muted" style="margin-top: 4px;">Dokumen ini merupakan laporan resmi pendapatan ASA JAYA PLAYGROUND.</div>
            </div>
            <div class="signature-box">
                <div class="role-label">Pemilik (Owner)</div>
                <div class="name-line">{{ Auth::user()->nama ?? Auth::user()->username ?? 'Pemilik' }}</div>
            </div>
        </div>
    </div>

    <!-- Script Otomatis Print -->
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
