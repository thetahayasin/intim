<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $totalLeaves      = Attendance::where('is_leave', true)->where('leave_approval', false)->count();
        $totalWorkHours   = Attendance::where('is_leave', false)->where('is_present', true)->sum('work_hours');
        $numberOfAss      = Associate::count();
        $activeAssociates = Associate::where('active', true)->count();
        $archivedAssociates = Associate::where('active', false)->count();

        // Cumulative billing totals
        $itemTotals = DB::table('invoice_items')
            ->selectRaw('COALESCE(SUM(amount), 0) as total_amount, COALESCE(SUM(tax), 0) as total_tax')
            ->first();

        $legacyTotals = DB::table('billings')
            ->whereNotIn('id', function ($q) { $q->select('billing_id')->from('invoice_items'); })
            ->selectRaw('COALESCE(SUM(amount), 0) as total_amount, COALESCE(SUM(tax), 0) as total_tax')
            ->first();

        $totalSales          = (float) $itemTotals->total_amount + (float) $legacyTotals->total_amount;
        $totalTaxb           = (float) $itemTotals->total_tax   + (float) $legacyTotals->total_tax;
        $totalBillingDiscount = (float) DB::table('billings')->sum('discount');
        $totalReceipts        = (float) DB::table('receipts')->sum('amount');
        $totalDiscount        = (float) DB::table('receipts')->sum('discount');
        $totalTax             = (float) DB::table('receipts')->sum('tax');

        // 7-day trend
        $sevenDaysAgo  = Carbon::now()->subDays(6)->startOfDay();
        $sevenDaysString = $sevenDaysAgo->toDateString();
        $dailyLabels   = [];
        $dailyBillings = [];
        $dailyReceipts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyLabels[]   = $date->format('D d');
            $dailyBillings[] = (float) DB::table('billings')->whereDate('created_at', $date->toDateString())->sum('amount');
            $dailyReceipts[] = (float) DB::table('receipts')->whereDate('date', $date->toDateString())->sum('amount');
        }

        // 7-day donut totals
        $weeklyItemTotals = DB::table('invoice_items')
            ->join('billings', 'invoice_items.billing_id', '=', 'billings.id')
            ->whereDate('billings.created_at', '>=', $sevenDaysString)
            ->selectRaw('COALESCE(SUM(invoice_items.amount), 0) as total_amount, COALESCE(SUM(invoice_items.tax), 0) as total_tax')
            ->first();

        $weeklyLegacyTotals = DB::table('billings')
            ->whereNotIn('id', function ($q) { $q->select('billing_id')->from('invoice_items'); })
            ->whereDate('created_at', '>=', $sevenDaysString)
            ->selectRaw('COALESCE(SUM(amount), 0) as total_amount, COALESCE(SUM(tax), 0) as total_tax')
            ->first();

        $weeklySales          = (float) $weeklyItemTotals->total_amount + (float) $weeklyLegacyTotals->total_amount;
        $weeklyTaxb           = (float) $weeklyItemTotals->total_tax   + (float) $weeklyLegacyTotals->total_tax;
        $weeklyBillingDiscount = (float) DB::table('billings')->whereDate('created_at', '>=', $sevenDaysString)->sum('discount');
        $weeklyReceipts        = (float) DB::table('receipts')->whereDate('date', '>=', $sevenDaysString)->sum('amount');
        $weeklyDiscount        = (float) DB::table('receipts')->whereDate('date', '>=', $sevenDaysString)->sum('discount');
        $weeklyTax             = (float) DB::table('receipts')->whereDate('date', '>=', $sevenDaysString)->sum('tax');

        return view('admin.home', compact(
            'totalLeaves', 'totalWorkHours', 'numberOfAss',
            'activeAssociates', 'archivedAssociates',
            'totalSales', 'totalReceipts', 'totalDiscount', 'totalTax', 'totalTaxb', 'totalBillingDiscount',
            'dailyLabels', 'dailyBillings', 'dailyReceipts',
            'weeklySales', 'weeklyTaxb', 'weeklyBillingDiscount', 'weeklyReceipts', 'weeklyDiscount', 'weeklyTax'
        ));
    }

    public function exportClientReport()
    {
        return Excel::download(new ClientReportExport, 'client_report.xlsx');
    }
}
