@extends('admin.main')

@section('title', 'Asif Associates | Associate Progress Breakup')

@section('content')

<div class="col-md-12 container-fluid">
<a wire:navigate href="{{ route('e.progress') }}" class="btn btn-secondary"><i class="fe fe-arrow-left fe-16"></i> Back</a> 

    <div class="row">
        <div class="col-md-12 my-4">
            <div class="card shadow">
                <div class="card-body att-body">
                    <h5 class="card-title">{{ $associate->name }} Progress Breakup</h5>
                    @include('components.message')
                    <table class="table table-hover att-table text-center">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Presents</th>
                                <th>Absents</th>
                                <th>Leaves</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($associate->opening_presents || $associate->opening_absents || $associate->opening_leaves)
                                <tr style="font-weight: bold; background-color: #f0f0f0;">
                                    <td colspan="2">Opening</td>
                                    <td>{{ $associate->opening_presents ?? 0 }}</td>
                                    <td>{{ $associate->opening_absents ?? 0 }}</td>
                                    <td>{{ $associate->opening_leaves ?? 0 }}</td>
                                    <td></td>
                                </tr>
                            @endif

                            @forelse($records as $record)
                            <tr class="text-dark">
                                <td class="font-weight-bold">{{ $record->month }}</td>
                                <td class="font-weight-bold">{{ $record->year }}</td>
                                <td>{{ $record->total_presents ?? '-' }}</td>
                                <td>{{ $record->total_absents ?? '-' }}</td>
                                <td>{{ $record->total_leaves ?? '-' }}</td>
                                <td>
                                    <a wire:navigate href="{{ route('e.month.details', [
                                            'id' => $associate->id,
                                            'year' => $record->year,
                                            'month' => $record->month_num
                                        ]) }}"
                                        class="btn btn-sm btn-primary">
                                        View Details
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