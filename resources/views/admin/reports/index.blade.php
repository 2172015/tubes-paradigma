@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('header', 'Sales Reports')

@section('content')

{{-- Filter Tanggal --}}
<div class="card card-midnight mb-4">
    <div class="card-body">
        <form action="{{ route('admin.reports') }}" method="GET" class="row align-items-end g-3">
            <div class="col-md-4">
                <label class="text-secondary small">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control bg-dark text-white border-secondary" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="text-secondary small">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control bg-dark text-white border-secondary" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Card Total Pendapatan --}}
    <div class="col-md-6">
        <div class="card card-midnight h-100 border-start border-4 border-success">
            <div class="card-body">
                <h6 class="text-secondary">Total Pendapatan (Periode Ini)</h6>
                <h2 class="text-white fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>

    {{-- Card Status Transaksi --}}
    <div class="col-md-6">
        <div class="card card-midnight h-100">
            <div class="card-body">
                <h6 class="text-secondary mb-3">Ringkasan Status</h6>
                <div class="d-flex gap-3 flex-wrap">
                    @foreach($transactionStats as $stat)
                        <div class="border border-secondary rounded p-2 px-3">
                            <span class="d-block small text-secondary">{{ $stat->status->label() }}</span>
                            <span class="fw-bold text-white fs-5">{{ $stat->total }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Tabel Produk Terlaris --}}
    <div class="col-md-8">
        <div class="card card-midnight h-100">
            <div class="card-header bg-transparent border-bottom border-secondary">
                <h5 class="text-white mb-0">Top 5 Produk Terlaris</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th class="text-center">Terjual</th>
                                <th class="text-end pe-4">Total Omset</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/'.$item->product->image) }}" width="40" class="rounded me-2">
                                        @endif
                                        <span class="fw-bold">{{ $item->product->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center">{{ $item->total_sold }}</td>
                                <td class="text-end pe-4 text-success">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada data penjualan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Harian Sederhana (Bisa diganti Chart.js nanti) --}}
    <div class="col-md-4">
        <div class="card card-midnight h-100">
            <div class="card-header bg-transparent border-bottom border-secondary">
                <h5 class="text-white mb-0">Pendapatan Harian</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($dailyRevenue->take(5) as $day)
                    <div class="list-group-item bg-transparent text-white border-secondary d-flex justify-content-between">
                        <span>{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</span>
                        <span class="fw-bold text-success">Rp {{ number_format($day->total, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted">Tidak ada data.</div>
                    @endforelse
                </div>
                @if($dailyRevenue->count() > 5)
                    <div class="p-2 text-center border-top border-secondary">
                        <small class="text-muted">dan {{ $dailyRevenue->count() - 5 }} hari lainnya...</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection