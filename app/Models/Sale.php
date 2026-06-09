<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'amount', 'billing_id' /* other fillable fields */];


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
