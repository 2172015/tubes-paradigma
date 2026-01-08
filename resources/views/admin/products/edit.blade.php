@extends('layouts.app')

@section('title', 'Edit Produk')
@section('header', 'Edit Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-midnight">
            <div class="card-header bg-transparent border-bottom border-secondary">
                <h5 class="mb-0 text-white">Form Edit Produk</h5>
            </div>
            <div class="card-body">
                {{-- Perhatikan route mengarah ke update dengan ID product --}}
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Wajib ada untuk proses update Laravel --}}
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary">Nama Produk <span class="text-danger">*</span></label>
                        {{-- value diisi dengan old() atau data dari database --}}
                        <input type="text" name="name" class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" placeholder="Contoh: Kopi Susu Aren">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control bg-dark text-white border-secondary @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" placeholder="15000">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary">Stok Saat Ini <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control bg-dark text-white border-secondary @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" placeholder="100">
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary">Deskripsi</label>
                        {{-- Untuk textarea, value ditaruh di antara tag pembuka dan penutup --}}
                        <textarea name="description" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Jelaskan detail produk...">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary">Foto Produk</label>
                        
                        {{-- Menampilkan gambar saat ini jika ada --}}
                        @if($product->image)
                            <div class="mb-2 p-2 border border-secondary rounded d-inline-block bg-secondary bg-opacity-10">
                                <div class="text-secondary small mb-1">Gambar Saat Ini:</div>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Produk" style="height: 80px; width: auto;" class="rounded">
                            </div>
                        @endif

                        <input type="file" name="image" class="form-control bg-dark text-white border-secondary @error('image') is-invalid @enderror">
                        <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar. Max 2MB.</div>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection