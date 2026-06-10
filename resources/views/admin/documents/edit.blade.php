@extends('admin.main')

@section('title', 'Asif Associates | Edit Agreement')

@section('content')
<div class="col-md-12 container-fluid">
    <a href="{{ route('e.documents') }}" wire:navigate class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left fe-16"></i> Back
    </a>

    <div class="row">
        <div class="col-md-10 my-4">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title"><i class="fe fe-edit-2 fe-16 mr-1"></i> Edit Agreement — {{ $doc->client_name }}</strong>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('e.documents.update', $doc->id) }}" method="POST" id="docForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label>Client / Company Name</label>
                                @php
                                    $selectedName = old('client_name', $doc->client_name);
                                    $clientNames  = $clients->pluck('name')->values()->toArray();
                                    if ($selectedName && !in_array($selectedName, $clientNames)) {
                                        array_unshift($clientNames, $selectedName);
                                    }
                                @endphp
                                <select name="client_name" id="agreementClient"
                                        class="form-control @error('client_name') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach($clientNames as $name)
                                        <option value="{{ $name }}" {{ $selectedName === $name ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('client_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label>Firm</label>
                                <select name="firm" class="form-control @error('firm') is-invalid @enderror">
                                    <option value="">— Select Firm —</option>
                                    <option value="0" {{ old('firm', $doc->firm) == '0' ? 'selected' : '' }}>Asif Associates, Chartered Accountants</option>
                                    <option value="1" {{ old('firm', $doc->firm) == '1' ? 'selected' : '' }}>H.A.M.D &amp; CO</option>
                                </select>
                                @error('firm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label>Start Date</label>
                                <input type="date" name="start_date"
                                       value="{{ old('start_date', $doc->start_date?->format('Y-m-d')) }}"
                                       class="form-control">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>End Date</label>
                                <input type="date" name="end_date"
                                       value="{{ old('end_date', $doc->end_date?->format('Y-m-d')) }}"
                                       class="form-control">
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fe fe-list fe-16 mr-1"></i> Engagement Services &amp; Fees</h6>
                            <button type="button" class="btn btn-secondary btn-sm" id="addServiceRow">
                                <i class="fe fe-plus fe-12"></i> Add Service
                            </button>
                        </div>

                        <div id="servicesContainer">
                            @php $services = old('services', $doc->services ?? []); @endphp
                            @foreach($services as $i => $svc)
                            <div class="row service-row mb-2" data-index="{{ $i }}">
                                <div class="col-md-7">
                                    <input type="text" name="services[{{ $i }}][name]"
                                           value="{{ $svc['name'] ?? '' }}"
                                           class="form-control @error('services.'.$i.'.name') is-invalid @enderror"
                                           placeholder="Service name e.g. Taxation Services">
                                    @error('services.'.$i.'.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="services[{{ $i }}][fee]"
                                           value="{{ $svc['fee'] ?? '' }}"
                                           class="form-control" placeholder="Fee e.g. PKR 50,000">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="form-group mb-3">
                            <label>Notes / Additional Terms <small class="text-muted">(optional)</small></label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Any additional notes or terms...">{{ old('notes', $doc->notes) }}</textarea>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('e.documents.view', $doc->id) }}" target="_blank" class="btn btn-outline-secondary mr-2">
                                <i class="fe fe-printer fe-16"></i> Preview
                            </a>
                            <button type="submit" class="btn btn-secondary btn-lg">
                                <i class="fe fe-save fe-16"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function initAgreementSelect2() {
    if (!document.getElementById('agreementClient')) return;
    if (typeof jQuery === 'undefined' || !jQuery.fn.select2) { setTimeout(initAgreementSelect2, 80); return; }
    if (jQuery('#agreementClient').data('select2')) jQuery('#agreementClient').select2('destroy');
    jQuery('#agreementClient').select2({
        width: '100%',
        placeholder: '-- Select or type client name --',
        tags: true,
        allowClear: false
    });
}
document.removeEventListener('livewire:navigated', initAgreementSelect2);
document.addEventListener('livewire:navigated', initAgreementSelect2);
initAgreementSelect2();

var rowIndex = {{ count(old('services', $doc->services ?? [])) }};

document.getElementById('addServiceRow').addEventListener('click', function() {
    var container = document.getElementById('servicesContainer');
    var row = document.createElement('div');
    row.className = 'row service-row mb-2';
    row.dataset.index = rowIndex;
    row.innerHTML =
        '<div class="col-md-7"><input type="text" name="services[' + rowIndex + '][name]" class="form-control" placeholder="Service name"></div>' +
        '<div class="col-md-4"><input type="text" name="services[' + rowIndex + '][fee]" class="form-control" placeholder="Fee (optional)"></div>' +
        '<div class="col-md-1"><button type="button" class="btn btn-outline-secondary btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button></div>';
    container.appendChild(row);
    rowIndex++;
});

document.getElementById('servicesContainer').addEventListener('click', function(e) {
    var btn = e.target.closest('.removeRow');
    if (btn && document.querySelectorAll('.service-row').length > 1) {
        btn.closest('.service-row').remove();
    }
});
</script>
@endsection
