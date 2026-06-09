<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PublicHoliday;
use Carbon\Carbon;



class PublicHolidayController extends Controller
{
    public function index()
    {
        $holidays = PublicHoliday::pluck('holiday_date')->toArray();
        $history = PublicHoliday::orderBy('holiday_date', 'desc')->simplePaginate(10);

        return view('admin.calender', compact('holidays', 'history'));
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'holidays' => [
                'required',
                'regex:/^\d{4}-\d{2}-\d{2}(\s*-\s*\d{4}-\d{2}-\d{2})?$/' // Ensures correct format YYYY-MM-DD
            ],
            'description' => 'required|string|max:255', // Ensure description is required
        ]);
    
        $dateRange = explode(' - ', $request->holidays);
        $startDate = Carbon::parse($dateRange[0]);
        $endDate = Carbon::parse($dateRange[1] ?? $dateRange[0]); // Handle single date selection
    
        $tomorrow = Carbon::tomorrow();
        $yearEnd = Carbon::now()->endOfYear();
    
        if ($startDate->lt($tomorrow) || $endDate->gt($yearEnd)) {
            return redirect()->back()->withErrors(['holidays' => 'Dates must be between tomorrow and the end of the current year.']);
        }
    
        while ($startDate->lte($endDate)) { // Loop through each date in the range
            PublicHoliday::updateOrCreate(
                ['holiday_date' => $startDate->toDateString()],
                [
                    'description' => $request->description,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            $startDate->addDay(); // Move to the next day
        }
    
        return redirect()->back()->with('success', 'Public holidays saved successfully!');
    }


    public function destroy($id)
    {
        $holiday = PublicHoliday::find($id);
    
        if ($holiday) {
            $holiday->delete();
            return redirect()->back()->with('error', 'Holiday deleted successfully!');
        }
    
        return redirect()->back()->with('error', 'Holiday not found!');
    }
}
