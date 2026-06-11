<div>
    <div class="d-flex flex-wrap align-items-center mb-3" style="gap: 8px;">
        <a href="{{ route('e.receipts.create') }}" class="btn btn-primary" wire:navigate><i class="fe fe-plus-circle fe-16"></i> New Receipt</a>
        
        <div class="d-flex align-items-center ml-auto" style="gap: 8px;">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text text-muted" style="font-size:12px;letter-spacing:.5px;">FROM</span></div>
                <input type="date" wire:model.live="startDate" class="form-control" style="min-width:150px;">
                <div class="input-group-append input-group-prepend"><span class="input-group-text text-muted" style="font-size:12px;letter-spacing:.5px;">TO</span></div>
                <input type="date" wire:model.live="endDate" class="form-control" style="min-width:150px;">
            </div>
            <div class="input-group" style="max-width: 250px;">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search client receipts..." autocomplete="off">
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
                <div wire:loading wire:target="search" class="position-absolute" style="top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 10; border-radius: .25rem;">
                    <div class="h-100 d-flex align-items-center justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="card-body att-body">
                    <h5 class="card-title">Receipts Management</h5>
                    
                    @if(session()->has('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                    @endif
                    @if(session()->has('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover att-table text-center">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Discount</th>
                                    <th>Tax Withheld</th>
                                    <th>Net Total</th>
                                    <th>Client</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($res as $c)
                                <tr wire:key="receipt-{{ $c->id }}">
                                    <td>{{ $c->date ? date('M d, Y | (D)', strtotime($c->date)) : '-' }}</td>
                                    <td>{{ number_format($c->amount) }}</td>
                                    <td>
                                        @if((float) $c->discount > 0)
                                            <span class="text-success">{{ number_format($c->discount) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if((float) $c->tax > 0)
                                            <span class="text-warning">{{ number_format($c->tax) }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><strong>{{ number_format($c->net_amount) }}</strong></td>
                                    <td>{{ $c->client->name ?? '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteReceiptModal{{ $c->id }}" title="Delete">
                                            <i class="fe fe-trash-2 fe-16"></i>
                                        </button>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No receipts found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <nav aria-label="Table Paging" class="mb-0 text-muted">
                        {{ $res->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @foreach($res as $c)
    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteReceiptModal{{ $c->id }}" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-left">
                    <p>Are you sure you want to delete receipt dated <strong>{{ $c->date ? date('M d, Y', strtotime($c->date)) : 'N/A' }}</strong> for <strong>{{ $c->client->name ?? 'Unknown' }}</strong>?</p>
                    <p class="text-danger small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('e.receipt.delete', $c->id) }}" method="POST" class="d-inline">
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
