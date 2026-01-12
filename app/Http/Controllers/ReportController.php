<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Panggil logic dari service
        $totalRevenue = $this->reportService->getRevenue($startDate, $endDate);
        $transactionStats = $this->reportService->getTransactionStats($startDate, $endDate);
        $topProducts = $this->reportService->getTopProducts($startDate, $endDate);
        $dailyRevenue = $this->reportService->getDailyRevenue($startDate, $endDate);

        return view('admin.reports.index', compact(
            'startDate', 
            'endDate', 
            'totalRevenue', 
            'transactionStats', 
            'topProducts',
            'dailyRevenue'
        ));
    }
}
