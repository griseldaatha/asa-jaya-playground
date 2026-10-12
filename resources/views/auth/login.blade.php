<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Asa Jaya Playground</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --sky-blue: #93c5fd;
            --sky-light: #bfdbfe;
            --sun-yellow: #fde047;
            --sun-glow: #fef08a;
            --grass-light: #86efac;
            --grass-mid: #4ade80;
            --grass-dark: #22c55e;
            --brand-navy: #2d4074;
            --brand-navy-hover: #1e2c52;
            --card-border: #1e293b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            background: linear-gradient(180deg, #93c5fd 0%, #bfdbfe 35%, #e0f2fe 65%, #dcfce7 100%);
        }

        /* ================= BACKGROUND SVG ARTWORK ================= */
        .playground-bg-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }

        /* Animasi Matahari Berdenyut */
        @keyframes sunPulse {
            0%, 100% {
                transform: scale(1);
                filter: drop-shadow(0 0 16px rgba(253, 224, 71, 0.6));
            }
            50% {
                transform: scale(1.05);
                filter: drop-shadow(0 0 28px rgba(253, 224, 71, 0.9));
            }
        }

        .anim-sun {
            transform-origin: 88% 18%;
            animation: sunPulse 6s ease-in-out infinite;
        }

        /* Animasi Awan Mengapung */
        @keyframes cloudFloatLeft {
            0% { transform: translateX(0); }
            50% { transform: translateX(24px); }
            100% { transform: translateX(0); }
        }

        @keyframes cloudFloatRight {
            0% { transform: translateX(0); }
            50% { transform: translateX(-30px); }
            100% { transform: translateX(0); }
        }

        .anim-cloud-left {
            animation: cloudFloatLeft 14s ease-in-out infinite;
        }

        .anim-cloud-right {
            animation: cloudFloatRight 18s ease-in-out infinite;
        }

        /* Balon bergoyang lembut */
        @keyframes balloonSway {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(3deg); }
        }

        .anim-balloon {
            transform-origin: 12% 60%;
            animation: balloonSway 7s ease-in-out infinite;
        }

        /* Opsi Respek Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .anim-sun, .anim-cloud-left, .anim-cloud-right, .anim-balloon {
                animation: none !important;
            }
        }

        /* ================= CARD LOGIN ================= */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 18px;
        }

        .login-card-playground {
            background-color: #ffffff;
            border: 3px solid #1e293b;
            border-radius: 36px;
            padding: 38px 36px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.18), 0 6px 16px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        /* Header Logo Bentuk Geometris (Segitiga Kuning, Lingkaran Merah, Kotak Biru) */
        .brand-shapes {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .shape-triangle {
            width: 0;
            height: 0;
            border-left: 17px solid transparent;
            border-right: 17px solid transparent;
            border-bottom: 28px solid #f59e0b;
        }

        .shape-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: #ef4444;
        }

        .shape-square {
            width: 27px;
            height: 27px;
            border-radius: 8px;
            background-color: #3b82f6;
        }

        .brand-title-login {
            font-size: 1.35rem;
            font-weight: 800;
            color: #1e3a8a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .brand-subtitle-login {
            font-size: 0.95rem;
            color: #475569;
            font-weight: 600;
            margin-bottom: 24px;
        }

        /* Form Inputs */
        .form-group-custom {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-label-custom {
            display: block;
            font-weight: 700;
            font-size: 0.9rem;
            color: #334155;
            margin-bottom: 6px;
        }

        .input-group-custom {
            display: flex;
            align-items: center;
            background: #ffffff;
            border: 2px solid #27374d;
            border-radius: 14px;
            padding: 4px 14px;
            transition: all 0.2s ease;
        }

        .input-group-custom:focus-within {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15);
        }

        .input-group-custom i {
            color: #475569;
            font-size: 1rem;
            margin-right: 10px;
        }

        .input-group-custom input {
            border: none;
            outline: none;
            width: 100%;
            padding: 10px 0;
            font-size: 0.98rem;
            font-weight: 600;
            color: #1e293b;
            background: transparent;
        }

        .btn-toggle-eye {
            background: none;
            border: none;
            color: #475569;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 4px 0 4px 8px;
            display: flex;
            align-items: center;
        }

        .btn-toggle-eye:hover {
            color: #1e293b;
        }

        /* Tombol Masuk */
        .btn-masuk-custom {
            background-color: var(--brand-navy);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 1.05rem;
            font-weight: 800;
            padding: 13px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 26px;
            box-shadow: 0 4px 14px rgba(45, 64, 116, 0.3);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-masuk-custom:hover {
            background-color: var(--brand-navy-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(45, 64, 116, 0.4);
        }

        .btn-masuk-custom:active {
            transform: translateY(1px);
        }

        /* Bottom Sparkles / Ground Dots */
        .ground-dots {
            position: absolute;
            bottom: 12px;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 80px;
            pointer-events: none;
            z-index: 2;
        }

        .g-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #ffffff;
            opacity: 0.9;
        }

        /* Responsive Mobile */
        @media (max-width: 480px) {
            .login-card-playground {
                padding: 28px 20px;
                border-radius: 28px;
            }

            .brand-title-login {
                font-size: 1.15rem;
            }

            .brand-subtitle-login {
                font-size: 0.88rem;
                margin-bottom: 20px;
            }

            .btn-masuk-custom {
                padding: 11px;
                font-size: 0.98rem;
            }
        }
    </style>
</head>
<body>

    <!-- ================= ILUSTRASI PLAYGROUND SVG VECTOR ================= -->
    <svg class="playground-bg-canvas" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="skyGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#93c5fd" />
                <stop offset="45%" stop-color="#bae6fd" />
                <stop offset="70%" stop-color="#e0f2fe" />
                <stop offset="100%" stop-color="#dcfce7" />
            </linearGradient>
            
            <linearGradient id="hillBackGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#86efac" />
                <stop offset="100%" stop-color="#4ade80" />
            </linearGradient>

            <linearGradient id="hillFrontGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="#4ade80" />
                <stop offset="100%" stop-color="#16a34a" />
            </linearGradient>

            <linearGradient id="slideYellow" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#fde047" />
                <stop offset="100%" stop-color="#eab308" />
            </linearGradient>
        </defs>

        <!-- Sky Base -->
        <rect width="1440" height="900" fill="url(#skyGrad)" />

        <!-- MATAHARI BERDENYUT (Pojok Kanan Atas) -->
        <g class="anim-sun">
            <!-- Lingkaran halo luar transparan -->
            <circle cx="1260" cy="170" r="115" fill="#fef08a" opacity="0.6" />
            <!-- Lingkaran matahari utama -->
            <circle cx="1260" cy="170" r="82" fill="#facc15" />
        </g>

        <!-- AWAN-AWAN MENGAPUNG -->
        <!-- Awan Kiri -->
        <g class="anim-cloud-left" fill="#ffffff" opacity="0.95">
            <path d="M210 240 a38 38 0 0 1 54 -32 a54 54 0 0 1 82 12 a38 38 0 0 1 24 50 l-140 0 a20 20 0 0 1 -20 -30 z" />
        </g>

        <!-- Awan Kanan -->
        <g class="anim-cloud-right" fill="#ffffff" opacity="0.95">
            <path d="M1060 380 a30 30 0 0 1 42 -25 a44 44 0 0 1 66 10 a32 32 0 0 1 20 40 l-115 0 a16 16 0 0 1 -13 -25 z" />
        </g>

        <!-- TALI BUNTING / BENDERA SEGITIGA WARNA-WARNI -->
        <g id="party-bunting">
            <!-- Tali lengkung -->
            <path d="M-20 25 Q720 120 1460 25" fill="none" stroke="#64748b" stroke-width="3" stroke-linecap="round" />

            <!-- Bendera-bendera segitiga berderet -->
            <!-- 1: Kuning -->
            <polygon points="120,42 165,49 142,105" fill="#eab308" />
            <!-- 2: Merah -->
            <polygon points="255,62 300,68 277,125" fill="#ef4444" />
            <!-- 3: Biru -->
            <polygon points="395,78 440,82 417,138" fill="#3b82f6" />
            <!-- 4: Hijau -->
            <polygon points="535,89 580,91 557,148" fill="#22c55e" />
            <!-- 5: Oranye -->
            <polygon points="680,94 725,94 702,150" fill="#f97316" />
            <!-- 6: Merah Muda -->
            <polygon points="825,91 870,89 847,147" fill="#f43f5e" />
            <!-- 7: Biru Cyan -->
            <polygon points="970,81 1015,77 992,135" fill="#06b6d4" />
            <!-- 8: Hijau Muda -->
            <polygon points="1110,67 1155,61 1132,120" fill="#84cc16" />
            <!-- 9: Kuning Emas -->
            <polygon points="1250,50 1295,43 1272,105" fill="#eab308" />
        </g>

        <!-- BUKIT-BUKIT RUMPUT HIJAU -->
        <!-- Bukit Belakang -->
        <path d="M-50 680 Q320 540 720 620 T1490 560 L1490 900 L-50 900 Z" fill="url(#hillBackGrad)" />

        <!-- Bukit Depan -->
        <path d="M-50 680 Q400 620 860 670 T1500 640 L1500 900 L-50 900 Z" fill="url(#hillFrontGrad)" />

        <!-- ELEMEN PLAYGROUND SISI KIRI -->
        <!-- Balon Warna-Warni Berdiri -->
        <g class="anim-balloon">
            <!-- Tali balon -->
            <line x1="172" y1="595" x2="75" y2="340" stroke="#64748b" stroke-width="2.5" />
            <line x1="172" y1="595" x2="135" y2="400" stroke="#64748b" stroke-width="2.5" />
            <line x1="172" y1="595" x2="218" y2="365" stroke="#64748b" stroke-width="2.5" />
            
            <!-- Balon Biru -->
            <circle cx="75" cy="340" r="32" fill="#3b82f6" />
            <!-- Balon Merah/Oranye -->
            <circle cx="135" cy="400" r="34" fill="#f97316" />
            <!-- Balon Kuning -->
            <circle cx="218" cy="365" r="34" fill="#eab308" />
        </g>

        <!-- Pohon Kartun Kiri Belakang -->
        <g>
            <rect x="302" y="555" width="16" height="50" fill="#78350f" rx="3" />
            <circle cx="310" cy="520" r="42" fill="#15803d" />
            <circle cx="328" cy="540" r="28" fill="#16a34a" />
        </g>

        <!-- Ayunan Bermain Anak-Anak (Kiri Bawah) -->
        <g id="swing-set">
            <!-- Kaki Ayunan Segitiga Biru -->
            <line x1="20" y1="780" x2="55" y2="670" stroke="#3b82f6" stroke-width="12" stroke-linecap="round" />
            <line x1="90" y1="780" x2="55" y2="670" stroke="#3b82f6" stroke-width="12" stroke-linecap="round" />
            
            <line x1="225" y1="780" x2="260" y2="670" stroke="#3b82f6" stroke-width="12" stroke-linecap="round" />
            <line x1="295" y1="780" x2="260" y2="670" stroke="#3b82f6" stroke-width="12" stroke-linecap="round" />

            <!-- Palang Atas Merah -->
            <line x1="40" y1="670" x2="275" y2="670" stroke="#ef4444" stroke-width="14" stroke-linecap="round" />

            <!-- Rantai Tali Ayunan -->
            <line x1="122" y1="675" x2="122" y2="765" stroke="#64748b" stroke-width="3.5" />
            <line x1="190" y1="675" x2="190" y2="765" stroke="#64748b" stroke-width="3.5" />

            <!-- Papan Dudukan Ayunan Kuning -->
            <rect x="110" y="760" width="92" height="15" fill="#facc15" rx="5" />
        </g>

        <!-- ELEMEN PLAYGROUND SISI KANAN -->
        <!-- Pohon Kartun Kanan Belakang -->
        <g>
            <rect x="1312" y="565" width="18" height="55" fill="#78350f" rx="3" />
            <circle cx="1320" cy="530" r="44" fill="#15803d" />
            <circle cx="1340" cy="550" r="30" fill="#16a34a" />
        </g>

        <!-- Perosotan Playground (Kanan Bawah) -->
        <g id="playground-slide">
            <!-- Tangga Merah -->
            <line x1="1145" y1="695" x2="1145" y2="825" stroke="#f87171" stroke-width="9" stroke-linecap="round" />
            <line x1="1188" y1="695" x2="1188" y2="825" stroke="#f87171" stroke-width="9" stroke-linecap="round" />
            <line x1="1145" y1="730" x2="1188" y2="730" stroke="#fb923c" stroke-width="6" />
            <line x1="1145" y1="765" x2="1188" y2="765" stroke="#fb923c" stroke-width="6" />
            <line x1="1145" y1="800" x2="1188" y2="800" stroke="#fb923c" stroke-width="6" />

            <!-- Platform Atas Biru -->
            <rect x="1180" y="670" width="95" height="16" fill="#3b82f6" rx="4" />
            <!-- Tiang Penyangga Platform -->
            <line x1="1275" y1="680" x2="1275" y2="825" stroke="#f87171" stroke-width="9" stroke-linecap="round" />

            <!-- Lengkungan Seluncuran / Perosotan Kuning -->
            <path d="M1270 675 Q1350 710 1420 835" fill="none" stroke="url(#slideYellow)" stroke-width="24" stroke-linecap="round" />
        </g>

        <!-- Bola Bermain Merah di Rumput -->
        <g>
            <circle cx="1060" cy="875" r="24" fill="#ef4444" />
            <path d="M1038 870 Q1060 885 1082 870" fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
        </g>
    </svg>

    <!-- Titik Putih di Dasar Tanah Rumput -->
    <div class="ground-dots">
        <div class="g-dot"></div>
        <div class="g-dot"></div>
        <div class="g-dot"></div>
        <div class="g-dot"></div>
        <div class="g-dot"></div>
    </div>

    <!-- ================= KOTAK FORM LOGIN DI TENGAH ================= -->
    <main class="login-wrapper">
        <div class="login-card-playground text-center">
            <!-- Icon Logo Tiga Bentuk Geometris -->
            <div class="brand-shapes">
                <div class="shape-triangle"></div>
                <div class="shape-circle"></div>
                <div class="shape-square"></div>
            </div>

            <!-- Judul -->
            <h1 class="brand-title-login mb-4">ASA JAYA PLAYGROUND</h1>

            <!-- Pesan Error Validasi -->
            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 small rounded-3 text-start mb-3 border-0 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> Username atau password salah.
                </div>
            @endif

            <!-- Form Autentikasi -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Input Username -->
                <div class="form-group-custom">
                    <label for="username" class="form-label-custom">Username</label>
                    <div class="input-group-custom">
                        <i class="fa-regular fa-user"></i>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus autocomplete="username">
                    </div>
                </div>

                <!-- Input Password -->
                <div class="form-group-custom">
                    <label for="password" class="form-label-custom">Password</label>
                    <div class="input-group-custom">
                        <i class="fa-solid fa-lock" style="font-size: 0.95rem;"></i>
                        <input type="password" name="password" id="password" required autocomplete="current-password">
                        <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility()" aria-label="Lihat Password">
                            <i class="fa-regular fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Masuk -->
                <button type="submit" class="btn-masuk-custom">
                    <i class="fa-solid fa-arrow-right-to-bracket fs-5"></i>
                    <span>Masuk</span>
                </button>
            </form>
        </div>
    </main>

    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>