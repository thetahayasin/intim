<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'file_path',
        'original_filename',
        'uploaded_by',
        'status',
    ];

    public function uploader()
    {
        return $this->belongsTo(\App\Models\Associate::class, 'uploaded_by');
    }
}
