@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-fire"></i> Antrean Pesanan Aktif</h3>
</div>
<div id="antrean-container" class="row g-3">
    <div class="col-12 text-center text-muted"><i class="fa-solid fa-circle-notch fa-spin fs-1 mt-5"></i><br>Memuat pesanan...</div>
</div>
@endsection
@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    function fetchPesanan() {
        fetch('/kasir/api/pesanan-aktif')
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('antrean-container');
                container.innerHTML = '';
                if(data.length === 0) {
                    container.innerHTML = '<div class="col-12 text-center mt-5 text-muted"><i class="fa-solid fa-mug-hot fs-1 mb-2 opacity-50"></i><br>Tidak ada pesanan aktif saat ini.</div>';
                    return;
                }
                data.forEach(p => {
                    let btnLunas = p.status_pembayaran === 'pending' ? `<button onclick="updateStatus(${p.id}, 'pembayaran', 'lunas')" class="btn btn-sm btn-success w-100 mb-2 fw-bold"><i class="fa-solid fa-money-bill-wave"></i> Terima Uang</button>` : '';
                    let btnProses = (p.status_pesanan === 'menunggu' && p.status_pembayaran === 'lunas') ? `<button onclick="updateStatus(${p.id}, 'pesanan', 'diproses')" class="btn btn-sm btn-primary w-100 mb-2 fw-bold"><i class="fa-solid fa-fire-burner"></i> Proses Pesanan</button>` : '';
                    let btnSelesai = p.status_pesanan === 'diproses' ? `<button onclick="updateStatus(${p.id}, 'pesanan', 'selesai')" class="btn btn-sm btn-info text-white w-100 mb-2 fw-bold"><i class="fa-solid fa-check-double"></i> Selesai (Serahkan)</button>` : '';
                    let btnBatal = p.status_pembayaran === 'pending' ? `<button onclick="updateStatus(${p.id}, 'pesanan', 'dibatalkan')" class="btn btn-sm btn-outline-danger w-100 fw-bold">Batal (Kembalikan Stok)</button>` : '';
                    
                    let bgBorder = p.status_pembayaran === 'lunas' ? 'border-success' : 'border-warning';
                    let listItems = p.detail_transaksi.map(dt => `<li class="list-group-item py-1 px-2 text-sm d-flex justify-content-between"><span>${dt.produk ? dt.produk.nama_produk : 'Dihapus'}</span> <span class="fw-bold">${dt.jumlah}x</span></li>`).join('');
                    
                    let html = `
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="card h-100 shadow-sm border-0 border-top border-4 ${bgBorder}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h5 class="fw-bold mb-0">#${p.kode_transaksi}</h5>
                                        <span class="badge ${p.status_pembayaran === 'lunas' ? 'bg-success' : 'bg-warning text-dark'}">${p.status_pembayaran.toUpperCase()}</span>
                                    </div>
                                    <p class="mb-1 text-muted small"><i class="fa-solid fa-user"></i> ${p.nama_pemesan}</p>
                                    <p class="mb-3 text-muted small"><i class="fa-solid fa-chair"></i> Meja: ${p.meja_id || '-'}</p>
                                    
                                    <ul class="list-group list-group-flush mb-3 border rounded">
                                        ${listItems}
                                    </ul>
                                    <h5 class="fw-bold text-end mb-3">Rp ${new Intl.NumberFormat('id-ID').format(p.total_harga)}</h5>
                                    
                                    <div class="actions">
                                        ${btnLunas} ${btnProses} ${btnSelesai} ${btnBatal}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += html;
                });
            });
    }
    function updateStatus(id, type, val) {
        fetch(`/kasir/pesanan/${id}/update`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
            body: JSON.stringify({ type: type, value: val })
        }).then(() => fetchPesanan());
    }
    fetchPesanan(); setInterval(fetchPesanan, 5000);
</script>
@endsection