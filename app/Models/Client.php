<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
