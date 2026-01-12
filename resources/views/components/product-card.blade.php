<div class="card card-midnight h-100 shadow-sm border-0">
    {{-- Logic Gambar --}}
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
                {{-- Class 'btn-add-cart' tetap ada agar script AJAX di halaman utama tetap jalan --}}
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