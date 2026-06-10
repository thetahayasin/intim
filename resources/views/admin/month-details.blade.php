@extends('admin.main')

@section('title', 'Monthly Detailed Attendance')

@section('content')

<div class="col-md-12 container-fluid">

    <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left"></i> Back
    </a>

    <div class="card">
        <div class="card-header">
            <strong class="card-title"><i class="fe fe-calendar fe-16 mr-1"></i> Date-wise Attendance</strong>
        </div>
        <div class="cds-table-wrap">
            <table class="table table-hover text-center mb-0" style="min-width:700px;">
                <thead>
                    <tr>
                        <th class="text-left">Date</th>
                        <th>Present</th>
                        <th>Leave</th>
                        <th>Approval</th>
                        <th>Absent</th>
                        <th>Final Status</th>
                        <th class="text-left">Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $row)
                    @php
                        $date     = \Carbon\Carbon::parse($row->date);
                        $dayName  = $date->format('l');
                        $isWeekend        = $date->isWeekend();
                        $isHoliday        = !empty($row->holiday_date);
                        $isPresent        = $row->is_present == 1 && $row->is_leave == 0;
                        $isLeave          = $row->is_leave == 1;
                        $isApproved       = $row->leave_approval == 1;
                        $isRejected       = $row->leave_approval == 2;
                        $isAbsent         = !$isPresent && !$isLeave && !$isWeekend && !$isHoliday;
                        $isUnauthorized   = !$isPresent && $isLeave && !$isApproved && !$isRejected;
                    @endphp
                    <tr class="{{ ($isWeekend || $isHoliday) ? 'cds-opening-row' : '' }}">

                        {{-- Date --}}
                        <td class="text-left">
                            <strong>{{ $date->format('d M Y') }}</strong>
                            <br>
                            <small class="text-muted">{{ $dayName }}</small>
                            @if($isHoliday)
                                <br><span class="cds-status-tag--done cds-status-tag" style="margin-top:3px;display:inline-flex;">
                                    {{ strtoupper($row->holiday_description) }}
                                </span>
                            @elseif($isWeekend)
                                <br><span class="cds-status-tag" style="margin-top:3px;display:inline-flex;">Weekend</span>
                            @endif
                        </td>

                        @if($isHoliday || $isWeekend)
                            <td class="text-muted">—</td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">—</td>
                            <td>
                                @if($isHoliday)
                                    <span class="cds-status-tag cds-status-tag--done">Public Holiday</span>
                                @else
                                    <span class="cds-status-tag">Weekend</span>
                                @endif
                            </td>
                            <td>—</td>
                        @else
                            {{-- Present --}}
                            <td>
                                @if($row->is_present == 1)
                                    <span style="color:#161616; font-weight:600;">✓</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Leave --}}
                            <td>
                                @if($row->is_leave == 1)
                                    <span style="color:#161616; font-weight:600;">✓</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Leave Approval --}}
                            <td>
                                @if($isApproved)
                                    <span class="cds-status-tag cds-status-tag--done">Approved</span>
                                @elseif($isRejected)
                                    <span class="cds-status-tag">Rejected</span>
                                @elseif($isLeave)
                                    <span class="cds-status-tag">Pending</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Absent --}}
                            <td>
                                @if($isAbsent)
                                    <span style="color:#525252; font-weight:600;">✓</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Final Status --}}
                            <td>
                                @if($isPresent)
                                    <span class="cds-status-tag cds-status-tag--done">Present</span>
                                @elseif($isLeave && $isApproved)
                                    <span class="cds-status-tag cds-status-tag--done">Approved Leave</span>
                                @elseif($isLeave && $isRejected)
                                    <span class="cds-status-tag">Rejected Leave</span>
                                @elseif($isUnauthorized)
                                    <span class="cds-status-tag">Unauthorized Leave</span>
                                @elseif($isLeave)
                                    <span class="cds-status-tag">Pending Leave</span>
                                @elseif($isAbsent)
                                    <span class="cds-status-tag">Absent</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- Reason --}}
                            <td class="text-left text-muted" style="font-size:13px;">
                                {{ $row->reason_for_leave ?? '—' }}
                            </td>
                        @endif
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
