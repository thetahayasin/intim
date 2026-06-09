@extends('admin.main')

@section('title', 'Asif Associates | Leave Approvals')

@section('content')

<div class="col-md-12 container-fluid">

<div class="row">
    <div class="col-md-8 col-lg-8 my-4">
        <div class="card shadow">
            <div class="card-body att-body">
                <h5 class="card-title">Pending Leaves</h5>
                @include('components.message')
                <table class="table table-hover att-table">
                    <thead>
                        <tr>
                            <th>Associate</th>
                            <th>No. of Days</th>
                            <th class="text-center">Approve | View Dates | Reject</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $associateLeaves)
                            @foreach($associateLeaves['leaves'] as $index => $leave)
                                <tr>
                                    @if($loop->first) <!-- Only display the associate's name and total number of leaves on the first row of each group -->
                                        <td rowspan="{{ count($associateLeaves['leaves']) }}">{{ $associateLeaves['associate_name'] }}</td>
                                        <td rowspan="{{ count($associateLeaves['leaves']) }}">{{ $associateLeaves['leave_count'] }}</td>
                                        <td class="text-center" style="color:white" rowspan="{{ count($associateLeaves['leaves']) }}"> <!-- Add rowspan for the action button -->
                                            <form style="display:inline" action="{{ route('e.leave.approve', $associateLeaves['associate_id']) }}" method="post">
                                                @csrf
                                                <button class="btn btn-success" style="color:white" type="submit"><i class="fe fe-16 fe-check-circle"></i></button>
                                            </form>

                                            <a wire:navigate href="{{ route('e.leave.view', $associateLeaves['associate_id']) }}" class="btn btn-primary display-block"><i class="fe fe-16 fe-calendar"></i></a>
                                            <form style="display:inline" action="{{ route('e.leave.reject', $associateLeaves['associate_id']) }}" method="post">
                                                @csrf
                                                <button class="btn btn-danger" type="submit"><i class="fe fe-16 fe-x"></i></button>
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
        <div class="card shadow">
            <div class="card-body att-body">
                <div class="ayat text-center">
                    <span class="ayat-text"><b>"there is no religion for one who cannot uphold a covenant"</b></span>
                    <br>
                    <span class="ref-text"><i>Musnad Aḥmad - H12383</i></span>
                            
                </div>
            </div>    
        </div>
    </div>
    <div class="col-md-8 col-lg-8 my-4">
        <div class="card shadow">
            <div class="card-body att-body">
                <h5 class="card-title">History</h5>
                @include('components.message')
                <table class="table table-hover att-table text-center">
                    <thead>
                        <tr>
                            <th>Associate</th>
                            <th>Number of Leaves</th>
                            <th >Breakup</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $h)
                            <tr>
                                <td>{{ $h->associate->name }}</td>
                                <td>{{ $h->total_leaves }}</td>
                                <td><a wire:navigate href="{{ route('e.leave.show', $h->associate->id) }}" class="btn btn-primary btn-sm"><i class="fe fe-eye fe-16"></i></a></td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
    
                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    </div>


</div>





@endsection


