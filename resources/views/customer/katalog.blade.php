<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Asa Jaya Playground</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --bg-canvas: #EFE1D1;
            --brand-navy: #2d4059;
            --brand-primary: #5B6D92;
            --brand-yellow: #f59e0b;
            --brand-coral: #ea580c;
            --ticket-bg: #ffffff;
            --ticket-photo-bg: #bae6fd;
            --text-dark: #1e293b;
        }

        body {
            background-color: var(--bg-canvas);
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="86" height="86" viewBox="0 0 86 86"><rect width="86" height="86" fill="%23EFE1D1"/><g transform="translate(6, 3) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(92, 3) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(6, 89) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><g transform="translate(92, 89) rotate(35)"><rect x="-6" y="-2.5" width="12" height="5" rx="2.5" fill="%23EF9B88"/></g><circle cx="47" cy="6" r="3.8" fill="%2384A8E8"/><circle cx="47" cy="92" r="3.8" fill="%2384A8E8"/><circle cx="33" cy="45" r="3.5" fill="%23F3D063"/><rect x="-6" y="62.5" width="12" height="5" rx="2.5" fill="%23C58DE8"/><rect x="80" y="62.5" width="12" height="5" rx="2.5" fill="%23C58DE8"/></svg>');
            background-repeat: repeat;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Top Navbar */
        .katalog-header {
            padding: 18px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-shapes {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .shape-triangle {
            width: 0;
            height: 0;
            border-left: 9px solid transparent;
            border-right: 9px solid transparent;
            border-bottom: 16px solid #eab308;
        }

        .shape-circle {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: #ef4444;
        }

        .shape-square {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            background-color: #3b82f6;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--text-dark);
            letter-spacing: -0.3px;
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .spot-badge {
            background-color: #dbeafe;
            color: #1e40af;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 8px 16px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .btn-pesanan-toggle {
            background-color: #27374d;
            color: #ffffff;
            border: none;
            border-radius: 30px;
            padding: 8px 18px;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(39, 55, 77, 0.2);
            cursor: pointer;
        }

        .btn-pesanan-toggle:hover {
            background-color: #1e293b;
            transform: translateY(-1px);
        }

        .badge-count-yellow {
            background-color: #f59e0b;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.78rem;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Main Content Layout with Offcanvas / Side Panel */
        .content-area {
            padding: 0 28px 40px 28px;
            transition: margin-right 0.3s ease;
        }

        /* Category Filter Tabs */
        .category-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            overflow-x: auto;
            padding-bottom: 6px;
            scrollbar-width: none;
        }
        .category-container::-webkit-scrollbar {
            display: none;
        }

        .btn-category-tab {
            background: #ffffff;
            border: 2px solid #27374d;
            color: #27374d;
            border-radius: 30px;
            padding: 8px 20px;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .btn-category-tab:hover, .btn-category-tab.active {
            background: #27374d;
            color: #ffffff;
        }

        .section-category-title {
            font-weight: 800;
            font-size: 1.25rem;
            color: #1e293b;
            margin-bottom: 18px;
        }

        /* ================= TICKET PASS CARD DESIGN ================= */
        .ticket-card {
            background-color: #ffffff;
            border-radius: 22px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            display: flex;
            overflow: hidden;
            position: relative;
            min-height: 195px;
            border: 1px solid rgba(0,0,0,0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .ticket-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09);
        }

        /* Left side: Photo Gelang / Illustration */
        .ticket-left {
            width: 145px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #cbeafe 0%, #bae6fd 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 2px dashed #cbd5e1;
        }

        /* Foto gelang jika ada */
        .ticket-wristband-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Gelang tiket visual default saat gambar tidak ada */
        .ticket-default-wristband {
            width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wristband-sun {
            position: absolute;
            top: -15px;
            right: -15px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background-color: #facc15;
        }

        .wristband-tag {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
            padding: 5px 12px;
            font-size: 0.78rem;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: 1.5px;
            border: 1.5px dashed #93c5fd;
            transform: rotate(-10deg);
            z-index: 2;
        }

        /* Potongan Bulat (Punch Hole Notches) Atas dan Bawah */
        .ticket-notch-top {
            position: absolute;
            top: -11px;
            left: 134px; /* pas di garis putus-putus */
            width: 22px;
            height: 22px;
            background-color: var(--bg-canvas);
            border-radius: 50%;
            z-index: 5;
            box-shadow: inset 0 -1px 2px rgba(0,0,0,0.06);
        }

        .ticket-notch-bottom {
            position: absolute;
            bottom: -11px;
            left: 134px; /* pas di garis putus-putus */
            width: 22px;
            height: 22px;
            background-color: var(--bg-canvas);
            border-radius: 50%;
            z-index: 5;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.06);
        }

        /* Right side: Detail, Harga, dan Stepper Qty */
        .ticket-right {
            flex-grow: 1;
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .ticket-badge-tersedia {
            background-color: #dcfce7;
            color: #166534;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 6px;
        }

        .ticket-title {
            font-weight: 800;
            font-size: 1.05rem;
            color: #1e293b;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .ticket-price {
            font-weight: 800;
            font-size: 1.3rem;
            color: #c2410c; /* Warna oranye tua kontras */
            margin-bottom: 12px;
        }

        /* Stepper Qty Button Box */
        .ticket-stepper-box {
            background-color: #f1f5f9;
            border-radius: 12px;
            padding: 3px;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            max-width: 175px;
            border: 1px solid #e2e8f0;
        }

        .btn-stepper {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            font-weight: 800;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .btn-stepper-minus {
            background-color: #ffffff;
            color: #475569;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .btn-stepper-plus {
            background-color: #1e3a8a;
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(30, 58, 138, 0.25);
        }

        .btn-stepper-plus:hover {
            background-color: #172554;
        }

        .stepper-val {
            font-weight: 700;
            font-size: 0.95rem;
            color: #1e293b;
            min-width: 38px;
            text-align: center;
        }

        /* ================= SIDEBAR KERANJANG (OFFCANVAS / PANEL) ================= */
        .cart-sidebar-panel {
            position: fixed;
            top: 0;
            right: 0;
            width: 360px;
            max-width: 100vw;
            height: 100vh;
            background: #ffffff;
            box-shadow: -6px 0 24px rgba(0, 0, 0, 0.12);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .cart-sidebar-panel.open {
            transform: translateX(0);
        }

        .cart-panel-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(2px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease;
        }

        .cart-panel-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .cart-panel-header {
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
        }

        .cart-panel-title {
            font-weight: 800;
            font-size: 1.15rem;
            color: #1e293b;
            margin: 0;
        }

        .btn-close-panel {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            transition: color 0.15s ease;
        }

        .btn-close-panel:hover {
            color: #0f172a;
        }

        .cart-panel-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px 24px;
        }

        /* Order item pill card */
        .order-item-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 16px;
            margin-bottom: 12px;
            position: relative;
        }

        .order-item-card .item-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .order-item-card .item-qty-price {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
        }

        .btn-item-delete {
            position: absolute;
            top: 10px;
            right: 12px;
            background: none;
            border: none;
            color: #ef4444;
            font-size: 0.85rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.15s ease;
        }

        .btn-item-delete:hover {
            opacity: 1;
        }

        .cart-panel-footer {
            padding: 20px 24px;
            border-top: 1px solid #f1f5f9;
            background-color: #ffffff;
        }

        .total-display-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 18px;
        }

        .total-display-label {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 600;
        }

        .total-display-val {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .btn-pesan-sekarang {
            background-color: #16a34a;
            color: #ffffff;
            border: none;
            border-radius: 14px;
            width: 100%;
            padding: 14px;
            font-size: 1.05rem;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(22, 163, 74, 0.25);
        }

        .btn-pesan-sekarang:hover {
            background-color: #15803d;
            transform: translateY(-1px);
        }

        /* Responsive on large screen: Option to keep panel sticky */
        @media (min-width: 1200px) {
            .with-sidebar-desktop .content-area {
                margin-right: 360px;
            }
            .with-sidebar-desktop .cart-sidebar-panel {
                transform: translateX(0);
                box-shadow: -4px 0 16px rgba(0,0,0,0.04);
            }
            .with-sidebar-desktop .cart-panel-overlay {
                display: none;
            }
        }

        /* ================= MOBILE RESPONSIVE TWEAKS (<= 768px & <= 576px) ================= */
        @media (max-width: 768px) {
            .katalog-header {
                padding: 12px 16px;
                gap: 10px;
                flex-wrap: wrap;
            }

            .brand-title {
                font-size: 1.15rem;
            }

            .brand-shapes {
                gap: 3px;
            }

            .shape-triangle {
                border-left-width: 7px;
                border-right-width: 7px;
                border-bottom-width: 13px;
            }

            .shape-circle {
                width: 13px;
                height: 13px;
            }

            .shape-square {
                width: 12px;
                height: 12px;
            }

            .header-actions {
                gap: 8px;
            }

            .spot-badge {
                font-size: 0.78rem;
                padding: 6px 12px;
            }

            .btn-pesanan-toggle {
                font-size: 0.82rem;
                padding: 6px 14px;
            }

            .content-area {
                padding: 0 16px 80px 16px; /* Extra bottom padding for mobile */
            }

            .category-container {
                margin-bottom: 16px;
                gap: 8px;
            }

            .btn-category-tab {
                font-size: 0.82rem;
                padding: 6px 15px;
            }

            .section-category-title {
                font-size: 1.1rem;
                margin-bottom: 12px;
            }

            /* Ticket pass card mobile refinement */
            .ticket-card {
                border-radius: 18px;
                min-height: 160px;
            }

            .ticket-left {
                width: 110px;
            }

            .ticket-notch-top {
                left: 100px;
                width: 20px;
                height: 20px;
                top: -10px;
            }

            .ticket-notch-bottom {
                left: 100px;
                width: 20px;
                height: 20px;
                bottom: -10px;
            }

            .wristband-sun {
                width: 65px;
                height: 65px;
            }

            .wristband-tag {
                font-size: 0.68rem;
                padding: 4px 8px;
                letter-spacing: 1px;
            }

            .ticket-right {
                padding: 12px 14px;
            }

            .ticket-title {
                font-size: 0.95rem;
                margin-bottom: 2px;
            }

            .ticket-price {
                font-size: 1.15rem;
                margin-bottom: 8px;
            }

            .ticket-stepper-box {
                max-width: 145px;
                padding: 2px;
            }

            .btn-stepper {
                width: 30px;
                height: 30px;
                font-size: 0.85rem;
            }

            .stepper-val {
                min-width: 30px;
                font-size: 0.85rem;
            }

            /* Cart panel on mobile as a bottom sheet / full slide drawer */
            .cart-sidebar-panel {
                width: 100%;
                border-radius: 20px 20px 0 0;
                max-height: 90vh;
                top: auto;
                bottom: 0;
                transform: translateY(100%);
            }

            .cart-sidebar-panel.open {
                transform: translateY(0);
            }

            .cart-panel-header {
                padding: 16px 20px;
            }

            .cart-panel-body {
                padding: 16px 20px;
            }

            /* Floating Mobile Cart Bar (Hijau Capsule) */
            .mobile-cart-bar {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .mobile-cart-bar {
                display: flex !important;
                position: fixed;
                bottom: 20px;
                left: 16px;
                right: 16px;
                background-color: #16a34a;
                color: #ffffff;
                border-radius: 18px;
                padding: 14px 20px;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 8px 24px rgba(22, 163, 74, 0.35);
                z-index: 1030;
                cursor: pointer;
                text-decoration: none;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .mobile-cart-bar:active {
                transform: scale(0.98);
            }

            .mobile-cart-bar-left {
                display: flex;
                flex-direction: column;
                line-height: 1.25;
            }

            .mobile-cart-bar-items {
                font-weight: 800;
                font-size: 1.05rem;
                letter-spacing: -0.2px;
            }

            .mobile-cart-bar-right {
                display: flex;
                align-items: center;
                gap: 6px;
                font-weight: 800;
                font-size: 1.05rem;
                text-align: right;
            }

            .mobile-cart-bar-arrow {
                font-size: 0.9rem;
            }
        }

        @media (min-width: 769px) {
            .mobile-cart-bar {
                display: none !important;
            }
        }

        @media (max-width: 420px) {
            .ticket-left {
                width: 95px;
            }

            .ticket-notch-top, .ticket-notch-bottom {
                left: 85px;
            }

            .ticket-title {
                font-size: 0.88rem;
            }

            .ticket-price {
                font-size: 1.05rem;
            }
        }

        .payment-method-card {
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 9px 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .payment-method-card:hover {
            border-color: #cbd5e1;
            background-color: #f8fafc;
        }
        .payment-method-card:has(input:checked) {
            border-color: #5B6D92 !important;
            background-color: #f0f4f9;
            box-shadow: 0 2px 8px rgba(91, 109, 146, 0.12);
        }
        .payment-radio-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
            background: #ffffff;
        }
        .payment-method-card:has(input:checked) .payment-radio-indicator {
            border-color: #5B6D92;
            background-color: #5B6D92;
        }
        .payment-radio-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #ffffff;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.2s ease;
        }
        .payment-method-card:has(input:checked) .payment-radio-dot {
            opacity: 1;
            transform: scale(1);
        }
    </style>
</head>
<body class="{{ !empty($items) ? 'with-sidebar-desktop' : '' }}">

    <!-- TOP HEADER -->
    <header class="katalog-header">
        <a href="{{ route('katalog') }}" class="brand-logo-area">
            <div class="brand-shapes">
                <div class="shape-triangle"></div>
                <div class="shape-circle"></div>
                <div class="shape-square"></div>
            </div>
            <h1 class="brand-title">Asa Jaya Playground</h1>
        </a>

        <div class="header-actions">
            <span class="spot-badge">
                <i class="fa-solid fa-location-dot"></i> Meja/Spot: {{ $nomor_meja ?? $meja_id }}
            </span>

            <button type="button" class="btn-pesanan-toggle" onclick="toggleCartPanel()">
                <i class="fa-solid fa-cart-shopping"></i> Pesanan
                @if(!empty($items))
                    <span class="badge-count-yellow">{{ count($items) }}</span>
                @endif
            </button>
        </div>
    </header>

    <!-- ALERT NOTIFIKASI -->
    <div class="px-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible py-2.5 small rounded-4 mb-3 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-1.5"></i> {{ session('success') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible py-2.5 small rounded-4 mb-3 border-0 shadow-sm" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-1.5"></i> {{ session('error') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- MAIN CONTENT AREA -->
    <main class="content-area">
        <!-- Horizontal Category Filter -->
        <div class="category-container">
            @foreach($kategori_produk as $kat)
                @if($kat->produk->where('is_active', true)->count() > 0)
                    <a href="#kat-{{ $kat->id }}" class="btn-category-tab">
                        {{ $kat->nama_kategori }}
                    </a>
                @endif
            @endforeach
        </div>

        <!-- Daftar Tiket / Produk Karcis -->
        @foreach($kategori_produk as $kat)
            @if($kat->produk->where('is_active', true)->count() > 0)
                <div class="mb-5" id="kat-{{ $kat->id }}">
                    <h2 class="section-category-title">{{ $kat->nama_kategori }}</h2>
                    <div class="row g-4">
                        @foreach($kat->produk->where('is_active', true) as $p)
                            @php
                                $itemInCart = collect($items)->firstWhere('produk_id', $p->id);
                                $qtyInCart = $itemInCart ? $itemInCart['jumlah'] : 0;
                            @endphp
                            <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                                <div class="ticket-card">
                                    <!-- Potongan Bulat Atas & Bawah -->
                                    <div class="ticket-notch-top"></div>
                                    <div class="ticket-notch-bottom"></div>

                                    <!-- Bagian Kiri: Foto Gelang / Tiket -->
                                    <div class="ticket-left">
                                        @if($p->foto_produk)
                                            <img src="{{ asset('storage/'.$p->foto_produk) }}" class="ticket-wristband-img" alt="{{ $p->nama_produk }}">
                                        @else
                                            <div class="ticket-default-wristband">
                                                <div class="wristband-sun"></div>
                                                <div class="wristband-tag">00001</div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Bagian Kanan: Info & Stepper -->
                                    <div class="ticket-right">
                                        <div>
                                            <span class="ticket-badge-tersedia">Tersedia</span>
                                            <h3 class="ticket-title">{{ $p->nama_produk }}</h3>
                                            <div class="ticket-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                                        </div>

                                        <div>
                                            <!-- Form Tambah/Update Keranjang dengan Stepper Sinkron -->
                                            <form action="{{ route('keranjang.update', $p->id) }}" method="POST" id="form-qty-{{ $p->id }}" class="d-flex align-items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="jumlah" id="input-qty-{{ $p->id }}" value="{{ $qtyInCart }}">

                                                <div class="ticket-stepper-box">
                                                    <button type="button" class="btn-stepper btn-stepper-minus" onclick="ubahJumlah({{ $p->id }}, -1)">
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <span class="stepper-val" id="display-qty-{{ $p->id }}">{{ $qtyInCart }}</span>
                                                    <button type="button" class="btn-stepper btn-stepper-plus" onclick="ubahJumlah({{ $p->id }}, 1)">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </main>

    <!-- FLOATING BOTTOM BAR KHUSUS MOBILE (HIJAU CAPSULE) -->
    @if(!empty($items))
        @php
            $totalQtyItems = collect($items)->sum('jumlah');
        @endphp
        <div class="mobile-cart-bar" onclick="toggleCartPanel()">
            <div class="mobile-cart-bar-left">
                <span class="mobile-cart-bar-items">{{ $totalQtyItems }} item · Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div class="mobile-cart-bar-right">
                <span>Lihat pesanan</span>
                <i class="fa-solid fa-chevron-right mobile-cart-bar-arrow"></i>
            </div>
        </div>
    @endif

    <!-- OVERLAY UNTUK PANEL SIDEBAR -->
    <div class="cart-panel-overlay" id="cartOverlay" onclick="closeCartPanel()"></div>

    <!-- SIDEBAR PANEL KERANJANG (PESANAN ANDA) -->
    <aside class="cart-sidebar-panel" id="cartSidebar">
        <div class="cart-panel-header">
            <h3 class="cart-panel-title">Pesanan Anda</h3>
            <button type="button" class="btn-close-panel" onclick="closeCartPanel()" aria-label="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="cart-panel-body">
            @if(empty($items))
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-cart-shopping fs-1 opacity-25 mb-3"></i>
                    <h6 class="fw-bold text-dark">Pesanan masih kosong</h6>
                    <p class="small text-muted">Pilih karcis tiket wahana untuk menambahkan ke pesanan.</p>
                </div>
            @else
                @foreach($items as $item)
                    <div class="order-item-card">
                        <form action="{{ route('keranjang.hapus', $item['produk_id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-item-delete" title="Hapus Item">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                        <div class="item-name">{{ $item['nama_produk'] }}</div>
                        <div class="item-qty-price">{{ $item['jumlah'] }}x · Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>
                    </div>
                @endforeach
            @endif
        </div>

        @if(!empty($items))
            <div class="cart-panel-footer">
                <div class="total-display-row">
                    <span class="total-display-label">Total</span>
                    <span class="total-display-val">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="nama_pemesan" class="form-control rounded-3 py-2" placeholder="Nama Pemesan (Wajib)" required style="border-color: #cbd5e1;">
                    </div>
                    
                    @if(\App\Services\MidtransService::isConfigured())
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted mb-1.5" style="font-size: 0.78rem; letter-spacing: 0.3px;">METODE PEMBAYARAN</label>
                            <div class="d-flex flex-column gap-2">
                                <!-- Opsi Bayar Non-Tunai -->
                                <label class="payment-method-card" for="pay_nontunai_katalog">
                                    <input type="radio" name="metode_pembayaran" id="pay_nontunai_katalog" value="payment_gateway" class="d-none" checked onchange="toggleKatalogChannelOptions()">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center shadow-2xs" style="width: 36px; height: 36px; background-color: #e0e7ff; color: #3730a3; flex-shrink: 0;">
                                            <i class="fa-solid fa-credit-card fs-6"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-1.5">
                                                <span class="fw-bold text-dark small" style="font-size: 0.86rem;">Bayar Non-Tunai</span>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 0.65rem; padding: 2px 6px;">Midtrans Snap</span>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.72rem; line-height: 1.2;">Bank & E-Wallet (Otomatis)</div>
                                        </div>
                                    </div>
                                    <div class="payment-radio-indicator">
                                        <div class="payment-radio-dot"></div>
                                    </div>
                                </label>

                                <!-- Pilihan Channel Non-Tunai (BCA, BNI, BRI, GoPay, ShopeePay) -->
                                <div id="katalogChannelContainer" class="p-2.5 bg-light rounded-3 border">
                                    <label class="fw-bold text-muted small d-block mb-1.5" style="font-size: 0.75rem;">PILIH METODE PEMBAYARAN:</label>
                                    
                                    <!-- Transfer Bank -->
                                    <div class="text-muted small fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">TRANSFER BANK</div>
                                    <div class="d-flex flex-column gap-1.5 mb-2">
                                        <label class="payment-method-card bg-white" for="katalog_channel_bca" style="padding: 7px 10px;">
                                            <input type="radio" name="channel" id="katalog_channel_bca" value="bca_va" class="d-none" checked>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-primary px-2 py-0.5 fw-bold text-white" style="font-size: 0.7rem;">BCA</span>
                                                <span class="fw-bold text-dark small" style="font-size: 0.82rem;">Transfer BCA</span>
                                            </div>
                                            <div class="payment-radio-indicator" style="width: 16px; height: 16px;">
                                                <div class="payment-radio-dot" style="width: 6px; height: 6px;"></div>
                                            </div>
                                        </label>

                                        <label class="payment-method-card bg-white" for="katalog_channel_bni" style="padding: 7px 10px;">
                                            <input type="radio" name="channel" id="katalog_channel_bni" value="bni_va" class="d-none">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-warning px-2 py-0.5 fw-bold text-dark" style="font-size: 0.7rem;">BNI</span>
                                                <span class="fw-bold text-dark small" style="font-size: 0.82rem;">Transfer BNI</span>
                                            </div>
                                            <div class="payment-radio-indicator" style="width: 16px; height: 16px;">
                                                <div class="payment-radio-dot" style="width: 6px; height: 6px;"></div>
                                            </div>
                                        </label>

                                        <label class="payment-method-card bg-white" for="katalog_channel_bri" style="padding: 7px 10px;">
                                            <input type="radio" name="channel" id="katalog_channel_bri" value="bri_va" class="d-none">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-info px-2 py-0.5 fw-bold text-white" style="font-size: 0.7rem;">BRI</span>
                                                <span class="fw-bold text-dark small" style="font-size: 0.82rem;">Transfer BRI</span>
                                            </div>
                                            <div class="payment-radio-indicator" style="width: 16px; height: 16px;">
                                                <div class="payment-radio-dot" style="width: 6px; height: 6px;"></div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- E-Wallet -->
                                    <div class="text-muted small fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">E-WALLET</div>
                                    <div class="d-flex flex-column gap-1.5">
                                        <label class="payment-method-card bg-white" for="katalog_channel_gopay" style="padding: 7px 10px;">
                                            <input type="radio" name="channel" id="katalog_channel_gopay" value="gopay" class="d-none">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-success px-2 py-0.5 fw-bold text-white" style="font-size: 0.7rem;">GoPay</span>
                                                <span class="fw-bold text-dark small" style="font-size: 0.82rem;">GoPay</span>
                                            </div>
                                            <div class="payment-radio-indicator" style="width: 16px; height: 16px;">
                                                <div class="payment-radio-dot" style="width: 6px; height: 6px;"></div>
                                            </div>
                                        </label>

                                        <label class="payment-method-card bg-white" for="katalog_channel_shopeepay" style="padding: 7px 10px;">
                                            <input type="radio" name="channel" id="katalog_channel_shopeepay" value="shopeepay" class="d-none">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge px-2 py-0.5 fw-bold text-white" style="background-color: #ee4d2d; font-size: 0.7rem;">ShopeePay</span>
                                                <span class="fw-bold text-dark small" style="font-size: 0.82rem;">ShopeePay</span>
                                            </div>
                                            <div class="payment-radio-indicator" style="width: 16px; height: 16px;">
                                                <div class="payment-radio-dot" style="width: 6px; height: 6px;"></div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Opsi Tunai -->
                                <label class="payment-method-card" for="pay_tunai_katalog">
                                    <input type="radio" name="metode_pembayaran" id="pay_tunai_katalog" value="tunai" class="d-none" onchange="toggleKatalogChannelOptions()">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center shadow-2xs" style="width: 36px; height: 36px; background-color: #dcfce7; color: #166534; flex-shrink: 0;">
                                            <i class="fa-solid fa-money-bill-wave fs-6"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-1.5">
                                                <span class="fw-bold text-dark small" style="font-size: 0.86rem;">Tunai</span>
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill" style="font-size: 0.65rem; padding: 2px 6px;">Di Kasir</span>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.72rem; line-height: 1.2;">Bayar langsung di kasir</div>
                                        </div>
                                    </div>
                                    <div class="payment-radio-indicator">
                                        <div class="payment-radio-dot"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="metode_pembayaran" value="tunai">
                        <div class="p-2 mb-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                            <span class="small fw-bold text-dark"><i class="fa-solid fa-money-bill-wave text-success me-1"></i> Pembayaran Tunai</span>
                            <span class="badge bg-secondary-subtle text-secondary">Bayar di Kasir</span>
                        </div>
                    @endif

                    <button type="submit" class="btn-pesan-sekarang">
                        Pesan sekarang
                    </button>
                </form>
            </div>
        @endif
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleCartPanel() {
            const panel = document.getElementById('cartSidebar');
            const overlay = document.getElementById('cartOverlay');
            if (panel.classList.contains('open')) {
                closeCartPanel();
            } else {
                panel.classList.add('open');
                overlay.classList.add('show');
            }
        }

        function closeCartPanel() {
            const panel = document.getElementById('cartSidebar');
            const overlay = document.getElementById('cartOverlay');
            panel.classList.remove('open');
            overlay.classList.remove('show');
            document.body.classList.remove('with-sidebar-desktop');
        }

        function ubahJumlah(id, delta) {
            const form = document.getElementById('form-qty-' + id);
            const input = document.getElementById('input-qty-' + id);
            const buttons = form.querySelectorAll('button');
            buttons.forEach(btn => btn.disabled = true);
            let val = parseInt(input.value, 10) || 0;
            let newVal = val + delta;
            if (newVal < 0) newVal = 0;
            input.value = newVal;
            form.submit();
        }

        function toggleKatalogChannelOptions() {
            const nonTunaiRadio = document.getElementById('pay_nontunai_katalog');
            const channelContainer = document.getElementById('katalogChannelContainer');
            if (nonTunaiRadio && channelContainer) {
                const isNonTunai = nonTunaiRadio.checked;
                channelContainer.style.display = isNonTunai ? 'block' : 'none';
                const channelInputs = channelContainer.querySelectorAll('input[name="channel"]');
                channelInputs.forEach(input => {
                    input.disabled = !isNonTunai;
                });
            }
        }
        document.addEventListener('DOMContentLoaded', toggleKatalogChannelOptions);
    </script>

</body>
</html>