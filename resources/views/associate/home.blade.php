@extends('associate.main')

@section('title', 'Asif Associates | Associate Dashboard')

@section('content')

<div class="col-md-12 container-fluid">
    @if($totalLeaves != 0 && $numberOfPresents != 0 && $numberOfPresents/$totalLeaves < 10)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <span class="fe fe-meh fe-16 mr-2"></span> Your Presence to Leave ratio is {{ round($numberOfPresents/$totalLeaves, 2)  }}, Satisfied? 
        </div>
    @endif

    <div class="row my-4">


        <div class="col-md-3">
            <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted mb-1">Cummulative</small>
                    <h3 class="card-title mb-0">Presents</h3>
                    <p class="small text-muted mb-0">
                    <span class="aa-color">{{ $numberOfPresents }}</span>
                    </p>
                </div>
                <div class="col-4 text-right">
                    <i class="fe fe-user-check fe-32"></i>
                </div>
                </div>
                <!-- /. row -->
            </div>
            <!-- /. card-body -->
            </div>
            <!-- /. card -->
        </div>

        <div class="col-md-3">
            <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted mb-1">Cummulative</small>
                    <h3 class="card-title mb-0">Work Hours</h3>
                    <p class="small text-muted mb-0">
                    <span class="aa-color">{{ $totalWorkHours }}</span>
                    </p>
                </div>
                <div class="col-4 text-right">
                    <i class="fe fe-clock fe-32"></i>
                </div>
                </div>
                <!-- /. row -->
            </div>
            <!-- /. card-body -->
            </div>
            <!-- /. card -->
        </div>

        <div class="col-md-3">
            <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted mb-1">Cummulative</small>
                    <h3 class="card-title mb-0">Leaves</h3>
                    <p class="small text-muted mb-0">
                    <span class="aa-color">{{ $totalLeaves }}</span>
                    </p>
                </div>
                <div class="col-4 text-right">
                    <i class="fe fe-coffee fe-32"></i>
                </div>
                </div>
                <!-- /. row -->
            </div>
            <!-- /. card-body -->
            </div>
            <!-- /. card -->
        </div>

        <div class="col-md-3">
            <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted mb-1">Cummulative</small>
                    <h3 class="card-title mb-0">Absents</h3>
                    <p class="small text-muted mb-0">
                    <span class="aa-color">{{ $numberOfAbsents }}</span>
                    </p>
                </div>
                <div class="col-4 text-right">
                    <i class="fe fe-x fe-32"></i>
                </div>
                </div>
                <!-- /. row -->
            </div>
            <!-- /. card-body -->
            </div>
            <!-- /. card -->
        </div>    
        

    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                <div class="card-title bu-card-head">
                    <strong>
                    <i class="fe fe-bar-chart-2 fe-16"></i> Breakup of Work Hours </strong>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    @foreach ($workHoursBreakup as $item)
                        <div class="row align-items-center my-3">
                            <div class="col">
                                <strong>
                                    {{ ($item->work_done == NULL) ? "Office Work" : $item->work_done  }}
                                </strong>
                            </div>
                            <div class="col-auto">
                                <strong>
                                    (@if($item->total_work_hours == NULL) 
                                        0hrs 
                                    @else 
                                        {{$item->total_work_hours}}hrs 
                                    @endif) 
                                    @if($totalWorkHours != 0)
                                        {{ number_format(($item->total_work_hours / $totalWorkHours) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </strong>    
                            </div>
                            <div class="col-3">
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ ($totalWorkHours != 0) ? ($item->total_work_hours / $totalWorkHours) * 100 : 0 }}%" aria-valuenow="{{ ($totalWorkHours != 0) ? ($item->total_work_hours / $totalWorkHours) * 100 : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                    <!-- .col-md-12 -->
                </div>
                <!-- .row -->
                </div>
                <!-- .card-body -->
            </div>
            <!-- .card -->
        </div>


        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="ayat text-center">
                            <span class="ayat-text"><b>"the life of this world is no more than the delusion of enjoyment"</b></span>
                            <br>
                            <span class="ref-text"><i>Quran (3:185)</i></span>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>


@endsection


