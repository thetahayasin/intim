<?php
namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        $clients = Client::with(['billings.items', 'receipts'])->get();

        $rows = [];
        $totals = [
            'billing_amount'     => 0,
            'billing_tax'        => 0,
            'billing_discount'   => 0,
            'billing_total'      => 0,
            'receipt_amount'     => 0,
            'receipt_tax'        => 0,
            'receipt_discount'   => 0,
            'receipt_total'      => 0,
            'pending'            => 0,
        ];

        foreach ($clients as $client) {
            $billingAmount = 0;
            $billingTax = 0;
            $billingDiscount = 0;

            foreach($client->billings as $b) {
                // Using computed attributes to coalesce legacy vs new items safely
                $billingAmount += $b->computed_amount;
                $billingTax += $b->computed_tax;
                $billingDiscount += $b->discount;
            }
            $billingTotal = $billingAmount + $billingTax - $billingDiscount;

            $receiptAmount = (float) $client->receipts->sum('amount');
            $receiptTax = (float) $client->receipts->sum('tax');
            $receiptDiscount = (float) $client->receipts->sum('discount');
            
            // Receipt Total is what's credited against the balance (Amount + Tax withhheld + Discount given)
            $receiptTotal = $receiptAmount + $receiptTax + $receiptDiscount;

            $pending = $billingTotal - $receiptTotal;

            // add row
            $rows[] = [
                'Client Name'        => $client->name,
                'Billing Subtotal'   => $billingAmount,
                'Billing Tax'        => $billingTax,
                'Billing Discount'   => $billingDiscount,
                'Billing Net'        => $billingTotal,
                'Receipt Amount'     => $receiptAmount,
                'Receipt Tax'        => $receiptTax,
                'Receipt Discount'   => $receiptDiscount,
                'Receipt Net'        => $receiptTotal,
                'Pending Amount'     => $pending,
            ];

            // add to totals
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

        // add empty row
        $rows[] = ['', '', '', '', '', '', '', '', '', ''];

        // add total row at the bottom
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
            'Tax Withheld by Client (On Receipt)',
            'Receipt Discount / Bad Debts',
            'Total Amount Credited',
            'Net Receivable Balance',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        return [
            // Style the first row as bold text with background color
            1    => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFF4AF1A'],
                ],
            ],
            // Style the total row
            $highestRow => [
                'font' => ['bold' => true],
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ]
            ],
        ];
    }
}
