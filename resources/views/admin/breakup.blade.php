@extends('admin.main')

@section('title', 'Asif Associates | Associate Progress Breakup')

@section('content')

<div class="col-md-12 container-fluid">
    <a wire:navigate href="{{ route('e.progress') }}" class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left fe-16"></i> Back
    </a>

    <div class="row">
        <div class="col-md-12 my-4">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">
                        <i class="fe fe-user fe-16 mr-1"></i> {{ $associate->name }} — Monthly Breakup
                    </strong>
                </div>
                <div class="cds-table-wrap">
                    @include('components.message')
                    <table class="table table-hover att-table text-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-left">Month</th>
                                <th>Year</th>
                                <th>Presents</th>
                                <th>Absents</th>
                                <th>Leaves</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($associate->opening_presents || $associate->opening_absents || $associate->opening_leaves)
                                <tr class="cds-opening-row">
                                    <td class="text-left font-weight-bold">Opening Balance</td>
                                    <td>—</td>
                                    <td class="cds-stat-presents font-weight-bold">{{ $associate->opening_presents ?? 0 }}</td>
                                    <td class="cds-stat-absents font-weight-bold">{{ $associate->opening_absents ?? 0 }}</td>
                                    <td class="cds-stat-leaves font-weight-bold">{{ $associate->opening_leaves ?? 0 }}</td>
                                    <td>—</td>
                                </tr>
                            @endif

                            @forelse($records as $record)
                            <tr>
                                <td class="text-left font-weight-bold">{{ $record->month }}</td>
                                <td>{{ $record->year }}</td>
                                <td class="cds-stat-presents font-weight-bold">{{ $record->total_presents ?? '—' }}</td>
                                <td class="cds-stat-absents font-weight-bold">{{ $record->total_absents ?? '—' }}</td>
                                <td class="cds-stat-leaves font-weight-bold">{{ $record->total_leaves ?? '—' }}</td>
                                <td>
                                    <a wire:navigate href="{{ route('e.month.details', [
                                            'id' => $associate->id,
                                            'year' => $record->year,
                                            'month' => $record->month_num
                                        ]) }}"
                                        class="btn btn-sm btn-secondary">
                                        <i class="fe fe-calendar fe-12 mr-1"></i> Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No attendance records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
