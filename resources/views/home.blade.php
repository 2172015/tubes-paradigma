<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - PIXELATE</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}">

    <style>
        .hero-section {
            background: linear-gradient(rgba(17, 24, 39, 0.7), rgba(17, 24, 39, 0.9)), url('https://images.unsplash.com/photo-1556742049-0cfed4f7a07d?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
            border-bottom: 1px solid var(--border-color);
        }
        .navbar-home {
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 0;
        }
        .product-img-top {
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid var(--border-color);
        }
        #floating-alert {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            display: none; 
        }
    </style>
</head>
<body>

    <div id="floating-alert" class="alert alert-success shadow-lg text-white border-0" style="background-color: var(--accent-color);">
        <i class="fas fa-check-circle me-2"></i> <span id="alert-message">Berhasil</span>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-home fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('dist/img/logo-pixelate.png') }}" alt="PIXELATE Logo" width="35" height="35" class="rounded-1">
                <span class="text-white ms-1" style="letter-spacing: 1px;">PIXELATE</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2 align-items-center">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ route('history.index') }}" class="btn btn-outline-light btn-sm" title="Riwayat Pesanan">
                                    <i class="fas fa-history me-1"></i> Riwayat
                                </a>
                            </li>

                            <li class="nav-item me-2">
                                <a href="{{ route('cart.index') }}" class="btn btn-dark border-secondary btn-sm position-relative text-white">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success" style="{{ session('cart') ? '' : 'display:none' }}">
                                        {{ session('cart') ? count(session('cart')) : 0 }}
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <span class="nav-link text-white small">Hi, {{ Auth::user()->name }}</span>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-user"></i> Akun
                                </a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Masuk</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-success btn-sm">Daftar</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section text-center mt-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Selamat Datang di Pixelate</h1>
            <p class="lead text-gray-300">Pilih design yang anda butuhkan, dengan menggunakan AI-Generated design</p>
        </div>
    </header>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-white border-start border-4 border-success ps-3">Katalog Produk</h3>
        </div>

        <div class="row g-4">
            @forelse($products as $product)
            <div class="col-md-3 col-6">
                <div class="card card-midnight h-100 shadow-sm border-0">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-img-top" alt="{{ $product->name }}">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center product-img-top text-white">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-white mb-1">{{ $product->name }}</h5>
                        <p class="card-text text-secondary small flex-grow-1">
                            {{ Str::limit($product->description, 50) }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <small class="text-secondary">Stok: {{ $product->stock }}</small>
                        </div>

                        <div class="mt-3">
                            @auth
                                <a href="javascript:void(0)" 
                                   data-url="{{ route('add.to.cart', $product->id) }}" 
                                   class="btn btn-success w-100 btn-sm btn-add-cart">
                                    <i class="fas fa-cart-plus"></i> Tambah (+1)
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-secondary w-100 btn-sm">
                                    Login untuk Membeli
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h4 class="text-secondary">Belum ada produk yang tersedia.</h4>
            </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.btn-add-cart').click(function(e) {
                e.preventDefault(); 
                var button = $(this);
                var url = button.data('url');
                var originalHtml = button.html();
                button.html('<i class="fas fa-spinner fa-spin"></i> Loading...');
                button.addClass('disabled');

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(response) {
                        button.html(originalHtml);
                        button.removeClass('disabled');

                        if(response.status == 'success') {
                            $('#cart-badge').text(response.cart_count).show();
                            $('#alert-message').text(response.message);
                            $('#floating-alert').fadeIn().delay(2000).fadeOut();
                        } else {
                            alert(response.message); 
                        }
                    },
                    error: function(xhr) {
                        button.html(originalHtml);
                        button.removeClass('disabled');
                        alert('Terjadi kesalahan, silakan coba lagi.');
                    }
                });
            });
        });
    </script>
</body>
</html>