@extends('associate.main')

@section('title', 'Asif Associates | Associate Dashboard')

@section('content')

<div class="col-md-12 container-fluid">

    @if($totalLeaves != 0 && $numberOfPresents != 0 && $numberOfPresents/$totalLeaves < 10)
        <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
            <i class="fe fe-info fe-16 mr-2"></i>
            Your Presence to Leave ratio is <strong>{{ round($numberOfPresents/$totalLeaves, 2) }}</strong>.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="row my-4">
        <div class="col-md-3 col-6">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Cumulative</small>
                            <div style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--cds-text-secondary);">Presents</div>
                            <div class="cds-stat-presents" style="font-size:1.9rem; font-weight:700; line-height:1.1; margin-top:4px;">{{ $numberOfPresents }}</div>
                        </div>
                        <i class="fe fe-user-check" style="font-size:1.75rem; color:var(--cds-border-strong);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Cumulative</small>
                            <div style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--cds-text-secondary);">Work Hours</div>
                            <div style="font-size:1.9rem; font-weight:700; line-height:1.1; margin-top:4px; color:var(--cds-text-primary);">{{ $totalWorkHours }}</div>
                        </div>
                        <i class="fe fe-clock" style="font-size:1.75rem; color:var(--cds-border-strong);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Cumulative</small>
                            <div style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--cds-text-secondary);">Leaves</div>
                            <div class="cds-stat-leaves" style="font-size:1.9rem; font-weight:700; line-height:1.1; margin-top:4px;">{{ $totalLeaves }}</div>
                        </div>
                        <i class="fe fe-coffee" style="font-size:1.75rem; color:var(--cds-border-strong);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Cumulative</small>
                            <div style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.06em; color:var(--cds-text-secondary);">Absents</div>
                            <div class="cds-stat-absents" style="font-size:1.9rem; font-weight:700; line-height:1.1; margin-top:4px;">{{ $numberOfAbsents }}</div>
                        </div>
                        <i class="fe fe-x-circle" style="font-size:1.75rem; color:var(--cds-border-strong);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Work Hours Breakdown + Ayat --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <strong class="card-title"><i class="fe fe-bar-chart-2 fe-16 mr-1"></i> Work Hours Breakdown</strong>
                </div>
                <div class="card-body">
                    @foreach ($workHoursBreakup as $item)
                    @php
                        $pct = $totalWorkHours > 0
                            ? round(($item->total_work_hours / $totalWorkHours) * 100, 1)
                            : 0;
                    @endphp
                    <div class="d-flex align-items-center mb-3" style="gap:12px;">
                        <div style="flex:1;min-width:0;">
                            <div class="d-flex justify-content-between mb-1">
                                <span style="font-size:13px;font-weight:600;color:var(--cds-text-primary);">
                                    {{ $item->work_done ?? 'Office Work' }}
                                </span>
                                <span style="font-size:12px;color:var(--cds-text-secondary);white-space:nowrap;margin-left:8px;">
                                    {{ $item->total_work_hours ?? 0 }}h &nbsp;·&nbsp; {{ $pct }}%
                                </span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body text-center" style="display:flex; align-items:center; justify-content:center; min-height:140px;">
                    <div>
                        <span class="ayat-text"><b>"the life of this world is no more than the delusion of enjoyment"</b></span>
                        <br>
                        <span class="text-muted mt-2 d-block"><i>Quran (3:185)</i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
