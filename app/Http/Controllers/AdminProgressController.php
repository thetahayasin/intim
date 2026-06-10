<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AdminProgressController extends Controller
{
    public function index()
    {

// Step 1: Define today's date
$today = Carbon::today();

// Step 2: Get active associates with created_at
$associates = DB::table('associates')
    ->where('active', 1)
    ->select('id', 'created_at')
    ->get();

if ($associates->isEmpty()) {
    return redirect()->back()->with('error', 'No active associates found.');
}

// Step 3: Get public holidays between earliest created_at and today
$publicHolidays = DB::table('public_holidays')
    ->whereBetween('holiday_date', [$associates->min('created_at'), $today])
    ->pluck('holiday_date')
    ->toArray();

// Step 4: Get existing attendance records
$existingRecords = DB::table('attendances')
    ->whereBetween('date', [$associates->min('created_at'), $today])
    ->select('associate_id', 'date')
    ->get()
    ->groupBy('associate_id');

// Step 5: Insert missing attendance records
$batchSize = 1000;
$inserts = [];

foreach ($associates as $associate) {
    $startDate = Carbon::parse($associate->created_at);
    $existingDates = isset($existingRecords[$associate->id])
        ? $existingRecords[$associate->id]->pluck('date')->toArray()
        : [];

    $date = $startDate->copy();
    while ($date <= $today) {
        if ($date->isWeekday() && !in_array($date->format('Y-m-d'), $publicHolidays)) {
            if (!in_array($date->format('Y-m-d'), $existingDates)) {
                $inserts[] = [
                    'associate_id' => $associate->id,
                    'date' => $date->format('Y-m-d'),
                    'is_present' => 0,
                    'is_leave' => 0,
                    'leave_approval' => 0,
                    'work_hours' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($inserts) >= $batchSize) {
                    DB::table('attendances')->insertOrIgnore($inserts);
                    $inserts = [];
                }
            }
        }
        $date->addDay();
    }
}

if (!empty($inserts)) {
    DB::table('attendances')->insertOrIgnore($inserts);
}

// Step 6: Get all associates
$allAssociates = DB::table('associates')
    ->select('id', 'name', 'crn', 'opening_presents', 'opening_leaves', 'opening_absents', 'active')
    ->orderByDesc('active')
    ->orderBy('name')
    ->get();

$publicHolidays = DB::table('public_holidays')->pluck('holiday_date')->toArray();
$publicHolidayList = empty($publicHolidays)
    ? "'1970-01-01'"
    : implode(',', array_map(fn($d) => "'$d'", $publicHolidays));
$todayStr = $today->format('Y-m-d');

// Step 7: Sum attendance per associate (same logic as associate side)
// Only count from 2025-03-31 (system go-live) or associate's created_at, whichever is later
$summaries = DB::table('attendances')
    ->join('associates', 'attendances.associate_id', '=', 'associates.id')
    ->select(
        'attendances.associate_id',
        DB::raw('SUM(CASE WHEN attendances.is_present = 1 THEN 1 ELSE 0 END) as total_presents'),
        DB::raw('SUM(CASE WHEN attendances.is_leave = 1 AND attendances.leave_approval = 1 THEN 1 ELSE 0 END) as total_leaves'),
        DB::raw("SUM(CASE
            WHEN attendances.is_present = 0
            AND attendances.is_leave = 0
            AND DAYOFWEEK(attendances.date) NOT IN (1,7)
            AND attendances.date NOT IN ($publicHolidayList)
            AND attendances.date <= '$todayStr'
            THEN 1 ELSE 0 END) as total_absents")
    )
    ->whereRaw("attendances.date >= GREATEST(DATE(associates.created_at), '2025-03-31')")
    ->whereDate('attendances.date', '<=', $todayStr)
    ->groupBy('attendances.associate_id')
    ->get()
    ->keyBy('associate_id');

// Step 8: Merge summaries into associates list
$attendanceData = $allAssociates->map(function ($assoc) use ($summaries) {
    $s = $summaries->get($assoc->id);
    $assoc->total_presents = $s->total_presents ?? 0;
    $assoc->total_leaves   = $s->total_leaves   ?? 0;
    $assoc->total_absents  = $s->total_absents  ?? 0;
    return $assoc;
});

// Step 9: Return view with records
return view('admin.progress', ['records' => $attendanceData]);

    }





public function breakup(int $id)
{
    $currentDate = now()->toDateString();

    $associate = DB::table('associates')->where('id', $id)->first();

    if (!$associate) {
        return redirect()->route('e.progress')->with('error', 'Associate not found.');
    }

    $publicHolidays = DB::table('public_holidays')->pluck('holiday_date')->toArray();

    $publicHolidayList = empty($publicHolidays)
        ? "'1970-01-01'"
        : implode(',', array_map(fn($d) => "'$d'", $publicHolidays));

    $systemStart = '2025-03-31';
    $joinDate    = substr($associate->created_at, 0, 10);
    $effectiveStart = $joinDate > $systemStart ? $joinDate : $systemStart;

    $records = DB::table('attendances')
        ->select(
            DB::raw('YEAR(date) as year'),
            DB::raw('MONTH(date) as month_num'),
            DB::raw('MONTHNAME(date) as month'),
            DB::raw('SUM(CASE WHEN is_present = 1 THEN 1 ELSE 0 END) as total_presents'),
            DB::raw('SUM(CASE WHEN is_leave = 1 AND leave_approval = 1 THEN 1 ELSE 0 END) as total_leaves'),
            DB::raw("SUM(CASE
                WHEN is_present = 0
                AND is_leave = 0
                AND DAYOFWEEK(date) NOT IN (1,7)
                AND date NOT IN ($publicHolidayList)
                AND date <= '$currentDate'
                THEN 1 ELSE 0 END) as total_absents"),
            DB::raw('SUM(work_hours) as total_work_hours')
        )
        ->where('associate_id', $id)
        ->whereDate('date', '>=', $effectiveStart)
        ->whereDate('date', '<=', $currentDate)
        ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'), DB::raw('MONTHNAME(date)'))
        ->orderByDesc('year')
        ->orderByDesc('month_num')
        ->get();

    return view('admin.breakup', compact('records', 'associate'));
}

public function monthDetails(int $id, int $year, int $month)
{
    $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
    $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

    $records = DB::table('attendances')
        ->join('associates', 'attendances.associate_id', '=', 'associates.id')
        ->leftJoin('public_holidays', 'attendances.date', '=', 'public_holidays.holiday_date')
        ->select(
            'attendances.id',
            'attendances.date',
            'attendances.is_present',
            'attendances.is_leave',
            'attendances.leave_approval',
            'attendances.reason_for_leave',
            'attendances.work_hours',
            'public_holidays.holiday_date',
            'public_holidays.description as holiday_description'
        )
        ->where('attendances.associate_id', $id)
        ->whereBetween('attendances.date', [$startDate, $endDate])
        ->orderBy('attendances.date')
        ->get();

    return view('admin.month-details', [
        'records'     => $records,
        'associateId' => $id,
        'year'        => $year,
        'month'       => $month,
    ]);
}

public function updateDayAttendance(Request $request, int $id)
{
    $attendance = DB::table('attendances')->where('id', $id)->first();

    if (!$attendance) {
        return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
    }

    $request->validate([
        'status'           => 'required|in:present,absent,leave',
        'leave_approval'   => 'nullable|in:0,1,2',
        'reason_for_leave' => 'nullable|string|max:500',
        'work_hours'       => 'nullable|integer|min:0|max:24',
    ]);

    $status        = $request->input('status');
    $isPresent     = $status === 'present' ? 1 : 0;
    $isLeave       = $status === 'leave'   ? 1 : 0;
    $leaveApproval = $isLeave ? (int) $request->input('leave_approval', 0) : 0;
    $reason        = $isLeave ? $request->input('reason_for_leave') : null;
    $workHours     = $request->filled('work_hours') ? (int) $request->input('work_hours') : null;

    DB::table('attendances')->where('id', $id)->update([
        'is_present'       => $isPresent,
        'is_leave'         => $isLeave,
        'leave_approval'   => $leaveApproval,
        'reason_for_leave' => $reason,
        'work_hours'       => $workHours,
        'updated_at'       => now(),
    ]);

    return response()->json([
        'success'          => true,
        'is_present'       => $isPresent,
        'is_leave'         => $isLeave,
        'leave_approval'   => $leaveApproval,
        'reason_for_leave' => $reason,
        'work_hours'       => $workHours,
    ]);
}



}
