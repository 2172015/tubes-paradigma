<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Belanja - PIXELATE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}">
</head>
<body>

    {{-- Import Enum --}}
    @use('App\Enums\TransactionStatus')

    <nav class="navbar navbar-dark bg-dark border-bottom border-secondary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-arrow-left"></i> Kembali ke Katalog
            </a>
            <span class="text-white">Riwayat Transaksi</span>
        </div>
    </nav>

    <div class="container">
        
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <h4 class="text-white mb-4 border-start border-4 border-success ps-3">Pesanan Saya</h4>

        <div class="row">
            <div class="col-md-12">
                
                @forelse($transactions as $trx)
                <div class="card card-midnight mb-4">
                    <div class="card-header bg-transparent border-bottom border-secondary d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary small">Invoice:</span>
                            <span class="text-white fw-bold ms-1">{{ $trx->invoice_code }}</span>
                            <span class="mx-2 text-secondary">|</span>
                            <span class="text-secondary small">{{ $trx->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2">
                            
                            {{-- 1. Badge Status Otomatis --}}
                            {{-- Mengambil warna dan label langsung dari logic Enum --}}
                            <span class="badge bg-{{ $trx->status->color() }}">
                                {{ $trx->status->label() }}
                            </span>

                            {{-- 2. Tombol Konfirmasi (Hanya muncul jika status SHIPPING) --}}
                            {{-- Kita bandingkan object status dengan Case Enum --}}
                            @if($trx->status === TransactionStatus::SHIPPING)
                                <form action="{{ route('transaction.complete', $trx->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success fw-bold">
                                        <i class="fas fa-check"></i> Pesanan Diterima
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($trx->transactionDetails as $detail)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                @if($detail->product->image)
                                    <img src="{{ asset('storage/' . $detail->product->image) }}" width="60" height="60" class="rounded" style="object-fit:cover">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width:60px;height:60px"><i class="fas fa-image"></i></div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-white mb-0">{{ $detail->product->name }}</h6>
                                <small class="text-secondary">{{ $detail->quantity }} x Rp {{ number_format($detail->price_at_purchase, 0, ',', '.') }}</small>
                            </div>
                            <div class="text-end">
                                <span class="text-success fw-bold">Rp {{ number_format($detail->quantity * $detail->price_at_purchase, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-transparent border-top border-secondary d-flex justify-content-between">
                        <div class="text-secondary small">Total Pembayaran</div>
                        <div class="text-white fw-bold fs-5">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-4x text-secondary mb-3"></i>
                    <h5 class="text-secondary">Belum ada riwayat transaksi.</h5>
                    <a href="{{ route('home') }}" class="btn btn-outline-success mt-3">Mulai Belanja</a>
                </div>
                @endforelse

            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>