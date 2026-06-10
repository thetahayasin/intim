@extends('admin.main')

@section('title', 'Asif Associates | Resources')

@section('content')
<div class="col-md-12 container-fluid">

    @include('components.message')

    {{-- Pending Approval --}}
    @if($pending->count())
    <div class="card mb-4" style="border-left: 3px solid #161616;">
        <div class="card-header d-flex align-items-center justify-content-between">
            <strong class="card-title mb-0">
                <i class="fe fe-clock fe-16 mr-2"></i> Pending Approval
            </strong>
            <span class="cds-count-tag">{{ $pending->count() }}</span>
        </div>
        <div class="cds-table-wrap">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Uploaded By</th>
                        <th>File</th>
                        <th>Submitted</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending as $res)
                    <tr>
                        <td>
                            <strong>{{ $res->name }}</strong>
                            @if($res->description)<br><small class="text-muted">{{ $res->description }}</small>@endif
                        </td>
                        <td><span class="cds-count-tag">{{ $res->category }}</span></td>
                        <td><strong>{{ $res->uploader?->name ?? '—' }}</strong></td>
                        <td><small class="text-muted">{{ $res->original_filename }}</small></td>
                        <td><small>{{ $res->created_at->format('d M Y') }}</small></td>
                        <td class="text-nowrap text-center">
                            @if(Storage::disk('public')->exists($res->file_path))
                                <a href="{{ Storage::url($res->file_path) }}" download="{{ $res->original_filename }}"
                                   class="btn btn-secondary btn-sm" title="Download">
                                    <i class="fe fe-download fe-12"></i>
                                </a>
                            @endif
                            <form action="{{ route('e.resources.approve', $res->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-secondary btn-sm" title="Approve">
                                    <i class="fe fe-check fe-12"></i> Approve
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger btn-sm btn-delete"
                                    data-action="{{ route('e.resources.reject', $res->id) }}"
                                    data-name="{{ $res->name }}"
                                    data-label="Reject">
                                <i class="fe fe-x fe-12"></i> Reject
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h5 class="mb-0"><i class="fe fe-folder fe-16 mr-2"></i> Resources</h5>
        </div>
        <a href="{{ route('e.resources.create') }}" wire:navigate class="btn btn-secondary">
            <i class="fe fe-upload fe-14 mr-1"></i> Upload Resource
        </a>
    </div>

    {{-- Resources by Category --}}
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
                            <th>File</th>
                            <th>Uploaded</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resources[$cat] as $res)
                        <tr>
                            <td><strong>{{ $res->name }}</strong></td>
                            <td class="text-muted">{{ $res->description ?? '—' }}</td>
                            <td><small class="text-muted">{{ $res->original_filename }}</small></td>
                            <td><small>{{ $res->created_at->format('d M Y') }}</small></td>
                            <td class="text-nowrap text-center">
                                @if(Storage::disk('public')->exists($res->file_path))
                                    <a href="{{ Storage::url($res->file_path) }}" download="{{ $res->original_filename }}"
                                       class="btn btn-secondary btn-sm">
                                        <i class="fe fe-download fe-12"></i>
                                    </a>
                                @else
                                    <span class="cds-status-tag">Missing</span>
                                @endif
                                <a href="{{ route('e.resources.edit', $res->id) }}" wire:navigate class="btn btn-secondary btn-sm">
                                    <i class="fe fe-edit-2 fe-12"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete"
                                        data-action="{{ route('e.resources.destroy', $res->id) }}"
                                        data-method="DELETE"
                                        data-name="{{ $res->name }}"
                                        data-label="Delete">
                                    <i class="fe fe-trash-2 fe-12"></i>
                                </button>
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
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            <i class="fe fe-folder fe-32 mb-3 d-block" style="color:var(--cds-border-strong);"></i>
            No resources uploaded yet.
        </div>
    </div>
    @endif

</div>

{{-- Confirm Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h6 class="modal-title font-weight-bold">
                    <i class="fe fe-alert-triangle fe-16 mr-1"></i>
                    <span id="deleteModalTitle">Confirm</span>
                </h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body pt-2">
                <p class="mb-0 text-muted" id="deleteModalBody">Are you sure?</p>
            </div>
            <div class="modal-footer pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <form id="deleteModalForm" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="_method" id="deleteModalMethod" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm" id="deleteModalBtn">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var label  = this.dataset.label || 'Delete';
        var name   = this.dataset.name  || 'this item';
        var action = this.dataset.action;
        var method = this.dataset.method || 'POST';

        document.getElementById('deleteModalTitle').textContent = label;
        document.getElementById('deleteModalBody').textContent  = label + ' "' + name + '"? This cannot be undone.';
        document.getElementById('deleteModalBtn').textContent   = label;
        document.getElementById('deleteModalForm').action       = action;
        document.getElementById('deleteModalMethod').value      = method;

        jQuery('#deleteModal').modal('show');
    });
});
</script>
@endsection
