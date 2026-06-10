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
                        <th>Type</th>
                        <th>Client</th>
                        <th>Firm</th>
                        <th>Period</th>
                        <th>Services</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>{{ $doc->created_at->format('d M Y') }}</td>
                        <td>
                            @if($doc->type === 'agreement')
                                <span class="cds-status-tag cds-status-tag--done">Agreement</span>
                            @else
                                <span class="cds-status-tag">Proposal</span>
                            @endif
                        </td>
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
                            @if($doc->services)
                                <small>{{ collect($doc->services)->pluck('name')->implode(', ') }}</small>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('e.documents.view', $doc->id) }}"
                               class="btn btn-secondary btn-sm mr-1" title="View / Print">
                                <i class="fe fe-printer fe-12"></i>
                            </a>
                            <a href="{{ route('e.documents.edit', $doc->id) }}" wire:navigate
                               class="btn btn-secondary btn-sm mr-1" title="Edit">
                                <i class="fe fe-edit-2 fe-12"></i>
                            </a>
                            <form action="{{ route('e.documents.destroy', $doc->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm"
                                        data-confirm-delete="Delete this document? This action cannot be undone.">
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
@endsection
