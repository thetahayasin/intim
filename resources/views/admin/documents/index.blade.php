@extends('admin.main')

@section('title', 'Asif Associates | Agreements')

@section('content')
<div class="col-md-12 container-fluid">

    @include('components.message')

    {{-- Stats Row --}}
    <div class="row my-4">
        <div class="col-6 col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body py-3">
                    <small class="text-muted d-block">Total</small>
                    <span class="aa-color" style="font-size:1.5rem;font-weight:700;">{{ $stats['total'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body py-3">
                    <small class="text-muted d-block">Agreements</small>
                    <span style="font-size:1.5rem;font-weight:700;color:var(--cds-text-primary);">{{ $stats['agreements'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body py-3">
                    <small class="text-muted d-block">Asif Associates</small>
                    <span style="font-size:1.5rem;font-weight:700;color:var(--cds-text-secondary);">{{ $stats['aa'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body py-3">
                    <small class="text-muted d-block">HAMD</small>
                    <span style="font-size:1.5rem;font-weight:700;color:var(--cds-text-secondary);">{{ $stats['hamd'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-2 mb-3 d-flex align-items-center">
            <a href="{{ route('e.documents.create') }}" wire:navigate class="btn btn-secondary btn-block">
                <i class="fe fe-plus fe-16 mr-1"></i> New
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('e.documents') }}">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="small text-muted mb-1 d-block">Client Name</label>
                        <input type="text" name="client" value="{{ request('client') }}"
                               class="form-control" placeholder="Search client...">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 d-block">Firm</label>
                        <select name="firm" class="form-control">
                            <option value="">All Firms</option>
                            <option value="0" {{ request('firm') === '0' ? 'selected' : '' }}>Asif Associates</option>
                            <option value="1" {{ request('firm') === '1' ? 'selected' : '' }}>H.A.M.D &amp; CO</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 d-block">From</label>
                        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small text-muted mb-1 d-block">To</label>
                        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                    </div>
                    <div class="col-md-3 mb-2 d-flex" style="gap:8px;">
                        <button type="submit" class="btn btn-secondary flex-fill">
                            <i class="fe fe-search fe-14 mr-1"></i> Filter
                        </button>
                        <a href="{{ route('e.documents') }}" class="btn btn-outline-secondary flex-fill">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-header">
            <strong class="card-title mb-0"><i class="fe fe-file-text fe-16 mr-1"></i> Documents</strong>
        </div>
        <div class="cds-table-wrap">
            <table class="table table-hover mb-0 text-center">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Firm</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Signed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>{{ $doc->created_at->format('d M Y') }}</td>
                        <td><strong>{{ $doc->client_name }}</strong></td>
                        <td><small>{{ $doc->firm_name }}</small></td>
                        <td>
                            @if($doc->start_date && $doc->end_date)
                                <small>{{ $doc->start_date->format('d M Y') }} – {{ $doc->end_date->format('d M Y') }}</small>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                        <td>
                            @if($doc->status === 'final')
                                <span class="cds-status-tag cds-status-tag--done" title="Finalised">Final</span>
                            @else
                                <span class="cds-status-tag" title="Draft">Draft</span>
                            @endif
                        </td>
                        <td>
                            @if($doc->signed_pdf)
                                <span class="cds-status-tag cds-status-tag--done" title="Signed PDF on file">
                                    <i class="fe fe-check fe-12 mr-1"></i>PDF
                                </span>
                            @else
                                <span class="text-muted" style="font-size:12px;">—</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            {{-- View / Print --}}
                            <a href="{{ route('e.documents.view', $doc->id) }}"
                               class="btn btn-secondary btn-sm" title="View / Print">
                                <i class="fe fe-printer fe-12"></i>
                            </a>

                            {{-- Signed PDF dropdown --}}
                            <div class="btn-group" style="margin-left:4px;">
                                <button type="button"
                                        class="btn btn-secondary btn-sm dropdown-toggle"
                                        data-toggle="dropdown" data-boundary="window"
                                        aria-haspopup="true" aria-expanded="false"
                                        title="Signed PDF">
                                    <i class="fe fe-file fe-12"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="min-width:180px;">
                                    @if($doc->signed_pdf)
                                        <a class="dropdown-item"
                                           href="{{ route('e.documents.signed-pdf', $doc->id) }}">
                                            <i class="fe fe-download fe-12 mr-2"></i> Download PDF
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger"
                                                onclick="confirmRemovePdf({{ $doc->id }})">
                                            <i class="fe fe-trash-2 fe-12 mr-2"></i> Remove PDF
                                        </button>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    <button type="button" class="dropdown-item"
                                            onclick="openUploadModal({{ $doc->id }}, '{{ addslashes($doc->client_name) }}')">
                                        <i class="fe fe-upload fe-12 mr-2"></i>
                                        {{ $doc->signed_pdf ? 'Replace PDF' : 'Upload PDF' }}
                                    </button>
                                </div>
                            </div>

                            {{-- Edit --}}
                            <a href="{{ route('e.documents.edit', $doc->id) }}" wire:navigate
                               class="btn btn-secondary btn-sm" title="Edit" style="margin-left:4px;">
                                <i class="fe fe-edit-2 fe-12"></i>
                            </a>

                            {{-- Delete document --}}
                            <form action="{{ route('e.documents.destroy', $doc->id) }}" method="POST"
                                  class="d-inline" style="margin-left:4px;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm"
                                        data-confirm-delete="Delete this document? This cannot be undone.">
                                    <i class="fe fe-trash-2 fe-12"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No documents found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body pt-2">{{ $documents->links('pagination::bootstrap-4') }}</div>
    </div>

</div>

{{-- Upload PDF modal --}}
<div class="modal fade" id="uploadPdfModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fe fe-upload fe-16 mr-2"></i> Upload Signed Agreement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadPdfForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-2" id="modalPdfClientLabel"></p>
                    <div class="form-group mb-2">
                        <div id="modalDropZone" onclick="document.getElementById('modalPdfInput').click()"
                             style="border:2px dashed #c6c6c6;padding:28px 16px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;background:#f4f4f4;">
                            <div id="modalDropIcon" style="font-size:2rem;margin-bottom:6px;color:#8d8d8d;">
                                <i class="fe fe-upload-cloud"></i>
                            </div>
                            <div id="modalDropLabel" style="font-weight:600;color:var(--cds-text-primary);margin-bottom:4px;">
                                Click or drag &amp; drop PDF here
                            </div>
                            <div style="font-size:12px;color:#8d8d8d;">PDF only &nbsp;•&nbsp; Max 5 MB</div>
                            <div id="modalFileInfo" style="display:none;margin-top:10px;">
                                <span style="display:inline-flex;align-items:center;gap:8px;background:#e0e0e0;border:1px solid #8d8d8d;padding:5px 14px;font-size:13px;color:var(--cds-text-primary);font-weight:600;">
                                    <i class="fe fe-file fe-12"></i>
                                    <span id="modalFileName"></span>
                                    <span id="modalFileSize" style="font-weight:400;color:#525252;"></span>
                                </span>
                            </div>
                        </div>
                        <input type="file" name="signed_pdf" id="modalPdfInput" accept="application/pdf" style="display:none" required>
                        <small class="text-muted">PDF only &middot; max 5 MB</small>
                    </div>
                    <div id="modalProgress" style="display:none;">
                        <div style="display:flex;justify-content:space-between;font-size:12px;color:#525252;margin-bottom:4px;">
                            <span>Uploading...</span><span id="modalProgressPct">0%</span>
                        </div>
                        <div class="progress">
                            <div id="modalProgressBar" class="progress-bar" role="progressbar" style="width:0%;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="modalSubmitBtn" class="btn btn-secondary">
                        <i class="fe fe-upload fe-12 mr-1"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden form for PDF removal --}}
<form id="removePdfForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
(function() {
    var dropZone  = null;
    var fileInput = null;

    function formatBytes(b) {
        if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
        return (b / 1048576).toFixed(1) + ' MB';
    }

    function showFile(file) {
        document.getElementById('modalFileName').textContent = file.name;
        document.getElementById('modalFileSize').textContent = '(' + formatBytes(file.size) + ')';
        document.getElementById('modalFileInfo').style.display = 'block';
        document.getElementById('modalDropLabel').textContent = 'File selected';
        document.getElementById('modalDropIcon').innerHTML = '<i class="fe fe-check-circle" style="color:#161616;"></i>';
        dropZone.style.borderColor = '#161616';
        dropZone.style.background  = '#e0e0e0';
    }

    function resetModal() {
        dropZone  = document.getElementById('modalDropZone');
        fileInput = document.getElementById('modalPdfInput');
        fileInput.value = '';
        document.getElementById('modalFileInfo').style.display = 'none';
        document.getElementById('modalDropLabel').textContent = 'Click or drag & drop PDF here';
        document.getElementById('modalDropIcon').innerHTML = '<i class="fe fe-upload-cloud"></i>';
        document.getElementById('modalProgress').style.display = 'none';
        document.getElementById('modalProgressBar').style.width = '0%';
        document.getElementById('modalProgressPct').textContent = '0%';
        document.getElementById('modalSubmitBtn').disabled = false;
        document.getElementById('modalSubmitBtn').innerHTML = '<i class="fe fe-upload fe-12 mr-1"></i> Upload';
        dropZone.style.borderColor = '#c6c6c6';
        dropZone.style.background  = '#f4f4f4';

        fileInput.onchange = function() { if (this.files[0]) showFile(this.files[0]); };
        dropZone.ondragover  = function(e) { e.preventDefault(); this.style.borderColor = '#525252'; this.style.background = '#e0e0e0'; };
        dropZone.ondragleave = function()  { this.style.borderColor = '#c6c6c6'; this.style.background = '#f4f4f4'; };
        dropZone.ondrop = function(e) {
            e.preventDefault(); this.style.borderColor = '#c6c6c6'; this.style.background = '#f4f4f4';
            var file = e.dataTransfer.files[0];
            if (file) { var dt = new DataTransfer(); dt.items.add(file); fileInput.files = dt.files; showFile(file); }
        };
    }

    window.openUploadModal = function(docId, clientName) {
        document.getElementById('uploadPdfForm').action = '/e/documents/' + docId + '/signed-pdf';
        document.getElementById('modalPdfClientLabel').textContent = 'Client: ' + clientName;
        resetModal();
        jQuery('#uploadPdfModal').modal('show');
    };

    jQuery(document).on('hidden.bs.modal', '#uploadPdfModal', function() { resetModal(); });

    document.getElementById('uploadPdfForm').addEventListener('submit', function(e) {
        fileInput = document.getElementById('modalPdfInput');
        if (!fileInput.files[0]) return;
        e.preventDefault();
        var form = this;
        var bar  = document.getElementById('modalProgressBar');
        var pct  = document.getElementById('modalProgressPct');
        var btn  = document.getElementById('modalSubmitBtn');
        document.getElementById('modalProgress').style.display = 'block';
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span> Uploading...';
        var xhr = new XMLHttpRequest();
        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) { var p = Math.round(e.loaded / e.total * 100); bar.style.width = p + '%'; pct.textContent = p + '%'; }
        };
        xhr.upload.addEventListener('load', function() { bar.style.width = '100%'; pct.textContent = '100%'; btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span> Processing...'; });
        xhr.onload  = function() { window.location.href = xhr.responseURL; };
        xhr.onerror = function() { btn.disabled = false; btn.innerHTML = '<i class="fe fe-upload fe-12 mr-1"></i> Upload'; document.getElementById('modalProgress').style.display = 'none'; alert('Upload failed. Please try again.'); };
        xhr.open('POST', form.action);
        xhr.send(new FormData(form));
    });
})();

function confirmRemovePdf(docId) {
    if (!confirm('Remove the signed PDF? This cannot be undone.')) return;
    var form = document.getElementById('removePdfForm');
    form.action = '/e/documents/' + docId + '/signed-pdf';
    form.submit();
}
</script>
@endsection
