@extends('layouts.app')

@section('title', 'Manajemen User')
@section('header', 'User Management')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card card-midnight border-primary border-start border-4 mb-3">
            <div class="card-body">
                <h6 class="text-secondary text-uppercase small ls-1">Total Pengguna</h6>
                <h2 class="text-white fw-bold mb-0">{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-midnight border-danger border-start border-4 mb-3">
            <div class="card-body">
                <h6 class="text-secondary text-uppercase small ls-1">Administrator</h6>
                <h2 class="text-danger fw-bold mb-0">{{ $totalAdmins }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-midnight border-success border-start border-4 mb-3">
            <div class="card-body">
                <h6 class="text-secondary text-uppercase small ls-1">Customer</h6>
                <h2 class="text-success fw-bold mb-0">{{ $totalCustomers }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card card-midnight">
    <div class="card-header bg-transparent border-bottom border-secondary d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-white">Daftar Pengguna</h5>
        
        <form action="{{ route('admin.users') }}" method="GET" class="d-flex" style="max-width: 300px;">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Cari nama/email..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary text-white" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>User Profile</th>
                        <th>Role</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th>Terdaftar</th>
                        <th class="text-end pe-4">Aksi</th> </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td class="ps-4 text-secondary">{{ $users->firstItem() + $index }}</td>
                        
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="d-block text-white fw-bold">{{ $user->name }}</span>
                                    <small class="text-secondary">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Admin</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">Customer</span>
                            @endif
                        </td>

                        <td>{{ $user->phone_number ?? '-' }}</td>

                        <td title="{{ $user->address }}">
                            {{ Str::limit($user->address, 20) ?? '-' }}
                        </td>

                        <td>
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        
                        <td class="text-end pe-4">
                            @if($user->id !== Auth::id()) <form action="#" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini selamanya?');">
                                    @csrf
                                    {{-- @method('DELETE') --}} 
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus User">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-secondary small">Saya</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-users-slash fa-3x mb-3"></i><br>
                            User tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-transparent border-top border-secondary py-3">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-secondary">
                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} user
            </small>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection