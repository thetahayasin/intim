@extends('admin.main')

@section('title', $associate->name . ' | Leave Approvals')

@section('content')

<div class="col-md-12 container-fluid">
    <a wire:navigate href="{{ route('e.leave') }}" class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left fe-16"></i> Back
    </a>

    <div class="row">
        <div class="col-md-8 my-4">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">
                        <i class="fe fe-check-circle fe-16 mr-1"></i> Leave History — {{ $associate->name }}
                    </strong>
                </div>
                <div class="cds-table-wrap">
                    @include('components.message')
                    <table class="table table-hover text-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-left">Date</th>
                                <th class="text-left">Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                                <tr>
                                    <td class="text-left">{{ $leave->date }}</td>
                                    <td class="text-left">{{ $leave->reason_for_leave }}</td>
                                    <td>
                                        @if($leave->leave_approval == 1)
                                            <span class="badge badge-secondary">
                                                <i class="fe fe-check fe-10 mr-1"></i> Approved
                                            </span>
                                        @else
                                            <span class="badge" style="background:#8d8d8d;color:#fff;">
                                                <i class="fe fe-x fe-10 mr-1"></i> Rejected
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-3">{{ $leaves->links('pagination::bootstrap-4') }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 my-4">
            <div class="card">
                <div class="card-body text-center" style="padding: 2rem;">
                    <span class="ayat-text"><b>"there is no religion for one who cannot uphold a covenant"</b></span>
                    <br>
                    <span class="text-muted mt-2 d-block"><i>Musnad Aḥmad - H12383</i></span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
