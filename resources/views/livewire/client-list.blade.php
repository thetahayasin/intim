<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('e.client.create') }}" class="btn btn-primary" wire:navigate>
            <i class="fe fe-plus-circle fe-16"></i> New Client
        </a>
    
        <div class="input-group ml-auto" style="max-width: 350px;">
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search clients instantly..." autocomplete="off">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fe fe-search"></i></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 my-4">
            <div class="card shadow">
                <div class="card-body att-body position-relative">
                    
                    {{-- Loading overlay --}}
                <div wire:loading wire:target="search" class="position-absolute" style="top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 10; border-radius: .25rem;">
                    <div class="h-100 d-flex align-items-center justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

                    <h5 class="card-title">Clients Management</h5>
                    <table class="table table-hover att-table text-center" id="clientsTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Representative</th>
                                <th>Contact</th>
                                <th>Edit | Stats</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $c)
                            <tr wire:key="client-{{ $c->id }}">
                                <td>{{ $c->name ?? '-' }}</td>
                                <td>{{ $c->email ?? '-' }}</td>
                                <td>{{ $c->client_representative ?? '-' }}</td>
                                <td>{{ $c->representative_contact ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('e.client.edit', $c->id) }}" class="btn btn-primary btn-sm" wire:navigate><i class="fe fe-edit fe-16"></i></a>
                                    <a style="color:white" href="{{ route('e.client.stats', $c->id) }}" class="btn btn-success btn-sm" wire:navigate><i class="fe fe-pie-chart fe-16"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No clients found matching "{{ $search }}"</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <nav aria-label="Table Paging" class="mb-0 text-muted">
                        {{ $clients->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
