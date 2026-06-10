@extends('admin.main')

@section('title', 'Monthly Detailed Attendance')

@section('content')

<div class="col-md-12 container-fluid">

    <a wire:navigate href="{{ route('e.progress.breakup', $associateId) }}" class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left"></i> Back
    </a>

    <div id="att-alert" class="alert d-none mb-3" role="alert"></div>

    <div class="card">
        <div class="card-header">
            <strong class="card-title"><i class="fe fe-calendar fe-16 mr-1"></i> Date-wise Attendance</strong>
        </div>
        <div class="cds-table-wrap">
            <table class="table table-hover text-center mb-0" style="min-width:800px;">
                <thead>
                    <tr>
                        <th class="text-left">Date</th>
                        <th>Present</th>
                        <th>Leave</th>
                        <th>Approval</th>
                        <th>Absent</th>
                        <th>Final Status</th>
                        <th class="text-left">Reason</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $row)
                    @php
                        $date           = \Carbon\Carbon::parse($row->date);
                        $dayName        = $date->format('l');
                        $isWeekend      = $date->isWeekend();
                        $isHoliday      = !empty($row->holiday_date);
                        $isPresent      = $row->is_present == 1 && $row->is_leave == 0;
                        $isLeave        = $row->is_leave == 1;
                        $isApproved     = $row->leave_approval == 1;
                        $isRejected     = $row->leave_approval == 2;
                        $isAbsent       = !$isPresent && !$isLeave && !$isWeekend && !$isHoliday;
                        $isUnauthorized = !$isPresent && $isLeave && !$isApproved && !$isRejected;
                        $currentStatus  = $isPresent ? 'present' : ($isLeave ? 'leave' : 'absent');
                    @endphp

                    {{-- Main display row --}}
                    <tr class="{{ ($isWeekend || $isHoliday) ? 'cds-opening-row' : '' }}" id="att-row-{{ $row->id }}">
                        <td class="text-left">
                            <strong>{{ $date->format('d M Y') }}</strong><br>
                            <small class="text-muted">{{ $dayName }}</small>
                            @if($isHoliday)
                                <br><span class="cds-status-tag cds-status-tag--done" style="margin-top:3px;display:inline-flex;">{{ strtoupper($row->holiday_description) }}</span>
                            @elseif($isWeekend)
                                <br><span class="cds-status-tag" style="margin-top:3px;display:inline-flex;">Weekend</span>
                            @endif
                        </td>

                        @if($isHoliday || $isWeekend)
                            <td class="text-muted">—</td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">—</td>
                            <td class="text-muted">—</td>
                            <td>
                                @if($isHoliday)
                                    <span class="cds-status-tag cds-status-tag--done">Public Holiday</span>
                                @else
                                    <span class="cds-status-tag">Weekend</span>
                                @endif
                            </td>
                            <td>—</td>
                            <td>—</td>
                        @else
                            <td class="att-cell-present">
                                @if($row->is_present == 1)
                                    <span style="color:#161616;font-weight:600;">✓</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="att-cell-leave">
                                @if($row->is_leave == 1)
                                    <span style="color:#161616;font-weight:600;">✓</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="att-cell-approval">
                                @if($isApproved)
                                    <span class="cds-status-tag cds-status-tag--done">Approved</span>
                                @elseif($isRejected)
                                    <span class="cds-status-tag">Rejected</span>
                                @elseif($isLeave)
                                    <span class="cds-status-tag">Pending</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="att-cell-absent">
                                @if($isAbsent)
                                    <span style="color:#525252;font-weight:600;">✓</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="att-cell-status">
                                @if($isPresent)
                                    <span class="cds-status-tag cds-status-tag--done">Present</span>
                                @elseif($isLeave && $isApproved)
                                    <span class="cds-status-tag cds-status-tag--done">Approved Leave</span>
                                @elseif($isLeave && $isRejected)
                                    <span class="cds-status-tag">Rejected Leave</span>
                                @elseif($isUnauthorized)
                                    <span class="cds-status-tag">Unauthorized Leave</span>
                                @elseif($isLeave)
                                    <span class="cds-status-tag">Pending Leave</span>
                                @elseif($isAbsent)
                                    <span class="cds-status-tag">Absent</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-left att-cell-reason" style="font-size:13px;">
                                <span class="text-muted">{{ $row->reason_for_leave ?? '—' }}</span>
                            </td>
                            <td>
                                <button type="button"
                                    class="btn btn-sm btn-secondary att-edit-btn"
                                    data-id="{{ $row->id }}"
                                    data-status="{{ $currentStatus }}"
                                    data-approval="{{ $row->leave_approval ?? 0 }}"
                                    data-reason="{{ $row->reason_for_leave ?? '' }}"
                                    data-hours="{{ $row->work_hours ?? '' }}"
                                    data-url="{{ route('e.attendance.update.day', $row->id) }}"
                                    title="Edit">
                                    <i class="fe fe-edit-2 fe-12"></i>
                                </button>
                            </td>
                        @endif
                    </tr>

                    {{-- Inline edit row (hidden by default, only for editable days) --}}
                    @if(!$isWeekend && !$isHoliday)
                    <tr class="att-edit-row d-none" id="att-edit-{{ $row->id }}">
                        <td colspan="8" style="background:#f4f4f4;padding:14px 16px;border-top:none;">
                            <form class="att-inline-form" data-id="{{ $row->id }}">
                                @csrf
                                <div class="form-row align-items-end">
                                    <div class="col-auto mb-2">
                                        <label class="small font-weight-bold d-block mb-1">Status</label>
                                        <select name="status" class="form-control form-control-sm att-status-select" style="min-width:120px;">
                                            <option value="present" {{ $currentStatus === 'present' ? 'selected' : '' }}>Present</option>
                                            <option value="absent"  {{ $currentStatus === 'absent'  ? 'selected' : '' }}>Absent</option>
                                            <option value="leave"   {{ $currentStatus === 'leave'   ? 'selected' : '' }}>On Leave</option>
                                        </select>
                                    </div>
                                    <div class="col-auto mb-2 att-leave-group {{ $currentStatus !== 'leave' ? 'd-none' : '' }}">
                                        <label class="small font-weight-bold d-block mb-1">Leave Approval</label>
                                        <select name="leave_approval" class="form-control form-control-sm" style="min-width:130px;">
                                            <option value="0" {{ ($row->leave_approval ?? 0) == 0 ? 'selected' : '' }}>Pending</option>
                                            <option value="1" {{ ($row->leave_approval ?? 0) == 1 ? 'selected' : '' }}>Approved</option>
                                            <option value="2" {{ ($row->leave_approval ?? 0) == 2 ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col mb-2 att-leave-group {{ $currentStatus !== 'leave' ? 'd-none' : '' }}">
                                        <label class="small font-weight-bold d-block mb-1">Reason</label>
                                        <input type="text" name="reason_for_leave" class="form-control form-control-sm"
                                            value="{{ $row->reason_for_leave ?? '' }}" placeholder="Leave reason…">
                                    </div>
                                    <div class="col-auto mb-2">
                                        <label class="small font-weight-bold d-block mb-1">Work Hours</label>
                                        <input type="number" name="work_hours" class="form-control form-control-sm"
                                            value="{{ $row->work_hours ?? '' }}" min="0" max="24" placeholder="—" style="width:80px;">
                                    </div>
                                    <div class="col-auto mb-2 d-flex align-items-end" style="gap:6px;">
                                        <button type="submit" class="btn btn-sm btn-primary att-save-btn">
                                            <i class="fe fe-check fe-12 mr-1"></i>Save
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary att-cancel-btn" data-id="{{ $row->id }}">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                                <div class="att-form-error text-danger small mt-1 d-none"></div>
                            </form>
                        </td>
                    </tr>
                    @endif

                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No attendance records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
(function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function esc(str) {
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(str || ''));
        return d.innerHTML;
    }

    function showAlert(msg, type) {
        var el = document.getElementById('att-alert');
        if (!el) return;
        el.className = 'alert alert-' + type + ' mb-3';
        el.textContent = msg;
        setTimeout(function () { el.className = 'alert d-none mb-3'; }, 3000);
    }

    function toggleLeaveFields(form, show) {
        form.querySelectorAll('.att-leave-group').forEach(function (el) {
            el.classList.toggle('d-none', !show);
        });
    }

    // Status select change → show/hide leave-specific fields in real time
    document.addEventListener('change', function (e) {
        if (!e.target.matches('.att-status-select')) return;
        var form = e.target.closest('form.att-inline-form');
        if (form) toggleLeaveFields(form, e.target.value === 'leave');
    });

    // Edit button → open inline edit row
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.att-edit-btn');
        if (!btn) return;
        var id = btn.dataset.id;

        // Close all other edit rows
        document.querySelectorAll('.att-edit-row').forEach(function (r) {
            r.classList.add('d-none');
        });

        var editRow = document.getElementById('att-edit-' + id);
        if (!editRow) return;

        // Re-populate form from button's current data attrs (updated after each save)
        var form = editRow.querySelector('form.att-inline-form');
        form.querySelector('[name="status"]').value = btn.dataset.status;
        form.querySelector('[name="leave_approval"]').value = btn.dataset.approval || '0';
        form.querySelector('[name="reason_for_leave"]').value = btn.dataset.reason || '';
        form.querySelector('[name="work_hours"]').value = btn.dataset.hours || '';
        toggleLeaveFields(form, btn.dataset.status === 'leave');
        form.querySelector('.att-form-error').classList.add('d-none');

        editRow.classList.remove('d-none');
    });

    // Cancel button → close edit row
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.att-cancel-btn');
        if (!btn) return;
        var editRow = document.getElementById('att-edit-' + btn.dataset.id);
        if (editRow) editRow.classList.add('d-none');
    });

    // Save form → AJAX POST
    document.addEventListener('submit', function (e) {
        var form = e.target.closest('form.att-inline-form');
        if (!form) return;
        e.preventDefault();

        var id      = form.dataset.id;
        var saveBtn = form.querySelector('.att-save-btn');
        var errDiv  = form.querySelector('.att-form-error');
        var editBtn = document.querySelector('.att-edit-btn[data-id="' + id + '"]');
        var url     = editBtn ? editBtn.dataset.url : '';

        errDiv.classList.add('d-none');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" style="width:.75rem;height:.75rem;border-width:.1em;"></span>';

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fe fe-check fe-12 mr-1"></i>Save';

            if (!data.success) {
                errDiv.textContent = data.message || 'Save failed.';
                errDiv.classList.remove('d-none');
                return;
            }

            var row       = document.getElementById('att-row-' + id);
            var isPresent = data.is_present == 1;
            var isLeave   = data.is_leave   == 1;
            var isAbsent  = !isPresent && !isLeave;
            var approval  = parseInt(data.leave_approval, 10);

            // Update display cells
            row.querySelector('.att-cell-present').innerHTML =
                isPresent
                    ? '<span style="color:#161616;font-weight:600;">✓</span>'
                    : '<span class="text-muted">—</span>';

            row.querySelector('.att-cell-leave').innerHTML =
                isLeave
                    ? '<span style="color:#161616;font-weight:600;">✓</span>'
                    : '<span class="text-muted">—</span>';

            var approvalHtml;
            if (isLeave) {
                if (approval === 1) approvalHtml = '<span class="cds-status-tag cds-status-tag--done">Approved</span>';
                else if (approval === 2) approvalHtml = '<span class="cds-status-tag">Rejected</span>';
                else approvalHtml = '<span class="cds-status-tag">Pending</span>';
            } else {
                approvalHtml = '<span class="text-muted">—</span>';
            }
            row.querySelector('.att-cell-approval').innerHTML = approvalHtml;

            row.querySelector('.att-cell-absent').innerHTML =
                isAbsent
                    ? '<span style="color:#525252;font-weight:600;">✓</span>'
                    : '<span class="text-muted">—</span>';

            var statusHtml;
            if (isPresent) {
                statusHtml = '<span class="cds-status-tag cds-status-tag--done">Present</span>';
            } else if (isLeave && approval === 1) {
                statusHtml = '<span class="cds-status-tag cds-status-tag--done">Approved Leave</span>';
            } else if (isLeave && approval === 2) {
                statusHtml = '<span class="cds-status-tag">Rejected Leave</span>';
            } else if (isLeave) {
                statusHtml = '<span class="cds-status-tag">Pending Leave</span>';
            } else {
                statusHtml = '<span class="cds-status-tag">Absent</span>';
            }
            row.querySelector('.att-cell-status').innerHTML = statusHtml;

            row.querySelector('.att-cell-reason').innerHTML =
                '<span class="text-muted">' + esc(data.reason_for_leave || '—') + '</span>';

            // Sync edit button data attrs for the next open
            editBtn.dataset.status   = isPresent ? 'present' : (isLeave ? 'leave' : 'absent');
            editBtn.dataset.approval = data.leave_approval;
            editBtn.dataset.reason   = data.reason_for_leave || '';
            editBtn.dataset.hours    = data.work_hours || '';

            // Close edit row
            document.getElementById('att-edit-' + id).classList.add('d-none');

            showAlert('Attendance updated successfully.', 'success');
        })
        .catch(function () {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fe fe-check fe-12 mr-1"></i>Save';
            errDiv.textContent = 'Network error. Please try again.';
            errDiv.classList.remove('d-none');
        });
    });
})();
</script>
@endsection
