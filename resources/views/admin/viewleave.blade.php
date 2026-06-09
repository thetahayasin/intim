@extends('admin.main')

@section('title', 'Asif Associates | Leave Dates')

@section('styles')

<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">

@endsection

@section('content')

<div class="col-md-12 container-fluid">
    <a wire:navigate href="{{ route('e.leave') }}" class="btn btn-secondary"><i class="fe fe-arrow-left fe-16"></i> Back</a>
<div class="row">
    <div class="col-md-8 col-lg-8 my-4">
        <div class="card shadow">
            <div class="card-body att-body">
                <h5 class="card-title">Leave Dates for {{ $associate->name }}</h5>
                @include('components.message')
                <table class="table table-hover att-table">
                    <thead>
                        <tr>
                            <th>Dates</th>
                            <th>Reason</th>
                            <th class="text-center">Approve | Reject</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveDates as $index => $date)
                            <tr>
                                <td>{{ date('M d, Y | (D)', strtotime($date)) }}</td>
                                <td>{{ $reasons[$index] }}</td>
                                <td class="text-center">
                                    <form style="display:inline" action="{{ route('e.leave.sapprove', $leaveId[$index]) }}" method="post">
                                        @csrf
                                        <button class="btn btn-success" style="color:white" type="submit"><i class="fe fe-16 fe-check-circle"></i></button>
                                    </form>

                                    <form style="display:inline" action="{{ route('e.leave.sreject', $leaveId[$index]) }}" method="post">
                                        @csrf
                                        <button class="btn btn-danger" type="submit"><i class="fe fe-16 fe-x"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-4 my-4">
        <div class="card shadow">
            <div class="card-body att-body">
                <h5 class="card-title">Instructions</h5>
                <ul>
                    <li>Do not write very long explanations in the reason field. A simple to the point word is enough.</li>
                    <li>If a date is marked as present, it cannot be applied for leave.</li>
                    <li>Leaves are subjected to admin's approval.</li>
                </ul>
                <hr>
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


