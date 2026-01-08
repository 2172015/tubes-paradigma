<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Online POS') - Sistem Kasir</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-3 col-lg-2 d-none d-md-block sidebar px-0">
            <a href="{{ route('dashboard') }}" class="brand-logo d-flex align-items-center gap-3 text-decoration-none py-3">
                <img src="{{ asset('dist/img/logo-pixelate.png') }}" alt="PIXELATE Logo" width="40" height="40" class="rounded-2">
                <span class="text-white fw-bold fs-4" style="letter-spacing: 1px;">PIXELATE</span>
            </a>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="fas fa-box"></i> Manajemen Produk
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.transactions') ? 'active' : '' }}" href="{{ route('admin.transactions') }}">
                        <i class="fas fa-file-invoice-dollar"></i> Transaksi Masuk
                        @php $pendingCount = \App\Models\Transaction::where('status', 'pending')->count(); @endphp
                        @if($pendingCount > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('promos.*') ? 'active' : '' }}" href="{{ route('promos.index') }}">
                        <i class="fas fa-tags"></i> Manajemen Promo
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-line"></i> Laporan
                    </a>
                </li>

                @if(Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                        <i class="fas fa-users"></i> User Management
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4 main-content">
            
            <div class="d-flex justify-content-between align-items-center top-navbar">
                <div>
                    <h4 class="mb-0">@yield('header', 'Dashboard')</h4>
                    <p class="text-secondary mb-0 small">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>!</p>
                </div>
                
                <div class="d-flex align-items-center">
                    <div class="me-3 text-end d-none d-sm-block">
                        <span class="d-block text-white">{{ Auth::user()->email }}</span>
                        <span class="badge bg-success">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-logout btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            @yield('content')

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>