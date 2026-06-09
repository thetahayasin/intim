@extends('admin.main')

@section('title', 'Asif Associates | Associate Management')

@section('content')

<div class="col-md-12 container-fluid">
    <a wire:navigate href="{{ route('e.associate.create') }}" class="btn btn-primary"><i class="fe fe-plus-circle fe-16"></i> New Associate</a>
    <div class="row">
        <div class="col-md-12 my-4">
            <div class="card shadow">
                <div class="card-body att-body">
                    <h5 class="card-title">Associates Management</h5>
                    @include('components.message')
                    <table class="table table-hover att-table text-center">
                        <thead>
                            <tr>
                                <th>Name</th>
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
                                $diff = $ended ? $today->diff($ended) : null;
                            @endphp
                            <tr>
                                <td>{{ $record->name }}</td>
                                <td>
                                @if($record->active ==  true)
                                    @if($diff)
                                        @if($diff->invert)
                                            -
                                        @endif
                                        {{ $diff->y > 0 ? $diff->y . ' year' . ($diff->y > 1 ? 's ' : ' ') : '' }}
                                        {{ $diff->m > 0 ? $diff->m . ' month' . ($diff->m > 1 ? 's ' : ' ') : '' }}
                                        {{ $diff->d > 0 ? $diff->d . ' day' . ($diff->d > 1 ? 's' : '') : '' }}
                                        @if($diff->y === 0 && $diff->m === 0 && $diff->d === 0)
                                            Today
                                        @endif
                                    @else
                                        -
                                    @endif
                                @else
                                    <span class="badge badge-primary">LEFT</span>
                                @endif
                                
                                </td>
                                <td>{{ $record->crn ?? '-' }}</td>
                                <td>{{ !empty($record->date_joined) ? date('M d, Y', strtotime($record->date_joined)) : '-' }}</td>
                                <td>{{ $record->period ?? '-' }}</td>
                                <td>{{ !empty($record->end_date) ? date('M d, Y', strtotime($record->end_date)) : '-' }}</td>

                                <td>
                                    <!-- Deactivate button -->
                                    @if($record->active == true)
                                        <form action="{{ route('e.associate.deactive', $record->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fe fe-archive fe-16"></i></button>
                                        </form>
                                    @else
                                        <form action="{{ route('e.associate.reactive', $record->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button style="color:white" type="submit" class="btn btn-success btn-sm"><i class="fe fe-check fe-16"></i></button>
                                        </form>
                                    @endif
                                    
                                    <!-- Edit button -->
                                    <a wire:navigate href="{{ route('e.associate.edit', $record->id) }}" class="btn btn-primary btn-sm"><i class="fe fe-edit fe-16"></i></a>
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


