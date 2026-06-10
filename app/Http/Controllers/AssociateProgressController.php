<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\PublicHoliday;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;



class AssociateProgressController extends Controller
{
    public function index()
    {
// Get associate
$associate = Auth::guard('associate')->user();
$associateId = $associate->id;
$createdAt = $associate->created_at->copy()->startOfDay();  // ✅ Use associate's created_at
$currentDate = now()->toDateString();

// Fetch public holidays
$publicHolidays = DB::table('public_holidays')->pluck('holiday_date')->toArray();

// Insert missing attendance records
$startDate = $createdAt->copy();  // ✅ Start from created_at
$endDate = Carbon::today();

// Step 1: Generate all dates (excluding weekends)
$allDates = [];
$dateIterator = $startDate->copy();

while ($dateIterator <= $endDate) {
    if ($dateIterator->isWeekday()) {
        $allDates[] = $dateIterator->format('Y-m-d');
    }
    $dateIterator->addDay();
}

// Step 2: Fetch existing attendance records
$existingDates = DB::table('attendances')
    ->where('associate_id', $associateId)
    ->whereDate('date', '<=', $endDate)
    ->pluck('date')
    ->toArray();

// Step 3: Identify missing dates
$missingDates = array_diff($allDates, $existingDates);

// Step 4: Insert missing dates (excluding public holidays)
foreach ($missingDates as $missingDate) {
    if (!in_array($missingDate, $publicHolidays)) {
        DB::table('attendances')->insert([
            'associate_id' => $associateId,
            'date' => $missingDate,
            'is_present' => 0,
            'is_leave' => 0,
            'leave_approval' => 0,
            'work_hours' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

// Step 5: Attendance Progress by Months (Excluding Public Holidays)
$workHoursByMonth = DB::table('attendances')
    ->select(
        DB::raw('YEAR(date) as year'),
        DB::raw('MONTH(date) as month'),
        DB::raw('SUM(CASE WHEN is_present = 1 THEN 1 ELSE 0 END) as total_presents'),
        DB::raw('SUM(CASE WHEN is_leave = 1 THEN 1 ELSE 0 END) as total_leaves'),
        DB::raw('SUM(CASE WHEN is_leave = 1 AND leave_approval = 1 THEN 1 ELSE 0 END) as total_approved_leaves'),
        DB::raw('SUM(CASE WHEN is_leave = 1 AND leave_approval = 1 THEN work_hours ELSE 0 END) as total_approved_leave_hours'),
        DB::raw('SUM(
            CASE 
                WHEN is_present = 0 
                AND is_leave = 0 
                AND DAYOFWEEK(date) NOT IN (1,7) 
                AND date NOT IN ("' . implode('","', $publicHolidays) . '") 
            THEN 1 ELSE 0 
            END
        ) as total_absents'),
        DB::raw('SUM(work_hours) as total_work_hours')
    )
    ->where('associate_id', $associateId)
    ->whereDate('date', '>=', max($createdAt->toDateString(), '2025-03-31'))
    ->whereDate('date', '<=', $currentDate)
    ->groupBy('year', 'month')
    ->orderBy('year', 'desc')
    ->orderBy('month', 'desc')
    ->get();

return view('associate.progress', compact('workHoursByMonth', 'associate'));


    }

    public function downloadReport($year, $month)
    {
        // Get associate ID
        $associateId = Auth::guard('associate')->user()->id;
        $currentDate = now()->toDateString();
        
        // Fetch public holidays for the given month and year
        $publicHolidays = DB::table('public_holidays')
            ->whereYear('holiday_date', $year)
            ->whereMonth('holiday_date', $month)
            ->pluck('holiday_date')
            ->toArray();
        
        // Retrieve data for the specified month and year for the authenticated associate
        $workHoursByMonth = DB::table('attendances')
            ->select(
                'work_done',
                DB::raw('SUM(CASE WHEN is_present = 1 THEN 1 ELSE 0 END) as total_presents'),
                DB::raw('SUM(CASE WHEN is_leave = 1 AND leave_approval = 1 THEN 1 ELSE 0 END) as total_approved_leaves'),
                DB::raw('SUM(work_hours) as total_work_hours'),
                DB::raw('SUM(
                    CASE 
                        WHEN is_present = 0 
                        AND is_leave = 0 
                        AND DAYOFWEEK(date) NOT IN (1,7) 
                        AND date NOT IN ("' . implode('","', $publicHolidays) . '") 
                    THEN 1 ELSE 0 
                    END
                ) as total_absents')
            )
            ->where('associate_id', $associateId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereDate('date', '<=', $currentDate) // Include only records up to today
            ->groupBy('work_done')
            ->get();
        
        // Calculate totals
        $totalPresents = $workHoursByMonth->sum('total_presents');
        $totalLeaves = $workHoursByMonth->sum('total_approved_leaves');
        $totalWorkHours = $workHoursByMonth->sum('total_work_hours');
        $totalAbsents = $workHoursByMonth->sum('total_absents');
        
        // Pass the data to the view
        return view('associate.report', compact('workHoursByMonth', 'year', 'month', 'totalPresents', 'totalLeaves', 'totalWorkHours', 'totalAbsents'));

    }

}
