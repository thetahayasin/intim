@extends('admin.main')

@section('title', 'Asif Associates | Resources')

@section('content')
<div class="col-md-12 container-fluid">

    @include('components.message')

    {{-- Pending Approval --}}
    @if($pending->count())
    <div class="card shadow mb-4 border-left-warning" style="border-left: 4px solid #ffc107;">
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
                        <td><small class="text-muted">{{ $res->original_filename }}</small></td>
                        <td><small>{{ $res->created_at->format('d M Y') }}</small></td>
                        <td class="text-nowrap">
                            @if(Storage::disk('public')->exists($res->file_path))
                                <a href="{{ Storage::url($res->file_path) }}" download="{{ $res->original_filename }}" class="btn btn-outline-primary btn-sm" title="Download to review">
                                    <i class="fe fe-download fe-12"></i>
                                </a>
                            @endif
                            <form action="{{ route('e.resources.approve', $res->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                    <i class="fe fe-check fe-12"></i> Approve
                                </button>
                            </form>
                            <form action="{{ route('e.resources.reject', $res->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Reject and delete this resource?')">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" title="Reject">
                                    <i class="fe fe-x fe-12"></i> Reject
                                </button>
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
        <a href="{{ route('e.resources.create') }}" wire:navigate class="btn btn-primary">
            <i class="fe fe-upload fe-16 mr-1"></i> Upload Resource
        </a>
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
                                    <a href="{{ Storage::url($res->file_path) }}" download="{{ $res->original_filename }}" class="btn btn-primary btn-sm" title="Download">
                                        <i class="fe fe-download fe-12"></i>
                                    </a>
                                @else
                                    <span class="badge badge-danger" title="File missing from disk">Missing</span>
                                @endif
                                <a href="{{ route('e.resources.edit', $res->id) }}" wire:navigate class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fe fe-edit-2 fe-12"></i>
                                </a>
                                <form action="{{ route('e.resources.destroy', $res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this resource?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fe fe-trash-2 fe-12"></i></button>
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

    @if($resources->isEmpty())
    <div class="card shadow">
        <div class="card-body text-center text-muted py-5">
            <i class="fe fe-folder fe-32 mb-3 d-block"></i>
            No resources uploaded yet. <a href="{{ route('e.resources.create') }}">Upload the first one.</a>
        </div>
    </div>
    @endif

</div>
@endsection
