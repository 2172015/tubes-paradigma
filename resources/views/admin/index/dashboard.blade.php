@use('App\Enums\TransactionStatus') {{-- Import Enum --}}

@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Overview')

@section('content')
    <div class="row g-4 mb-4">
        {{-- Card Statistik (Total Penjualan, Transaksi, Low Stock) --}}
        <div class="col-md-4">
            <div class="card card-midnight p-3 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-1">Total Penjualan Hari Ini</p>
                        <h3 class="fw-bold mb-0 text-white">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <div class="icon-box bg-success bg-opacity-10 text-success rounded p-3">
                        <i class="fas fa-wallet fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-midnight p-3 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-1">Total Transaksi Hari Ini</p>
                        <h3 class="fw-bold mb-0 text-white">{{ $todayTransactions }}</h3>
                    </div>
                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded p-3">
                        <i class="fas fa-receipt fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-midnight p-3 border-0 shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-secondary mb-1">Produk Low Stock (< 5)</p>
                        <h3 class="fw-bold mb-0 text-warning">{{ $lowStockCount }} Item</h3>
                    </div>
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded p-3">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Transaksi --}}
    <div class="card card-midnight">
        <div class="card-header bg-transparent border-bottom border-secondary d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-white">Transaksi Terakhir</h5>
            <a href="{{ route('admin.transactions') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Invoice</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $trx)
                        <tr>
                            <td class="ps-4 text-white fw-bold">{{ $trx->invoice_code }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white me-2" style="width:30px;height:30px;font-size:12px">
                                        {{ substr($trx->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-white">{{ $trx->user->name }}</span>
                                </div>
                            </td>
                            <td class="text-secondary small">{{ $trx->created_at->format('d M, H:i') }}</td>
                            
                            <td>
                                <span class="badge bg-{{ $trx->status->color() }} {{ $trx->status === TransactionStatus::PENDING ? 'text-dark' : '' }}">
                                    {{ $trx->status->label() }}
                                </span>
                            </td>

                            <td class="text-success fw-bold">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.transactions') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open mb-2 fa-2x"></i><br>
                                Belum ada transaksi masuk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection