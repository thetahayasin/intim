<div>
    <div class="col-md-12 container-fluid">
        <a href="{{ route('e.billings') }}" class="btn btn-secondary mb-3"><i class="fe fe-arrow-left fe-16"></i> Back</a>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong class="card-title mb-0"><i class="fe fe-file-text fe-16 mr-1"></i> New Billing</strong>
                        <span class="badge badge-primary">{{ count($items) }} Item(s)</span>
                    </div>
                    <div class="card-body">
                        {{-- Flash Messages --}}
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fe fe-check-circle fe-16 mr-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fe fe-alert-circle fe-16 mr-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form wire:submit.prevent="save">
                            <div class="row">
                                {{-- Client Selection --}}
                                <div class="col-md-4 form-group mb-3">
                                    <label for="billing_client_id"><strong>Client</strong></label>
                                    <input type="hidden" id="billing_client_id_hidden" wire:model.live="client_id">
                                    <div wire:ignore>
                                        <select class="form-control" id="billing_client_id" style="width:100%">
                                            <option value="">-- Select Client --</option>
                                            @foreach($clients as $c)
                                                <option value="{{ $c->id }}" {{ $client_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('client_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                {{-- Firm Selection --}}
                                <div class="col-md-4 form-group mb-3">
                                    <label for="billing_firm"><strong>Firm</strong></label>
                                    <input type="hidden" id="billing_firm_hidden" wire:model.live="firm">
                                    <div wire:ignore>
                                        <select class="form-control" id="billing_firm" style="width:100%">
                                            <option value="">-- Select Firm --</option>
                                            <option value="0" {{ $firm == '0' ? 'selected' : '' }}>Asif Associates</option>
                                            <option value="1" {{ $firm == '1' ? 'selected' : '' }}>HAMD</option>
                                        </select>
                                    </div>
                                    @error('firm') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                {{-- Discount --}}
                                <div class="col-md-4 form-group mb-3">
                                    <label for="discount"><strong>Total Discount (Rs.)</strong></label>
                                    <input type="number" step="0.01" wire:model.live.debounce.300ms="discount" class="form-control @error('discount') is-invalid @enderror" id="discount" placeholder="0">
                                    @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <hr>

                            {{-- Invoice Items --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="fe fe-list fe-16 mr-1"></i> Invoice Line Items</h6>
                                <button type="button" class="btn btn-success btn-sm" wire:click="addItem">
                                    <i class="fe fe-plus fe-12"></i> Add Row
                                </button>
                            </div>

                            @foreach($items as $index => $item)
                                <div class="card mb-3" wire:key="item-{{ $index }}" style="border: 1px solid rgba(0,0,0,0.1); border-radius: 8px;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge badge-secondary">Item #{{ $index + 1 }}</span>
                                            @if(count($items) > 1)
                                                <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeItem({{ $index }})">
                                                    <i class="fe fe-trash-2 fe-12"></i> Remove
                                                </button>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-2">
                                                    <label class="small text-muted">Description</label>
                                                    <input type="text" wire:model.live.debounce.500ms="items.{{ $index }}.description" class="form-control @error('items.'.$index.'.description') is-invalid @enderror" placeholder="e.g. Annual Audit FY 2025">
                                                    @error('items.'.$index.'.description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-2">
                                                    <label class="small text-muted">Service</label>
                                                    <select wire:model.live.debounce.500ms="items.{{ $index }}.service" class="form-control">
                                                        <option value="">-- Select --</option>
                                                        <option value="Audit">Audit</option>
                                                        <option value="Tax">Tax</option>
                                                        <option value="ERP">ERP</option>
                                                        <option value="Advisory">Advisory</option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-2">
                                                    <label class="small text-muted">Amount (Rs.)</label>
                                                    <input type="number" step="0.01" wire:model.live.debounce.500ms="items.{{ $index }}.amount" class="form-control @error('items.'.$index.'.amount') is-invalid @enderror" placeholder="0">
                                                    @error('items.'.$index.'.amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group mb-2">
                                                    <label class="small text-muted">Tax (Rs.)</label>
                                                    <input type="number" step="0.01" wire:model.live.debounce.500ms="items.{{ $index }}.tax" class="form-control" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <hr>

                            {{-- Summary & Submit --}}
                            <div class="row align-items-center p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border: 1px solid #dee2e6;">
                                <div class="col-md-8 d-flex flex-wrap align-items-center">
                                    <div class="mr-4 mb-2"><strong>Subtotal:</strong> <span class="ml-1 text-dark">Rs. {{ number_format($subtotal, 2) }}</span></div>
                                    <div class="mr-4 mb-2"><strong>Tax:</strong> <span class="ml-1 text-dark">Rs. {{ number_format($totalTax, 2) }}</span></div>
                                    @if($discount > 0)
                                        <div class="mr-4 mb-2 text-danger"><strong>Discount:</strong> <span class="ml-1">- Rs. {{ number_format($discount, 2) }}</span></div>
                                    @endif
                                    <div class="aa-color mb-2" style="font-size: 1.25rem; font-weight: 700;">Grand Total: Rs. {{ number_format($grandTotal, 2) }}</div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button type="submit" class="btn btn-primary btn-lg w-100" wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fe fe-save fe-16 mr-1"></i> Create Billing
                                        </span>
                                        <span wire:loading>
                                            <span class="spinner-border spinner-border-sm mr-1"></span> Saving...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
