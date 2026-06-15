<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $periodLabel;

    public function __construct($startDate, $endDate, $periodLabel)
    {
        $this->startDate   = $startDate;
        $this->endDate     = $endDate;
        $this->periodLabel = $periodLabel;
    }

    public function collection()
    {
        $startStr = $this->startDate->toDateString();
        $endStr   = $this->endDate->toDateString();

        $clients = Client::with(['billings.items', 'receipts'])->get();

        $rows   = [];
        $totals = array_fill_keys(['billing_amount','billing_tax','billing_discount','billing_total','receipt_amount','receipt_tax','receipt_discount','receipt_total','pending'], 0);

        foreach ($clients as $client) {
            $billingAmount   = 0;
            $billingTax      = 0;
            $billingDiscount = 0;

            // Filter billings by date
            $filteredBillings = $client->billings->filter(function ($b) use ($startStr, $endStr) {
                $d = optional($b->created_at)->toDateString();
                return $d && $d >= $startStr && $d <= $endStr;
            });

            foreach ($filteredBillings as $b) {
                $billingAmount   += $b->computed_amount;
                $billingTax      += $b->computed_tax;
                $billingDiscount += $b->discount;
            }
            $billingTotal = $billingAmount + $billingTax - $billingDiscount;

            // Filter receipts by date
            $filteredReceipts = $client->receipts->filter(function ($r) use ($startStr, $endStr) {
                $d = $r->date instanceof \Carbon\Carbon ? $r->date->toDateString() : $r->date;
                return $d && $d >= $startStr && $d <= $endStr;
            });

            $receiptAmount   = (float) $filteredReceipts->sum('amount');
            $receiptTax      = (float) $filteredReceipts->sum('tax');
            $receiptDiscount = (float) $filteredReceipts->sum('discount');
            $receiptTotal    = $receiptAmount + $receiptTax + $receiptDiscount;

            $pending = $billingTotal - $receiptTotal;

            // Skip clients with no activity in the period
            if ($billingTotal == 0 && $receiptTotal == 0) continue;

            $rows[] = [
                $client->name,
                $billingAmount,
                $billingTax,
                $billingDiscount,
                $billingTotal,
                $receiptAmount,
                $receiptTax,
                $receiptDiscount,
                $receiptTotal,
                $pending,
            ];

            $totals['billing_amount']   += $billingAmount;
            $totals['billing_tax']      += $billingTax;
            $totals['billing_discount'] += $billingDiscount;
            $totals['billing_total']    += $billingTotal;
            $totals['receipt_amount']   += $receiptAmount;
            $totals['receipt_tax']      += $receiptTax;
            $totals['receipt_discount'] += $receiptDiscount;
            $totals['receipt_total']    += $receiptTotal;
            $totals['pending']          += $pending;
        }

        $rows[] = ['', '', '', '', '', '', '', '', '', ''];
        $rows[] = [
            'TOTAL',
            $totals['billing_amount'],
            $totals['billing_tax'],
            $totals['billing_discount'],
            $totals['billing_total'],
            $totals['receipt_amount'],
            $totals['receipt_tax'],
            $totals['receipt_discount'],
            $totals['receipt_total'],
            $totals['pending'],
        ];

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Client Name',
            'Gross Sales (Services)',
            'Sales Tax Payable (Billed)',
            'Billing Discount',
            'Total Invoiced Amount',
            'Total Receipts (Bank/Cash)',
            'Tax Withheld by Client',
            'Receipt Discount / Bad Debts',
            'Total Amount Credited',
            'Net Receivable Balance',
        ];
    }

    public function title(): string
    {
        return $this->periodLabel;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF161616']],
            ],
            $highestRow => [
                'font'    => ['bold' => true],
                'borders' => ['top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ],
        ];
    }
}
