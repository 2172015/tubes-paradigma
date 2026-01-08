@extends('layouts.app')

@section('title', 'Tambah Promo')
@section('header', 'Create New Promo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-midnight">
            <div class="card-header bg-transparent border-bottom border-secondary">
                <h5 class="mb-0 text-white">Form Promo Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('promos.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary">Kode Promo (Unik)</label>
                        <input type="text" name="code" class="form-control bg-dark text-white border-secondary" placeholder="Contoh: LEBARAN2025" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary">Persentase Diskon (%)</label>
                        <input type="number" name="discount_percent" class="form-control bg-dark text-white border-secondary" placeholder="1 - 100" min="1" max="100" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Tanggal Berakhir</label>
                            <input type="date" name="end_date" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked value="1">
                        <label class="form-check-label text-white" for="isActive">Status Aktif</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('promos.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success px-4">Simpan Promo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection