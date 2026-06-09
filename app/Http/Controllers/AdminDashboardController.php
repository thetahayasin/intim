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
    public function index()
    {
        // Pending leaves
        $totalLeaves = Attendance::where('is_leave', true)
            ->where('leave_approval', false)
            ->count();

        // Work hours
        $totalWorkHours = Attendance::where('is_leave', false)
            ->where('is_present', true)
            ->sum('work_hours');

        // Associates
        $numberOfAss = Associate::count();
        $activeAssociates = Associate::where('active', true)->count();
        $archivedAssociates = Associate::where('active', false)->count();

        // Billing totals - use invoice_items if available, fallback to billings table
        $itemTotals = DB::table('invoice_items')
            ->selectRaw('COALESCE(SUM(amount), 0) as total_amount, COALESCE(SUM(tax), 0) as total_tax')
            ->first();

        // Legacy billings without invoice_items
        $legacyTotals = DB::table('billings')
            ->whereNotIn('id', function ($query) {
                $query->select('billing_id')->from('invoice_items');
            })
            ->selectRaw('COALESCE(SUM(amount), 0) as total_amount, COALESCE(SUM(tax), 0) as total_tax')
            ->first();

        $totalSales = (float) $itemTotals->total_amount + (float) $legacyTotals->total_amount;
        $totalTaxb = (float) $itemTotals->total_tax + (float) $legacyTotals->total_tax;

        // Discount from billings
        $totalBillingDiscount = (float) DB::table('billings')->sum('discount');

        // Receipt totals
        $totalReceipts = (float) DB::table('receipts')->sum('amount');
        $totalDiscount = (float) DB::table('receipts')->sum('discount');
        $totalTax = (float) DB::table('receipts')->sum('tax');

        // --- 7-Day Billing & Receipt Data ---
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        $dailyLabels = [];
        $dailyBillings = [];
        $dailyReceipts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyLabels[] = $date->format('D d');

            $dayBilling = (float) DB::table('billings')
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount');
            $dailyBillings[] = $dayBilling;

            $dayReceipt = (float) DB::table('receipts')
                ->whereDate('date', $date->toDateString())
                ->sum('amount');
            $dailyReceipts[] = $dayReceipt;
        }

        // --- 7-Day Exact Totals for Bottom Graphs ---
        $sevenDaysString = $sevenDaysAgo->toDateString();

        $weeklyLeavesApplied = Attendance::where('is_leave', true)
            ->where('leave_approval', 0)
            ->whereDate('date', '>=', $sevenDaysString)
            ->count();

        $weeklyLeavesApproved = Attendance::where('is_leave', true)
            ->where('leave_approval', 1)
            ->whereDate('date', '>=', $sevenDaysString)
            ->count();

        $weeklyLeavesRejected = Attendance::where('is_leave', true)
            ->where('leave_approval', 2)
            ->whereDate('date', '>=', $sevenDaysString)
            ->count();

        $weeklyPresents = Attendance::where('is_leave', false)
            ->where('is_present', true)
            ->whereDate('date', '>=', $sevenDaysString)
            ->count();

        $weeklyAbsents = Attendance::where('is_leave', false)
            ->where('is_present', false)
            ->whereDate('date', '>=', $sevenDaysString)
            ->whereNotIn(DB::raw('DAYOFWEEK(date)'), [1, 7]) // Ignore weekends usually, assuming logic
            ->count();

        // 7-day Financial Breakdown
        $weeklyItemTotals = DB::table('invoice_items')
            ->join('billings', 'invoice_items.billing_id', '=', 'billings.id')
            ->whereDate('billings.created_at', '>=', $sevenDaysString)
            ->selectRaw('COALESCE(SUM(invoice_items.amount), 0) as total_amount, COALESCE(SUM(invoice_items.tax), 0) as total_tax')
            ->first();

        $weeklyLegacyTotals = DB::table('billings')
            ->whereNotIn('id', function ($query) {
                $query->select('billing_id')->from('invoice_items');
            })
            ->whereDate('created_at', '>=', $sevenDaysString)
            ->selectRaw('COALESCE(SUM(amount), 0) as total_amount, COALESCE(SUM(tax), 0) as total_tax')
            ->first();

        $weeklySales = (float) $weeklyItemTotals->total_amount + (float) $weeklyLegacyTotals->total_amount;
        $weeklyTaxb = (float) $weeklyItemTotals->total_tax + (float) $weeklyLegacyTotals->total_tax;

        $weeklyBillingDiscount = (float) DB::table('billings')
            ->whereDate('created_at', '>=', $sevenDaysString)
            ->sum('discount');

        $weeklyReceipts = (float) DB::table('receipts')
            ->whereDate('date', '>=', $sevenDaysString)
            ->sum('amount');
            
        $weeklyDiscount = (float) DB::table('receipts')
            ->whereDate('date', '>=', $sevenDaysString)
            ->sum('discount');
            
        $weeklyTax = (float) DB::table('receipts')
            ->whereDate('date', '>=', $sevenDaysString)
            ->sum('tax');


        return view('admin.home', compact(
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
