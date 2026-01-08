@extends('layouts.app')

@section('title', 'Manajemen Promo')
@section('header', 'Promo Management')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card card-midnight">
    <div class="card-header bg-transparent border-bottom border-secondary d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-white">Daftar Kode Promo</h5>
        <a href="{{ route('promos.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Buat Promo Baru
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode</th>
                        <th>Diskon</th>
                        <th>Masa Berlaku</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $promo)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-secondary fs-6" style="letter-spacing: 1px;">{{ $promo->code }}</span>
                        </td>
                        <td class="text-success fw-bold">{{ $promo->discount_percent }}%</td>
                        <td class="text-secondary small">
                            {{ date('d M Y', strtotime($promo->start_date)) }} <br> s/d <br>
                            {{ date('d M Y', strtotime($promo->end_date)) }}
                        </td>
                        <td>
                            @if($promo->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Aktif</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('promos.edit', $promo->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('promos.destroy', $promo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus promo ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada promo dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection