@extends('admin.main')

@section('title', 'Monthly Detailed Attendance')

@section('content')

<style>
    .badge {
        font-size: 15px !important;
        font-weight: 700 !important;
        padding: 8px 16px !important;
    }

    .big-text {
        font-size: 16px;
        font-weight: 600;
    }

    .na-badge {
        background: #6c757d;
        color: white;
    }
</style>

<div class="container-fluid">

    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left"></i> Back
    </a>

    <div class="card shadow">
        <div class="card-body">

            <h4 class="card-title mb-4 fw-bold">Date-wise Attendance</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Present</th>
                            <th>Leave</th>
                            <th>Leave Approval</th>
                            <th>Absent</th>
                            <th>Final Status</th>
                            <th>Reason</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($records as $row)

                            @php
                                $date = \Carbon\Carbon::parse($row->date);
                                $dayName = $date->format('l');

                                $isWeekend = $date->isWeekend();
                                $isPublicHoliday = !empty($row->holiday_date);

                                $isPresent = $row->is_present == 1 && $row->is_leave == 0;
                                $isLeave   = $row->is_leave == 1;

                                $isApproved = $row->leave_approval == 1;
                                $isRejected = $row->leave_approval == 2;

                                $isAbsent = !$isPresent && !$isLeave;
                                $isUnauthorizedLeave = !$isPresent && $isLeave && !$isApproved;
                            @endphp

                            <tr class="
                                {{ $isPublicHoliday ? 'bg-primary text-black' : 
                                   ($isWeekend ? 'bg-dark text-white' : 
                                   ($isAbsent ? 'table-danger' : 
                                   ($isUnauthorizedLeave ? 'bg-black text-white' : 
                                   ($isPresent ? 'table-success' : '')))) }}">

                                {{-- Date --}}
                                <td class="big-text bg-success">
                                    <span class="text-dark">{{ $date->format('d M Y') }}</span>
                                    <br>
                                    <small class="text-secondary">{{ $dayName }}</small>

                                    @if($isPublicHoliday)
                                        <br>
                                        <span class="badge bg-warning text-dark mt-2">
                                            {{ strtoupper($row->holiday_description) }}
                                        </span>
                                    @elseif($isWeekend)
                                        <br>
                                        <span class="badge bg-light text-dark border border-dark mt-2">
                                            WEEKEND
                                        </span>
                                    @endif
                                </td>

                                {{-- PUBLIC HOLIDAY LOGIC --}}
                                @if($isPublicHoliday)

                                    <td><span class="badge na-badge">NA</span></td>
                                    <td><span class="badge na-badge">NA</span></td>
                                    <td><span class="badge na-badge">NA</span></td>
                                    <td><span class="badge na-badge">NA</span></td>

                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            PUBLIC HOLIDAY
                                        </span>
                                    </td>

                                    <td>-</td>

                                @else

                                    {{-- Present --}}
                                    <td>
                                        @if($row->is_present == 1)
                                            <span class="badge bg-success">YES</span>
                                        @else
                                            <span class="badge bg-danger">NO</span>
                                        @endif
                                    </td>

                                    {{-- Leave --}}
                                    <td>
                                        @if($row->is_leave == 1)
                                            <span class="badge bg-primary">YES</span>
                                        @else
                                            <span class="badge bg-danger">NO</span>
                                        @endif
                                    </td>

                                    {{-- Leave Approval --}}
                                    <td>
                                        @if($isApproved)
                                            <span class="badge bg-success">APPROVED</span>
                                        @elseif($isRejected)
                                            <span class="badge bg-danger">REJECTED</span>
                                        @elseif($isLeave)
                                            <span class="badge bg-warning text-dark">PENDING</span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Absent --}}
                                    <td>
                                        @if($isAbsent)
                                            <span class="badge bg-danger">YES</span>
                                        @elseif($isUnauthorizedLeave)
                                            <span class="badge bg-dark">UNAUTHORIZED</span>
                                        @else
                                            <span class="badge bg-success">NO</span>
                                        @endif
                                    </td>

                                    {{-- FINAL STATUS (Correct Priority) --}}
                                    <td>

                                        {{-- 1. Present (even on weekend) --}}
                                        @if($isPresent)
                                            <span class="badge bg-success">
                                                PRESENT
                                            </span>

                                        {{-- 2. Weekend (only if NOT present) --}}
                                        @elseif($isWeekend)
                                            <span class="badge bg-light text-dark border border-dark">
                                                WEEKEND
                                            </span>

                                        {{-- 3. Approved Leave --}}
                                        @elseif($isLeave && $isApproved)
                                            <span class="badge bg-primary">
                                                APPROVED LEAVE
                                            </span>

                                        {{-- 4. Rejected Leave --}}
                                        @elseif($isLeave && $isRejected)
                                            <span class="badge bg-danger">
                                                REJECTED LEAVE
                                            </span>

                                        {{-- 5. Unauthorized Leave --}}
                                        @elseif($isUnauthorizedLeave)
                                            <span class="badge bg-dark">
                                                UNAUTHORIZED LEAVE
                                            </span>

                                        {{-- 6. Pending Leave --}}
                                        @elseif($isLeave)
                                            <span class="badge bg-warning text-dark">
                                                PENDING LEAVE
                                            </span>

                                        {{-- 7. Absent --}}
                                        @elseif($isAbsent)
                                            <span class="badge bg-danger">
                                                ABSENT
                                            </span>

                                        @else
                                            -
                                        @endif

                                    </td>

                                    {{-- Reason --}}
                                    <td class="big-text">
                                        {{ $row->reason_for_leave ?? '-' }}
                                    </td>

                                @endif

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="fw-bold text-center">
                                    No attendance records found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection