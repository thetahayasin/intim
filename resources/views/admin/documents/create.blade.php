@extends('admin.main')

@section('title', 'Asif Associates | New Agreement')

@section('content')
<div class="col-md-12 container-fluid">
    <a href="{{ route('e.documents') }}" wire:navigate class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left fe-16"></i> Back
    </a>

    <div class="row">
        <div class="col-md-10 my-4">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title"><i class="fe fe-file fe-16 mr-1"></i> New Agreement</strong>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('e.documents.store') }}" method="POST" id="docForm">
                        @csrf
                        <input type="hidden" name="type" value="agreement">

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label>Client / Company Name</label>
                                @php $selectedName = old('client_name', ''); @endphp
                                <select name="client_name" id="agreementClient"
                                        class="form-control @error('client_name') is-invalid @enderror">
                                    <option value=""></option>
                                    @foreach($clients->pluck('name')->values() as $name)
                                        <option value="{{ $name }}" {{ $selectedName === $name ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                    @if($selectedName && !$clients->pluck('name')->contains($selectedName))
                                        <option value="{{ $selectedName }}" selected>{{ $selectedName }}</option>
                                    @endif
                                </select>
                                @error('client_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label>Firm</label>
                                <select name="firm" class="form-control @error('firm') is-invalid @enderror">
                                    <option value="">— Select Firm —</option>
                                    <option value="0" {{ old('firm') === '0' ? 'selected' : '' }}>Asif Associates, Chartered Accountants</option>
                                    <option value="1" {{ old('firm') === '1' ? 'selected' : '' }}>H.A.M.D &amp; CO</option>
                                </select>
                                @error('firm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label>Start Date</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label>End Date</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control">
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
                            @if(old('services'))
                                @foreach(old('services') as $i => $svc)
                                <div class="row service-row mb-2" data-index="{{ $i }}">
                                    <div class="col-md-7">
                                        <select class="form-control svc-picker mb-1">
                                            @include('admin.documents._svc_options')
                                        </select>
                                        <input type="text" class="form-control svc-custom" placeholder="Type service name..." style="display:none">
                                        <input type="hidden" name="services[{{ $i }}][name]" class="svc-hidden" value="{{ $svc['name'] ?? '' }}">
                                        @error('services.'.$i.'.name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="services[{{ $i }}][fee]" value="{{ $svc['fee'] ?? '' }}"
                                               class="form-control" placeholder="Fee e.g. PKR 50,000">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-outline-secondary btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="row service-row mb-2" data-index="0">
                                <div class="col-md-7">
                                    <select class="form-control svc-picker mb-1">
                                        @include('admin.documents._svc_options')
                                    </select>
                                    <input type="text" class="form-control svc-custom" placeholder="Type service name..." style="display:none">
                                    <input type="hidden" name="services[0][name]" class="svc-hidden" value="">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="services[0][fee]" class="form-control" placeholder="Fee e.g. PKR 50,000">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-secondary btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button>
                                </div>
                            </div>
                            @endif
                        </div>

                        <hr>

                        <div class="form-group mb-3">
                            <label>Notes / Additional Terms <small class="text-muted">(optional)</small></label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Any additional notes or terms...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-secondary btn-lg">
                                <i class="fe fe-save fe-16"></i> Generate Agreement
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

var rowIndex = {{ old('services') ? count(old('services')) : 1 }};

var SVC_NAMES = Array.from(document.querySelectorAll('#servicesContainer .svc-picker option')).map(function(o){ return o.value; }).filter(function(v){ return v && v !== '__custom__'; });

function svcPickerHTML(idx) {
    var sel = document.querySelector('.svc-picker');
    return '<select class="form-control svc-picker mb-1">' + sel.innerHTML + '</select>' +
           '<input type="text" class="form-control svc-custom" placeholder="Type service name..." style="display:none">' +
           '<input type="hidden" name="services[' + idx + '][name]" class="svc-hidden" value="">';
}

function initSvcRow(row, existingVal) {
    var picker = row.querySelector('.svc-picker');
    var custom = row.querySelector('.svc-custom');
    var hidden = row.querySelector('.svc-hidden');
    if (existingVal) {
        hidden.value = existingVal;
        if (SVC_NAMES.indexOf(existingVal) !== -1) {
            picker.value = existingVal;
        } else {
            picker.value = '__custom__';
            custom.value = existingVal;
            custom.style.display = 'block';
        }
    }
    picker.addEventListener('change', function() {
        if (this.value === '__custom__') {
            custom.style.display = 'block'; custom.focus(); hidden.value = custom.value;
        } else if (this.value === '') {
            custom.style.display = 'none'; hidden.value = '';
        } else {
            custom.style.display = 'none'; hidden.value = this.value;
        }
    });
    custom.addEventListener('input', function() { hidden.value = this.value; });
}

document.querySelectorAll('.service-row').forEach(function(row) {
    initSvcRow(row, row.querySelector('.svc-hidden').value);
});

document.getElementById('addServiceRow').addEventListener('click', function() {
    var container = document.getElementById('servicesContainer');
    var row = document.createElement('div');
    row.className = 'row service-row mb-2';
    row.dataset.index = rowIndex;
    row.innerHTML =
        '<div class="col-md-7">' + svcPickerHTML(rowIndex) + '</div>' +
        '<div class="col-md-4"><input type="text" name="services[' + rowIndex + '][fee]" class="form-control" placeholder="Fee (optional)"></div>' +
        '<div class="col-md-1"><button type="button" class="btn btn-outline-secondary btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button></div>';
    container.appendChild(row);
    initSvcRow(row, '');
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
