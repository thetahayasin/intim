<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Associate;

class AdminLeaveController extends Controller
{
    public function index()
    {
        // Pre-fetch public holiday dates as a plain array — most reliable approach
        $publicHolidays = \DB::table('public_holidays')
                             ->pluck('holiday_date')
                             ->toArray();
    
        // Pending leaves, excluding public holidays
        $leaves = Attendance::with('associate')
                    ->where('is_leave', true)
                    ->where('leave_approval', false)
                    ->whereNotIn('date', $publicHolidays)  // plain array, no subquery closure
                    ->orderBy('date', 'asc')
                    ->get()
                    ->groupBy('associate_id')
                    ->map(function ($group, $associateId) {
                        return [
                            'associate_id'   => $associateId,
                            'associate_name' => $group->first()->associate->name,
                            'leave_count'    => $group->count(), // now guaranteed to exclude holidays
                            'leaves'         => $group,
                        ];
                    });
    
        // Leave history (approved), excluding public holidays
        $history = Attendance::with('associate')
                    ->whereIn('is_leave', [0, 1])
                    ->where('leave_approval', 1)
                    ->whereNotIn('date', $publicHolidays)  // reuse the same array
                    ->selectRaw('associate_id, COUNT(*) as total_leaves')
                    ->groupBy('associate_id')
                    ->orderBy('total_leaves', 'desc')
                    ->paginate(30);
    
        return view('admin.approvals', compact('leaves', 'history'));
    }

    //approve
    public function approveAllLeaves(Request $request, $id)
    {
        // Retrieve all leaves for the given associate
        $leaves = Attendance::where('associate_id', $id)->where('is_leave', true)->where('leave_approval', false)->get();

        // Update the approval status of each leave
        foreach ($leaves as $leave) {
            $leave->update(['leave_approval' => true]);
        }

        // Redirect back or to any desired route
        return redirect()->back()->with('success', 'Leave approved successfully.');
    }
public function viewLeaveDates($id)
{
    $publicHolidays = \DB::table('public_holidays')
                         ->pluck('holiday_date')
                         ->toArray();

    // Retrieve leave dates and reasons, excluding public holidays
    $leaveData = Attendance::where('associate_id', $id)
        ->where('is_leave', true)
        ->where('leave_approval', false)
        ->whereNotIn('date', $publicHolidays)
        ->select('id', 'date', 'reason_for_leave')
        ->get();

    $leaveDates = $leaveData->pluck('date')->toArray();
    $reasons    = $leaveData->pluck('reason_for_leave')->toArray();
    $leaveId    = $leaveData->pluck('id')->toArray();

    $associate = Associate::findOrFail($id);

    return view('admin.viewleave', compact('associate', 'leaveDates', 'reasons', 'leaveId'));
}

    //reject
    public function reject(Request $request, $id)
    {
        // Retrieve all leaves for the given associate
        $leaves = Attendance::where('associate_id', $id)->where('is_leave', true)->where('leave_approval', false)->get();

        // Update the approval status of each leave
        foreach ($leaves as $leave) {
            $leave->is_leave = false;
            $leave->leave_approval = 2;
            $leave->update();
        }

        // Redirect back or to any desired route
        return redirect()->back()->with('error', 'Leave Rejected Successfully.');
    }

    //single reject
    public function sreject(Request $request, $id)
    {
        // Retrieve all leaves for the given associate
        $leave = Attendance::findOrFail($id);

        // Update the approval status of each leave
        $leave->is_leave = false;
        $leave->leave_approval = 2;
        $leave->update();

        // Redirect back or to any desired route
        return redirect()->back()->with('error', 'Leave Rejected Successfully.');
    }

    //single approve
    public function sapprove(Request $request, $id)
    {
        $leave = Attendance::findOrFail($id);

        // Update the approval status of each leave
        $leave->update(['leave_approval' => true]);

        // Redirect back or to any desired route
        return redirect()->back()->with('success', 'Leave approved successfully.');
    }
    
    public function show($associate_id)
    {
        $associate = Associate::findOrFail($associate_id);
        
        $leaves = Attendance::where('associate_id', $associate_id)
                    ->whereIn('is_leave', [1, 0]) // Include both leave and non-leave records
                    ->whereIn('leave_approval', [1, 2]) // Approved or rejected leave
                    ->whereNotIn('date', function($query) {
                        $query->select('holiday_date')
                              ->from('public_holidays');
                    })
                    ->orderBy('date', 'desc')
                    ->simplePaginate(20);
        
        return view('admin.showleave', compact('associate', 'leaves'));
    }
}
