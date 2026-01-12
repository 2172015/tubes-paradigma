@use('App\Enums\TransactionStatus') {{-- Import Enum --}}

@extends('layouts.app')

@section('title', 'Kelola Transaksi')
@section('header', 'Transaction Management')

@section('content')

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Statistik Atas --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card card-midnight border-success border-start border-4 mb-3">
                <div class="card-body">
                    <h6 class="text-secondary text-uppercase small ls-1">Total Pendapatan (Paid)</h6>
                    <h2 class="text-success fw-bold mb-0">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-midnight border-warning border-start border-4 mb-3">
                <div class="card-body">
                    <h6 class="text-secondary text-uppercase small ls-1">Menunggu Konfirmasi</h6>
                    <h2 class="text-warning fw-bold mb-0">
                        {{-- REFACTOR: Filter Collection menggunakan Enum --}}
                        {{ $transactions->where('status', TransactionStatus::PENDING)->count() }} 
                        <span class="fs-6 text-secondary fw-normal">Pesanan</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-midnight border-primary border-start border-4 mb-3">
                <div class="card-body">
                    <h6 class="text-secondary text-uppercase small ls-1">Pesanan Selesai</h6>
                    <h2 class="text-white fw-bold mb-0">
                        {{-- REFACTOR: Filter Collection menggunakan Enum --}}
                        {{ $transactions->where('status', TransactionStatus::COMPLETED)->count() }}
                        <span class="fs-6 text-secondary fw-normal">Pesanan</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Transaksi --}}
    <div class="card card-midnight">
        <div class="card-header bg-transparent border-bottom border-secondary d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">Daftar Pesanan Masuk</h5>
            <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Invoice ID</th>
                            <th>Customer</th>
                            <th>Status & Bukti</th>
                            <th>Total & Promo</th> 
                            <th>Detail</th>
                            <th class="text-end pe-4">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        
                        {{-- Hitung Subtotal --}}
                        @php
                            $subtotal = $trx->transactionDetails->sum(function($item){
                                return $item->price_at_purchase * $item->quantity;
                            });
                            $discount = $subtotal - $trx->total_amount;
                        @endphp

                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-white">{{ $trx->invoice_code }}</span>
                                <div class="small text-muted">{{ $trx->created_at->format('d M Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white me-2" style="width: 30px; height: 30px;">
                                        {{ substr($trx->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="d-block text-white">{{ $trx->user->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $trx->status->color() }} {{ $trx->status === TransactionStatus::PENDING ? 'text-dark' : '' }} mb-1">
                                    {{ $trx->status->label() }}
                                </span>

                                @if($trx->payment_proof)
                                    <div class="small text-info">
                                        <i class="fas fa-receipt"></i> Ada Bukti Bayar
                                    </div>
                                @else
                                    <div class="small text-muted fst-italic">Belum upload bukti</div>
                                @endif
                            </td>
                            
                            {{-- KOLOM TOTAL & PROMO --}}
                            <td>
                                <div class="fw-bold text-success">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</div>
                                
                                @if($trx->promo_id)
                                    <div class="small text-warning mt-1">
                                        <i class="fas fa-tags"></i> {{ $trx->promo->promo_code ?? 'Promo' }}
                                    </div>
                                    <div class="small text-muted" style="font-size: 0.75rem;">
                                        (Hemat: Rp {{ number_format($discount, 0, ',', '.') }})
                                    </div>
                                @endif
                            </td>
                            
                            {{-- Tombol Modal Detail --}}
                            <td>
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $trx->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>

                                <div class="modal fade" id="detailModal{{ $trx->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content bg-dark text-white border-secondary">
                                            <div class="modal-header border-secondary">
                                                <h5 class="modal-title">Invoice: {{ $trx->invoice_code }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-7 border-end border-secondary">
                                                        <h6 class="text-secondary mb-3">Item Dibeli</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-dark table-sm mb-0">
                                                                <thead>
                                                                    <tr class="text-secondary">
                                                                        <th>Produk</th>
                                                                        <th>Qty</th>
                                                                        <th class="text-end">Subtotal</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($trx->transactionDetails as $detail)
                                                                    <tr>
                                                                        <td>
                                                                            <small>{{ $detail->product->name }}</small>
                                                                            <div class="text-muted small">@ Rp {{ number_format($detail->price_at_purchase, 0, ',', '.') }}</div>
                                                                        </td>
                                                                        <td>{{ $detail->quantity }}</td>
                                                                        <td class="text-end fw-bold">
                                                                            {{ number_format($detail->price_at_purchase * $detail->quantity, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot class="border-top border-secondary">
                                                                    <tr>
                                                                        <td colspan="2" class="text-end text-muted small">Subtotal</td>
                                                                        <td class="text-end">
                                                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                    @if($trx->promo_id)
                                                                    <tr>
                                                                        <td colspan="2" class="text-end text-warning small">
                                                                            Diskon ({{ $trx->promo->promo_code ?? 'Promo' }})
                                                                        </td>
                                                                        <td class="text-end text-warning">
                                                                            - Rp {{ number_format($discount, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    <tr class="border-top border-secondary">
                                                                        <td colspan="2" class="text-end fw-bold">TOTAL BAYAR</td>
                                                                        <td class="text-end fw-bold text-success fs-5">
                                                                            Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <h6 class="text-secondary mb-3">Bukti Pembayaran</h6>
                                                        <div class="card bg-secondary bg-opacity-10 border-0 mb-3">
                                                            <div class="card-body text-center p-2">
                                                                @if($trx->payment_proof)
                                                                    <img src="{{ asset('storage/' . $trx->payment_proof) }}" 
                                                                         class="img-fluid rounded border border-secondary" 
                                                                         alt="Bukti Bayar"
                                                                         style="max-height: 200px; object-fit: contain;">
                                                                    <div class="mt-2">
                                                                        <a href="{{ asset('storage/' . $trx->payment_proof) }}" target="_blank" class="btn btn-xs btn-outline-light">
                                                                            <i class="fas fa-search-plus"></i> Perbesar
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <div class="py-4 text-muted">
                                                                        <i class="fas fa-image fa-2x mb-2"></i><br>
                                                                        Belum ada bukti upload
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="p-2 bg-secondary bg-opacity-10 rounded">
                                                            <small class="text-secondary d-block">Pembeli:</small>
                                                            <span class="fw-bold">{{ $trx->user->name }}</span> <br>
                                                            <small class="text-muted">{{ $trx->user->email }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- END MODAL --}}
                            </td>

                            {{-- Kolom Aksi --}}
                            <td class="text-end pe-4">
                                {{-- REFACTOR: Menggunakan Enum Comparison --}}
                                @if($trx->status === TransactionStatus::PENDING)
                                    <div class="d-flex justify-content-end gap-1">
                                        <form action="{{ route('admin.transaction.ship', $trx->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Konfirmasi & Kirim" onclick="return confirm('Bukti bayar valid? Kirim pesanan ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.transaction.cancel', $trx->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Tolak Pesanan" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                @elseif($trx->status === TransactionStatus::SHIPPING)
                                    <span class="badge bg-outline-primary border border-primary text-primary">
                                        <i class="fas fa-shipping-fast"></i> Sedang Dikirim
                                    </span>
                                @elseif($trx->status === TransactionStatus::COMPLETED)
                                    <span class="text-success small fw-bold"><i class="fas fa-check-double"></i> Tuntas</span>
                                @elseif($trx->status === TransactionStatus::CANCELED)
                                    <span class="text-secondary small"><i class="fas fa-ban"></i> Batal</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-secondary">
                                    <i class="fas fa-shopping-cart fa-3x mb-3 opacity-25"></i>
                                    <h5>Belum ada transaksi.</h5>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection