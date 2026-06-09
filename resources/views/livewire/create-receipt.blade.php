<div>
    <div class="col-md-12 container-fluid">
        <a href="{{ route('e.receipts') }}" class="btn btn-secondary mb-3"><i class="fe fe-arrow-left fe-16"></i> Back</a>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header">
                        <strong class="card-title mb-0"><i class="fe fe-shopping-cart fe-16 mr-1"></i> New Receipt</strong>
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
                                {{-- Client --}}
                                <div class="col-md-4 form-group mb-3">
                                    <label for="receipt_client_id"><strong>Client</strong></label>
                                    <div wire:ignore>
                                        <select class="form-control" id="receipt_client_id" style="width:100%">
                                            <option value="">-- Select Client --</option>
                                            @foreach($clients as $c)
                                                <option value="{{ $c->id }}" {{ $client_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('client_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                                {{-- Date --}}
                                <div class="col-md-4 form-group mb-3">
                                    <label for="date"><strong>Date</strong></label>
                                    <input type="date" wire:model.live="date" class="form-control @error('date') is-invalid @enderror" id="date">
                                    @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                {{-- Amount --}}
                                <div class="col-md-4 form-group mb-3">
                                    <label for="amount"><strong>Amount Received (Rs.)</strong></label>
                                    <input type="number" step="0.01" wire:model.live.debounce.300ms="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="Enter amount">
                                    @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                {{-- Tax --}}
                                <div class="col-md-6 form-group mb-3">
                                    <label for="tax"><strong>Tax Withheld (Rs.)</strong></label>
                                    <input type="number" step="0.01" wire:model.live.debounce.300ms="tax" class="form-control @error('tax') is-invalid @enderror" id="tax" placeholder="0">
                                    @error('tax') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                {{-- Discount --}}
                                <div class="col-md-6 form-group mb-3">
                                    <label for="receipt_discount"><strong>Discount (Rs.)</strong></label>
                                    <input type="number" step="0.01" wire:model.live.debounce.300ms="discount" class="form-control @error('discount') is-invalid @enderror" id="receipt_discount" placeholder="0">
                                    @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <hr>

                            {{-- Summary & Submit --}}
                            <div class="row align-items-center p-3 rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border: 1px solid #dee2e6;">
                                <div class="col-md-8 d-flex flex-wrap align-items-center">
                                    <div class="mr-4 mb-2"><strong>Gross Amount:</strong> <span class="ml-1">Rs. {{ number_format((float)($amount ?: 0), 2) }}</span></div>
                                    @if((float)($tax ?: 0) > 0)
                                        <div class="mr-4 mb-2 text-info"><strong>Tax Withheld:</strong> <span class="ml-1">+ Rs. {{ number_format((float)$tax, 2) }}</span></div>
                                    @endif
                                    @if((float)($discount ?: 0) > 0)
                                        <div class="mr-4 mb-2 text-danger"><strong>Discount:</strong> <span class="ml-1">- Rs. {{ number_format((float)$discount, 2) }}</span></div>
                                    @endif
                                    <div class="aa-color mb-2" style="font-size: 1.25rem; font-weight: 700;">Net Total: Rs. {{ number_format($netTotal, 2) }}</div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button type="submit" class="btn btn-primary btn-lg w-100" wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fe fe-save fe-16 mr-1"></i> Create Receipt
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
