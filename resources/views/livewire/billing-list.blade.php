<div>
    <div class="d-flex flex-wrap align-items-center mb-3" style="gap: 8px;">
        <a href="{{ route('e.billing.create') }}" class="btn btn-primary" wire:navigate><i class="fe fe-plus-circle fe-16"></i> Add Billing</a>
        
        <div class="d-flex align-items-center ml-auto" style="gap: 8px;">
            <div class="input-group">
                <input type="date" wire:model.live="startDate" class="form-control" style="max-width: 140px;">
                <div class="input-group-append input-group-prepend"><span class="input-group-text">to</span></div>
                <input type="date" wire:model.live="endDate" class="form-control" style="max-width: 140px;">
            </div>
            <div class="input-group" style="max-width: 250px;">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search clients..." autocomplete="off">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fe fe-search"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow position-relative">
                
                {{-- Loading overlay --}}
                <div wire:loading wire:target="search, setFilter" class="position-absolute" style="top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 10; border-radius: .25rem;">
                    <div class="h-100 d-flex align-items-center justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="card-body att-body">
                    <h5 class="card-title">Billings Management</h5>
                    
                    @if(session()->has('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover att-table text-center">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Items</th>
                                    <th>Gross Sales</th>
                                    <th>Sales Tax</th>
                                    <th>Discount</th>
                                    <th>Total Billed</th>
                                    <th>Client</th>
                                    <th>Service</th>
                                    <th>Firm</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $c)
                                <tr wire:key="billing-{{ $c->id }}">
                                    <td>{{ date('M d, Y', strtotime($c->created_at)) }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($c->description, 30) }}</td>
                                    <td>
                                        @if($c->items && $c->items->count() > 0)
                                            <span class="badge badge-info">{{ $c->items->count() }}</span>
                                        @else
                                            <span class="badge badge-secondary">1</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($c->computed_amount) }}</td>
                                    <td>{{ number_format($c->computed_tax) }}</td>
                                    <td>
                                        @if((float) $c->discount > 0)
                                            <span class="text-danger">-{{ number_format($c->discount) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><strong>{{ number_format($c->grand_total) }}</strong></td>
                                    <td>{{ $c->client->name ?? '-' }}</td>
                                    <td><strong class="text-muted" style="font-size: 0.9em;">{{ $c->items->pluck('service')->filter()->implode(', ') ?: ($c->remarks ?? 'N/A') }}</strong></td>
                                    <td>{{ ($c->firm == 1) ? 'HAMD' : 'AA' }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('e.billing.print', $c->id) }}" class="btn btn-success btn-sm text-white" target="_blank" title="View Invoice"><i class="fe fe-printer fe-16"></i></a>
                                        <a href="{{ route('e.billing.edit', $c->id) }}" wire:navigate class="btn btn-warning btn-sm" title="Edit Billing"><i class="fe fe-edit fe-16"></i></a>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $c->id }}" title="Delete">
                                            <i class="fe fe-trash-2 fe-16"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">No billings found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <nav aria-label="Table Paging" class="mb-0 text-muted">
                            {{ $sales->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($sales as $c)
    <div class="modal fade" id="deleteModal{{ $c->id }}" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-left">
                    <p>Are you sure you want to delete billing #{{ $c->id }} for <strong>{{ $c->client->name ?? 'Unknown' }}</strong>?</p>
                    <p class="text-danger small">This action cannot be undone and will delete all associated invoice items.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('e.billing.delete', $c->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
