<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', '7d');
        $from   = $request->input('from');
        $to     = $request->input('to');

        // Custom date range overrides quick period
        if ($from && $to) {
            $period    = 'custom';
            $startDate = Carbon::parse($from)->startOfDay();
            $endDate   = Carbon::parse($to)->endOfDay();
        } else {
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
                    $period    = '7d';
                    $startDate = Carbon::now()->subDays(6)->startOfDay();
                    break;
            }
            $endDate = Carbon::now()->endOfDay();
        }

        $startStr = $startDate->toDateString();
        $endStr   = $endDate->toDateString();

        // Financial totals for selected period
        $itemTotals = DB::table('invoice_items')
            ->join('billings', 'invoice_items.billing_id', '=', 'billings.id')
            ->whereDate('billings.created_at', '>=', $startStr)
            ->whereDate('billings.created_at', '<=', $endStr)
            ->selectRaw('COALESCE(SUM(invoice_items.amount), 0) as total_amount, COALESCE(SUM(invoice_items.tax), 0) as total_tax')
            ->first();

        $legacyTotals = DB::table('billings')
            ->whereNotIn('id', function ($q) { $q->select('billing_id')->from('invoice_items'); })
            ->whereDate('created_at', '>=', $startStr)
            ->whereDate('created_at', '<=', $endStr)
            ->selectRaw('COALESCE(SUM(amount), 0) as total_amount, COALESCE(SUM(tax), 0) as total_tax')
            ->first();

        $totalSales           = (float) $itemTotals->total_amount + (float) $legacyTotals->total_amount;
        $totalTaxb            = (float) $itemTotals->total_tax   + (float) $legacyTotals->total_tax;
        $totalBillingDiscount = (float) DB::table('billings')->whereDate('created_at', '>=', $startStr)->whereDate('created_at', '<=', $endStr)->sum('discount');
        $totalReceipts        = (float) DB::table('receipts')->whereDate('date', '>=', $startStr)->whereDate('date', '<=', $endStr)->sum('amount');
        $totalDiscount        = (float) DB::table('receipts')->whereDate('date', '>=', $startStr)->whereDate('date', '<=', $endStr)->sum('discount');
        $totalTax             = (float) DB::table('receipts')->whereDate('date', '>=', $startStr)->whereDate('date', '<=', $endStr)->sum('tax');

        // Trend chart — group by day / week / month depending on range
        $days = $startDate->diffInDays($endDate);
        $trendLabels   = [];
        $trendBillings = [];
        $trendReceipts = [];

        if ($days <= 31) {
            // Daily
            for ($i = $days; $i >= 0; $i--) {
                $date = $endDate->copy()->subDays($i)->startOfDay();
                $trendLabels[]   = $date->format('D d');
                $trendBillings[] = (float) DB::table('billings')->whereDate('created_at', $date->toDateString())->sum('amount');
                $trendReceipts[] = (float) DB::table('receipts')->whereDate('date', $date->toDateString())->sum('amount');
            }
        } elseif ($days <= 92) {
            // Weekly
            $cursor = $startDate->copy()->startOfWeek();
            while ($cursor->lte($endDate)) {
                $weekEnd = $cursor->copy()->endOfWeek()->min($endDate);
                $trendLabels[]   = $cursor->format('d M');
                $trendBillings[] = (float) DB::table('billings')->whereBetween(DB::raw('DATE(created_at)'), [$cursor->toDateString(), $weekEnd->toDateString()])->sum('amount');
                $trendReceipts[] = (float) DB::table('receipts')->whereBetween('date', [$cursor->toDateString(), $weekEnd->toDateString()])->sum('amount');
                $cursor->addWeek();
            }
        } else {
            // Monthly
            $cursor = $startDate->copy()->startOfMonth();
            while ($cursor->lte($endDate)) {
                $trendLabels[]   = $cursor->format('M Y');
                $trendBillings[] = (float) DB::table('billings')->whereYear('created_at', $cursor->year)->whereMonth('created_at', $cursor->month)->sum('amount');
                $trendReceipts[] = (float) DB::table('receipts')->whereYear('date', $cursor->year)->whereMonth('date', $cursor->month)->sum('amount');
                $cursor->addMonth();
            }
        }

        return view('admin.reports', compact(
            'period', 'from', 'to',
            'startDate', 'endDate',
            'totalSales', 'totalTaxb', 'totalBillingDiscount',
            'totalReceipts', 'totalDiscount', 'totalTax',
            'trendLabels', 'trendBillings', 'trendReceipts'
        ));
    }
}
