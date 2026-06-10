@extends('admin.main')

@section('title', 'Asif Associates | Associate Management')

@section('content')

<div class="col-md-12 container-fluid">

    <div class="my-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fe fe-users fe-16 mr-2"></i> Associate Management</h5>
        <a wire:navigate href="{{ route('e.associate.create') }}" class="btn btn-secondary">
            <i class="fe fe-plus-circle fe-16 mr-1"></i> New Associate
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <strong class="card-title"><i class="fe fe-list fe-16 mr-1"></i> Associates</strong>
        </div>
        @include('components.message')
        <div class="cds-table-wrap">
            <table class="table table-hover att-table text-center mb-0">
                <thead>
                    <tr>
                        <th class="text-left">Name</th>
                        <th>Time Remaining</th>
                        <th>CRN</th>
                        <th>Date Joined</th>
                        <th>Period (Yrs)</th>
                        <th>End Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                    @php
                        $today = \Carbon\Carbon::today();
                        $ended = !empty($record->end_date) ? \Carbon\Carbon::parse($record->end_date) : null;
                        $diff  = $ended ? $today->diff($ended) : null;
                    @endphp
                    <tr>
                        <td class="text-left">
                            @if(!$record->active)
                                <span class="cds-status-tag mr-1">Archived</span>
                            @endif
                            <strong>{{ $record->name }}</strong>
                        </td>
                        <td>
                            @if($record->active)
                                @if($diff)
                                    @if($diff->invert) - @endif
                                    {{ $diff->y > 0 ? $diff->y . ' yr' . ($diff->y > 1 ? 's ' : ' ') : '' }}
                                    {{ $diff->m > 0 ? $diff->m . ' mo' . ($diff->m > 1 ? 's ' : ' ') : '' }}
                                    {{ $diff->d > 0 ? $diff->d . ' day' . ($diff->d > 1 ? 's' : '') : '' }}
                                    @if($diff->y === 0 && $diff->m === 0 && $diff->d === 0) Today @endif
                                @else
                                    —
                                @endif
                            @else
                                <span class="cds-status-tag cds-status-tag--done">Left</span>
                            @endif
                        </td>
                        <td>{{ $record->crn ?? '—' }}</td>
                        <td>{{ !empty($record->date_joined) ? date('M d, Y', strtotime($record->date_joined)) : '—' }}</td>
                        <td>{{ $record->period ?? '—' }}</td>
                        <td>{{ !empty($record->end_date) ? date('M d, Y', strtotime($record->end_date)) : '—' }}</td>
                        <td class="text-nowrap">
                            @if($record->active)
                                <form action="{{ route('e.associate.deactive', $record->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-secondary btn-sm" title="Archive">
                                        <i class="fe fe-archive fe-16"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('e.associate.reactive', $record->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-secondary btn-sm" title="Reactivate">
                                        <i class="fe fe-check fe-16"></i>
                                    </button>
                                </form>
                            @endif
                            <a wire:navigate href="{{ route('e.associate.edit', $record->id) }}" class="btn btn-secondary btn-sm" title="Edit">
                                <i class="fe fe-edit fe-16"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
