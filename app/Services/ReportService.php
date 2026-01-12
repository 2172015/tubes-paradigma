<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Enums\TransactionStatus;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportService
{
    /**
     * Mengambil Total Pendapatan dalam rentang tanggal
     */
    public function getRevenue(string $startDate, string $endDate)
    {
        return Transaction::where('status', TransactionStatus::COMPLETED)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('total_amount');
    }

    /**
     * Mengambil Jumlah Transaksi per Status
     */
    public function getTransactionStats(string $startDate, string $endDate)
    {
        return Transaction::select('status', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('status')
            ->get();
    }

    /**
     * Mengambil Produk Terlaris (Top 5)
     */
    public function getTopProducts(string $startDate, string $endDate, int $limit = 5)
    {
        return TransactionDetail::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(quantity * price_at_purchase) as total_revenue')
            )
            ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->where('status', TransactionStatus::COMPLETED)
                  ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->with('product') // Eager load product name
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Grafik Pendapatan per Hari (Untuk Chart)
     */
    public function getDailyRevenue(string $startDate, string $endDate)
    {
        return Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', TransactionStatus::COMPLETED)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}