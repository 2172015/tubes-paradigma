@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('header', 'Add New Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-midnight">
            <div class="card-header bg-transparent border-bottom border-secondary">
                <h5 class="mb-0 text-white">Form Input Produk</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Kopi Susu Aren">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control bg-dark text-white border-secondary @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="15000">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control bg-dark text-white border-secondary @error('stock') is-invalid @enderror" value="{{ old('stock') }}" placeholder="100">
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary">Deskripsi</label>
                        <textarea name="description" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Jelaskan detail produk...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary">Foto Produk</label>
                        <input type="file" name="image" class="form-control bg-dark text-white border-secondary @error('image') is-invalid @enderror">
                        <div class="form-text text-muted">Format: jpg, png, jpeg. Maksimal 2MB.</div>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection