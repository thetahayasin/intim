@extends('associate.main')

@section('title', 'Asif Associates | Leave Application')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
@endsection

@section('content')

<div class="col-md-12 container-fluid">

    {{-- Summary Stats --}}
    <div class="row my-4">
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body text-center py-3">
                    <small class="text-muted d-block mb-1">Total Applied</small>
                    <div style="font-size:1.6rem; font-weight:700; color:var(--cds-text-primary);">
                        {{ $pendingLeaves->count() + $history->count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body text-center py-3">
                    <small class="text-muted d-block mb-1">Pending</small>
                    <div style="font-size:1.6rem; font-weight:700; color:var(--cds-text-primary);">
                        {{ $pendingLeaves->count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body text-center py-3">
                    <small class="text-muted d-block mb-1">Approved</small>
                    <div style="font-size:1.6rem; font-weight:700; color:var(--cds-text-primary);">
                        {{ $history->where('leave_approval', 1)->count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card">
                <div class="card-body text-center py-3">
                    <small class="text-muted d-block mb-1">Rejected</small>
                    <div style="font-size:1.6rem; font-weight:700; color:var(--cds-text-secondary);">
                        {{ $history->where('leave_approval', 0)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Leave Application Form --}}
        <div class="col-md-8 my-4">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title"><i class="fe fe-file-text fe-16 mr-1"></i> Leave Application</strong>
                </div>
                <div class="card-body">
                    @include('components.message')
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fe fe-alert-circle fe-16 mr-2"></i> {{ $error }}
                        </div>
                    @endforeach

                    <form method="POST" action="{{ route('ass.leave.store') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Reason</label>
                            <select id="leselect" class="form-control" onchange="showHideTextarea()">
                                <option disabled selected>Select reason</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Exam Leave">Exam Leave</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Date Range <small class="text-muted">(mm/dd/yyyy)</small></label>
                            <input id="daterange" class="form-control" type="text" name="daterange" autocomplete="off" />
                        </div>
                        <div class="form-group mb-3">
                            <textarea id="reason" class="form-control" rows="4" style="display:none;" name="reason"
                                placeholder="Elaborate the reason for leave"></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-secondary btn-lg">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Instructions --}}
        <div class="col-md-4 my-4">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title"><i class="fe fe-info fe-16 mr-1"></i> Instructions</strong>
                </div>
                <div class="card-body">
                    <ul style="padding-left:1.25rem; font-size:14px; color:var(--cds-text-secondary);">
                        <li class="mb-2">Keep the reason brief and to the point.</li>
                        <li class="mb-2">If a date is marked as present, it cannot be applied for leave.</li>
                        <li class="mb-2">Leaves are subject to admin approval.</li>
                    </ul>
                    <hr>
                    <div class="text-center" style="padding-top:8px;">
                        <span class="ayat-text"><b>"there is no religion for one who cannot uphold a covenant"</b></span>
                        <br>
                        <span class="text-muted mt-2 d-block"><i>Musnad Aḥmad — H12383</i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Pending Leaves --}}
        <div class="col-md-6 my-4">
            {{-- Bulk cancel form (inputs associated via form="..." attribute) --}}
            <form id="bulkCancelForm" action="{{ route('ass.leave.cancel.bulk') }}" method="POST">
                @csrf
            </form>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong class="card-title mb-0">
                        <i class="fe fe-clock fe-16 mr-1"></i> Pending Leaves
                    </strong>
                    <div class="d-flex align-items-center" style="gap:8px;">
                        <button id="bulkCancelBtn" type="submit" form="bulkCancelForm"
                                class="btn btn-sm btn-secondary" style="display:none;">
                            <i class="fe fe-x fe-12 mr-1"></i> Cancel Selected
                            (<span id="selectedCount">0</span>)
                        </button>
                        <span class="cds-count-tag">{{ $pendingLeaves->count() }}</span>
                    </div>
                </div>
                <div class="cds-table-wrap">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:36px;">
                                    <input type="checkbox" id="selectAllLeaves">
                                </th>
                                <th>Date</th>
                                <th>Reason</th>
                                <th class="text-center">Cancel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingLeaves as $l)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $l->id }}"
                                               form="bulkCancelForm" class="leave-checkbox">
                                    </td>
                                    <td>{{ date('d M Y (D)', strtotime($l->date)) }}</td>
                                    <td>{{ $l->reason_for_leave }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('ass.leave.cancel', $l->id) }}" method="post">
                                            @csrf
                                            <button class="btn btn-secondary btn-sm" type="submit">
                                                <i class="fe fe-x fe-12"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No pending leaves</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- History --}}
        <div class="col-md-6 my-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong class="card-title mb-0">
                        <i class="fe fe-list fe-16 mr-1"></i> History
                    </strong>
                    <span class="cds-count-tag">{{ $history->count() }}</span>
                </div>
                <div class="cds-table-wrap">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reason</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $l)
                                <tr>
                                    <td>{{ date('d M Y (D)', strtotime($l->date)) }}</td>
                                    <td>{{ $l->reason_for_leave }}</td>
                                    <td class="text-center">
                                        @if($l->leave_approval == 1)
                                            <span class="cds-status-tag cds-status-tag--done">Approved</span>
                                        @else
                                            <span class="cds-status-tag">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No history yet</td>
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
    var sel = document.getElementById('leselect');
    var val = sel.options[sel.selectedIndex].value;
    var ta  = document.getElementById('reason');
    ta.style.display = (val === 'Other') ? 'block' : 'none';
    ta.value = (val === 'Sick Leave' || val === 'Exam Leave') ? val : '';
}

function updateBulkUI() {
    var checked = document.querySelectorAll('.leave-checkbox:checked');
    var btn = document.getElementById('bulkCancelBtn');
    var count = document.getElementById('selectedCount');
    if (!btn) return;
    if (checked.length > 0) {
        btn.style.display = 'inline-flex';
        count.textContent = checked.length;
    } else {
        btn.style.display = 'none';
    }
}

document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'selectAllLeaves') {
        document.querySelectorAll('.leave-checkbox').forEach(function(cb) {
            cb.checked = e.target.checked;
        });
        updateBulkUI();
    }
    if (e.target && e.target.classList.contains('leave-checkbox')) {
        var all = document.querySelectorAll('.leave-checkbox');
        var checked = document.querySelectorAll('.leave-checkbox:checked');
        var selectAll = document.getElementById('selectAllLeaves');
        if (selectAll) selectAll.checked = all.length === checked.length;
        updateBulkUI();
    }
});
</script>
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script id="leaveDataJson" type="application/json">{!! json_encode(['leaveDates' => $leaveDates, 'holidays' => $publicHolidays]) !!}</script>
<script>
function initLeaveDaterange() {
    if (!document.getElementById('daterange')) return;
    var dataEl = document.getElementById('leaveDataJson');
    if (!dataEl || typeof $ === 'undefined' || !$.fn.daterangepicker) { setTimeout(initLeaveDaterange, 80); return; }

    var data = JSON.parse(dataEl.textContent);
    if ($('#daterange').data('daterangepicker')) $('#daterange').data('daterangepicker').remove();

    $('#daterange').daterangepicker({
        opens: 'left',
        singleDatePicker: false,
        isInvalidDate: function(date) {
            var d = date.format('MM/DD/YYYY');
            if (date.day() === 0 || date.day() === 6) return true;
            if (date.isBefore(moment(), 'day')) return true;
            if (data.leaveDates.includes(d)) return true;
            if (data.holidays.includes(d)) return true;
            return false;
        }
    });
}

document.removeEventListener('livewire:navigated', initLeaveDaterange);
document.addEventListener('livewire:navigated', initLeaveDaterange);
initLeaveDaterange();
</script>
@endsection
