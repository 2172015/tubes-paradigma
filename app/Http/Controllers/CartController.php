<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Promo;
use Carbon\Carbon;

class CartController extends Controller
{
    // 1. TAMPILKAN HALAMAN KERANJANG
    public function index()
    {
        return view('cart.index');
    }

    // 2. TAMBAH PRODUK KE KERANJANG
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // Cek stok (Validasi sederhana)
        if($product->stock < 1) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Stok habis!']);
            }
            return redirect()->back()->with('error', 'Stok produk habis!');
        }

        // Jika produk sudah ada di cart, tambah quantity-nya
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Jika belum ada, masukkan data baru
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            // Hitung total item unik di keranjang
            $cartCount = count($cart);
            
            return response()->json([
                'status' => 'success', 
                'message' => 'Produk ditambahkan!',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
    }

    // 3. UPDATE JUMLAH (Dipanggil via AJAX)
    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Keranjang diperbarui');
        }
    }

    // 4. HAPUS ITEM (Dipanggil via AJAX)
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Produk dihapus dari keranjang');
        }
    }

    // 5. CEK KODE PROMO
    public function applyPromo(Request $request)
    {
        $code = $request->input('promo_code');
        $now = Carbon::now();

        // Cari kode di database
        $promo = Promo::where('code', $code)->first();

        // Validasi 1: Apakah kode ada?
        if(!$promo) {
            return redirect()->back()->with('error', 'Kode promo tidak ditemukan!');
        }

        // Validasi 2: Apakah status aktif?
        if(!$promo->is_active) {
            return redirect()->back()->with('error', 'Kode promo sudah tidak aktif.');
        }

        // Validasi 3: Apakah tanggal valid?
        if($now < $promo->start_date || $now > $promo->end_date) {
            return redirect()->back()->with('error', 'Kode promo belum mulai atau sudah kadaluarsa.');
        }

        // Simpan Persentase ke Session
        session()->put('coupon', [
            'code' => $promo->code,
            'discount_percent' => $promo->discount_percent // Simpan persennya, bukan rupiahnya
        ]);

        return redirect()->back()->with('success', 'Kode promo berhasil dipasang! Diskon ' . $promo->discount_percent . '%');
    }

    // 6. HAPUS PROMO
    public function removePromo()
    {
        session()->forget('coupon');
        return redirect()->back()->with('success', 'Promo dihapus.');
    }
}