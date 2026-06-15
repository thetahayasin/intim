<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Associate;
use Illuminate\Support\Facades\DB;
use App\Exports\ClientReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', '7d');

        switch ($period) {
            case '30d':
                $startDate = Carbon::now()->subDays(29)->startOfDay();
                break;
            case '90d':
                $startDate = Carbon::now()->subDays(89)->startOfDay();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth()->startOfDay();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear()->startOfDay();
                break;
            default:
                $period = '7d';
                $startDate = Carbon::now()->subDays(6)->startOfDay();
                break;
        }

        $endDate   = Carbon::now()->endOfDay();
        $startStr  = $startDate->toDateString();

        // --- Always-cumulative stats ---
        $totalLeaves      = Attendance::where('is_leave', true)->where('leave_approval', false)->count();
        $numberOfAss      = Associate::count();
        $activeAssociates = Associate::where('active', true)->count();
        $archivedAssociates = Associate::where('active', false)->count();

        // --- Period-filtered: Work Hours ---
        $totalWorkHours = Attendance::where('is_leave', false)
            ->where('is_present', true)
            ->whereDate('date', '>=', $startStr)
            ->sum('work_hours');

        // --- Period-filtered: Financial Overview ---
        $itemTotals = DB::table('invoice_items')
            ->join('billings', 'invoice_items.billing_id', '=', 'billings.id')
            ->whereDate('billings.created_at', '>=', $startStr)
            ->selectRaw('COALESCE(SUM(invoice_items.amount), 0) as total_amount, COALESCE(SUM(invoice_items.tax), 0) as total_tax')
            ->first();

        $legacyTotals = DB::table('billings')
            ->whereNotIn('id', function ($q) { $q->select('billing_id')->from('invoice_items'); })
            ->whereDate('created_at', '>=', $startStr)
            ->selectRaw('COALESCE(SUM(amount), 0) as total_amount, COALESCE(SUM(tax), 0) as total_tax')
            ->first();

        $totalSales          = (float) $itemTotals->total_amount + (float) $legacyTotals->total_amount;
        $totalTaxb           = (float) $itemTotals->total_tax   + (float) $legacyTotals->total_tax;
        $totalBillingDiscount = (float) DB::table('billings')->whereDate('created_at', '>=', $startStr)->sum('discount');
        $totalReceipts        = (float) DB::table('receipts')->whereDate('date', '>=', $startStr)->sum('amount');
        $totalDiscount        = (float) DB::table('receipts')->whereDate('date', '>=', $startStr)->sum('discount');
        $totalTax             = (float) DB::table('receipts')->whereDate('date', '>=', $startStr)->sum('tax');

        // Same values reused for the donut chart
        $weeklySales          = $totalSales;
        $weeklyTaxb           = $totalTaxb;
        $weeklyBillingDiscount = $totalBillingDiscount;
        $weeklyReceipts        = $totalReceipts;
        $weeklyDiscount        = $totalDiscount;
        $weeklyTax             = $totalTax;

        // --- Trend chart: grouping depends on period ---
        $dailyLabels   = [];
        $dailyBillings = [];
        $dailyReceipts = [];

        if ($period === 'year') {
            $cursor = $startDate->copy()->startOfMonth();
            while ($cursor->lte($endDate)) {
                $dailyLabels[]   = $cursor->format('M');
                $dailyBillings[] = (float) DB::table('billings')
                    ->whereYear('created_at', $cursor->year)->whereMonth('created_at', $cursor->month)->sum('amount');
                $dailyReceipts[] = (float) DB::table('receipts')
                    ->whereYear('date', $cursor->year)->whereMonth('date', $cursor->month)->sum('amount');
                $cursor->addMonth();
            }
        } elseif ($period === '90d') {
            $cursor = $startDate->copy()->startOfWeek();
            while ($cursor->lte($endDate)) {
                $weekEnd = $cursor->copy()->endOfWeek();
                $dailyLabels[]   = $cursor->format('d M');
                $dailyBillings[] = (float) DB::table('billings')
                    ->whereBetween(DB::raw('DATE(created_at)'), [$cursor->toDateString(), $weekEnd->toDateString()])->sum('amount');
                $dailyReceipts[] = (float) DB::table('receipts')
                    ->whereBetween('date', [$cursor->toDateString(), $weekEnd->toDateString()])->sum('amount');
                $cursor->addWeek();
            }
        } else {
            $days = (int) $startDate->diffInDays(Carbon::now());
            for ($i = $days; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dailyLabels[]   = $date->format('D d');
                $dailyBillings[] = (float) DB::table('billings')->whereDate('created_at', $date->toDateString())->sum('amount');
                $dailyReceipts[] = (float) DB::table('receipts')->whereDate('date', $date->toDateString())->sum('amount');
            }
        }

        // Kept for backward compat with any view references
        $weeklyPresents      = 0;
        $weeklyAbsents       = 0;
        $weeklyLeavesApplied = 0;
        $weeklyLeavesApproved = 0;
        $weeklyLeavesRejected = 0;

        return view('admin.home', compact(
            'period',
            'totalLeaves',
            'totalWorkHours',
            'numberOfAss',
            'activeAssociates',
            'archivedAssociates',
            'totalSales',
            'totalReceipts',
            'totalDiscount',
            'totalTax',
            'totalTaxb',
            'totalBillingDiscount',
            'dailyLabels',
            'dailyBillings',
            'dailyReceipts',
            'weeklyPresents',
            'weeklyAbsents',
            'weeklyLeavesApplied',
            'weeklyLeavesApproved',
            'weeklyLeavesRejected',
            'weeklySales',
            'weeklyTaxb',
            'weeklyBillingDiscount',
            'weeklyReceipts',
            'weeklyDiscount',
            'weeklyTax'
        ));
    }

    public function exportClientReport()
    {
        return Excel::download(new ClientReportExport, 'client_report.xlsx');
    }
}
