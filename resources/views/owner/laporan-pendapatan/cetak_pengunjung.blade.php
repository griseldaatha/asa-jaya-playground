<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengunjung Harian - ASA JAYA PLAYGROUND ({{ \Carbon\Carbon::parse($dari)->format('d-m-Y') }} s.d. {{ \Carbon\Carbon::parse($sampai)->format('d-m-Y') }})</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background-color: #f1f5f9;
            line-height: 1.4;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
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
        .btn-print { background-color: #2563eb; color: #ffffff; }
        .btn-print:hover { background-color: #1d4ed8; }
        .btn-close-window { background-color: #475569; color: #ffffff; }
        .btn-close-window:hover { background-color: #334155; }
        .hint-text { font-size: 11px; color: #cbd5e1; margin-right: 12px; }

        .print-container {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 16mm 14mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
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
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 12px;
        }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }
        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.3px;
        }
        tfoot th, tfoot td {
            background-color: #e2e8f0;
            font-weight: 700;
            border-top: 2px solid #94a3b8;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: 700; }
        .fw-semibold { font-weight: 600; }
        .text-success { color: #059669; }
        .text-danger { color: #dc2626; }
        .text-primary { color: #2563eb; }
        .text-muted { color: #64748b; }

        .table-summary { margin-bottom: 14px; }
        .table-summary th { width: 22%; background-color: #f8fafc; }
        .table-summary td { width: 28%; }

        .footer-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            page-break-inside: avoid;
        }
        .print-meta { font-size: 10px; color: #475569; line-height: 1.5; }
        .signature-box { text-align: center; width: 180px; }
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

        @page { size: A4 portrait; margin: 10mm 10mm 12mm 10mm; }
        @media print {
            body { background-color: #ffffff; color: #000000; }
            .no-print, .no-print-bar { display: none !important; }
            .print-container { width: 100% !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print-bar no-print">
        <div class="title">
            <i class="fa-solid fa-file-pdf text-danger"></i>
            <span>Pratinjau Cetak / Ekspor PDF - Laporan Pengunjung Harian</span>
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

    <div class="print-container">
        <!-- KOP -->
        <div class="header-kop">
            <div class="brand">ASA JAYA PLAYGROUND</div>
            <div class="doc-title">LAPORAN PENGUNJUNG HARIAN</div>
            <div class="period">
                Periode: <strong>{{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</strong>
            </div>
        </div>

        <!-- 1. RINGKASAN PENGUNJUNG & FINANSIAL -->
        <div class="section-title">
            <i class="fa-solid fa-calculator"></i>
            <span>1. Ringkasan Pengunjung & Finansial</span>
        </div>
        <table class="table-summary">
            <tbody>
                <tr>
                    <th>Total Pengunjung / Pesanan</th>
                    <td class="fw-bold">{{ number_format($countTransaksi) }} Orang</td>
                    <th>Total Pendapatan Kotor</th>
                    <td class="fw-bold text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Rata-rata per Pengunjung</th>
                    <td class="fw-semibold">Rp {{ number_format($rataRataPerTransaksi, 0, ',', '.') }} / orang</td>
                    <th>Beban Pengeluaran</th>
                    <td class="fw-bold text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Metode Tunai</th>
                    <td>Rp {{ number_format($totalTunai, 0, ',', '.') }} ({{ $countTunai }} trx)</td>
                    <th>Laba Bersih Usaha</th>
                    <td class="fw-bold {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                        <span class="text-muted" style="font-size: 8.5px;">({{ $marginLaba }}% margin)</span>
                    </td>
                </tr>
                <tr>
                    <th>Metode Transfer</th>
                    <td>Rp {{ number_format($totalTransfer, 0, ',', '.') }} ({{ $countTransfer }} trx)</td>
                    <th>Metode E-Wallet</th>
                    <td>Rp {{ number_format($totalEwallet, 0, ',', '.') }} ({{ $countEwallet }} trx)</td>
                </tr>
            </tbody>
        </table>

        <!-- 2. REKAP HARIAN -->
        <div class="section-title">
            <i class="fa-solid fa-calendar-day"></i>
            <span>2. Rekap Pengunjung & Finansial Harian</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 25px;" class="text-center">No</th>
                    <th style="width: 75px;">Tanggal</th>
                    <th class="text-center" style="width: 75px;">Pengunjung</th>
                    <th class="text-end" style="width: 80px;">Tunai</th>
                    <th class="text-end" style="width: 80px;">Transfer</th>
                    <th class="text-end" style="width: 80px;">E-Wallet</th>
                    <th class="text-end" style="width: 95px;">Pendapatan</th>
                    <th class="text-end" style="width: 90px;">Pengeluaran</th>
                    <th class="text-end" style="width: 95px;">Laba Bersih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapHarian as $index => $row)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $row['tanggal'] }}</td>
                        <td class="text-center">{{ number_format($row['jml_transaksi']) }} orang</td>
                        <td class="text-end">Rp {{ number_format($row['total_tunai'], 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($row['total_transfer'], 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($row['total_ewallet'], 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($row['total_pendapatan'], 0, ',', '.') }}</td>
                        <td class="text-end text-danger">{{ $row['total_pengeluaran'] > 0 ? 'Rp ' . number_format($row['total_pengeluaran'], 0, ',', '.') : '-' }}</td>
                        <td class="text-end fw-bold {{ $row['laba_bersih'] >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($row['laba_bersih'], 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted" style="padding: 10px;">
                             Tidak ada catatan pengunjung atau finansial pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-center">TOTAL</th>
                    <th class="text-center">{{ number_format($countTransaksi) }} Pengunjung</th>
                    <th class="text-end">Rp {{ number_format($totalTunai, 0, ',', '.') }}</th>
                    <th class="text-end">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</th>
                    <th class="text-end">Rp {{ number_format($totalEwallet, 0, ',', '.') }}</th>
                    <th class="text-end text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                    <th class="text-end text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
                    <th class="text-end {{ $labaBersih >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>

        <!-- FOOTER TANDA TANGAN -->
        <div class="footer-section">
            <div class="print-meta">
                <div>Dicetak oleh: <strong>{{ Auth::user()->nama ?? Auth::user()->username ?? 'Owner' }}</strong></div>
                <div>Pada: <strong>{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</strong></div>
                <div class="text-muted" style="margin-top: 4px;">Dokumen ini merupakan laporan resmi pengunjung harian ASA JAYA PLAYGROUND.</div>
            </div>
            <div class="signature-box">
                <div class="role-label">Pemilik (Owner)</div>
                <div class="name-line">{{ Auth::user()->nama ?? Auth::user()->username ?? 'Pemilik' }}</div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
