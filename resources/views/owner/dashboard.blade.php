@extends('layouts.admin')
@section('content')
    <h3 class="mb-4 fw-bold">Ringkasan Keuangan ({{ \Carbon\Carbon::now()->translatedFormat('d F Y') }})</h3>
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 border-start border-primary border-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Total Transaksi</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ $totalTransaksi }}</h3>
                        </div>
                        <i class="fa-solid fa-receipt fs-2 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 border-start border-success border-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Pendapatan Kotor</p>
                            <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fa-solid fa-money-bill-wave fs-2 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 border-start border-danger border-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Total Pengeluaran</p>
                            <h3 class="fw-bold mb-0 text-danger">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fa-solid fa-cart-shopping fs-2 text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 border-start border-warning border-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size:12px;">Pendapatan Bersih</p>
                            <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($pendapatanBersih, 0, ',', '.') }}</h3>
                        </div>
                        <i class="fa-solid fa-vault fs-2 text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection