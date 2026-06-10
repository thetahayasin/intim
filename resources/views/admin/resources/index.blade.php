@extends('admin.main')

@section('title', 'Asif Associates | Resources')

@section('content')
<div class="col-md-12 container-fluid">

    @include('components.message')

    {{-- Pending Approval --}}
    @if($pending->count())
    <div class="card shadow mb-4" style="border-left: 4px solid #ffc107;">
        <div class="card-header d-flex align-items-center">
            <strong class="card-title mb-0 text-warning">
                <i class="fe fe-clock fe-16 mr-2"></i> Pending Approval
            </strong>
            <span class="badge badge-warning ml-2">{{ $pending->count() }}</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Uploaded By</th>
                        <th>File</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending as $res)
                    <tr>
                        <td>
                            <strong>{{ $res->name }}</strong>
                            @if($res->description)<br><small class="text-muted">{{ $res->description }}</small>@endif
                        </td>
                        <td><span class="badge badge-secondary">{{ $res->category }}</span></td>
                        <td><strong>{{ $res->uploader?->name ?? '—' }}</strong></td>
                        <td><small class="text-muted">{{ $res->original_filename }}</small></td>
                        <td><small>{{ $res->created_at->format('d M Y') }}</small></td>
                        <td class="text-nowrap">
                            @if(Storage::disk('public')->exists($res->file_path))
                                <a href="{{ Storage::url($res->file_path) }}" download="{{ $res->original_filename }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fe fe-download fe-12"></i>
                                </a>
                            @endif
                            <form action="{{ route('e.resources.approve', $res->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm"><i class="fe fe-check fe-12"></i> Approve</button>
                            </form>
                            <form action="{{ route('e.resources.reject', $res->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Reject and delete this resource?')">
                                @csrf
                                <button class="btn btn-danger btn-sm"><i class="fe fe-x fe-12"></i> Reject</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center my-4">
        <h4 class="mb-0"><i class="fe fe-folder fe-16 mr-2"></i> Resources</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
            <i class="fe fe-upload fe-16 mr-1"></i> Upload Resource
        </button>
    </div>

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
                            <th>File</th>
                            <th>Uploaded</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resources[$cat] as $res)
                        <tr>
                            <td><strong>{{ $res->name }}</strong></td>
                            <td class="text-muted">{{ $res->description ?? '—' }}</td>
                            <td><small class="text-muted">{{ $res->original_filename }}</small></td>
                            <td><small>{{ $res->created_at->format('d M Y') }}</small></td>
                            <td class="text-nowrap">
                                @if(Storage::disk('public')->exists($res->file_path))
                                    <a href="{{ Storage::url($res->file_path) }}" download="{{ $res->original_filename }}" class="btn btn-primary btn-sm">
                                        <i class="fe fe-download fe-12"></i>
                                    </a>
                                @else
                                    <span class="badge badge-danger">Missing</span>
                                @endif
                                <a href="{{ route('e.resources.edit', $res->id) }}" wire:navigate class="btn btn-warning btn-sm">
                                    <i class="fe fe-edit-2 fe-12"></i>
                                </a>
                                <form action="{{ route('e.resources.destroy', $res->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this resource?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fe fe-trash-2 fe-12"></i></button>
                                </form>
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

    @if($resources->isEmpty() && !$pending->count())
    <div class="card shadow">
        <div class="card-body text-center text-muted py-5">
            <i class="fe fe-folder fe-32 mb-3 d-block"></i>
            No resources uploaded yet.
        </div>
    </div>
    @endif

</div>

{{-- Upload Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fe fe-upload fe-16 mr-2"></i> Upload Resource</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="adminUploadForm" action="{{ route('e.resources.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div id="adminUploadErrors" class="alert alert-danger d-none"></div>

                    <div class="form-group">
                        <label><strong>Name</strong></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Income Tax Return Format 2025" required>
                    </div>
                    <div class="form-group">
                        <label><strong>Category</strong></label>
                        <select name="category" class="form-control" required>
                            <option value="">-- Select --</option>
                            @foreach(['Tax','Audit','Advisory','Corporate'] as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong>Description</strong> <small class="text-muted">(optional)</small></label>
                        <textarea name="description" rows="2" class="form-control" placeholder="Brief note..."></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label><strong>File</strong></label>
                        <input type="file" name="file" id="adminFileInput" class="form-control-file" required>
                    </div>

                    {{-- Progress bar (hidden until upload starts) --}}
                    <div id="adminProgress" style="display:none; margin-top:12px;">
                        <div style="display:flex; justify-content:space-between; font-size:12px; color:#6c757d; margin-bottom:4px;">
                            <span>Uploading...</span>
                            <span id="adminProgressPct">0%</span>
                        </div>
                        <div style="height:8px; background:#e9ecef; border-radius:4px; overflow:hidden;">
                            <div id="adminProgressBar" style="height:100%; width:0%; background:linear-gradient(90deg,#4dabf7,#228be6); border-radius:4px; transition:width .15s;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="adminSubmitBtn" class="btn btn-primary">
                        <i class="fe fe-upload fe-14 mr-1"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('adminUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    var bar  = document.getElementById('adminProgressBar');
    var pct  = document.getElementById('adminProgressPct');
    var btn  = document.getElementById('adminSubmitBtn');

    document.getElementById('adminProgress').style.display = 'block';
    document.getElementById('adminUploadErrors').classList.add('d-none');
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

    xhr.onload = function() {
        window.location.href = xhr.responseURL;
    };

    xhr.onerror = function() {
        btn.disabled = false;
        btn.innerHTML = '<i class="fe fe-upload fe-14 mr-1"></i> Upload';
        document.getElementById('adminProgress').style.display = 'none';
        alert('Upload failed. Please try again.');
    };

    xhr.open('POST', form.action);
    xhr.send(new FormData(form));
});
</script>
@endsection
