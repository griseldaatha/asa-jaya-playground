@extends('layouts.admin')

@section('content')
<div class="pos-page">
    <style>
        .product-card {
            border: 1px solid #e3e8ef;
            border-radius: 14px;
            background: #ffffff;
            transition: all 0.2s ease;
            cursor: pointer;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-color: #5B6D92;
        }

        .product-img-box {
            height: 120px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid #f1f5f9;
        }

        .product-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-sticky-panel {
            position: sticky;
            top: 20px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
        }

        .cart-items-scroll {
            max-height: 280px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .cart-items-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .cart-items-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .category-pill {
            cursor: pointer;
            border-radius: 30px;
            padding: 6px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .category-pill.active, .category-pill:hover {
            background: #5B6D92;
            color: #ffffff;
            border-color: #5B6D92;
        }

        .btn-qty {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 12px;
        }
    </style>



    <div class="row g-4">
        <!-- Kolom Kiri: Katalog & Pencarian Produk -->
        <div class="col-lg-7 col-xl-8">
            <!-- Search & Filter Bar -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body p-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <div class="position-relative">
                                <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" id="searchProdukInput" class="form-control ps-5 shadow-sm" placeholder="Cari menu atau tiket..." onkeyup="filterProdukKatalog()">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 overflow-x-auto pb-1" id="categoryFilterContainer">
                                <button type="button" class="category-pill active" onclick="pilihKategori(this, 'all')">
                                    Semua
                                </button>
                                @foreach($kategori_produk as $kat)
                                    @if($kat->produk->count() > 0)
                                        <button type="button" class="category-pill" onclick="pilihKategori(this, 'kat-{{ $kat->id }}')">
                                            {{ $kat->nama_kategori }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid Produk -->
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3" id="katalogGrid">
                @php $hasProduk = false; @endphp
                @foreach($kategori_produk as $kat)
                    @foreach($kat->produk as $p)
                        @php $hasProduk = true; @endphp
                        <div class="col item-produk-col" 
                             data-kategori="kat-{{ $kat->id }}" 
                             data-nama="{{ strtolower($p->nama_produk) }}"
                             data-id="{{ $p->id }}"
                             data-stok="{{ $p->stok }}"
                             data-harga="{{ $p->harga }}">
                            <div class="product-card h-100 shadow-sm" onclick="tambahKeKeranjang({{ $p->id }}, '{{ addslashes($p->nama_produk) }}', {{ $p->harga }}, {{ $p->stok }})">
                                <div class="product-img-box position-relative">
                                    @if($p->foto_produk && file_exists(public_path('storage/'.$p->foto_produk)))
                                        <img src="{{ asset('storage/'.$p->foto_produk) }}" alt="{{ $p->nama_produk }}">
                                    @elseif($p->foto && file_exists(public_path('storage/'.$p->foto)))
                                        <img src="{{ asset('storage/'.$p->foto) }}" alt="{{ $p->nama_produk }}">
                                    @else
                                        <i class="fa-solid fa-shapes text-secondary fs-1 opacity-50"></i>
                                    @endif
                                    <span class="position-absolute top-0 end-0 m-2 badge {{ $p->stok <= 5 ? 'bg-danger' : 'bg-dark-subtle text-dark' }} border" style="font-size: 10px;">
                                        Sisa: {{ $p->stok }}
                                    </span>
                                </div>
                                <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 11px;">{{ $kat->nama_kategori }}</small>
                                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $p->nama_produk }}" style="font-size: 0.95rem;">
                                            {{ $p->nama_produk }}
                                        </h6>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                        <span class="fw-bold text-primary">
                                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                                        </span>
                                        <span class="btn btn-sm btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 26px; height: 26px;">
                                            <i class="fa-solid fa-plus" style="font-size: 11px;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach

                @if(!$hasProduk)
                    <div class="col-12 py-5 text-center text-muted">
                        <i class="fa-solid fa-box-open fs-1 mb-2 opacity-50"></i>
                        <h6 class="fw-bold">Tidak ada produk aktif dengan stok tersedia.</h6>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Panel Keranjang & Pembayaran -->
        <div class="col-lg-5 col-xl-4">
            <div class="cart-sticky-panel p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-primary"></i>
                        Keranjang Pesanan
                    </h6>
                    <button type="button" class="btn btn-outline-danger btn-sm py-1 px-2.5" onclick="kosongkanKeranjang()" title="Kosongkan Keranjang">
                        <i class="fa-solid fa-trash-can me-1"></i>Reset
                    </button>
                </div>

                <!-- Alert Error dari Server (tanpa reset keranjang) -->
                <div id="cartAlertError" class="alert alert-danger py-2 px-3 small mb-3 border-0 shadow-sm" style="display: none;">
                    <i class="fa-solid fa-triangle-exclamation me-1.5"></i>
                    <span id="cartAlertErrorText"></span>
                </div>

                <!-- Input Nama Pemesan -->
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-1" style="font-size: 11px;">Nama Pemesan</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user"></i></span>
                        <input type="text" id="inputNamaPemesan" class="form-control" placeholder="Pelanggan" value="Pelanggan">
                    </div>
                </div>

                <!-- Daftar Item Keranjang (Scrollable) -->
                <div class="cart-items-scroll mb-3" id="cartItemsContainer">
                    <!-- Dinamis via JS -->
                </div>

                <!-- Ringkasan Total Tagihan -->
                <div class="p-3 rounded-3 bg-light border mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-bold small text-uppercase">Total Tagihan:</span>
                        <span class="fs-4 fw-bold text-primary mb-0" id="cartTotalText">Rp 0</span>
                    </div>
                </div>

                <!-- Pembayaran Tunai & Kembalian Live -->
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold text-uppercase mb-1" style="font-size: 11px;">Uang Diterima (Rp)</label>
                    <div class="input-group input-group-md mb-2 shadow-sm">
                        <span class="input-group-text bg-white fw-bold text-secondary">Rp</span>
                        <input type="text" id="cartUangDiterima" class="form-control fw-bold fs-5 text-dark" placeholder="0" autocomplete="off" oninput="onInputUangDiterimaCart()">
                    </div>

                    <!-- Tombol Cepat -->
                    <div class="d-flex flex-wrap gap-1 mb-2">
                        <button type="button" class="btn btn-outline-primary btn-sm flex-fill fw-bold py-1" onclick="setNominalCepatCart('pas')">
                            Uang Pas
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm flex-fill fw-bold py-1" onclick="setNominalCepatCart(20000)">
                            20.000
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm flex-fill fw-bold py-1" onclick="setNominalCepatCart(50000)">
                            50.000
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm flex-fill fw-bold py-1" onclick="setNominalCepatCart(100000)">
                            100.000
                        </button>
                    </div>

                    <!-- Status Kembalian Live -->
                    <div id="cartBoxKembalian" class="alert alert-secondary py-2 px-3 mb-0 rounded-3 border d-flex align-items-center">
                        <i id="cartKembalianIcon" class="fa-solid fa-circle-info fs-5 me-2 text-secondary"></i>
                        <div>
                            <div class="small fw-semibold" id="cartKembalianLabel">Status Pembayaran</div>
                            <div class="fw-bold" id="cartKembalianValue">Masukkan nominal</div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Proses Pesanan -->
                <div class="d-grid mt-auto">
                    <button type="button" id="btnSubmitPesanan" class="btn btn-success btn-lg fw-bold shadow-sm py-2.5" disabled onclick="submitPesananLangsung()">
                        <i class="fa-solid fa-check-circle me-1.5"></i> Proses & Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sukses Transaksi Kasir -->
    <div class="modal fade" id="modalSuksesPesanan" tabindex="-1" aria-labelledby="modalSuksesTitle" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body p-4 text-center">
                    <div class="mx-auto mb-3 text-success bg-success-subtle rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 76px; height: 76px;">
                        <i class="fa-solid fa-circle-check fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Pesanan Berhasil Disimpan!</h4>
                    <p class="text-muted small mb-4">Transaksi langsung telah dicatat dan masuk ke antrean aktif.</p>

                    <div class="p-3 bg-light rounded-4 border mb-4 text-start shadow-sm">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Kode Transaksi:</span>
                            <span class="fw-bold text-dark" id="suksesKasirKodeTrx">-</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Total Tagihan:</span>
                            <span class="fw-bold text-dark" id="suksesKasirTotalTagihan">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>Uang Diterima:</span>
                            <span class="fw-bold text-dark" id="suksesKasirUangDiterima">Rp 0</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark fs-6">Kembalian:</span>
                            <span class="fs-2 fw-bold text-success" id="suksesKasirKembalianText">Rp 0</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a id="btnCetakStrukKasir" href="#" target="_blank" class="btn btn-primary btn-lg fw-bold shadow-sm">
                            <i class="fa-solid fa-print me-2"></i> Cetak Struk
                        </a>
                        <button type="button" class="btn btn-outline-secondary py-2" data-bs-dismiss="modal" onclick="resetPesananBaru()">
                            <i class="fa-solid fa-plus me-1"></i> Pesanan Baru
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

    // State Keranjang di Klien
    let keranjang = {}; // format: { [produk_id]: { id, nama, harga, stok, jumlah, catatan } }
    let modalSuksesInstance = null;
    let selectedKategori = 'all';

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('modalSuksesPesanan');
        if (modalEl) {
            modalSuksesInstance = new bootstrap.Modal(modalEl);
        }
        renderKeranjang();
    });

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID').format(Math.max(0, num || 0));
    }

    function parseNominal(str) {
        if (!str) return 0;
        const cleaned = str.toString().replace(/[^0-9]/g, '');
        return cleaned ? parseInt(cleaned, 10) : 0;
    }

    // Filter Produk Berdasarkan Kategori & Search
    function pilihKategori(btn, kategori) {
        selectedKategori = kategori;
        document.querySelectorAll('.category-pill').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
        filterProdukKatalog();
    }

    function filterProdukKatalog() {
        const query = document.getElementById('searchProdukInput').value.toLowerCase().trim();
        const items = document.querySelectorAll('.item-produk-col');

        items.forEach(item => {
            const kat = item.getAttribute('data-kategori');
            const nama = item.getAttribute('data-nama');

            const matchKategori = (selectedKategori === 'all' || kat === selectedKategori);
            const matchSearch = (!query || nama.includes(query));

            if (matchKategori && matchSearch) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Kelola Keranjang
    function tambahKeKeranjang(id, nama, harga, stok) {
        sembunyikanError();
        if (keranjang[id]) {
            if (keranjang[id].jumlah + 1 > stok) {
                tampilkanError(`Stok produk "${nama}" tidak mencukupi (tersisa ${stok}).`);
                return;
            }
            keranjang[id].jumlah += 1;
        } else {
            if (stok < 1) {
                tampilkanError(`Stok produk "${nama}" habis.`);
                return;
            }
            keranjang[id] = {
                id: id,
                nama: nama,
                harga: harga,
                stok: stok,
                jumlah: 1,
                catatan: ''
            };
        }
        renderKeranjang();
    }

    function ubahJumlah(id, delta) {
        sembunyikanError();
        if (!keranjang[id]) return;
        
        const newQty = keranjang[id].jumlah + delta;
        if (newQty <= 0) {
            delete keranjang[id];
        } else if (newQty > keranjang[id].stok) {
            tampilkanError(`Stok produk "${keranjang[id].nama}" tidak mencukupi (tersisa ${keranjang[id].stok}).`);
            return;
        } else {
            keranjang[id].jumlah = newQty;
        }
        renderKeranjang();
    }

    function hapusItem(id) {
        sembunyikanError();
        if (keranjang[id]) {
            delete keranjang[id];
        }
        renderKeranjang();
    }

    function kosongkanKeranjang() {
        sembunyikanError();
        keranjang = {};
        document.getElementById('inputNamaPemesan').value = 'Pelanggan';
        document.getElementById('cartUangDiterima').value = '';
        renderKeranjang();
    }

    function hitungTotalKeranjang() {
        let total = 0;
        for (const id in keranjang) {
            total += keranjang[id].harga * keranjang[id].jumlah;
        }
        return total;
    }

    function renderKeranjang() {
        const container = document.getElementById('cartItemsContainer');
        const items = Object.values(keranjang);
        const total = hitungTotalKeranjang();

        document.getElementById('cartTotalText').textContent = 'Rp ' + formatRupiah(total);

        if (items.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="fa-solid fa-basket-shopping fs-2 mb-2 opacity-50"></i>
                    <p class="small mb-0">Keranjang masih kosong.<br>Klik produk di sebelah kiri untuk menambah.</p>
                </div>
            `;
        } else {
            container.innerHTML = items.map(item => `
                <div class="p-2 mb-2 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div class="text-dark fw-bold small text-truncate pe-2" style="max-width: 170px;" title="${item.nama}">
                            ${item.nama}
                        </div>
                        <button type="button" class="btn btn-link text-danger p-0 border-0" onclick="hapusItem(${item.id})" title="Hapus">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-qty" onclick="ubahJumlah(${item.id}, -1)">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <span class="px-2 fw-bold small">${item.jumlah}</span>
                            <button type="button" class="btn btn-outline-secondary btn-qty" onclick="ubahJumlah(${item.id}, 1)">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-dark small">Rp ${formatRupiah(item.harga * item.jumlah)}</div>
                            <small class="text-muted" style="font-size: 10px;">@ Rp ${formatRupiah(item.harga)}</small>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Perbarui validasi uang kembalian
        const uangDiterima = parseNominal(document.getElementById('cartUangDiterima').value);
        updateKembalianCartUI(uangDiterima, total);
    }

    // Uang Diterima & Tombol Cepat di Cart
    function onInputUangDiterimaCart() {
        const input = document.getElementById('cartUangDiterima');
        const nominal = parseNominal(input.value);
        if (nominal > 0) {
            input.value = formatRupiah(nominal);
        } else {
            input.value = '';
        }
        updateKembalianCartUI(nominal, hitungTotalKeranjang());
    }

    function setNominalCepatCart(val) {
        const total = hitungTotalKeranjang();
        let nominal = 0;
        if (val === 'pas') {
            nominal = total;
        } else {
            nominal = parseInt(val, 10);
        }
        const input = document.getElementById('cartUangDiterima');
        input.value = formatRupiah(nominal);
        updateKembalianCartUI(nominal, total);
    }

    function updateKembalianCartUI(uangDiterima, total) {
        const box = document.getElementById('cartBoxKembalian');
        const icon = document.getElementById('cartKembalianIcon');
        const label = document.getElementById('cartKembalianLabel');
        const val = document.getElementById('cartKembalianValue');
        const btn = document.getElementById('btnSubmitPesanan');

        const hasItems = Object.keys(keranjang).length > 0;

        if (!hasItems) {
            box.className = 'alert alert-secondary py-2 px-3 mb-0 rounded-3 border d-flex align-items-center';
            icon.className = 'fa-solid fa-circle-info fs-5 me-2 text-secondary';
            label.textContent = 'Status Pembayaran';
            val.textContent = 'Pilih produk terlebih dahulu';
            btn.disabled = true;
            return;
        }

        if (!uangDiterima || uangDiterima === 0) {
            box.className = 'alert alert-secondary py-2 px-3 mb-0 rounded-3 border d-flex align-items-center';
            icon.className = 'fa-solid fa-circle-info fs-5 me-2 text-secondary';
            label.textContent = 'Status Pembayaran';
            val.textContent = 'Masukkan uang diterima';
            btn.disabled = true;
            return;
        }

        if (uangDiterima < total) {
            const kurang = total - uangDiterima;
            box.className = 'alert alert-danger py-2 px-3 mb-0 rounded-3 border d-flex align-items-center';
            icon.className = 'fa-solid fa-circle-xmark fs-5 me-2 text-danger';
            label.textContent = 'Uang Belum Cukup';
            val.textContent = 'Uang kurang Rp ' + formatRupiah(kurang);
            btn.disabled = true;
        } else {
            const kembalian = uangDiterima - total;
            box.className = 'alert alert-success py-2 px-3 mb-0 rounded-3 border d-flex align-items-center';
            icon.className = 'fa-solid fa-circle-check fs-5 me-2 text-success';
            label.textContent = 'Kembalian Pas/Lebih';
            val.textContent = 'Kembalian: Rp ' + formatRupiah(kembalian);
            btn.disabled = false;
        }
    }

    function tampilkanError(pesan) {
        const alertEl = document.getElementById('cartAlertError');
        const textEl = document.getElementById('cartAlertErrorText');
        textEl.textContent = pesan;
        alertEl.style.display = 'block';
    }

    function sembunyikanError() {
        const alertEl = document.getElementById('cartAlertError');
        alertEl.style.display = 'none';
    }

    // Submit Pesanan Langsung ke Server
    function submitPesananLangsung() {
        sembunyikanError();
        const items = Object.values(keranjang);
        if (items.length === 0) {
            tampilkanError('Keranjang belanja masih kosong.');
            return;
        }

        const total = hitungTotalKeranjang();
        const uangDiterima = parseNominal(document.getElementById('cartUangDiterima').value);

        if (uangDiterima < total) {
            tampilkanError('Uang yang diterima kurang dari total tagihan.');
            return;
        }

        const namaPemesan = document.getElementById('inputNamaPemesan').value.trim() || 'Pelanggan';

        const payload = {
            nama_pemesan: namaPemesan,
            uang_diterima: uangDiterima,
            items: items.map(it => ({
                produk_id: it.id,
                jumlah: it.jumlah,
                catatan: it.catatan || null
            }))
        };

        const btn = document.getElementById('btnSubmitPesanan');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Menyimpan...';

        fetch('/admin/pesanan-baru', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;

            if (data.success) {
                // Tampilkan Modal Sukses dengan ringkasan
                document.getElementById('suksesKasirKodeTrx').textContent = '#' + data.kode_transaksi;
                document.getElementById('suksesKasirTotalTagihan').textContent = data.total_harga_formatted || ('Rp ' + formatRupiah(data.total_harga));
                document.getElementById('suksesKasirUangDiterima').textContent = data.uang_diterima_formatted || ('Rp ' + formatRupiah(data.uang_diterima));
                document.getElementById('suksesKasirKembalianText').textContent = data.kembalian_formatted || ('Rp ' + formatRupiah(data.uang_kembalian));

                const btnStruk = document.getElementById('btnCetakStrukKasir');
                btnStruk.href = data.struk_url || `/admin/transaksi/${data.transaksi_id}/struk`;

                if (modalSuksesInstance) {
                    modalSuksesInstance.show();
                }
            } else {
                // Jangan kosongkan keranjang, tampilkan error
                tampilkanError(data.message || 'Terjadi kesalahan saat menyimpan pesanan.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            tampilkanError('Terjadi kesalahan jaringan atau server.');
        });
    }

    function resetPesananBaru() {
        kosongkanKeranjang();
        if (modalSuksesInstance) {
            modalSuksesInstance.hide();
        }
        // Refresh katalog halaman agar stok terupdate
        location.reload();
    }
</script>
@endsection
