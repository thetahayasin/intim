@extends('associate.main')

@section('title', 'Asif Associates | Leave Application')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
@endsection

@section('content')

<div class="col-md-12 container-fluid">

    {{-- Summary Stats Row --}}
    <div class="row my-3">
        <div class="col-6 col-md-3 mb-2">
            <div class="card shadow text-center py-3">
                <small class="text-muted">Total Applied</small>
                <h4 class="mb-0">{{ $pendingLeaves->count() + $history->count() }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card shadow text-center py-3">
                <small class="text-muted">Pending</small>
                <h4 class="mb-0" style="color: #e67e22;">{{ $pendingLeaves->count() }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card shadow text-center py-3">
                <small class="text-muted">Approved</small>
                <h4 class="mb-0" style="color: #27ae60;">{{ $history->where('leave_approval', 1)->count() }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card shadow text-center py-3">
                <small class="text-muted">Rejected</small>
                <h4 class="mb-0" style="color: #e74c3c;">{{ $history->where('leave_approval', 0)->count() }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Leave Application Form --}}
        <div class="col-md-8 my-4">
            <div class="card shadow">
                <div class="card-body att-body">
                    <h5 class="card-title">Leave Application</h5>
                    @include('components.message')
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="fe fe-minus-circle fe-16 mr-2"></span> {{ $error }}
                        </div>
                    @endforeach
                    <form method="POST" action="{{ route('ass.leave.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="leselect">Reason:</label>
                            <select id="leselect" class="form-control" onchange="showHideTextarea()">
                                <option disabled selected>Select Reason</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Exam Leave">Exam Leave</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <br>
                        <label for="daterange">Dates (mm/dd/yyyy):</label>
                        <input id="daterange" class="form-control" type="text" name="daterange" />
                        <br>
                        <textarea max="255" id="reason" class="form-control" rows="6"
                            style="display: none;" name="reason"
                            placeholder="Elaborate the Reason for Leave"></textarea>
                        <br>
                        <input type="submit" value="Submit" class="btn btn-primary btn-lg float-right">
                    </form>
                </div>
            </div>
        </div>

        {{-- Instructions --}}
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
                        <span class="ref-text"><i>Musnad Aḥmad - H12383</i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Pending Leaves --}}
        <div class="col-md-6 col-lg-6 my-4">
            <div class="card shadow">
                <div class="card-body att-body">
                    <h5 class="card-title">
                        Pending Leaves
                        <span style="display: inline-block; background-color: #ffc107; color: #000; font-size: 12px; font-weight: 600; padding: 2px 10px; border-radius: 12px; vertical-align: middle;">
                            {{ $pendingLeaves->count() }}
                        </span>
                    </h5>
                    <table class="table table-hover att-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingLeaves as $l)
                                <tr>
                                    <td>{{ date('M d, Y | (D)', strtotime($l->date)) }}</td>
                                    <td>{{ $l->reason_for_leave }}</td>
                                    <td>
                                        <form action="{{ route('ass.leave.cancel', $l->id) }}" method="post">
                                            @csrf
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fe fe-x fe-16"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No pending leaves</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- History --}}
        <div class="col-md-6 col-lg-6 my-4">
            <div class="card shadow">
                <div class="card-body att-body">
                    <h5 class="card-title">
                        History
                        <span style="display: inline-block; background-color: #6c757d; color: #fff; font-size: 12px; font-weight: 600; padding: 2px 10px; border-radius: 12px; vertical-align: middle;">
                            {{ $history->count() }}
                        </span>
                    </h5>
                    <table class="table table-hover att-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $l)
                                <tr>
                                    <td>{{ date('M d, Y | (D)', strtotime($l->date)) }}</td>
                                    <td>{{ $l->reason_for_leave }}</td>
                                    <td>
                                        @if($l->leave_approval == 1)
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No history yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
function showHideTextarea() {
    var selectBox = document.getElementById("leselect");
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    var textarea = document.getElementById("reason");

    if (selectedValue === "Other") {
        textarea.style.display = "block";
    } else {
        textarea.style.display = "none";
    }

    if (selectedValue === "Sick Leave" || selectedValue === "Exam Leave") {
        textarea.value = selectedValue;
    } else {
        textarea.value = "";
    }
}
</script>
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script>
$(function() {
    var leaveDates = {!! json_encode($leaveDates) !!};
    var publicHolidays = {!! json_encode($publicHolidays) !!};

    $('#daterange').daterangepicker({
        opens: 'left',
        singleDatePicker: false,
        isInvalidDate: function(date) {
            var formattedDate = date.format('MM/DD/YYYY');
            if (date.day() === 0 || date.day() === 6) return true;
            if (date.isBefore(moment(), 'day')) return true;
            if (leaveDates.includes(formattedDate)) return true;
            if (publicHolidays.includes(formattedDate)) return true;
            return false;
        }
    });
});
</script>
@endsection