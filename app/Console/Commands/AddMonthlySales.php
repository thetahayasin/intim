<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Billing;
use App\Models\Sale;
use App\Models\Client;
use App\Mail\TotalPayableNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class AddMonthlySales extends Command
{
    protected $signature = 'sales:add-monthly';

    protected $description = 'Add monthly sales from eligible billings and notify clients of their total payable amount.';

    public function handle()
    {
        $now = Carbon::now();

        // Retrieve eligible billings
        $eligibleBillings = Billing::where('recursive', true)
            ->where('halt', false)
            ->where('next_charge_date', '<=', $now)
            ->get();

        // Array to store clients to be notified
        $clientsToNotify = [];

        // Add new sales for each eligible billing
        foreach ($eligibleBillings as $billing) {
            $sale = new Sale([
                'client_id' => $billing->client_id,
                'billing_id' => $billing->id,
                'amount' => $billing->amount,
            ]);
            $sale->save();

            $billing->next_charge_date = Carbon::parse($billing->next_charge_date)->addMonth();
            $billing->save();

            // Add client to the notification list
            $clientsToNotify[$billing->client_id] = $billing->client_id;
        }

        // Notify each client of their total payable amount
        foreach ($clientsToNotify as $clientId) {
            $client = Client::find($clientId);
            $totalSalesAmount = $client->sales()->sum('amount');
            $totalReceiptsAmount = $client->receipts()->sum('amount');
            $totalPayable = $totalSalesAmount - $totalReceiptsAmount;

            if ($totalPayable > 0) {
                Mail::to($client->email)->send(new TotalPayableNotification($client, $totalPayable));
            }
        }

        $this->info('Monthly sales added and eligible clients notified successfully.');
    }
}