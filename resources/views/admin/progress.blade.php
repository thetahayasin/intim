@extends('admin.main')

@section('title', 'Asif Associates | Associate Progress')

@section('content')

<div class="col-md-12 container-fluid">
    <div class="row">
        <div class="col-md-12 my-4">
            <div class="card shadow">
                <div class="card-body att-body">
                    <h5 class="card-title">Associates Progress</h5>
                    @include('components.message')
                    <table class="table table-hover att-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>CRN</th>
                                <th>Presents</th>
                                <th>Absents</th>
                                <th>Leaves</th>
                                <th>Breakup</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $record)
                            <tr>
                                <td>
                                    @if(!$record->active)
                                        <span class="badge bg-danger text-white me-1">Deactivated</span>
                                    @endif
                                    {{ $record->name }}
                                </td>
                                <td>{{ $record->crn ?? '-' }}</td>
                                <td>
                                    {{ isset($record->total_presents) && isset($record->opening_presents) 
                                        ? $record->total_presents + $record->opening_presents 
                                        : '-' 
                                    }}
                                </td>
                                
                                <td>
                                    {{ isset($record->total_absents) && isset($record->opening_absents) 
                                        ? $record->total_absents + $record->opening_absents 
                                        : '-' 
                                    }}
                                </td>
                                
                                <td>
                                    {{ isset($record->total_leaves) && isset($record->opening_leaves) 
                                        ? $record->total_leaves + $record->opening_leaves 
                                        : '-' 
                                    }}
                                </td>

                                <td>                                    
                                    <!-- Edit button -->
                                    <a wire:navigate href="{{ route('e.progress.breakup', $record->id) }}" class="btn btn-primary btn-sm"><i class="fe fe-eye fe-16"></i></a>
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