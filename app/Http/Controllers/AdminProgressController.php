<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
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
                    DB::table('attendances')->insert($inserts);
                    $inserts = [];
                }
            }
        }
        $date->addDay();
    }
}

if (!empty($inserts)) {
    DB::table('attendances')->insert($inserts);
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
$summaries = DB::table('attendances')
    ->select(
        'associate_id',
        DB::raw('SUM(CASE WHEN is_present = 1 THEN 1 ELSE 0 END) as total_presents'),
        DB::raw('SUM(CASE WHEN is_leave = 1 AND leave_approval = 1 THEN 1 ELSE 0 END) as total_leaves'),
        DB::raw("SUM(CASE
            WHEN is_present = 0
            AND is_leave = 0
            AND DAYOFWEEK(date) NOT IN (1,7)
            AND date NOT IN ($publicHolidayList)
            AND date <= '$todayStr'
            THEN 1 ELSE 0 END) as total_absents")
    )
    ->whereDate('date', '<=', $todayStr)
    ->groupBy('associate_id')
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





public function breakup($id)
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
        ->whereDate('date', '<=', $currentDate)
        ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'), DB::raw('MONTHNAME(date)'))
        ->orderByDesc('year')
        ->orderByDesc('month_num')
        ->get();

    return view('admin.breakup', compact('records', 'associate'));
}

public function monthDetails($id, $year, $month)
{
    $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
    $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

    $records = DB::table('attendances')
        ->join('associates', 'attendances.associate_id', '=', 'associates.id')
        ->leftJoin('public_holidays', 'attendances.date', '=', 'public_holidays.holiday_date')
        ->select(
            'attendances.date',
            'attendances.is_present',
            'attendances.is_leave',
            'attendances.leave_approval',
            'attendances.reason_for_leave',
            'public_holidays.holiday_date',
            'public_holidays.description as holiday_description'
        )
        ->where('attendances.associate_id', $id)
        ->whereBetween('attendances.date', [$startDate, $endDate])
        ->orderBy('attendances.date')
        ->get();

    return view('admin.month-details', compact('records'));
}



}
