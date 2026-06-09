<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Billing;
use App\Models\Client;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class CreateBilling extends Component
{
    // Form fields — no strict float types so Livewire can bind empty strings
    public $client_id = '';
    public $firm = '';
    public $discount = 0;

    // Dynamic invoice items
    public $items = [];

    // Computed totals (for display)
    public $subtotal = 0;
    public $totalTax = 0;
    public $grandTotal = 0;

    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'firm' => 'required|in:0,1',
        'discount' => 'nullable|numeric|min:0',
        'items' => 'required|array|min:1',
        'items.*.description' => 'required|string|max:255',
        'items.*.service' => 'required|string|max:255',
        'items.*.amount' => 'required|numeric',
        'items.*.tax' => 'nullable|numeric',
    ];

    protected $messages = [
        'client_id.required' => 'Please select a client.',
        'firm.required' => 'Please select a firm.',
        'items.required' => 'At least one item is required.',
        'items.min' => 'At least one item is required.',
        'items.*.description.required' => 'Item description is required.',
        'items.*.service.required' => 'Item service type is required.',
        'items.*.amount.required' => 'Item amount is required.',
        'items.*.amount.numeric' => 'Item amount must be a number.',
    ];

    public function mount()
    {
        // Start with one empty item row
        $this->items = [
            [
                'description' => '',
                'service' => '',
                'amount' => '',
                'tax' => '',
            ]
        ];
    }

    /**
     * Add a new empty item row.
     */
    public function addItem()
    {
        $this->items[] = [
            'description' => '',
            'service' => '',
            'amount' => '',
            'tax' => '',
        ];
    }

    /**
     * Remove an item row by index.
     */
    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    /**
     * Recompute totals whenever an item or discount changes.
     */
    public function updated($property)
    {
        if (str_starts_with($property, 'items.') || $property === 'discount') {
            $this->computeTotals();
        }
    }

    /**
     * Calculate subtotal, total tax, and grand total from items.
     */
    public function computeTotals()
    {
        $this->subtotal = 0;
        $this->totalTax = 0;

        foreach ($this->items as $item) {
            $this->subtotal += (float) ($item['amount'] ?? 0);
            $this->totalTax += (float) ($item['tax'] ?? 0);
        }

        $this->grandTotal = $this->subtotal + $this->totalTax - (float) ($this->discount ?? 0);
    }

    /**
     * Save the billing with all its items.
     */
    public function save()
    {
        $this->validate();
        $this->computeTotals();

        DB::transaction(function () {
            // Create the billing record
            $billing = Billing::create([
                'client_id' => $this->client_id,
                'amount' => $this->subtotal,
                'tax' => $this->totalTax,
                'discount' => (float) ($this->discount ?? 0),
                'remarks' => null,
                'description' => $this->items[0]['description'] ?? '',
                'firm' => $this->firm,
                'recursive' => false,
            ]);

            // Create each invoice item
            foreach ($this->items as $item) {
                InvoiceItem::create([
                    'billing_id' => $billing->id,
                    'description' => $item['description'],
                    'service' => $item['service'] ?? null,
                    'amount' => (float) ($item['amount'] ?? 0),
                    'tax' => (float) ($item['tax'] ?? 0),
                ]);
            }
        });

        session()->flash('success', 'Billing created successfully with ' . count($this->items) . ' item(s).');
        return redirect()->route('e.billings');
    }

    public function render()
    {
        $clients = Client::orderBy('name', 'asc')->get();

        return view('livewire.create-billing', [
            'clients' => $clients,
        ]);
    }
}
