@extends('admin.main')

@section('title', 'Asif Associates | Edit Resource')

@section('content')
<div class="col-md-12 container-fluid">
    <a href="{{ route('e.resources') }}" wire:navigate class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left fe-16"></i> Back
    </a>
    <div class="row">
        <div class="col-md-7 my-4">
            <div class="card shadow">
                <div class="card-header">
                    <strong class="card-title"><i class="fe fe-edit-2 fe-16 mr-1"></i> Edit Resource</strong>
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

                    <form action="{{ route('e.resources.update', $resource->id) }}" method="POST" enctype="multipart/form-data" id="editResourceForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name"><strong>Resource Name</strong></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $resource->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="e.g. Income Tax Return Format 2025">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="category"><strong>Category</strong></label>
                            <select id="category" name="category"
                                    class="form-control @error('category') is-invalid @enderror">
                                @foreach(['Tax','Audit','Advisory','Corporate'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $resource->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description"><strong>Note / Description</strong> <small class="text-muted">(optional)</small></label>
                            <textarea id="description" name="description" rows="2"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Brief note about this resource...">{{ old('description', $resource->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Current file --}}
                        <div class="form-group mb-3">
                            <label><strong>Current File</strong></label>
                            <div class="d-flex align-items-center p-2 rounded" style="background:#f8f9fa;border:1px solid #dee2e6;">
                                <i class="fe fe-file fe-16 mr-2 text-primary"></i>
                                <span class="font-weight-600">{{ $resource->original_filename }}</span>
                                @if(Storage::disk('public')->exists($resource->file_path))
                                    <a href="{{ Storage::url($resource->file_path) }}" download="{{ $resource->original_filename }}"
                                       class="btn btn-primary btn-sm ml-auto">
                                        <i class="fe fe-download fe-12"></i> Download
                                    </a>
                                @else
                                    <span class="badge badge-danger ml-auto">File missing from disk</span>
                                @endif
                            </div>
                        </div>

                        {{-- Replace file --}}
                        <div class="form-group mb-4">
                            <label><strong>Replace File</strong> <small class="text-muted">(optional — leave blank to keep current)</small></label>
                            <div id="dropZone" onclick="document.getElementById('fileInput').click()"
                                 style="border:2px dashed #ced4da;border-radius:10px;padding:28px 24px;text-align:center;cursor:pointer;transition:all .2s;background:#fafafa;">
                                <div id="dropIcon" style="font-size:2rem;margin-bottom:8px;color:#adb5bd;">
                                    <i class="fe fe-upload-cloud"></i>
                                </div>
                                <div id="dropLabel" style="font-weight:600;color:#495057;margin-bottom:4px;">
                                    Click or drag &amp; drop to replace file
                                </div>
                                <div style="font-size:12px;color:#adb5bd;">Any file type &nbsp;•&nbsp; Max 20 MB</div>
                                <div id="fileInfo" style="display:none;margin-top:12px;">
                                    <span id="fileChip" style="display:inline-flex;align-items:center;gap:8px;background:#e8f4fd;border:1px solid #bee3f8;border-radius:20px;padding:6px 14px;font-size:13px;color:#2d6a9f;font-weight:600;">
                                        <i class="fe fe-file fe-12"></i>
                                        <span id="fileName"></span>
                                        <span id="fileSize" style="font-weight:400;color:#6c9ec9;"></span>
                                    </span>
                                </div>
                            </div>
                            <input type="file" id="fileInput" name="file" style="display:none"
                                   class="@error('file') is-invalid @enderror">
                            @error('file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-warning btn-lg float-right">
                            <i class="fe fe-save fe-16 mr-1"></i> Save Changes
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
</script>
@endsection
