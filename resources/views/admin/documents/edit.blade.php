@extends('admin.main')

@section('title', 'Asif Associates | Edit Agreement')

@section('content')
<div class="col-md-12 container-fluid">
    <a href="{{ route('e.documents') }}" wire:navigate class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left fe-16"></i> Back
    </a>

    <div class="row my-2">

        {{-- Left: Agreement Details --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title"><i class="fe fe-edit-2 fe-16 mr-1"></i> Edit Agreement — {{ $doc->client_name }}</strong>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success py-2">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('e.documents.update', $doc->id) }}" method="POST" id="docForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Client / Company Name</label>
                                @php
                                    $selectedName = old('client_name', $doc->client_name);
                                    $clientNames  = $clients->pluck('name')->values()->toArray();
                                    if ($selectedName && !in_array($selectedName, $clientNames)) {
                                        array_unshift($clientNames, $selectedName);
                                    }
                                @endphp
                                <select name="client_name" id="agreementClient"
                                        class="form-control @error('client_name') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach($clientNames as $name)
                                        <option value="{{ $name }}" {{ $selectedName === $name ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('client_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label>Firm</label>
                                <select name="firm" class="form-control @error('firm') is-invalid @enderror">
                                    <option value="">— Select Firm —</option>
                                    <option value="0" {{ old('firm', $doc->firm) == '0' ? 'selected' : '' }}>Asif Associates, Chartered Accountants</option>
                                    <option value="1" {{ old('firm', $doc->firm) == '1' ? 'selected' : '' }}>H.A.M.D &amp; CO</option>
                                </select>
                                @error('firm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label>Start Date</label>
                                <input type="date" name="start_date"
                                       value="{{ old('start_date', $doc->start_date?->format('Y-m-d')) }}"
                                       class="form-control">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label>End Date</label>
                                <input type="date" name="end_date"
                                       value="{{ old('end_date', $doc->end_date?->format('Y-m-d')) }}"
                                       class="form-control">
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fe fe-list fe-16 mr-1"></i> Engagement Services &amp; Fees</h6>
                            <button type="button" class="btn btn-secondary btn-sm" id="addServiceRow">
                                <i class="fe fe-plus fe-12"></i> Add Service
                            </button>
                        </div>

                        @php
                            $services = old('services', $doc->services ?? []);
                            if (empty($services)) { $services = [['names' => [], 'fee' => '']]; }
                            $svcPreData = array_values(array_map(function($svc) {
                                return [
                                    'names' => isset($svc['names']) ? $svc['names'] : (isset($svc['name']) ? [$svc['name']] : []),
                                    'fee'   => $svc['fee'] ?? '',
                                ];
                            }, $services));
                        @endphp
                        <script id="svcPreData" type="application/json">{!! json_encode($svcPreData, JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>

                        <div id="servicesContainer">
                            @foreach($services as $i => $svc)
                            <div class="row service-row align-items-start mb-2" data-index="{{ $i }}">
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
                        </div>

                        <hr>

                        <div class="form-group mb-3">
                            <label>Notes / Additional Terms <small class="text-muted">(optional)</small></label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Any additional notes or terms...">{{ old('notes', $doc->notes) }}</textarea>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('e.documents.view', $doc->id) }}" target="_blank" class="btn btn-outline-secondary mr-2">
                                <i class="fe fe-printer fe-16"></i> Preview
                            </a>
                            <button type="submit" class="btn btn-secondary btn-lg">
                                <i class="fe fe-save fe-16"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right: Signed PDF --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong class="card-title mb-0"><i class="fe fe-file fe-16 mr-1"></i> Signed PDF</strong>
                    @if($doc->signed_pdf)
                        <span class="cds-status-tag cds-status-tag--done">On file</span>
                    @else
                        <span class="cds-status-tag">None</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($doc->signed_pdf)
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2" style="gap:8px;">
                                <i class="fe fe-file-text fe-16 text-muted"></i>
                                <span class="text-muted small">Signed agreement uploaded</span>
                            </div>
                            <a href="{{ route('e.documents.signed-pdf', $doc->id) }}"
                               class="btn btn-secondary btn-sm btn-block mb-2">
                                <i class="fe fe-download fe-12 mr-1"></i> Download PDF
                            </a>
                            <form action="{{ route('e.documents.signed-pdf.destroy', $doc->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary btn-sm btn-block"
                                        data-confirm-delete="Remove the signed PDF? This cannot be undone.">
                                    <i class="fe fe-trash-2 fe-12 mr-1"></i> Remove PDF
                                </button>
                            </form>
                        </div>
                        <hr>
                        <p class="text-muted small mb-2">Replace with a new file:</p>
                    @endif

                    <form action="{{ route('e.documents.signed-pdf.upload', $doc->id) }}" method="POST" enctype="multipart/form-data" id="pdfUploadForm">
                        @csrf
                        @error('signed_pdf')
                            <div class="alert alert-danger py-2 mb-2">{{ $message }}</div>
                        @enderror
                        <div class="form-group mb-2">
                            <div id="pdfDropZone" onclick="document.getElementById('pdfFileInput').click()"
                                 style="border:2px dashed #c6c6c6;padding:28px 16px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;background:#f4f4f4;">
                                <div id="pdfDropIcon" style="font-size:2rem;margin-bottom:6px;color:#8d8d8d;">
                                    <i class="fe fe-upload-cloud"></i>
                                </div>
                                <div id="pdfDropLabel" style="font-weight:600;color:var(--cds-text-primary);margin-bottom:4px;">
                                    Click or drag &amp; drop PDF here
                                </div>
                                <div style="font-size:12px;color:#8d8d8d;">PDF only &nbsp;•&nbsp; Max 5 MB</div>
                                <div id="pdfFileInfo" style="display:none;margin-top:10px;">
                                    <span style="display:inline-flex;align-items:center;gap:8px;background:#e0e0e0;border:1px solid #8d8d8d;padding:5px 14px;font-size:13px;color:var(--cds-text-primary);font-weight:600;">
                                        <i class="fe fe-file fe-12"></i>
                                        <span id="pdfFileName"></span>
                                        <span id="pdfFileSize" style="font-weight:400;color:#525252;"></span>
                                    </span>
                                </div>
                            </div>
                            <input type="file" name="signed_pdf" id="pdfFileInput" accept="application/pdf" style="display:none" required>
                            <small class="text-muted">PDF only &middot; max 5 MB</small>
                        </div>
                        <div id="pdfProgress" style="display:none;margin-bottom:10px;">
                            <div style="display:flex;justify-content:space-between;font-size:12px;color:#525252;margin-bottom:4px;">
                                <span>Uploading...</span><span id="pdfProgressPct">0%</span>
                            </div>
                            <div class="progress">
                                <div id="pdfProgressBar" class="progress-bar" role="progressbar" style="width:0%;"></div>
                            </div>
                        </div>
                        <button type="submit" id="pdfSubmitBtn" class="btn btn-secondary btn-sm btn-block">
                            <i class="fe fe-upload fe-12 mr-1"></i> {{ $doc->signed_pdf ? 'Replace PDF' : 'Upload Signed PDF' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    var dropZone  = document.getElementById('pdfDropZone');
    var fileInput = document.getElementById('pdfFileInput');
    if (dropZone && fileInput) {
        function formatBytes(b) { return b < 1048576 ? (b/1024).toFixed(1)+' KB' : (b/1048576).toFixed(1)+' MB'; }
        function showFile(file) {
            document.getElementById('pdfFileName').textContent = file.name;
            document.getElementById('pdfFileSize').textContent = '(' + formatBytes(file.size) + ')';
            document.getElementById('pdfFileInfo').style.display = 'block';
            document.getElementById('pdfDropLabel').textContent = 'File selected';
            document.getElementById('pdfDropIcon').innerHTML = '<i class="fe fe-check-circle" style="color:#161616;"></i>';
            dropZone.style.borderColor = '#161616'; dropZone.style.background = '#e0e0e0';
        }
        fileInput.addEventListener('change', function () { if (this.files[0]) showFile(this.files[0]); });
        dropZone.addEventListener('dragover',  function (e) { e.preventDefault(); this.style.borderColor='#525252'; this.style.background='#e0e0e0'; });
        dropZone.addEventListener('dragleave', function ()  { this.style.borderColor='#c6c6c6'; this.style.background='#f4f4f4'; });
        dropZone.addEventListener('drop', function (e) {
            e.preventDefault(); this.style.borderColor='#c6c6c6'; this.style.background='#f4f4f4';
            var file = e.dataTransfer.files[0];
            if (file) { var dt = new DataTransfer(); dt.items.add(file); fileInput.files = dt.files; showFile(file); }
        });
        document.getElementById('pdfUploadForm').addEventListener('submit', function (e) {
            if (!fileInput.files[0]) return;
            e.preventDefault();
            var form = this, bar = document.getElementById('pdfProgressBar'),
                pct = document.getElementById('pdfProgressPct'), btn = document.getElementById('pdfSubmitBtn');
            document.getElementById('pdfProgress').style.display = 'block';
            btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span> Uploading...';
            var xhr = new XMLHttpRequest();
            xhr.upload.onprogress = function (e) { if (e.lengthComputable) { var p=Math.round(e.loaded/e.total*100); bar.style.width=p+'%'; pct.textContent=p+'%'; } };
            xhr.upload.addEventListener('load', function () { bar.style.width='100%'; pct.textContent='100%'; btn.innerHTML='<span class="spinner-border spinner-border-sm mr-1"></span> Processing...'; });
            xhr.onload  = function () { window.location.href = xhr.responseURL; };
            xhr.onerror = function () { btn.disabled=false; btn.innerHTML='<i class="fe fe-upload fe-12 mr-1"></i> Upload Signed PDF'; document.getElementById('pdfProgress').style.display='none'; alert('Upload failed. Please try again.'); };
            xhr.open('POST', form.action); xhr.send(new FormData(form));
        });
    }
})();

function initAgreementSelect2() {
    if (!document.getElementById('agreementClient')) return;
    if (typeof jQuery === 'undefined' || !jQuery.fn.select2) { setTimeout(initAgreementSelect2, 80); return; }
    if (jQuery('#agreementClient').data('select2')) jQuery('#agreementClient').select2('destroy');
    jQuery('#agreementClient').select2({ width:'100%', placeholder:'-- Select or type client name --', tags:true, allowClear:false });
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
            'Audit & Assurance Services', 'Statutory Audits',
            'Internal Audits', 'Review Engagements', 'Forensic Audits', 'Special Purpose Audits'
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

    function esc(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    function makePanelHTML() {
        var h = '<div class="svc-trigger form-control d-flex align-items-center justify-content-between" style="cursor:pointer;user-select:none;min-height:38px;height:auto;">';
        h += '<span class="svc-label text-muted">— Select services —</span>';
        h += '<i class="fe fe-chevron-down fe-12 ml-2 flex-shrink-0"></i></div>';
        h += '<div class="svc-panel" style="display:none;position:absolute;top:100%;left:0;right:0;z-index:1050;background:#fff;border:1px solid #c6c6c6;max-height:280px;overflow-y:auto;box-shadow:0 4px 12px rgba(0,0,0,.12);">';
        h += '<div style="padding:8px 12px 6px;"><label style="cursor:pointer;display:flex;align-items:center;gap:6px;margin-bottom:0;font-weight:400;"><input type="checkbox" class="svc-cb" value="__custom__"> <em>&#9999;&#xFE0E; Specify yourself&hellip;</em></label>';
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
            if (cb.value === '__custom__') { var t = wrap.querySelector('.svc-custom-text'); if (t && t.value.trim()) names.push(t.value.trim()); }
            else names.push(cb.value);
        });
        var lbl = wrap.querySelector('.svc-label');
        if (!names.length) { lbl.textContent = '— Select services —'; lbl.className = 'svc-label text-muted'; }
        else if (names.length === 1) { lbl.textContent = names[0]; lbl.className = 'svc-label'; }
        else { lbl.textContent = names.length + ' services selected'; lbl.className = 'svc-label'; }
    }

    function initWrap(wrap) {
        var trigger = wrap.querySelector('.svc-trigger'), panel = wrap.querySelector('.svc-panel');
        var customCb = findCb(wrap, '__custom__'), customText = wrap.querySelector('.svc-custom-text');
        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = panel.style.display !== 'none';
            document.querySelectorAll('.svc-panel').forEach(function (p) { p.style.display = 'none'; });
            panel.style.display = open ? 'none' : 'block';
        });
        panel.addEventListener('click', function (e) { e.stopPropagation(); });
        if (customCb) { customCb.addEventListener('change', function () { customText.style.display = this.checked ? 'block' : 'none'; if (this.checked) customText.focus(); syncLabel(wrap); }); }
        if (customText) customText.addEventListener('input', function () { syncLabel(wrap); });
        wrap.querySelectorAll('.svc-cb:not([value="__custom__"])').forEach(function (cb) { cb.addEventListener('change', function () { syncLabel(wrap); }); });
    }

    function initRow(row, data) {
        var wrap = row.querySelector('.svc-multi-wrap');
        if (!wrap) return;
        wrap.innerHTML = makePanelHTML();
        var preNames = (data && Array.isArray(data.names)) ? data.names : [];
        var feeVal   = (data && data.fee) ? data.fee : '';
        var feeInput = row.querySelector('.svc-fee');
        if (feeInput && feeVal) feeInput.value = feeVal;
        preNames.forEach(function (name) {
            if (!name) return;
            var cb = findCb(wrap, name);
            if (cb) { cb.checked = true; }
            else { var ccb = findCb(wrap, '__custom__'), ct = wrap.querySelector('.svc-custom-text'); if (ccb && ct) { ccb.checked = true; ct.value = name; ct.style.display = 'block'; } }
        });
        syncLabel(wrap); initWrap(wrap);
    }

    document.addEventListener('click', function () { document.querySelectorAll('.svc-panel').forEach(function (p) { p.style.display = 'none'; }); });

    var rowIndex = 0;

    function bindSvcControls() {
        var container = document.getElementById('servicesContainer');
        if (!container || container.dataset.bound) return;
        container.dataset.bound = '1';

        document.getElementById('addServiceRow').addEventListener('click', function () {
            var row = document.createElement('div');
            row.className = 'row service-row align-items-start mb-2';
            row.dataset.index = rowIndex;
            row.setAttribute('data-svc-init', '1');
            row.innerHTML =
                '<div class="col-md-8" style="position:relative;"><div class="svc-multi-wrap" style="position:relative;"></div></div>' +
                '<div class="col-md-3"><input type="text" class="form-control svc-fee" placeholder="Fee e.g. PKR 50,000"></div>' +
                '<div class="col-md-1"><button type="button" class="btn btn-outline-secondary btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button></div>';
            container.appendChild(row);
            initRow(row, null); rowIndex++;
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
                    if (cb.value === '__custom__') { var t = wrap.querySelector('.svc-custom-text'); if (t && t.value.trim()) names.push(t.value.trim()); }
                    else names.push(cb.value);
                });
                if (!names.length) { valid = false; var tr = row.querySelector('.svc-trigger'); if (tr) tr.style.outline = '2px solid #dc3545'; return; }
                names.forEach(function (name) {
                    var n = document.createElement('input'); n.type='hidden'; n.className='gen-svc'; n.name='services['+si+'][names][]'; n.value=name; form.appendChild(n);
                });
                var f = document.createElement('input'); f.type='hidden'; f.className='gen-svc'; f.name='services['+si+'][fee]'; f.value=fee; form.appendChild(f);
                si++;
            });
            if (!valid) e.preventDefault();
        });
    }

    function initSvcRows() {
        if (!document.getElementById('servicesContainer')) return;
        var preData = [];
        try { var el = document.getElementById('svcPreData'); if (el) preData = JSON.parse(el.textContent); } catch(e) {}
        console.log('[svc] initSvcRows — preData:', preData);
        document.querySelectorAll('.service-row:not([data-svc-init])').forEach(function (row) {
            row.setAttribute('data-svc-init', '1');
            var idx = parseInt(row.dataset.index || 0);
            if (idx >= rowIndex) rowIndex = idx + 1;
            console.log('[svc] row', idx, '— data:', preData[idx]);
            initRow(row, preData[idx] || null);
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
