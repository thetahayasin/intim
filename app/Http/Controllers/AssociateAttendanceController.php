<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\PublicHoliday;

use Illuminate\Support\Facades\Auth;




class AssociateAttendanceController extends Controller
{
    public function index()
    {
        // Get associate ID
        $associateId = Auth::guard('associate')->user()->id;
    
        // Get the current date
        $currentDate = Carbon::today();
    
        // Get the last 7 days (including today)
        $startDate = $currentDate->copy()->subDays(6);
        
    

    
        // Fetch attendance records for the last 7 days
        $attendances = Attendance::where('associate_id', $associateId)
            ->whereBetween('date', [$startDate, $currentDate])
            ->get()
            ->keyBy('date'); // Index by date for easy lookup
    
        // Fetch public holidays with descriptions
        $holidays = PublicHoliday::whereBetween('holiday_date', [$startDate, $currentDate])
            ->get()
            ->keyBy('holiday_date');
    
        // Generate date range and mark holidays
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($currentDate); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $dates[] = [
                'date' => $formattedDate,
                'is_holiday' => $holidays->has($formattedDate),
                'description' => $holidays->has($formattedDate) ? $holidays[$formattedDate]->description : null,
            ];
        }
    
        return view('associate.attendance', compact('attendances', 'dates'));
    }


public function storeOrUpdate(Request $request)
{
    // Get associate ID
    $associateId = Auth::guard('associate')->user()->id;

    
    // Define custom error messages
    $customMessages = [
        'attendance.*.work_hours.integer' => 'Work hours must be a number',
        'attendance.*.work_hours.min' => 'Work hours must be at least :min',
        'attendance.*.work_hours.max' => 'Work hours must not exceed :max',
    ];

    // Validate data
    $request->validate([
        'attendance.*.work_hours' => 'integer|nullable|min:0|max:12',
    ], $customMessages);

    // Get all selected attendance dates
    $attendanceDates = array_keys($request->attendance);

    // Convert dates to proper format (Y-m-d) if necessary
    $formattedDates = array_map(fn($date) => Carbon::parse($date)->format('Y-m-d'), $attendanceDates);

    // Fetch public holidays in the correct format
    $holidays = PublicHoliday::whereIn('holiday_date', $formattedDates)
        ->pluck('holiday_date')
        ->map(fn($date) => Carbon::parse($date)->format('Y-m-d')) // Ensure format consistency
        ->toArray();

    // Find only those public holiday dates that are present in form data
    $invalidDates = array_filter($formattedDates, fn($date) => in_array($date, $holidays) && isset($request->attendance[$date]));

    // If any invalid dates are found in form submission, show error
    // if (count($invalidDates) > 0) {
    //     return redirect()->back()->with('error', 'Attendance cannot be marked on public holidays: ' . implode(', ', $invalidDates));
    // }

    // Loop through attendance data and save/update records
    foreach ($request->attendance as $date => $data) {
        $formattedDate = Carbon::parse($date)->format('Y-m-d'); // Ensure correct format

        // Convert checkbox value to 1 or 0
        $data['is_present'] = isset($data['is_present']) ? 1 : 0;

        // Check existing attendance record
        $attendance = Attendance::where('date', $formattedDate)
            ->where('associate_id', $associateId)
            ->first();

        if ($attendance) {
            // If attendance is not on leave or locked, update it
            if ($attendance->is_leave == 0 && $attendance->is_locked == 0) {
                $attendance->update($data);
            }
            continue; // Skip creating a new record if already exists
        }

        // If no record exists, create a new one
        Attendance::create([
            'associate_id' => $associateId,
            'is_present' => $data['is_present'],
            'work_done' => $data['work_done'] ?? null, // Avoid undefined index error
            'work_hours' => $data['work_hours'] ?? 0,   // Set default to prevent null issues
            'date' => $formattedDate,
        ]);
    }

    return redirect()->route('ass.attendance')->with('success', 'Attendance Updated Successfully');
}




    private function generateDateRange(Carbon $currentDate)
    {
        $dates = [];
        $startDate = $currentDate->copy()->subDays(6); // Get 15 days including today
    
        for ($date = $startDate; $date->lte($currentDate); $date->addDay()) {
            $dates[] = $date->copy();
        }
    
        return $dates;
    }
}
