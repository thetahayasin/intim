@extends('associate.main')

@section('title', 'Asif Associates | Associate Attendance')

@section('content')

<div class="col-md-12 container-fluid">

<div class="row">
    <div class="col-md-8 col-lg-8 my-4">
        <div class="card shadow">
            <div class="card-body att-body">
            <h5 class="card-title">Time Sheet</h5>
            @include('components.message')
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span class="fe fe-minus-circle fe-16 mr-2"></span> {{ $error }} 
            </div>
            @endforeach
            <form method="POST" action="{{ route('ass.attendance.store') }}">
            @csrf
                <table class="table table-hover att-table">
                        <thead>
                        <tr>
                            <th width="30%">Date</th>
                            <th>Present</th>
                            <th width="50%">Client</th>
                            <th width="20%">Hours Worked</th>
                        </tr>
                        </thead>
                        <tbody> 
                            @foreach ($dates as $date)
                                <input type="hidden" name="attendance[{{ $date['date'] }}][date]" value="{{ $date['date'] }}">
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($date['date'])->format('M d, Y | (D)') }} 
                            
                                        @if($date['is_holiday'])
                                            <span class="badge badge-pill badge-warning">{{ $date['description'] }}</span>
                                        @endif
                            
                                        @if($attendances->has($date['date']))
                                            @if($attendances[$date['date']]->is_leave == 1) 
                                                <span class="badge badge-pill badge-warning">Leave</span> 
                                                @if($attendances[$date['date']]->leave_approval == 1) 
                                                    <span class="badge badge-pill badge-success">Approved</span>  
                                                @elseif($attendances[$date['date']]->leave_approval == 0) 
                                                    <span class="badge badge-pill badge-warning">Pending</span> 
                                                @endif 
                                            @endif 
                                            @if($attendances[$date['date']]->leave_approval == 2) 
                                                <span class="badge badge-pill badge-danger">Rejected</span> 
                                            @endif
                                        @endif
                            
                                        @if(\Carbon\Carbon::parse($date['date'])->isWeekend()) 
                                            <span class="badge badge-pill badge-success">Weekend</span> 
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="customSwitch-{{ $date['date'] }}" 
                                                name="attendance[{{ $date['date'] }}][is_present]" 
                                                @if(isset($attendances[$date['date']]) && $attendances[$date['date']]->is_present) checked @endif
                                                @if($date['is_holiday'] || (isset($attendances[$date['date']]) && ($attendances[$date['date']]->is_leave == 1 || $attendances[$date['date']]->is_locked == 1))) disabled @endif>
                                            <label class="custom-control-label" for="customSwitch-{{ $date['date'] }}"></label>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <input class="form-control" type="text" name="attendance[{{ $date['date'] }}][work_done]" 
                                            value="{{ isset($attendances[$date['date']]) ? $attendances[$date['date']]->work_done : '' }}" 
                                            @if($date['is_holiday'] || (isset($attendances[$date['date']]) && ($attendances[$date['date']]->is_leave == 1 || $attendances[$date['date']]->is_locked == 1))) disabled @endif>
                                    </td>
                                    
                                    <td>
                                        <input type="number" class="form-control" min="0" max="12" name="attendance[{{ $date['date'] }}][work_hours]" 
                                            value="{{ isset($attendances[$date['date']]) ? $attendances[$date['date']]->work_hours : '' }}" 
                                            @if($date['is_holiday'] || (isset($attendances[$date['date']]) && ($attendances[$date['date']]->is_leave == 1 || $attendances[$date['date']]->is_locked == 1))) disabled @endif>
                                    </td>
                                </tr>
                            @endforeach
 
                            
                        </tbody>
                    </table>
                    <input type="submit" value="Save" class="btn btn-primary btn-lg float-right">
                </form>
                
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-4 my-4">
        <div class="card shadow">
            <div class="card-body att-body">
                <h5 class="card-title">Instructions</h5>
                <ul>
                    <li>Leave the work done field empty to be counted as office work.</li>
                </ul>
                <hr>
                <div class="ayat text-center">
                    <span class="ayat-text"><b>"and do not argue on behalf of those who deceive themselves. indeed, Allah loves not one who is a habitually sinful deceiver"</b></span>
                    <br>
                    <span class="ref-text"><i>Quran (4:107)</i></span>
                            
                </div>
            </div>    
        </div>
    </div>
</div>


</div>


@endsection


