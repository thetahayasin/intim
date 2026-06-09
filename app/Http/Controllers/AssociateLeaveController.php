<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\PublicHoliday;
use Illuminate\Support\Facades\DB;


class AssociateLeaveController extends Controller
{
    private function getPublicHolidayDates(): array
    {
        return DB::table('public_holidays')->pluck('holiday_date')->toArray();
    }

    public function index()
    {
        $associateId = Auth::guard('associate')->user()->id;
        $publicHolidayDates = $this->getPublicHolidayDates();

        // Get associate's leave dates (excluding public holidays and rejected leaves)
        $leaveDates = Attendance::where('associate_id', $associateId)
                                ->where('is_leave', true)
                                ->whereNotIn('leave_approval', [2])
                                ->whereNotIn('date', $publicHolidayDates)
                                ->pluck('date')
                                ->map(function ($date) {
                                    return Carbon::parse($date)->format('m/d/Y');
                                })
                                ->toArray();

        // Get pending leave requests (excluding public holidays)
        $pendingLeaves = Attendance::where('associate_id', $associateId)
                                    ->where('is_leave', true)
                                    ->where('leave_approval', 0)
                                    ->whereNotIn('date', $publicHolidayDates)
                                    ->get();

        // Get leave history (approved or rejected) excluding public holidays
        $history = Attendance::where('associate_id', $associateId)
                             ->whereIn('is_leave', [0, 1])
                             ->whereIn('leave_approval', [1, 2])
                             ->whereNotIn('date', $publicHolidayDates)
                             ->orderBy('date', 'desc')
                             ->limit(30)
                             ->get();

        // Format public holidays for the frontend calendar
        $publicHolidays = collect($publicHolidayDates)
                            ->map(function ($date) {
                                return Carbon::parse($date)->format('m/d/Y');
                            })
                            ->toArray();

        return view('associate.leave', compact('leaveDates', 'pendingLeaves', 'history', 'publicHolidays'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'daterange' => [
                'required',
                'regex:/^\d{2}\/\d{2}\/\d{4}(\s*-\s*\d{2}\/\d{2}\/\d{4})?$/',
                function ($attribute, $value, $fail) {
                    if (strpos($value, ' - ') !== false) {
                        [$startDate, $endDate] = explode(' - ', $value);
                        $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
                        $endDate   = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

                        if (Carbon::now()->startOfDay()->greaterThan($startDate)) {
                            $fail('Start date cannot be a past date.');
                        }
                        if ($endDate->lessThan($startDate)) {
                            $fail('End date cannot be before start date.');
                        }
                    } else {
                        $date = Carbon::createFromFormat('m/d/Y', $value)->startOfDay();
                        if (Carbon::now()->startOfDay()->greaterThan($date)) {
                            $fail('Date cannot be a past date.');
                        }
                    }
                },
            ],
            'reason' => 'required|max:255',
        ]);

        if (strpos($request->daterange, ' - ') !== false) {
            [$startDate, $endDate] = explode(' - ', $request->daterange);
        } else {
            $startDate = $endDate = $request->daterange;
        }

        $startDate = Carbon::createFromFormat('m/d/Y', $startDate)->startOfDay();
        $endDate   = Carbon::createFromFormat('m/d/Y', $endDate)->endOfDay();

        $associateId = Auth::guard('associate')->user()->id;

        // Fetch public holidays within the selected date range only
        $publicHolidays = DB::table('public_holidays')
            ->whereBetween('holiday_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->pluck('holiday_date')
            ->toArray();

        // Pre-validate the ENTIRE date range before saving anything
        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekend()) {
                continue;
            }

            // Silently skip public holidays
            if (in_array($date->toDateString(), $publicHolidays)) {
                continue;
            }

            $attendance = Attendance::where('associate_id', $associateId)
                ->where('date', $date->toDateString())
                ->first();

            if ($attendance) {
                if ($attendance->is_present == 1) {
                    return redirect()->route('ass.leave')->with('error', 'Date marked as present: ' . $date->format('M d, Y'));
                }

                if ($attendance->is_leave == 1 && in_array($attendance->leave_approval, [0, 1])) {
                    return redirect()->route('ass.leave')->with('error', 'Leave already applied or approved for ' . $date->format('M d, Y'));
                }
            }
        }

        // All dates passed — now safe to save
        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekend()) {
                continue;
            }

            // Silently skip public holidays
            if (in_array($date->toDateString(), $publicHolidays)) {
                continue;
            }

            $attendance = Attendance::firstOrNew([
                'associate_id' => $associateId,
                'date'         => $date->toDateString(),
            ]);

            $attendance->is_leave         = true;
            $attendance->reason_for_leave = $request->reason;
            $attendance->leave_approval   = 0;
            $attendance->save();
        }

        return redirect()->route('ass.leave')->with('success', 'Leave Application Submitted');
    }

    public function cancel(Request $request, $id)
    {
        $l = Attendance::where('id', $id)->first();
        $l->is_leave = 0;
        $l->update();

        return redirect()->route('ass.leave')->with('success', 'Leave Cancelled');
    }
}