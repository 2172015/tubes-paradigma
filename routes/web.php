<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PromoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. HALAMAN UTAMA (Landing Page / Katalog)
Route::get('/', function () {
    // Logic: Jika yang login adalah ADMIN, jangan kasih liat halaman depan,
    // langsung lempar ke dashboard admin.
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('dashboard');
    }

    $products = Product::latest()->get();

    // Jika Customer atau Belum Login, tampilkan halaman katalog produk
    return view('home', compact('products'));
})->name('home');


// 2. GROUP KHUSUS ADMIN (Wajib Login & Role Admin)
Route::middleware(['auth', 'verified', 'isAdmin'])->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Manajemen User
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');

    // Product
    Route::resource('products', ProductController::class);

    //Transaction
    Route::get('/admin/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
    Route::patch('/admin/transaction/{id}/ship', [App\Http\Controllers\TransactionController::class, 'markAsShipped'])->name('admin.transaction.ship');
    Route::patch('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])
    ->name('admin.transaction.cancel');

    //Promo
    Route::resource('promos', PromoController::class);
    
    // NANTI: Route Laporan ditaruh disini
});


// 3. GROUP KHUSUS CUSTOMER / USER LOGIN (Wajib Login Saja)
Route::middleware('auth')->group(function () {
    //Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::get('/add-to-cart/{id}', [App\Http\Controllers\CartController::class, 'addToCart'])->name('add.to.cart');
    Route::patch('/update-cart', [App\Http\Controllers\CartController::class, 'updateCart'])->name('update.cart');
    Route::delete('/remove-from-cart', [App\Http\Controllers\CartController::class, 'remove'])->name('remove.from.cart');

    //Transaction
    Route::patch('/transaction/{id}/complete', [App\Http\Controllers\TransactionController::class, 'markAsCompleted'])->name('transaction.complete');
    Route::post('/checkout', [App\Http\Controllers\TransactionController::class, 'checkout'])->name('checkout');
    Route::get('/history', [App\Http\Controllers\TransactionController::class, 'history'])->name('history.index');

    //Promo
    Route::post('/apply-promo', [App\Http\Controllers\CartController::class, 'applyPromo'])->name('apply.promo');
    Route::get('/remove-promo', [App\Http\Controllers\CartController::class, 'removePromo'])->name('remove.promo');
    // NANTI: Route Keranjang, Checkout, History ditaruh disini
});

require __DIR__.'/auth.php';