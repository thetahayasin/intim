@extends('admin.main')

@section('title', $associate->name . ' | Leave Approvals')

@section('content')

<div class="col-md-12 container-fluid">
<a wire:navigate href="{{ route('e.leave') }}" class="btn btn-secondary"><i class="fe fe-arrow-left fe-16"></i> Back</a>
<div class="row">
    <div class="col-md-8 col-lg-8 my-4">
        <div class="card shadow">
            <div class="card-body att-body">
                <h5 class="card-title">Leave History for {{ $associate->name }}</h5>
                @include('components.message')
                <table class="table table-hover att-table text-center">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Reason</th>
                            <th>Leave Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $leave)
                            <tr>
                                <td>{{ $leave->date }}</td>
                                <td>{{ $leave->reason_for_leave }}</td>
                                <td>
                                    @if($leave->leave_approval == 1)
                                        <span class="badge bg-success text-white">Approved</span>
                                    @else
                                        <span class="badge bg-danger text-white">Rejected</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
    
                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $leaves->links() }}
                </div>
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
    


</div>





@endsection


