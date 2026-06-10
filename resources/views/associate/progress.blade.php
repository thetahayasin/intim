@extends('associate.main')

@section('title', 'Asif Associates | Associate Progress')

@section('content')

<div class="col-md-12 container-fluid">

@php
    $totalP = $workHoursByMonth->sum('total_presents') + ($associate->opening_presents ?? 0);
    $totalL = $workHoursByMonth->sum('total_approved_leaves') + ($associate->opening_leaves ?? 0);
    $totalA = $workHoursByMonth->sum('total_absents') + ($associate->opening_absents ?? 0);
    $totalH = $workHoursByMonth->sum('total_work_hours');
@endphp

{{-- Cumulative stat cards --}}
<div class="row my-4">
    <div class="col-6 col-md-3">
        <div class="card mb-3">
            <div class="card-body">
                <small class="text-muted d-block mb-1">Cumulative</small>
                <div class="text-secondary" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.06em;">Presents</div>
                <div class="cds-stat-presents" style="font-size:2rem; font-weight:700; line-height:1.1; margin-top:4px;">{{ $totalP }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card mb-3">
            <div class="card-body">
                <small class="text-muted d-block mb-1">Cumulative</small>
                <div class="text-secondary" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.06em;">Leaves</div>
                <div class="cds-stat-leaves" style="font-size:2rem; font-weight:700; line-height:1.1; margin-top:4px;">{{ $totalL }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card mb-3">
            <div class="card-body">
                <small class="text-muted d-block mb-1">Cumulative</small>
                <div class="text-secondary" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.06em;">Absents</div>
                <div class="cds-stat-absents" style="font-size:2rem; font-weight:700; line-height:1.1; margin-top:4px;">{{ $totalA }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card mb-3">
            <div class="card-body">
                <small class="text-muted d-block mb-1">Cumulative</small>
                <div class="text-secondary" style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.06em;">Hours Worked</div>
                <div class="cds-stat-hours" style="font-size:2rem; font-weight:700; line-height:1.1; margin-top:4px;">{{ $totalH }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Monthly Breakdown Table --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong class="card-title"><i class="fe fe-calendar fe-16 mr-1"></i> Monthly Breakdown</strong>
            </div>
            <div class="cds-table-wrap">
                <table class="table table-hover att-table text-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-left">Period</th>
                            <th>Presents</th>
                            <th>Leaves</th>
                            <th>Absents</th>
                            <th>Hours</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workHoursByMonth as $record)
                            <tr>
                                <td class="text-left font-weight-bold">
                                    {{ date('F Y', mktime(0, 0, 0, $record->month, 1, $record->year)) }}
                                </td>
                                <td class="font-weight-bold cds-stat-presents">{{ $record->total_presents ?? 0 }}</td>
                                <td class="font-weight-bold cds-stat-leaves">{{ $record->total_approved_leaves ?? 0 }}</td>
                                <td class="font-weight-bold cds-stat-absents">{{ $record->total_absents ?? 0 }}</td>
                                <td class="font-weight-bold cds-stat-hours">{{ $record->total_work_hours ?? 0 }}</td>
                                <td>
                                    <a href="{{ route('ass.report', ['year' => $record->year, 'month' => $record->month]) }}"
                                       class="btn btn-secondary btn-sm">
                                        <i class="fe fe-eye fe-14"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        @if(($associate->opening_presents ?? 0) || ($associate->opening_leaves ?? 0) || ($associate->opening_absents ?? 0))
                        <tr class="cds-opening-row">
                            <td class="text-left font-weight-bold">Opening Balance</td>
                            <td class="font-weight-bold cds-stat-presents">{{ $associate->opening_presents ?? 0 }}</td>
                            <td class="font-weight-bold cds-stat-leaves">{{ $associate->opening_leaves ?? 0 }}</td>
                            <td class="font-weight-bold cds-stat-absents">{{ $associate->opening_absents ?? 0 }}</td>
                            <td>—</td>
                            <td>—</td>
                        </tr>
                        @endif

                        <tr class="cds-highlight-row" style="font-weight:700;">
                            <td class="text-left">Cumulative Total</td>
                            <td class="cds-stat-presents">{{ $totalP }}</td>
                            <td class="cds-stat-leaves">{{ $totalL }}</td>
                            <td class="cds-stat-absents">{{ $totalA }}</td>
                            <td class="cds-stat-hours">{{ $totalH }}</td>
                            <td>—</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>

@endsection
