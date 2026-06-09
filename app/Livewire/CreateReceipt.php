<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Receipt;
use App\Models\Client;

class CreateReceipt extends Component
{
    // Form fields — no strict types so Livewire can bind empty strings
    public $client_id = '';
    public $amount = '';
    public $discount = '';
    public $tax = '';
    public $date = '';

    // Computed total
    public $netTotal = 0;

    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'amount' => 'required|numeric|min:1',
        'discount' => 'nullable|numeric|min:0',
        'tax' => 'nullable|numeric|min:0',
        'date' => 'required|date',
    ];

    protected $messages = [
        'client_id.required' => 'Please select a client.',
        'amount.required' => 'Amount is required.',
        'amount.numeric' => 'Amount must be a number.',
        'amount.min' => 'Amount must be at least 1.',
        'date.required' => 'Date is required.',
    ];

    /**
     * Recompute net total on any field update.
     */
    public function updated($property)
    {
        if (in_array($property, ['amount', 'discount', 'tax'])) {
            $this->computeTotal();
        }
    }

    /**
     * Calculate net total: amount - discount + tax.
     */
    public function computeTotal()
    {
        $this->netTotal = (float) ($this->amount ?: 0) - (float) ($this->discount ?: 0) + (float) ($this->tax ?: 0);
        if ($this->netTotal < 0) {
            $this->netTotal = 0;
        }
    }

    /**
     * Save the receipt.
     */
    public function save()
    {
        $this->validate();

        Receipt::create([
            'amount' => (float) ($this->amount ?: 0),
            'client_id' => $this->client_id,
            'date' => $this->date,
            'discount' => (float) ($this->discount ?: 0),
            'tax' => (float) ($this->tax ?: 0),
        ]);

        session()->flash('success', 'Receipt created successfully.');
        return redirect()->route('e.receipts');
    }

    public function render()
    {
        $clients = Client::orderBy('name', 'asc')->get();

        return view('livewire.create-receipt', [
            'clients' => $clients,
        ]);
    }
}
