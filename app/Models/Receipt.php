<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'client_id',
        'date',
        'discount',
        'tax',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'date' => 'datetime',
    ];

    /**
     * Get the client that owns this receipt.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the net amount (amount - discount + tax).
     */
    public function getNetAmountAttribute(): float
    {
        return (float) $this->amount - (float) $this->discount + (float) $this->tax;
    }
}
