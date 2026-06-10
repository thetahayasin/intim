@extends('admin.main')

@section('title', 'Asif Associates | Upload Resource')

@section('content')
<div class="col-md-12 container-fluid">
    <a href="{{ route('e.resources') }}" wire:navigate class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left fe-16"></i> Back
    </a>
    <div class="row">
        <div class="col-md-7 my-4">
            <div class="card shadow">
                <div class="card-header">
                    <strong class="card-title"><i class="fe fe-upload fe-16 mr-1"></i> Upload Resource</strong>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('e.resources.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name"><strong>Resource Name</strong></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="e.g. Income Tax Return Format 2025">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="category"><strong>Category</strong></label>
                            <select id="category" name="category"
                                    class="form-control @error('category') is-invalid @enderror">
                                <option value="">-- Select Category --</option>
                                @foreach(['Tax','Audit','Advisory','Corporate'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description"><strong>Note / Description</strong> <small class="text-muted">(optional)</small></label>
                            <textarea id="description" name="description" rows="2"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Brief note about this resource...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Custom file drop zone --}}
                        <div class="form-group mb-4">
                            <label><strong>File</strong></label>
                            <div id="dropZone" onclick="document.getElementById('fileInput').click()"
                                 style="border: 2px dashed #c6c6c6; padding: 36px 24px; text-align: center; cursor: pointer; transition: border-color .2s, background .2s; background: #f4f4f4;">
                                <div id="dropIcon" style="font-size: 2.5rem; margin-bottom: 10px; color: #8d8d8d;">
                                    <i class="fe fe-upload-cloud"></i>
                                </div>
                                <div id="dropLabel" style="font-weight: 600; color: var(--cds-text-primary); margin-bottom: 4px;">
                                    Click or drag &amp; drop a file here
                                </div>
                                <div style="font-size: 12px; color: #8d8d8d;">Any file type &nbsp;•&nbsp; Max 20 MB</div>
                                <div id="fileInfo" style="display:none; margin-top:14px;">
                                    <span id="fileChip" style="display:inline-flex;align-items:center;gap:8px;background:#e0e0e0;border:1px solid #8d8d8d;padding:6px 14px;font-size:13px;color:var(--cds-text-primary);font-weight:600;">
                                        <i class="fe fe-file fe-12"></i>
                                        <span id="fileName"></span>
                                        <span id="fileSize" style="font-weight:400;color:#525252;"></span>
                                    </span>
                                </div>
                            </div>
                            <input type="file" id="fileInput" name="file" style="display:none"
                                   class="@error('file') is-invalid @enderror">
                            @error('file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                            {{-- Upload progress bar (shown during submit) --}}
                            <div id="progressWrap" style="display:none; margin-top:12px;">
                                <div style="display:flex;justify-content:space-between;font-size:12px;color:#525252;margin-bottom:4px;">
                                    <span>Uploading...</span><span id="progressPct">0%</span>
                                </div>
                                <div class="progress">
                                    <div id="progressBar" class="progress-bar" role="progressbar" style="width:0%;"></div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" id="submitBtn" class="btn btn-secondary btn-lg float-right">
                            <i class="fe fe-upload fe-16 mr-1"></i> Upload
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
    var dropZone  = document.getElementById('dropZone');
    var fileInput = document.getElementById('fileInput');
    var fileInfo  = document.getElementById('fileInfo');
    var fileName  = document.getElementById('fileName');
    var fileSize  = document.getElementById('fileSize');
    var dropLabel = document.getElementById('dropLabel');
    var dropIcon  = document.getElementById('dropIcon');

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
        dropIcon.innerHTML = '<i class="fe fe-check-circle" style="color:#161616;"></i>';
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

    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        if (!fileInput.files[0]) return;
        e.preventDefault();

        var form        = this;
        var progressWrap = document.getElementById('progressWrap');
        var progressBar  = document.getElementById('progressBar');
        var progressPct  = document.getElementById('progressPct');
        var submitBtn    = document.getElementById('submitBtn');

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
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span> Processing...';
        });

        xhr.onload = function() {
            window.location.href = xhr.responseURL;
        };

        xhr.onerror = function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fe fe-upload fe-16 mr-1"></i> Upload';
            progressWrap.style.display = 'none';
            alert('Upload failed. Please try again.');
        };

        xhr.open('POST', form.action);
        xhr.send(new FormData(form));
    });
</script>
@endsection
