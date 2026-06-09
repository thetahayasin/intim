<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Models\PublicHoliday;





class AssociateDashboardController extends Controller
{
    public function index()
    {
// Get associate ID
$associate = Auth::guard('associate')->user();
$associateId = $associate->id;
$createdAt = $associate->created_at->toDateString();

$currentDate = now()->toDateString();

// Fetch public holidays
$publicHolidays = DB::table('public_holidays')->pluck('holiday_date')->toArray();

// Total approved leaves (excluding public holidays)
$totalLeaves = Attendance::where('associate_id', $associateId)
    ->where('is_leave', true)
    ->where('leave_approval', true)
    ->whereNotIn('date', $publicHolidays)
    ->count();
    
$totalLeaves = $totalLeaves + ($associate->opening_leaves ?? 0);

// Total work hours (excluding leaves)
$totalWorkHours = Attendance::where('associate_id', $associateId)
    ->where('is_leave', false)
    ->where('is_present', true)
    ->sum('work_hours');

// Total presents
$numberOfPresents = Attendance::where('associate_id', $associateId)
    ->where('is_present', true)
    ->where('is_leave', false)
    ->count();
$numberOfPresents = $numberOfPresents + ($associate->opening_presents ?? 0);

// Total absents (from created_at, excluding weekends and holidays)
$numberOfAbsents = Attendance::where('associate_id', $associateId)
    ->where('is_present', false)
    ->where('is_leave', false)
    ->whereDate('date', '<=', $currentDate)
    ->whereDate('date', '>=', $createdAt)
    ->whereRaw('DAYOFWEEK(date) NOT IN (1, 7)')
    ->whereNotIn('date', $publicHolidays)
    ->count();
    
$numberOfAbsents = $numberOfAbsents + ($associate->opening_absents ?? 0);


// Work hours breakup by work_done
$workHoursBreakup = Attendance::select('work_done', DB::raw('SUM(work_hours) as total_work_hours'))
    ->where('associate_id', $associateId)
    ->where('is_leave', false)
    ->where('is_present', true)
    ->groupBy('work_done')
    ->get();

return view('associate.home', compact('totalLeaves', 'totalWorkHours', 'numberOfPresents', 'workHoursBreakup', 'numberOfAbsents'));
    }

}
