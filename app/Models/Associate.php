<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Carbon\Carbon;


class Associate extends Model implements Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'email', 'password', 'name', 'crn', 'date_joined', 'active', 'fts', 'period', 'opening_presents', 'opening_absents', 'opening_leaves'
    ];

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }
    
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    // Define the relationship with the Attendance model
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'associate_id');
    }

    // Define the attribute accessor for the calculated date
    public function getEndDateAttribute()
    {
        // Ensure that both date_joined and period are set
        if (!empty($this->date_joined) && !empty($this->period)) {
            // Separate the integer part and the fractional part of the period
            $integerPart = floor($this->period);
            $fractionalPart = $this->period - $integerPart;

            // Convert the date_joined to a Carbon instance
            $startDate = Carbon::parse($this->date_joined);

            // Add the integer part of the period in years to the start date
            $endDate = $startDate->copy()->addYears($integerPart);

            // Add the fractional part of the period in months to the start date
            $endDate->addMonths((int)($fractionalPart * 12));

            // Return the resulting date
            return $endDate;
        } else {
            return null;
        }
    }
}
