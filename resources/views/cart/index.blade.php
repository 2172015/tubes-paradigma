<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - PIXELATE</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}">
</head>
<body>

    <nav class="navbar navbar-dark bg-dark border-bottom border-secondary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                <i class="fas fa-arrow-left"></i> Kembali ke Katalog
            </a>
            <span class="text-white fw-bold">KERANJANG SAYA</span>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-times-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card card-midnight mb-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark-custom align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50%" class="ps-4">Produk</th>
                                        <th style="width:15%">Harga</th>
                                        <th style="width:15%">Qty</th>
                                        <th style="width:15%" class="text-center">Subtotal</th>
                                        <th style="width:5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subtotal = 0 @endphp
                                    @if(session('cart'))
                                        @foreach(session('cart') as $id => $details)
                                            @php $subtotal += $details['price'] * $details['quantity'] @endphp
                                            <tr data-id="{{ $id }}">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        @if($details['image'])
                                                            <img src="{{ asset('storage/' . $details['image']) }}" width="60" height="60" class="rounded me-3" style="object-fit:cover">
                                                        @else
                                                            <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center" style="width:60px;height:60px"><i class="fas fa-image"></i></div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0 text-white">{{ $details['name'] }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                                <td>
                                                    <input type="number" value="{{ $details['quantity'] }}" class="form-control form-control-sm bg-dark text-white border-secondary quantity cart_update" min="1">
                                                </td>
                                                <td class="text-center text-success">
                                                    Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-danger cart_remove">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="fas fa-shopping-basket fa-3x mb-3"></i><br>
                                                Keranjang Anda kosong.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-midnight">
                    <div class="card-header bg-transparent border-bottom border-secondary">
                        <h5 class="mb-0 text-white">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $discountPercent = 0;
                            $discountAmount = 0;
                            $grandTotal = $subtotal;

                            // Cek Session Promo
                            if(session('coupon')) {
                                $discountPercent = session('coupon')['discount_percent'];
                                // Hitung potongan rupiah berdasarkan persen
                                $discountAmount = ($subtotal * $discountPercent) / 100;
                                $grandTotal = $subtotal - $discountAmount;
                            }
                        @endphp

                        <div class="d-flex justify-content-between mb-2 text-secondary">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if(session('coupon'))
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>
                                    Diskon ({{ session('coupon')['code'] }}) 
                                    <span class="badge bg-success ms-1">{{ $discountPercent }}%</span>
                                    <a href="{{ route('remove.promo') }}" class="text-danger ms-2" title="Hapus Promo"><i class="fas fa-times"></i></a>
                                </span>
                                <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <hr class="border-secondary">

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 text-white fw-bold">Total Bayar</span>
                            <span class="fs-5 text-success fw-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        @if(!session('coupon') && session('cart'))
                            <form action="{{ route('apply.promo') }}" method="POST" class="mb-4">
                                @csrf
                                <label class="small text-secondary mb-1">Punya kode promo?</label>
                                <div class="input-group">
                                    <input type="text" name="promo_code" class="form-control bg-dark text-white border-secondary" placeholder="Masukkan kode..." required>
                                    <button class="btn btn-outline-success" type="submit">Pakai</button>
                                </div>
                            </form>
                        @endif

                        @if(session('cart'))
                            {{-- TAMBAHKAN enctype="multipart/form-data" AGAR BISA UPLOAD FILE --}}
                            <form action="{{ route('checkout') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                {{-- INPUT BUKTI BAYAR --}}
                                <div class="mb-3">
                                    <label for="payment_proof" class="form-label text-white small">Upload Bukti Pembayaran</label>
                                    <input class="form-control form-control-sm bg-dark text-white border-secondary" 
                                        type="file" 
                                        id="payment_proof" 
                                        name="payment_proof" 
                                        accept="image/*" 
                                        required>
                                    <div class="form-text text-secondary small">Format: JPG, PNG. Max: 2MB.</div>
                                </div>

                                <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm" onclick="return confirm('Pastikan bukti pembayaran sudah benar. Lanjutkan?')">
                                    <i class="fas fa-credit-card me-2"></i> BAYAR SEKARANG
                                </button>
                            </form>
                        @else
                            <a href="{{ route('home') }}" class="btn btn-secondary w-100">Cari Produk Dulu</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(".cart_update").change(function (e) {
            e.preventDefault();
            var ele = $(this);
            $.ajax({
                url: '{{ route('update.cart') }}',
                method: "patch",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: ele.parents("tr").attr("data-id"), 
                    quantity: ele.parents("tr").find(".quantity").val()
                },
                success: function (response) {
                   window.location.reload();
                }
            });
        });

        $(".cart_remove").click(function (e) {
            e.preventDefault();
            var ele = $(this);
            if(confirm("Hapus produk ini dari keranjang?")) {
                $.ajax({
                    url: '{{ route('remove.from.cart') }}',
                    method: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}', 
                        id: ele.parents("tr").attr("data-id")
                    },
                    success: function (response) {
                        window.location.reload();
                    }
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>