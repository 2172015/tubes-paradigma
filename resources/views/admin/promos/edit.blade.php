@extends('layouts.app')

@section('title', 'Edit Promo')
@section('header', 'Edit Promo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-midnight">
            <div class="card-header bg-transparent border-bottom border-secondary">
                <h5 class="mb-0 text-white">Edit Promo: {{ $promo->code }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('promos.update', $promo->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary">Kode Promo</label>
                        <input type="text" name="code" value="{{ $promo->code }}" class="form-control bg-dark text-white border-secondary" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary">Persentase Diskon (%)</label>
                        <input type="number" name="discount_percent" value="{{ $promo->discount_percent }}" class="form-control bg-dark text-white border-secondary" min="1" max="100" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $promo->start_date }}" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Tanggal Berakhir</label>
                            <input type="date" name="end_date" value="{{ $promo->end_date }}" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ $promo->is_active ? 'checked' : '' }}>
                        <label class="form-check-label text-white" for="isActive">Status Aktif</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('promos.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-success px-4">Update Promo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection