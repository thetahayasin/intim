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
                <form action="{{ route('ass.resources.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label><strong>Name</strong></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Resource name" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label><strong>Category</strong></label>
                            <select name="category" class="form-control" required>
                                <option value="">-- Select --</option>
                                <option value="Tax" {{ old('category') === 'Tax' ? 'selected' : '' }}>Tax</option>
                                <option value="Audit" {{ old('category') === 'Audit' ? 'selected' : '' }}>Audit</option>
                                <option value="Advisory" {{ old('category') === 'Advisory' ? 'selected' : '' }}>Advisory</option>
                                <option value="Corporate" {{ old('category') === 'Corporate' ? 'selected' : '' }}>Corporate</option>
                            </select>
                        </div>
                        <div class="col-md-5 form-group">
                            <label><strong>Description</strong> <small class="text-muted">(optional)</small></label>
                            <input type="text" name="description" value="{{ old('description') }}" class="form-control" placeholder="Brief description">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label><strong>File</strong></label>
                            <input type="file" name="file" class="form-control-file" required>
                            <small class="text-muted">Max 20MB. Any file type.</small>
                        </div>
                        <div class="col-md-6 d-flex align-items-end form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fe fe-send fe-14 mr-1"></i> Submit for Approval
                            </button>
                        </div>
                    </div>
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
