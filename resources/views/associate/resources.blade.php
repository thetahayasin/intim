@extends('associate.main')

@section('title', 'Asif Associates | Resources')

@section('content')
<div class="col-md-12 container-fluid">

    <div class="my-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0"><i class="fe fe-folder fe-16 mr-2"></i> Resources</h4>
            <small class="text-muted">Templates, formats and documents from Asif Associates</small>
        </div>
        <button class="btn btn-primary" data-toggle="collapse" data-target="#uploadForm">
            <i class="fe fe-upload fe-14 mr-1"></i> Upload Resource
        </button>
    </div>

    @include('components.message')

    {{-- Upload Form --}}
    <div class="collapse mb-4 {{ $errors->any() ? 'show' : '' }}" id="uploadForm">
        <div class="card shadow border-left-primary" style="border-left: 4px solid #007bff;">
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
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Resource name" required>
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
                            <input type="text" name="description" value="{{ old('description') }}" class="form-control" placeholder="Brief description">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label><strong>File</strong></label>
                        <div id="assDropZone" onclick="document.getElementById('assFileInput').click()"
                             style="border:2px dashed #ced4da;border-radius:10px;padding:30px 24px;text-align:center;cursor:pointer;transition:all .2s;background:#fafafa;">
                            <div id="assDropIcon" style="font-size:2.2rem;margin-bottom:8px;color:#adb5bd;">
                                <i class="fe fe-upload-cloud"></i>
                            </div>
                            <div id="assDropLabel" style="font-weight:600;color:#495057;margin-bottom:4px;">
                                Click or drag &amp; drop a file here
                            </div>
                            <div style="font-size:12px;color:#adb5bd;">Any file type &nbsp;•&nbsp; Max 20 MB</div>
                            <div id="assFileInfo" style="display:none;margin-top:12px;">
                                <span style="display:inline-flex;align-items:center;gap:8px;background:#e8f4fd;border:1px solid #bee3f8;border-radius:20px;padding:6px 14px;font-size:13px;color:#2d6a9f;font-weight:600;">
                                    <i class="fe fe-file fe-12"></i>
                                    <span id="assFileName"></span>
                                    <span id="assFileSize" style="font-weight:400;color:#6c9ec9;"></span>
                                </span>
                            </div>
                        </div>
                        <input type="file" id="assFileInput" name="file" style="display:none" class="@error('file') is-invalid @enderror" required>
                        @error('file')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" id="assSubmitBtn" class="btn btn-primary">
                        <i class="fe fe-send fe-14 mr-1"></i> Submit for Approval
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- My Uploads Status --}}
    @if($myUploads->count())
    <div class="card shadow mb-4">
        <div class="card-header d-flex align-items-center">
            <strong class="card-title mb-0"><i class="fe fe-clock fe-16 mr-2"></i> My Uploads</strong>
        </div>
        <div class="card-body p-0">
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
                        <td><strong>{{ $u->name }}</strong><br><small class="text-muted">{{ $u->original_filename }}</small></td>
                        <td>{{ $u->category }}</td>
                        <td>
                            @if($u->status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($u->status === 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
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
        <div class="card shadow mb-4">
            <div class="card-header d-flex align-items-center">
                <strong class="card-title mb-0">
                    @if($cat === 'Tax') <i class="fe fe-file-text fe-16 mr-2 text-warning"></i>
                    @elseif($cat === 'Audit') <i class="fe fe-search fe-16 mr-2 text-info"></i>
                    @elseif($cat === 'Advisory') <i class="fe fe-briefcase fe-16 mr-2 text-success"></i>
                    @else <i class="fe fe-layers fe-16 mr-2 text-primary"></i>
                    @endif
                    {{ $cat }}
                </strong>
                <span class="badge badge-secondary ml-2">{{ $resources[$cat]->count() }}</span>
            </div>
            <div class="card-body p-0">
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
                            <td class="text-nowrap">
                                <a href="{{ route('ass.resources.download', $res->id) }}"
                                   class="btn btn-primary btn-sm">
                                    <i class="fe fe-download fe-12"></i> Download
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
    <div class="card shadow">
        <div class="card-body text-center text-muted py-5">
            <i class="fe fe-folder fe-32 mb-3 d-block"></i>
            No resources available yet.
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    var dropZone  = document.getElementById('assDropZone');
    var fileInput = document.getElementById('assFileInput');
    var fileInfo  = document.getElementById('assFileInfo');
    var fileName  = document.getElementById('assFileName');
    var fileSize  = document.getElementById('assFileSize');
    var dropLabel = document.getElementById('assDropLabel');
    var dropIcon  = document.getElementById('assDropIcon');

    if (dropZone) {
        function formatBytes(b) {
            if (b < 1024) return b + ' B';
            if (b < 1048576) return (b/1024).toFixed(1) + ' KB';
            return (b/1048576).toFixed(1) + ' MB';
        }
        function showFile(file) {
            fileName.textContent = file.name;
            fileSize.textContent = '(' + formatBytes(file.size) + ')';
            fileInfo.style.display = 'block';
            dropLabel.textContent  = 'File selected';
            dropIcon.innerHTML = '<i class="fe fe-check-circle" style="color:#28a745;"></i>';
            dropZone.style.borderColor = '#28a745';
            dropZone.style.background  = '#f0fff4';
        }
        fileInput.addEventListener('change', function() {
            if (this.files[0]) showFile(this.files[0]);
        });
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = '#4dabf7';
            this.style.background  = '#e8f4fd';
        });
        dropZone.addEventListener('dragleave', function() {
            this.style.borderColor = '#ced4da';
            this.style.background  = '#fafafa';
        });
        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = '#ced4da';
            this.style.background  = '#fafafa';
            var file = e.dataTransfer.files[0];
            if (file) {
                var dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                showFile(file);
            }
        });

        // XHR upload with progress
        document.getElementById('assUploadForm').addEventListener('submit', function(e) {
            if (!fileInput.files[0]) return;
            e.preventDefault();

            var form       = this;
            var submitBtn  = document.getElementById('assSubmitBtn');

            // Build progress UI
            var progressWrap = document.getElementById('assProgressWrap');
            if (!progressWrap) {
                progressWrap = document.createElement('div');
                progressWrap.id = 'assProgressWrap';
                progressWrap.style.cssText = 'margin-top:12px;';
                progressWrap.innerHTML =
                    '<div style="display:flex;justify-content:space-between;font-size:12px;color:#6c757d;margin-bottom:4px;">' +
                        '<span id="assProgressLabel">Uploading...</span>' +
                        '<span id="assProgressPct">0%</span>' +
                    '</div>' +
                    '<div style="height:8px;background:#e9ecef;border-radius:4px;overflow:hidden;">' +
                        '<div id="assProgressBar" style="height:100%;width:0%;background:linear-gradient(90deg,#4dabf7,#228be6);border-radius:4px;transition:width .1s;"></div>' +
                    '</div>';
                submitBtn.parentNode.insertBefore(progressWrap, submitBtn.nextSibling);
            }

            var progressBar   = document.getElementById('assProgressBar');
            var progressPct   = document.getElementById('assProgressPct');
            var progressLabel = document.getElementById('assProgressLabel');

            progressWrap.style.display = 'block';
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span> Uploading...';

            var xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    var pct = Math.round(e.loaded / e.total * 100);
                    progressBar.style.width = pct + '%';
                    progressPct.textContent = pct + '%';
                }
            });

            xhr.upload.addEventListener('load', function() {
                progressBar.style.width = '100%';
                progressPct.textContent = '100%';
                progressLabel.textContent = 'Processing...';
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span> Processing...';
            });

            xhr.onload = function() {
                window.location.href = xhr.responseURL;
            };

            xhr.onerror = function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fe fe-send fe-14 mr-1"></i> Submit for Approval';
                progressWrap.style.display = 'none';
                alert('Upload failed. Please try again.');
            };

            xhr.open('POST', form.action);
            xhr.send(new FormData(form));
        });
    }
</script>
@endsection
