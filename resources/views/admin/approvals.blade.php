@extends('admin.main')

@section('title', 'Asif Associates | Leave Approvals')

@section('content')

<div class="col-md-12 container-fluid">

<div class="row">

    <div class="col-md-8 col-lg-8 my-4">
        <div class="card">
            <div class="card-header">
                <strong class="card-title"><i class="fe fe-check-circle fe-16 mr-1"></i> Pending Leaves</strong>
            </div>
            @include('components.message')
            <div class="cds-table-wrap">
                <table class="table table-hover att-table mb-0">
                    <thead>
                        <tr>
                            <th>Associate</th>
                            <th>No. of Days</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $associateLeaves)
                            @foreach($associateLeaves['leaves'] as $index => $leave)
                                <tr>
                                    @if($loop->first)
                                        <td rowspan="{{ count($associateLeaves['leaves']) }}">{{ $associateLeaves['associate_name'] }}</td>
                                        <td rowspan="{{ count($associateLeaves['leaves']) }}">{{ $associateLeaves['leave_count'] }}</td>
                                        <td class="text-center text-nowrap" rowspan="{{ count($associateLeaves['leaves']) }}">
                                            <form style="display:inline" action="{{ route('e.leave.approve', $associateLeaves['associate_id']) }}" method="post">
                                                @csrf
                                                <button class="btn btn-secondary btn-sm" type="submit" title="Approve">
                                                    <i class="fe fe-16 fe-check-circle"></i>
                                                </button>
                                            </form>
                                            <a wire:navigate href="{{ route('e.leave.view', $associateLeaves['associate_id']) }}" class="btn btn-secondary btn-sm" title="View Dates">
                                                <i class="fe fe-16 fe-calendar"></i>
                                            </a>
                                            <form style="display:inline" action="{{ route('e.leave.reject', $associateLeaves['associate_id']) }}" method="post">
                                                @csrf
                                                <button class="btn btn-danger btn-sm" type="submit" title="Reject">
                                                    <i class="fe fe-16 fe-x"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-4 my-4">
        <div class="card">
            <div class="card-body">
                <div class="ayat text-center">
                    <span class="ayat-text"><b>"there is no religion for one who cannot uphold a covenant"</b></span>
                    <br>
                    <span class="ref-text"><i>Musnad Aḥmad - H12383</i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-lg-8 my-4">
        <div class="card">
            <div class="card-header">
                <strong class="card-title"><i class="fe fe-clock fe-16 mr-1"></i> History</strong>
            </div>
            <div class="cds-table-wrap">
                <table class="table table-hover att-table text-center mb-0">
                    <thead>
                        <tr>
                            <th>Associate</th>
                            <th>Number of Leaves</th>
                            <th>Breakup</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $h)
                            <tr>
                                <td>{{ $h->associate->name }}</td>
                                <td>{{ $h->total_leaves }}</td>
                                <td>
                                    <a wire:navigate href="{{ route('e.leave.show', $h->associate->id) }}" class="btn btn-secondary btn-sm">
                                        <i class="fe fe-eye fe-16"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body pt-2">
                <div class="d-flex justify-content-center">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

</div>

@endsection
