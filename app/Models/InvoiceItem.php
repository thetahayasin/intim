<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'description',
        'service',
        'amount',
        'tax',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax' => 'decimal:2',
    ];

    /**
     * Get the billing that owns this invoice item.
     */
    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    /**
     * Get the total for this line item (amount + tax).
     */
    public function getTotalAttribute(): float
    {
        return (float) $this->amount + (float) $this->tax;
    }
}
