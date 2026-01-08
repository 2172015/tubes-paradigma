<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * PROSES CHECKOUT
     * Menghitung total, diskon, validasi stok, dan menyimpan transaksi.
     */
    public function checkout()
    {
        $cart = session()->get('cart');

        // Validasi: Keranjang kosong
        if (!$cart || count($cart) <= 0) {
            return redirect()->route('home')->with('error', 'Keranjang Anda kosong!');
        }

        // --- REFACTOR 1: Optimasi Perhitungan ---
        // Hitung Subtotal (Harga Asli)
        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        // Hitung Diskon (Jika ada promo)
        $discountAmount = 0;
        if (session()->has('coupon')) {
            $percent = session('coupon')['discount_percent'];
            $discountAmount = ($subtotal * $percent) / 100;
        }

        // Hitung Total Akhir (Grand Total)
        $finalTotal = $subtotal - $discountAmount;
        if ($finalTotal < 0) $finalTotal = 0;

        // --- DATABASE TRANSACTION START ---
        try {
            DB::beginTransaction();

            // A. Buat Data Transaksi Utama
            $transaction = Transaction::create([
                'user_id'       => Auth::id(),
                'total_amount'  => $finalTotal, // FIX: Simpan harga setelah diskon
                'status'        => 'pending',
                'invoice_code'  => 'INV-' . date('Ymd') . '-' . mt_rand(1000, 9999),
                'created_at'    => now(),
            ]);

            // B. Simpan Detail & Kurangi Stok
            foreach ($cart as $id => $details) {
                // Lock baris produk untuk mencegah Race Condition (Stok minus saat beli barengan)
                $product = Product::lockForUpdate()->find($id);

                if (!$product) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Produk dengan ID ' . $id . ' tidak ditemukan!');
                }

                if ($product->stock < $details['quantity']) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Stok produk "' . $product->name . '" tidak mencukupi!');
                }

                // Simpan Detail
                TransactionDetail::create([
                    'transaction_id'    => $transaction->id,
                    'product_id'        => $id,
                    'quantity'          => $details['quantity'],
                    'price_at_purchase' => $details['price']
                ]);

                // Update Stok
                $product->decrement('stock', $details['quantity']);
            }

            // C. Bersihkan Session (Cart & Coupon)
            session()->forget(['cart', 'coupon']);

            DB::commit(); // Simpan Permanen

            return redirect()->route('history.index')
                ->with('success', 'Transaksi Berhasil! Invoice: ' . $transaction->invoice_code);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika error
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * HALAMAN RIWAYAT TRANSAKSI (CUSTOMER)
     */
    public function history()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('transactionDetails.product') // Eager Loading
            ->latest()
            ->get();

        return view('customer.history', compact('transactions'));
    }

    /**
     * UPDATE STATUS PENGIRIMAN (ADMIN)
     * Mengubah status dari pending -> shipping
     */
    public function markAsShipped($id)
    {
        // Validasi Role Admin (Double check selain middleware)
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $transaction = Transaction::findOrFail($id);
        
        // Hanya boleh dikirim jika statusnya 'pending'
        if ($transaction->status == 'pending') {
            $transaction->update(['status' => 'shipping']);
            return redirect()->back()->with('success', 'Status pesanan diperbarui menjadi: Sedang Dikirim');
        }

        return redirect()->back()->with('error', 'Pesanan tidak dapat diubah statusnya.');
    }

    /**
     * KONFIRMASI TERIMA BARANG (CUSTOMER)
     * Mengubah status dari shipping -> completed
     */
    public function markAsCompleted($id)
    {
        // Pastikan transaksi milik user yang login
        $transaction = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hanya boleh selesai jika statusnya 'shipping'
        if ($transaction->status == 'shipping') {
            $transaction->update(['status' => 'completed']);
            return redirect()->back()->with('success', 'Terima kasih! Pesanan telah selesai.');
        }

        return redirect()->back()->with('error', 'Pesanan belum dikirim atau sudah selesai.');
    }

    /**
     * Cancel Pesanan
     */
    public function cancel(Transaction $transaction)
    {
        // Validasi: Jangan batalkan jika sudah selesai atau sudah dikirim (opsional)
        if ($transaction->status === 'completed' || $transaction->status === 'shipping') {
            return redirect()->back()->with('error', 'Pesanan yang sudah diproses tidak bisa dibatalkan.');
        }

        // Update status jadi canceled
        $transaction->update([
            'status' => 'canceled'
        ]);

        // OPSI TAMBAHAN (Jika Anda ingin mengembalikan stok produk):
        // foreach ($transaction->transactionDetails as $detail) {
        //     $detail->product->increment('stock', $detail->quantity);
        // }

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}