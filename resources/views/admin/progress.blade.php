@extends('admin.main')

@section('title', 'Asif Associates | Associate Progress')

@section('content')

<div class="col-md-12 container-fluid">
    <div class="row">
        <div class="col-md-12 my-4">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title"><i class="fe fe-trending-up fe-16 mr-1"></i> Associates Progress</strong>
                </div>
                <div class="cds-table-wrap">
                    @include('components.message')
                    <table class="table table-hover att-table mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>CRN</th>
                                <th class="text-center">Presents</th>
                                <th class="text-center">Absents</th>
                                <th class="text-center">Leaves</th>
                                <th class="text-center">Breakup</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                            <tr>
                                <td>
                                    @if(!$record->active)
                                        <span class="badge badge-danger mr-1">Deactivated</span>
                                    @endif
                                    <strong>{{ $record->name }}</strong>
                                </td>
                                <td class="text-secondary">{{ $record->crn ?? '—' }}</td>
                                <td class="text-center font-weight-bold cds-stat-presents">
                                    {{ isset($record->total_presents) ? $record->total_presents + $record->opening_presents : '—' }}
                                </td>
                                <td class="text-center font-weight-bold cds-stat-absents">
                                    {{ isset($record->total_absents) ? $record->total_absents + $record->opening_absents : '—' }}
                                </td>
                                <td class="text-center font-weight-bold cds-stat-leaves">
                                    {{ isset($record->total_leaves) ? $record->total_leaves + $record->opening_leaves : '—' }}
                                </td>
                                <td class="text-center">
                                    <a wire:navigate href="{{ route('e.progress.breakup', $record->id) }}"
                                       class="btn btn-secondary btn-sm">
                                        <i class="fe fe-eye fe-14 mr-1"></i> Breakup
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
