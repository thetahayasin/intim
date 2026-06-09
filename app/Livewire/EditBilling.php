<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Billing;
use App\Models\Client;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class EditBilling extends Component
{
    public $billingId;
    public $client_id = '';
    public $firm = '';
    public $discount = 0;
    public $items = [];

    public $subtotal = 0;
    public $totalTax = 0;
    public $grandTotal = 0;

    protected $rules = [
        'client_id'              => 'required|exists:clients,id',
        'firm'                   => 'required|in:0,1',
        'discount'               => 'nullable|numeric|min:0',
        'items'                  => 'required|array|min:1',
        'items.*.description'    => 'required|string|max:255',
        'items.*.service'        => 'required|string|max:255',
        'items.*.amount'         => 'required|numeric',
        'items.*.tax'            => 'nullable|numeric',
    ];

    public function mount($billingId)
    {
        $billing = Billing::with('items')->findOrFail($billingId);
        $this->billingId  = $billing->id;
        $this->client_id  = $billing->client_id;
        $this->firm       = (string) $billing->firm;
        $this->discount   = (float) $billing->discount;

        if ($billing->items->count() > 0) {
            $this->items = $billing->items->map(fn($i) => [
                'description' => $i->description,
                'service'     => $i->service ?? '',
                'amount'      => (float) $i->amount,
                'tax'         => (float) $i->tax,
            ])->toArray();
        } else {
            $this->items = [[
                'description' => $billing->description ?? '',
                'service'     => $billing->remarks ?? '',
                'amount'      => (float) $billing->amount,
                'tax'         => (float) $billing->tax,
            ]];
        }

        $this->computeTotals();
    }

    public function addItem()
    {
        $this->items[] = ['description' => '', 'service' => '', 'amount' => '', 'tax' => ''];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function updated($property)
    {
        if (str_starts_with($property, 'items.') || $property === 'discount') {
            $this->computeTotals();
        }
    }

    public function computeTotals()
    {
        $this->subtotal  = 0;
        $this->totalTax  = 0;

        foreach ($this->items as $item) {
            $this->subtotal  += (float) ($item['amount'] ?? 0);
            $this->totalTax  += (float) ($item['tax'] ?? 0);
        }

        $this->grandTotal = $this->subtotal + $this->totalTax - (float) ($this->discount ?? 0);
    }

    public function save()
    {
        $this->validate();
        $this->computeTotals();

        DB::transaction(function () {
            $billing = Billing::findOrFail($this->billingId);

            $billing->update([
                'client_id'   => $this->client_id,
                'amount'      => $this->subtotal,
                'tax'         => $this->totalTax,
                'discount'    => (float) ($this->discount ?? 0),
                'description' => $this->items[0]['description'] ?? '',
                'firm'        => $this->firm,
            ]);

            // Replace all items
            $billing->items()->delete();
            foreach ($this->items as $item) {
                InvoiceItem::create([
                    'billing_id'  => $billing->id,
                    'description' => $item['description'],
                    'service'     => $item['service'] ?? null,
                    'amount'      => (float) ($item['amount'] ?? 0),
                    'tax'         => (float) ($item['tax'] ?? 0),
                ]);
            }
        });

        session()->flash('success', 'Billing updated successfully.');
        return redirect()->route('e.billings');
    }

    public function render()
    {
        $clients = Client::orderBy('name', 'asc')->get();
        return view('livewire.edit-billing', ['clients' => $clients]);
    }
}
