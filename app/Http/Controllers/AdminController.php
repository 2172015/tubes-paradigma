<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Enums\TransactionStatus;
use App\Enums\UserRole;


class AdminController extends Controller
{
    // Menampilkan Dashboard Admin
    public function index()
    {
        $today = Carbon::today();

        // REFACTOR: Gunakan Enum di dalam array whereIn
        $todayRevenue = Transaction::whereDate('created_at', $today)
            ->whereIn('status', [
                TransactionStatus::SHIPPING, 
                TransactionStatus::COMPLETED
            ])
            ->sum('total_amount');

        $todayTransactions = Transaction::whereDate('created_at', $today)->count();

        $lowStockCount = Product::where('stock', '<', 5)->count();

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
        $query = User::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(10);
        $totalUsers = User::count();
        $totalAdmins = User::where('role', UserRole::ADMIN)->count();       // Bukan 'admin'
        $totalCustomers = User::where('role', UserRole::CUSTOMER)->count(); // Bukan 'customer'

        return view('admin.users.index', compact('users', 'totalUsers', 'totalAdmins', 'totalCustomers'));
    }

    //Transaction
    public function transactions()
    {
        $transactions = Transaction::with('user')->latest()->get();
        $totalRevenue = Transaction::whereIn('status', [
            TransactionStatus::SHIPPING, 
            TransactionStatus::COMPLETED
        ])->sum('total_amount');

        return view('admin.transactions.index', compact('transactions', 'totalRevenue'));
    }
}