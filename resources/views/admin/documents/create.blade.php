@extends('admin.main')

@section('title', 'Asif Associates | New Agreement')

@section('content')
<div class="col-md-12 container-fluid">
    <a href="{{ route('e.documents') }}" wire:navigate class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left fe-16"></i> Back
    </a>

    <div class="row">
        <div class="col-md-10 my-4">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title"><i class="fe fe-file fe-16 mr-1"></i> New Agreement</strong>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('e.documents.store') }}" method="POST" id="docForm">
                        @csrf
                        <input type="hidden" name="type" value="agreement">

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label>Client / Company Name</label>
                                @php $selectedName = old('client_name', ''); @endphp
                                <select name="client_name" id="agreementClient"
                                        class="form-control @error('client_name') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach($clients->pluck('name')->values() as $name)
                                        <option value="{{ $name }}" {{ $selectedName === $name ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                    @if($selectedName && !$clients->pluck('name')->contains($selectedName))
                                        <option value="{{ $selectedName }}" selected>{{ $selectedName }}</option>
                                    @endif
                                </select>
                                @error('client_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label>Firm</label>
                                <select name="firm" class="form-control @error('firm') is-invalid @enderror">
                                    <option value="">— Select Firm —</option>
                                    <option value="0" {{ old('firm') === '0' ? 'selected' : '' }}>Asif Associates, Chartered Accountants</option>
                                    <option value="1" {{ old('firm') === '1' ? 'selected' : '' }}>H.A.M.D &amp; CO</option>
                                </select>
                                @error('firm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label>Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control">
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fe fe-list fe-16 mr-1"></i> Engagement Services &amp; Fees</h6>
                            <button type="button" class="btn btn-secondary btn-sm" id="addServiceRow">
                                <i class="fe fe-plus fe-12"></i> Add Row
                            </button>
                        </div>

                        <div id="servicesContainer">
                            @if(old('services'))
                                @foreach(old('services') as $i => $svc)
                                @php $preNames = isset($svc['names']) ? $svc['names'] : []; @endphp
                                <div class="row service-row align-items-start mb-2" data-index="{{ $i }}"
                                     data-svc="@json($preNames)"
                                     data-fee="{{ $svc['fee'] ?? '' }}">
                                    <div class="col-md-8" style="position:relative;">
                                        <div class="svc-multi-wrap" style="position:relative;"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control svc-fee" placeholder="Fee e.g. PKR 50,000">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-outline-secondary btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="row service-row align-items-start mb-2" data-index="0" data-svc="@json([])" data-fee="">
                                <div class="col-md-8" style="position:relative;">
                                    <div class="svc-multi-wrap" style="position:relative;"></div>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control svc-fee" placeholder="Fee e.g. PKR 50,000">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button>
                                </div>
                            </div>
                            @endif
                        </div>

                        <hr>

                        <div class="form-group mb-3">
                            <label>Notes / Additional Terms <small class="text-muted">(optional)</small></label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Any additional notes or terms...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-secondary btn-lg">
                                <i class="fe fe-save fe-16"></i> Generate Agreement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function initAgreementSelect2() {
    if (!document.getElementById('agreementClient')) return;
    if (typeof jQuery === 'undefined' || !jQuery.fn.select2) { setTimeout(initAgreementSelect2, 80); return; }
    if (jQuery('#agreementClient').data('select2')) jQuery('#agreementClient').select2('destroy');
    jQuery('#agreementClient').select2({
        width: '100%',
        placeholder: '-- Select or type client name --',
        tags: true,
        allowClear: false
    });
}
document.removeEventListener('livewire:navigated', initAgreementSelect2);
document.addEventListener('livewire:navigated', initAgreementSelect2);
initAgreementSelect2();

(function () {
    var SVC_GROUPS = [
        { label: 'Taxation Services', items: [
            'Taxation Services', 'Income Tax Return (u/s 114)',
            'Tax Withholding Statements (u/s 165)',
            'Sale Tax Returns – All Provinces / Federal',
            'Income Tax Litigation Letters (ITO 2001)',
            'Sales Tax Litigation Letters (ST Act 1990)',
            'Section 100C Exemption Letters',
            'Rectification & Penalty Waiver Application',
            'FBR / KPRA / BRA / SRB / AJ&K Representation',
            'Tax Opinions'
        ]},
        { label: 'Audit & Assurance Services', items: [
            'Audit & Assurance Services', 'Statutory Audit',
            'Internal Audit', 'Review Engagement', 'Forensic Audit', 'Special Purpose Audit'
        ]},
        { label: 'Advisory & Consultancy Support', items: [
            'Advisory & Consultancy Support', 'Book Keeping Services',
            'Financial Modeling Services', 'NBFC Consultancy Services', 'Budgeting'
        ]},
        { label: 'Corporate & SECP Compliance', items: [
            'Corporate & SECP Compliance', 'Annual Return Filing',
            'Form A Filing', 'Form B Filing', 'Form 19 Filing', 'Company Incorporation'
        ]}
    ];

    function esc(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function makePanelHTML() {
        var h = '<div class="svc-trigger form-control d-flex align-items-center justify-content-between" style="cursor:pointer;user-select:none;min-height:38px;height:auto;">';
        h += '<span class="svc-label text-muted">— Select services —</span>';
        h += '<i class="fe fe-chevron-down fe-12 ml-2 flex-shrink-0"></i></div>';
        h += '<div class="svc-panel" style="display:none;position:absolute;top:100%;left:0;right:0;z-index:1050;background:#fff;border:1px solid #c6c6c6;max-height:280px;overflow-y:auto;box-shadow:0 4px 12px rgba(0,0,0,.12);">';
        h += '<div style="padding:8px 12px 6px;">';
        h += '<label style="cursor:pointer;display:flex;align-items:center;gap:6px;margin-bottom:0;font-weight:400;"><input type="checkbox" class="svc-cb" value="__custom__"> <em>&#9999;&#xFE0E; Specify yourself&hellip;</em></label>';
        h += '<input type="text" class="form-control form-control-sm svc-custom-text mt-1" placeholder="Type service name..." style="display:none;"></div>';
        h += '<div style="border-top:1px solid #e0e0e0;"></div>';
        SVC_GROUPS.forEach(function (g) {
            h += '<div style="padding:6px 12px;"><div style="font-size:10px;text-transform:uppercase;letter-spacing:.5px;color:#8d8d8d;font-weight:700;margin-bottom:4px;">' + esc(g.label) + '</div>';
            g.items.forEach(function (item) {
                h += '<label style="cursor:pointer;display:flex;align-items:center;gap:6px;margin-bottom:3px;font-weight:400;"><input type="checkbox" class="svc-cb" value="' + esc(item) + '"> ' + esc(item) + '</label>';
            });
            h += '</div><div style="border-top:1px solid #e0e0e0;"></div>';
        });
        h += '</div>';
        return h;
    }

    function findCb(wrap, val) {
        var cbs = wrap.querySelectorAll('.svc-cb');
        for (var i = 0; i < cbs.length; i++) { if (cbs[i].value === val) return cbs[i]; }
        return null;
    }

    function syncLabel(wrap) {
        var names = [];
        wrap.querySelectorAll('.svc-cb:checked').forEach(function (cb) {
            if (cb.value === '__custom__') {
                var t = wrap.querySelector('.svc-custom-text');
                if (t && t.value.trim()) names.push(t.value.trim());
            } else names.push(cb.value);
        });
        var lbl = wrap.querySelector('.svc-label');
        if (!names.length) { lbl.textContent = '— Select services —'; lbl.className = 'svc-label text-muted'; }
        else if (names.length === 1) { lbl.textContent = names[0]; lbl.className = 'svc-label'; }
        else { lbl.textContent = names.length + ' services selected'; lbl.className = 'svc-label'; }
    }

    function initWrap(wrap) {
        var trigger = wrap.querySelector('.svc-trigger');
        var panel   = wrap.querySelector('.svc-panel');
        var customCb   = findCb(wrap, '__custom__');
        var customText = wrap.querySelector('.svc-custom-text');
        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = panel.style.display !== 'none';
            document.querySelectorAll('.svc-panel').forEach(function (p) { p.style.display = 'none'; });
            panel.style.display = open ? 'none' : 'block';
        });
        panel.addEventListener('click', function (e) { e.stopPropagation(); });
        if (customCb) {
            customCb.addEventListener('change', function () {
                customText.style.display = this.checked ? 'block' : 'none';
                if (this.checked) customText.focus();
                syncLabel(wrap);
            });
        }
        if (customText) customText.addEventListener('input', function () { syncLabel(wrap); });
        wrap.querySelectorAll('.svc-cb:not([value="__custom__"])').forEach(function (cb) {
            cb.addEventListener('change', function () { syncLabel(wrap); });
        });
    }

    function initRow(row) {
        var wrap = row.querySelector('.svc-multi-wrap');
        if (!wrap) return;
        wrap.innerHTML = makePanelHTML();
        var preNames = [];
        try { preNames = JSON.parse(row.dataset.svc || '[]'); } catch (e) {}
        var feeVal   = row.dataset.fee || '';
        var feeInput = row.querySelector('.svc-fee');
        if (feeInput && feeVal) feeInput.value = feeVal;
        preNames.forEach(function (name) {
            if (!name) return;
            var cb = findCb(wrap, name);
            if (cb) { cb.checked = true; }
            else {
                var ccb = findCb(wrap, '__custom__');
                var ct  = wrap.querySelector('.svc-custom-text');
                if (ccb && ct) { ccb.checked = true; ct.value = name; ct.style.display = 'block';}
            }
        });
        syncLabel(wrap);
        initWrap(wrap);
    }

    document.addEventListener('click', function () {
        document.querySelectorAll('.svc-panel').forEach(function (p) { p.style.display = 'none'; });
    });

    var rowIndex = 0;

    function bindSvcControls() {
        var container = document.getElementById('servicesContainer');
        if (!container || container.dataset.bound) return;
        container.dataset.bound = '1';

        document.getElementById('addServiceRow').addEventListener('click', function () {
            var row = document.createElement('div');
            row.className = 'row service-row align-items-start mb-2';
            row.dataset.index = rowIndex;
            row.dataset.svc   = '[]';
            row.dataset.fee   = '';
            row.setAttribute('data-svc-init', '1');
            row.innerHTML =
                '<div class="col-md-8" style="position:relative;"><div class="svc-multi-wrap" style="position:relative;"></div></div>' +
                '<div class="col-md-3"><input type="text" class="form-control svc-fee" placeholder="Fee e.g. PKR 50,000"></div>' +
                '<div class="col-md-1"><button type="button" class="btn btn-outline-secondary btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button></div>';
            container.appendChild(row);
            initRow(row);
            rowIndex++;
        });

        container.addEventListener('click', function (e) {
            var btn = e.target.closest('.removeRow');
            if (btn && document.querySelectorAll('.service-row').length > 1) btn.closest('.service-row').remove();
        });

        document.getElementById('docForm').addEventListener('submit', function (e) {
            this.querySelectorAll('.gen-svc').forEach(function (el) { el.remove(); });
            var form = this, si = 0, valid = true;
            document.querySelectorAll('.service-row').forEach(function (row) {
                var wrap = row.querySelector('.svc-multi-wrap');
                var fee  = (row.querySelector('.svc-fee') || { value: '' }).value || '';
                var names = [];
                wrap.querySelectorAll('.svc-cb:checked').forEach(function (cb) {
                    if (cb.value === '__custom__') {
                        var t = wrap.querySelector('.svc-custom-text');
                        if (t && t.value.trim()) names.push(t.value.trim());
                    } else names.push(cb.value);
                });
                if (!names.length) {
                    valid = false;
                    var tr = row.querySelector('.svc-trigger');
                    if (tr) tr.style.outline = '2px solid #dc3545';
                    return;
                }
                names.forEach(function (name) {
                    var n = document.createElement('input');
                    n.type = 'hidden'; n.className = 'gen-svc';
                    n.name = 'services[' + si + '][names][]'; n.value = name;
                    form.appendChild(n);
                });
                var f = document.createElement('input');
                f.type = 'hidden'; f.className = 'gen-svc';
                f.name = 'services[' + si + '][fee]'; f.value = fee;
                form.appendChild(f);
                si++;
            });
            if (!valid) e.preventDefault();
        });
    }

    function initSvcRows() {
        if (!document.getElementById('servicesContainer')) return;
        document.querySelectorAll('.service-row:not([data-svc-init])').forEach(function (row) {
            row.setAttribute('data-svc-init', '1');
            var idx = parseInt(row.dataset.index || 0);
            if (idx >= rowIndex) rowIndex = idx + 1;
            initRow(row);
        });
        bindSvcControls();
    }

    document.removeEventListener('livewire:navigated', initSvcRows);
    document.addEventListener('livewire:navigated', initSvcRows);
    setTimeout(initSvcRows, 0);
    initSvcRows();
})();
</script>
@endsection
