@extends('layouts.admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Kelola Data Meja / Spot</h3>
    <form action="{{ route('owner.meja.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary shadow-sm"><i class="fa-solid fa-qrcode"></i> Generate Meja Baru</button>
    </form>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID Meja</th>
                        <th>QR Token (Link Katalog)</th>
                        <th>Status Layanan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($meja as $m)
                    <tr>
                        <td class="fw-bold">Meja #{{ $m->id }}</td>
                        <td>
                            <div class="fs-5 fw-bold text-primary tracking-widest">{{ $m->qr_token }}</div>
                            <a href="{{ url('/meja/'.$m->qr_token) }}" target="_blank" class="text-decoration-none small"><i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Katalog</a>
                        </td>
                        <td>
                            <form action="{{ route('owner.meja.update', $m->id) }}" method="POST">
                                @csrf @method('PUT')
                                <select name="is_active" class="form-select form-select-sm w-auto mx-auto {{ $m->is_active ? 'border-success text-success' : 'border-danger text-danger' }}" onchange="this.form.submit()">
                                    <option value="1" {{ $m->is_active ? 'selected' : '' }}>🟢 Aktif Melayani</option>
                                    <option value="0" {{ !$m->is_active ? 'selected' : '' }}>🔴 Nonaktif / Rusak</option>
                                </select>
                            </form>
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('owner.meja.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus meja ini secara permanen?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection