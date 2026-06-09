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

{{-- Cumulative summary cards --}}
<div class="row my-4">
    <div class="col-6 col-md-3">
        <div class="card shadow text-center">
            <div class="card-body py-3">
                <small class="text-muted d-block">Total Presents</small>
                <span class="aa-color" style="font-size:1.5rem;font-weight:700;">{{ $totalP }}</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow text-center">
            <div class="card-body py-3">
                <small class="text-muted d-block">Total Leaves</small>
                <span class="aa-color" style="font-size:1.5rem;font-weight:700;">{{ $totalL }}</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow text-center">
            <div class="card-body py-3">
                <small class="text-muted d-block">Total Absents</small>
                <span class="aa-color" style="font-size:1.5rem;font-weight:700;">{{ $totalA }}</span>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow text-center">
            <div class="card-body py-3">
                <small class="text-muted d-block">Total Hours</small>
                <span class="aa-color" style="font-size:1.5rem;font-weight:700;">{{ $totalH }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body att-body">
                <h5 class="card-title">Monthly Breakdown</h5>
                <table class="table table-hover att-table text-center">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Presents</th>
                            <th>Leaves</th>
                            <th>Absents</th>
                            <th>Hours Worked</th>
                            <th>View/Print</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workHoursByMonth as $record)
                            <tr>
                                <td>{{ date('F Y', mktime(0, 0, 0, $record->month, 1, $record->year)) }}</td>
                                <td>{{ $record->total_presents ?? 0 }}</td>
                                <td>{{ $record->total_approved_leaves ?? 0 }}</td>
                                <td>{{ $record->total_absents ?? 0 }}</td>
                                <td>{{ $record->total_work_hours ?? 0 }}</td>
                                <td>
                                    <a href="{{ route('ass.report', ['year' => $record->year, 'month' => $record->month]) }}" class="btn btn-primary btn-sm">
                                        <i class="fe fe-eye fe-16"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @if(($associate->opening_presents ?? 0) || ($associate->opening_leaves ?? 0) || ($associate->opening_absents ?? 0))
                        <tr class="table-warning">
                            <td><strong>Opening Balance</strong></td>
                            <td>{{ $associate->opening_presents ?? 0 }}</td>
                            <td>{{ $associate->opening_leaves ?? 0 }}</td>
                            <td>{{ $associate->opening_absents ?? 0 }}</td>
                            <td>—</td>
                            <td>—</td>
                        </tr>
                        @endif
                        <tr style="background:#fff9e6; font-weight:700; border-top:2px solid #f4af1a;">
                            <td>Cumulative Total</td>
                            <td class="aa-color">{{ $totalP }}</td>
                            <td class="aa-color">{{ $totalL }}</td>
                            <td class="aa-color">{{ $totalA }}</td>
                            <td class="aa-color">{{ $totalH }}</td>
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


