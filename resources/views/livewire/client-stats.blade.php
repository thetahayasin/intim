<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Stats for {{ $client->name }}</h5>

        <div class="d-flex align-items-center ml-auto" style="gap: 8px;">
            <div class="input-group bg-white rounded shadow-sm">
                <input type="date" wire:model.live="startDate" class="form-control border-0" style="max-width: 150px;">
                <div class="input-group-append input-group-prepend"><span class="input-group-text border-0 bg-light">to</span></div>
                <input type="date" wire:model.live="endDate" class="form-control border-0" style="max-width: 150px;">
            </div>
            @if($startDate || $endDate)
                <button class="btn btn-outline-danger shadow-sm" wire:click="$set('startDate', null); $set('endDate', null)">Reset</button>
            @endif
        </div>
    </div>

    <div class="row position-relative">

        {{-- Loading overlay --}}
        <div wire:loading wire:target="startDate, endDate" class="position-absolute" style="top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.7); z-index: 10; border-radius: .25rem;">
            <div class="h-100 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="list-group mb-4 shadow">
                <div class="list-group-item">
                    <div class="row align-items-center">
                    <div class="col">
                        <strong class="mb-1">Gross Sales (Services)</strong>
                    </div>
                    <div class="col-auto">
                        <p class="text-muted mb-0 font-weight-bold">Rs. {{ number_format($totalSalesAmount) }}</p>
                    </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="row align-items-center">
                    <div class="col">
                        <strong class="mb-1 text-warning">Sales Tax Payable (Billed to Client)</strong>
                    </div>
                    <div class="col-auto">
                        <p class="text-warning mb-0 font-weight-bold">Rs. {{ number_format($totaltaxs) }}</p>
                    </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="row align-items-center">
                    <div class="col">
                        <strong class="mb-1">Total Invoiced</strong>
                    </div>
                    <div class="col-auto">
                        <p class="text-dark font-weight-bold mb-0">Rs. {{ number_format($totalSalesAmount + $totaltaxs) }}</p>
                    </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="row align-items-center">
                    <div class="col">
                        <strong class="mb-1 text-danger">Billing Discounts</strong>
                    </div>
                    <div class="col-auto">
                        <p class="text-danger mb-0 font-weight-bold">- Rs. {{ number_format($totalBillingDiscount) }}</p>
                    </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="row align-items-center">
                    <div class="col">
                        <strong class="mb-1 text-success">Total Receipts (Bank/Cash)</strong>
                    </div>
                    <div class="col-auto">
                        <p class="text-success mb-0 font-weight-bold">Rs. {{ number_format($totalReceiptsAmount) }}</p>
                    </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="row align-items-center">
                    <div class="col">
                        <strong class="mb-1 text-info">Tax Withheld by Client (On Receipt)</strong>
                    </div>
                    <div class="col-auto">
                        <p class="text-info mb-0 font-weight-bold">Rs. {{ number_format($totalTaxAmount) }}</p>
                    </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="row align-items-center">
                    <div class="col">
                        <strong class="mb-1 text-danger">Receipt Discounts / Bad Debts</strong>
                    </div>
                    <div class="col-auto">
                        <p class="text-danger mb-0 font-weight-bold">- Rs. {{ number_format($totalDiscountAmount) }}</p>
                    </div>
                    </div>
                </div>
                <div class="list-group-item" style="background: linear-gradient(135deg, #fff9e6, #fff3cc); border-left: 4px solid #f4af1a;">
                    <div class="row align-items-center">
                    <div class="col">
                        <strong class="mb-1 text-dark">Net Receivable Balance</strong>
                    </div>
                    <div class="col-auto">
                        <p class="aa-color font-weight-bold mb-0" style="font-size: 1.15rem;">Rs. {{ number_format(($totalSalesAmount + $totaltaxs) - $totalBillingDiscount - ($totalReceiptsAmount + $totalTaxAmount + $totalDiscountAmount)) }}</p>
                    </div>
                    </div>
                </div>
                <div class="list-group-item bg-light">
                    <div class="row align-items-center">
                    <div class="col">
                        <strong class="mb-1 text-muted small text-uppercase">Last Receipt Date in Period</strong>
                    </div>
                    <div class="col-auto">
                        <p class="text-muted mb-0 small font-weight-bold">{{ ($lastReceiptDate != NULL) ? \Carbon\Carbon::parse($lastReceiptDate)->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <h6 class="mb-3">Period Context & Entries</h6>
            
            <div class="card shadow mb-4 border-0">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fe fe-file-text mr-1"></i> Billings</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($bills as $b)
                            <div class="list-group-item py-3" wire:key="stat-bill-{{ $b->id }}">
                                <div class="d-flex w-100 justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong class="d-block mb-1">{{ Str::limit($b->items->pluck('service')->filter()->implode(', ') ?: ($b->remarks ?? $b->description ?? 'Billing/Invoice'), 120) }}</strong>
                                        <small class="text-muted"><i class="fe fe-calendar"></i> {{ date('M d, Y', strtotime($b->created_at)) }}</small>
                                    </div>
                                    <div class="text-right">
                                        <h6 class="mb-1 text-dark">
                                            Rs. {{ number_format($b->amount + $b->tax) }}<br>
                                            <small class="text-muted">Total (Inc. {{ number_format($b->tax) }} Tax)</small>
                                        </h6>
                                        <div class="mt-2">
                                            @if($b->halt != 1 && $b->recursive == true)
                                                <form action="{{ route('e.billing.halt', $b->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-warning btn-sm px-2 py-1" style="font-size: 0.75rem;"><i class="fe fe-pause-circle"></i> Stop</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('e.billing.delete', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this billing?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1" style="font-size: 0.75rem;"><i class="fe fe-trash-2"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @if((float)$b->discount > 0)
                                    <div class="text-danger small mt-1"><i class="fe fe-arrow-down-right"></i> Includes Discount: Rs. {{ number_format($b->discount) }}</div>
                                @endif
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted py-4">No billings found for this period.</div>
                        @endforelse
                    </div>
                </div>
                @if($bills->hasPages())
                    <div class="card-footer bg-white border-top-0 pt-3">
                        {{ $bills->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>

            <div class="card shadow border-0">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fe fe-download mr-1"></i> Receipts</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($receipts as $b)
                            <div class="list-group-item py-3" wire:key="stat-receipt-{{ $b->id }}">
                                <div class="d-flex w-100 justify-content-between align-items-start mb-1">
                                    <div>
                                        <strong class="d-block mb-1 text-success">Incoming Receipt</strong>
                                        <small class="text-muted"><i class="fe fe-calendar"></i> {{ $b->date ? date('M d, Y', strtotime($b->date)) : date('M d, Y', strtotime($b->created_at)) }}</small>
                                        @if($b->discount > 0 || $b->tax > 0)
                                            <div class="mt-1 small">
                                                @if($b->discount > 0)<span class="text-danger mr-2">Dis. Rs. {{ number_format($b->discount) }}</span>@endif
                                                @if($b->tax > 0)<span class="text-warning">Tax Whld. Rs. {{ number_format($b->tax) }}</span>@endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <h6 class="mb-1 text-success">
                                            + Rs. {{ number_format($b->amount) }}<br>
                                            <small class="text-muted">Gross Amount</small>
                                        </h6>
                                        <div class="mt-2">
                                            <form action="{{ route('e.receipt.delete', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this receipt?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1" style="font-size: 0.75rem;"><i class="fe fe-trash-2"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted py-4">No receipts found for this period.</div>
                        @endforelse
                    </div>
                </div>
                @if($receipts->hasPages())
                    <div class="card-footer bg-white border-top-0 pt-3">
                        {{ $receipts->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
