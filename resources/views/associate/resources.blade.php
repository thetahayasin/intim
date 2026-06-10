@extends('associate.main')

@section('title', 'Asif Associates | Resources')

@section('content')
<div class="col-md-12 container-fluid">

    <div class="my-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0"><i class="fe fe-folder fe-16 mr-2"></i> Resources</h4>
            <small class="text-muted">Templates, formats and documents from Asif Associates</small>
        </div>
        <button class="btn btn-secondary" data-toggle="collapse" data-target="#uploadForm">
            <i class="fe fe-upload fe-14 mr-1"></i> Upload Resource
        </button>
    </div>

    @include('components.message')

    {{-- Upload Form --}}
    <div class="collapse mb-4 {{ $errors->any() ? 'show' : '' }}" id="uploadForm">
        <div class="card" style="border-left: 3px solid #161616;">
            <div class="card-header"><strong><i class="fe fe-upload fe-14 mr-1"></i> Submit a Resource for Approval</strong></div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif
                <form action="{{ route('ass.resources.upload') }}" method="POST" enctype="multipart/form-data" id="assUploadForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label><strong>Name</strong></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Resource name" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3 form-group">
                            <label><strong>Category</strong></label>
                            <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                @foreach(['Tax','Audit','Advisory','Corporate'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5 form-group">
                            <label><strong>Description</strong> <small class="text-muted">(optional)</small></label>
                            <input type="text" name="description" value="{{ old('description') }}"
                                   class="form-control" placeholder="Brief description">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><strong>File</strong></label>
                        <div id="assDropZone" onclick="document.getElementById('assFileInput').click()"
                             style="border:2px dashed #c6c6c6; padding:32px 20px; text-align:center; cursor:pointer; transition:border-color .2s, background .2s; background:#f4f4f4;">
                            <div id="assDropIcon" style="font-size:2.2rem; margin-bottom:8px; color:#8d8d8d;">
                                <i class="fe fe-upload-cloud"></i>
                            </div>
                            <div id="assDropLabel" style="font-weight:600; color:var(--cds-text-primary); margin-bottom:4px;">
                                Click or drag &amp; drop a file here
                            </div>
                            <div style="font-size:12px; color:#8d8d8d;">Any file type &nbsp;•&nbsp; Max 20 MB</div>
                            <div id="assFileInfo" style="display:none; margin-top:12px;">
                                <span style="display:inline-flex;align-items:center;gap:8px;background:#e0e0e0;border:1px solid #8d8d8d;padding:5px 14px;font-size:13px;color:var(--cds-text-primary);font-weight:600;">
                                    <i class="fe fe-file fe-12"></i>
                                    <span id="assFileName"></span>
                                    <span id="assFileSize" style="font-weight:400;color:#525252;"></span>
                                </span>
                            </div>
                        </div>
                        <input type="file" name="file" id="assFileInput" style="display:none"
                               class="@error('file') is-invalid @enderror" required>
                        @error('file')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    {{-- Progress bar --}}
                    <div id="assProgress" style="display:none; margin-bottom:12px;">
                        <div style="display:flex; justify-content:space-between; font-size:12px; color:#525252; margin-bottom:4px;">
                            <span>Uploading...</span>
                            <span id="assProgressPct">0%</span>
                        </div>
                        <div class="progress">
                            <div id="assProgressBar" class="progress-bar" role="progressbar" style="width:0%;"></div>
                        </div>
                    </div>

                    <button type="submit" id="assSubmitBtn" class="btn btn-secondary">
                        Submit for Approval
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- My Uploads Status --}}
    @if($myUploads->count())
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <strong class="card-title mb-0"><i class="fe fe-clock fe-16 mr-2"></i> My Uploads</strong>
            <span class="cds-count-tag">{{ $myUploads->count() }}</span>
        </div>
        <div class="cds-table-wrap">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myUploads as $u)
                    <tr>
                        <td>
                            <strong>{{ $u->name }}</strong>
                            <br><small class="text-muted">{{ $u->original_filename }}</small>
                        </td>
                        <td><span class="cds-count-tag">{{ $u->category }}</span></td>
                        <td>
                            @if($u->status === 'approved')
                                <span class="cds-status-tag cds-status-tag--done">Approved</span>
                            @elseif($u->status === 'pending')
                                <span class="cds-status-tag">Pending</span>
                            @else
                                <span class="cds-status-tag">Rejected</span>
                            @endif
                        </td>
                        <td><small>{{ $u->created_at->format('d M Y') }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Approved Resources --}}
    @forelse(['Tax', 'Audit', 'Advisory', 'Corporate'] as $cat)
        @if(isset($resources[$cat]) && $resources[$cat]->count())
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <strong class="card-title mb-0">
                    <i class="fe fe-folder fe-16 mr-2"></i> {{ $cat }}
                </strong>
                <span class="cds-count-tag">{{ $resources[$cat]->count() }}</span>
            </div>
            <div class="cds-table-wrap">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resources[$cat] as $res)
                        <tr>
                            <td>
                                <strong>{{ $res->name }}</strong>
                                <br><small class="text-muted">{{ $res->original_filename }}</small>
                            </td>
                            <td class="text-muted">{{ $res->description ?? '—' }}</td>
                            <td>
                                <a href="{{ route('ass.resources.download', $res->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fe fe-download fe-12 mr-1"></i> Download
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @empty
    @endforelse

    @if($resources->isEmpty() && $myUploads->isEmpty())
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            <i class="fe fe-folder fe-32 mb-3 d-block" style="color:var(--cds-border-strong);"></i>
            No resources available yet.
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
(function() {
    var dropZone  = document.getElementById('assDropZone');
    var fileInput = document.getElementById('assFileInput');
    if (!dropZone || !fileInput) return;

    function formatBytes(b) {
        if (b < 1024) return b + ' B';
        if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
        return (b / 1048576).toFixed(1) + ' MB';
    }

    function showFile(file) {
        document.getElementById('assFileName').textContent = file.name;
        document.getElementById('assFileSize').textContent = '(' + formatBytes(file.size) + ')';
        document.getElementById('assFileInfo').style.display = 'block';
        document.getElementById('assDropLabel').textContent  = 'File selected';
        document.getElementById('assDropIcon').innerHTML = '<i class="fe fe-check-circle" style="color:#161616;"></i>';
        dropZone.style.borderColor = '#161616';
        dropZone.style.background  = '#e0e0e0';
    }

    fileInput.addEventListener('change', function() {
        if (this.files[0]) showFile(this.files[0]);
    });

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#525252';
        this.style.background  = '#e0e0e0';
    });
    dropZone.addEventListener('dragleave', function() {
        this.style.borderColor = '#c6c6c6';
        this.style.background  = '#f4f4f4';
    });
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#c6c6c6';
        this.style.background  = '#f4f4f4';
        var file = e.dataTransfer.files[0];
        if (file) {
            var dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
            showFile(file);
        }
    });

    document.getElementById('assUploadForm').addEventListener('submit', function(e) {
        if (!fileInput.files[0]) return;
        e.preventDefault();

        var form = this;
        var bar  = document.getElementById('assProgressBar');
        var pct  = document.getElementById('assProgressPct');
        var btn  = document.getElementById('assSubmitBtn');

        document.getElementById('assProgress').style.display = 'block';
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span> Uploading...';

        var xhr = new XMLHttpRequest();

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                var p = Math.round(e.loaded / e.total * 100);
                bar.style.width = p + '%';
                pct.textContent = p + '%';
            }
        };

        xhr.upload.addEventListener('load', function() {
            bar.style.width = '100%';
            pct.textContent = '100%';
            btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span> Processing...';
        });

        xhr.onload = function() {
            window.location.href = xhr.responseURL;
        };

        xhr.onerror = function() {
            btn.disabled = false;
            btn.innerHTML = 'Submit for Approval';
            document.getElementById('assProgress').style.display = 'none';
            alert('Upload failed. Please try again.');
        };

        xhr.open('POST', form.action);
        xhr.send(new FormData(form));
    });
})();
</script>
@endsection
