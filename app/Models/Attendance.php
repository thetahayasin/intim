<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'associate_id',
        'is_present',
        'work_done',
        'work_hours',
        'leave_approval',
    ];

    // Define the relationship with the Associate model
    public function associate()
    {
        return $this->belongsTo(Associate::class, 'associate_id');
    }
}
