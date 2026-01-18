<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use App\Enums\TransactionStatus;
use App\Enums\UserRole;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * PROSES CHECKOUT
     * Menghitung total, diskon, validasi stok, dan menyimpan transaksi.
     */
    public function checkout(Request $request)
    {
        // 1. Validasi Input (Wajib ada gambar bukti bayar)
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'payment_proof.required' => 'Mohon upload bukti pembayaran terlebih dahulu.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $cart = session()->get('cart');

        if (!$cart || count($cart) <= 0) {
            return redirect()->route('home')->with('error', 'Keranjang Anda kosong!');
        }

        // Hitung Subtotal
        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        // Hitung Diskon
        $discountAmount = 0;
        if (session()->has('coupon')) {
            $percent = session('coupon')['discount_percent'];
            $discountAmount = ($subtotal * $percent) / 100;
        }

        // Hitung Total Akhir
        $finalTotal = max($subtotal - $discountAmount, 0);

        try {
            DB::beginTransaction();

            // 2. Upload Bukti Bayar Menggunakan Service
            // Folder tujuan: storage/app/public/payments
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $this->imageService->upload($request->file('payment_proof'), 'payments');
            }

            // 3. Buat Data Transaksi
            $transaction = Transaction::create([
                'user_id'       => Auth::id(),
                'total_amount'  => $finalTotal,
                'status'        => TransactionStatus::PENDING,
                'payment_proof' => $proofPath, // Simpan path gambar ke DB
                'invoice_code'  => 'INV-' . date('Ymd') . '-' . mt_rand(1000, 9999),
                'created_at'    => now(),
            ]);

            // Simpan Detail & Update Stok
            foreach ($cart as $id => $details) {
                $product = Product::lockForUpdate()->find($id);

                if (!$product || $product->stock < $details['quantity']) {
                    DB::rollBack();
                    // Jika gagal, hapus gambar yang terlanjur diupload agar tidak nyampah
                    if ($proofPath) $this->imageService->delete($proofPath);
                    
                    return redirect()->back()->with('error', 'Stok produk "' . $details['name'] . '" habis atau tidak mencukupi.');
                }

                TransactionDetail::create([
                    'transaction_id'    => $transaction->id,
                    'product_id'        => $id,
                    'quantity'          => $details['quantity'],
                    'price_at_purchase' => $details['price']
                ]);

                $product->decrement('stock', $details['quantity']);
            }

            session()->forget(['cart', 'coupon']);
            DB::commit();

            return redirect()->route('history.index')
                ->with('success', 'Checkout Berhasil! Mohon tunggu konfirmasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus gambar jika transaksi database gagal
            if (isset($proofPath)) $this->imageService->delete($proofPath);
            
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
        if (Auth::user()->role !== UserRole::ADMIN) {
            abort(403, 'Halaman ini khusus Administrator.');
        }

        $transaction = Transaction::findOrFail($id);
        
        // Logika Bisnis:
        // Hanya boleh dikirim jika statusnya 'pending' (Menunggu Konfirmasi)
        if ($transaction->status === TransactionStatus::PENDING) {
            
            // ACTION: Ubah status menjadi SHIPPING (Sedang Dikirim)
            $transaction->update([
                'status' => TransactionStatus::SHIPPING
            ]);

            return redirect()->back()->with('success', 'Pembayaran dikonfirmasi. Status diubah menjadi: Sedang Dikirim.');
        }

        return redirect()->back()->with('error', 'Pesanan tidak dapat diproses (Status bukan Pending).');
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
        if ($transaction->status === TransactionStatus::SHIPPING) {
            $transaction->update(['status' => TransactionStatus::COMPLETED]);
            return redirect()->back()->with('success', 'Terima kasih! Pesanan selesai.');
        }

        return redirect()->back()->with('error', 'Pesanan belum dikirim atau sudah selesai.');
    }

    /**
     * Cancel Pesanan
     */
    public function cancel(Transaction $transaction)
    {
        // Validasi: Jangan batalkan jika sudah selesai atau sudah dikirim (opsional)
        if ($transaction->status === TransactionStatus::COMPLETED || 
            $transaction->status === TransactionStatus::SHIPPING) {
            
            return redirect()->back()->with('error', 'Pesanan tidak bisa dibatalkan.');
        }

        // Update status jadi canceled
        $transaction->update([
            'status' => TransactionStatus::CANCELED
        ]);
        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}