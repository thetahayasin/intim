<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'client_id', 'client_name', 'firm',
        'services', 'start_date', 'end_date', 'notes', 'signed_pdf', 'status',
    ];

    protected $casts = [
        'services'   => 'array',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getFirmNameAttribute(): string
    {
        return $this->firm == 1 ? 'H.A.M.D & CO' : 'Asif Associates, Chartered Accountants';
    }

    public function getFirmLogoAttribute(): string
    {
        return $this->firm == 1 ? 'assets/img/hamd.png' : 'assets/img/logo-full.png';
    }
}
