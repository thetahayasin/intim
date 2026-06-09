<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientStats extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $clientId;
    public $startDate;
    public $endDate;

    public function mount($clientId)
    {
        $this->clientId = $clientId;
    }

    public function render()
    {
        $client = Client::findOrFail($this->clientId);
        
        $billingQuery = $client->billings();
        $receiptQuery = $client->receipts();

        if ($this->startDate) {
            $billingQuery->whereDate('created_at', '>=', $this->startDate);
            $receiptQuery->whereDate('date', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $billingQuery->whereDate('created_at', '<=', $this->endDate);
            $receiptQuery->whereDate('date', '<=', $this->endDate);
        }

        // Totals for this period
        $billsForTotals = clone $billingQuery;
        
        $itemTotals = DB::table('invoice_items')
            ->join('billings', 'invoice_items.billing_id', '=', 'billings.id')
            ->where('billings.client_id', $this->clientId);
            
        $legacyTotals = DB::table('billings')
            ->where('client_id', $this->clientId)
            ->whereNotIn('id', function ($q) {
                $q->select('billing_id')->from('invoice_items');
            });

        if ($this->startDate) {
            $itemTotals->whereDate('billings.created_at', '>=', $this->startDate);
            $legacyTotals->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $itemTotals->whereDate('billings.created_at', '<=', $this->endDate);
            $legacyTotals->whereDate('created_at', '<=', $this->endDate);
        }

        $iTotals = $itemTotals->selectRaw('COALESCE(SUM(invoice_items.amount), 0) as amount, COALESCE(SUM(invoice_items.tax), 0) as tax')->first();
        $lTotals = $legacyTotals->selectRaw('COALESCE(SUM(amount), 0) as amount, COALESCE(SUM(tax), 0) as tax')->first();

        $totalSalesAmount = (float) $iTotals->amount + (float) $lTotals->amount;
        $totaltaxs = (float) $iTotals->tax + (float) $lTotals->tax;
        $totalBillingDiscount = (float) (clone $billingQuery)->sum('discount');

        $totalReceiptsAmount = (clone $receiptQuery)->sum('amount');
        $totalDiscountAmount = (clone $receiptQuery)->sum('discount');
        $totalTaxAmount = (clone $receiptQuery)->sum('tax');
        
        $lastReceiptDate = (clone $receiptQuery)->max('date');

        $bills = (clone $billingQuery)->orderBy('created_at', 'desc')->paginate(10, ['*'], 'billsPage');
        $receipts = (clone $receiptQuery)->orderBy('date', 'desc')->paginate(10, ['*'], 'receiptsPage');

        return view('livewire.client-stats', compact(
            'client', 'totalSalesAmount', 'totaltaxs', 'totalBillingDiscount',
            'totalReceiptsAmount', 'totalDiscountAmount', 'totalTaxAmount', 
            'lastReceiptDate', 'bills', 'receipts'
        ));
    }
}
