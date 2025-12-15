<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Online POS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-3 col-lg-2 d-none d-md-block sidebar px-0">
            <a href="#" class="brand-logo">
                ONLINE <span>P.O.S</span>
            </a>
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-box"></i> Manajemen Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-shopping-cart"></i> Transaksi (Kasir)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-tags"></i> Promo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-line"></i> Laporan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-users"></i> User Management
                    </a>
                </li>
            </ul>
        </div>

        <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4 main-content">
            
            <div class="d-flex justify-content-between align-items-center top-navbar">
                <div>
                    <h4 class="mb-0">Overview</h4>
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

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card card-midnight p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-secondary mb-1">Total Penjualan Hari Ini</p>
                                <h3 class="fw-bold mb-0">Rp 0</h3>
                            </div>
                            <div class="icon-box">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-midnight p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-secondary mb-1">Total Transaksi</p>
                                <h3 class="fw-bold mb-0">0</h3>
                            </div>
                            <div class="icon-box">
                                <i class="fas fa-receipt"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-midnight p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-secondary mb-1">Produk Low Stock</p>
                                <h3 class="fw-bold mb-0 text-warning">0 Item</h3>
                            </div>
                            <div class="icon-box text-warning" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-midnight">
                <div class="card-header bg-transparent border-bottom border-secondary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Transaksi Terakhir</h5>
                    <a href="#" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Invoice</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>