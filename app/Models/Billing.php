<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'amount',
        'tax',
        'discount',
        'adjustment',
        'remarks',
        'description',
        'firm',
        'recursive',
        'halt',
        'next_charge_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'adjustment' => 'decimal:2',
        'recursive' => 'boolean',
        'halt' => 'boolean',
        'next_charge_date' => 'datetime',
    ];

    /**
     * Get the client that owns this billing.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the sales for this billing (legacy relationship).
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the invoice items for this billing.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Compute total amount from items, or fallback to legacy amount.
     */
    public function getComputedAmountAttribute(): float
    {
        if ($this->items->count() > 0) {
            return (float) $this->items->sum('amount');
        }

        return (float) $this->amount;
    }

    /**
     * Compute total tax from items, or fallback to legacy tax.
     */
    public function getComputedTaxAttribute(): float
    {
        if ($this->items->count() > 0) {
            return (float) $this->items->sum('tax');
        }

        return (float) $this->tax;
    }

    /**
     * Get the grand total (amount + tax - discount + adjustment).
     */
    public function getGrandTotalAttribute(): float
    {
        return $this->computed_amount + $this->computed_tax - (float) $this->discount + (float) $this->adjustment;
    }
}
