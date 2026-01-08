<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AdminController extends Controller
{
    // Menampilkan Dashboard Admin
    public function index()
    {
        // 1. Tentukan Hari Ini
        $today = Carbon::today();

        // 2. Hitung Penjualan Hari Ini (Omzet)
        // Hanya hitung yang statusnya bukan 'pending' (artinya sudah dibayar/dikirim/selesai)
        $todayRevenue = Transaction::whereDate('created_at', $today)
                                   ->whereIn('status', ['shipping', 'completed', 'paid'])
                                   ->sum('total_amount');

        // 3. Hitung Jumlah Transaksi Hari Ini (Semua status masuk)
        $todayTransactions = Transaction::whereDate('created_at', $today)->count();

        // 4. Hitung Produk Low Stock (Misal: Stok di bawah 5)
        $lowStockCount = Product::where('stock', '<', 5)->count();

        // 5. Ambil 5 Transaksi Terakhir untuk Tabel
        $recentTransactions = Transaction::with('user')->latest()->take(5)->get();

        return view('admin.index.dashboard', compact(
            'todayRevenue', 
            'todayTransactions', 
            'lowStockCount', 
            'recentTransactions'
        ));
    }

    // Menampilkan Daftar User
    public function users(Request $request)
    {
        // 1. Query Dasar
        $query = User::query();

        // 2. Logika Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%');
        }

        // 3. Ambil data dengan Pagination (10 per halaman)
        $users = $query->latest()->paginate(10);

        // 4. Statistik untuk Kartu Atas
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalCustomers = User::where('role', 'customer')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'totalAdmins', 'totalCustomers'));
    }

    //Transaction
    public function transactions()
    {
        // Ambil data transaksi terbaru beserta relasi user
        $transactions = Transaction::with('user')->latest()->get();
        
        // Hitung Total Pendapatan
        // Kita hitung uang dari transaksi yang statusnya 'shipping' (dikirim) atau 'completed' (selesai)
        // Status 'pending' belum dihitung sebagai pendapatan fix
        $totalRevenue = Transaction::whereIn('status', ['shipping', 'completed'])->sum('total_amount');

        return view('admin.transactions.index', compact('transactions', 'totalRevenue'));
    }
}