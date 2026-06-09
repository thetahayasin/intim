@extends('admin.main')

@section('title', 'Asif Associates | New Agreement')

@section('content')
<div class="col-md-12 container-fluid">
    <a href="{{ route('e.documents') }}" wire:navigate class="btn btn-secondary mb-3">
        <i class="fe fe-arrow-left fe-16"></i> Back
    </a>

    <div class="row">
        <div class="col-md-10 my-4">
            <div class="card shadow">
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
                                <label><strong>Client / Company Name</strong></label>
                                <input type="text" name="client_name" value="{{ old('client_name') }}"
                                       list="clientsList"
                                       class="form-control @error('client_name') is-invalid @enderror"
                                       placeholder="Type or select client...">
                                <datalist id="clientsList">
                                    @foreach($clients as $c)
                                        <option value="{{ $c->name }}">
                                    @endforeach
                                </datalist>
                                @error('client_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label><strong>Firm</strong></label>
                                <select name="firm" class="form-control @error('firm') is-invalid @enderror">
                                    <option value="">-- Select Firm --</option>
                                    <option value="0" {{ old('firm') === '0' ? 'selected' : '' }}>Asif Associates, Chartered Accountants</option>
                                    <option value="1" {{ old('firm') === '1' ? 'selected' : '' }}>H.A.M.D &amp; CO</option>
                                </select>
                                @error('firm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label><strong>Start Date</strong></label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control">
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label><strong>End Date</strong></label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control">
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0"><i class="fe fe-list fe-16 mr-1"></i> Engagement Services &amp; Fees</h6>
                            <button type="button" class="btn btn-success btn-sm" id="addServiceRow">
                                <i class="fe fe-plus fe-12"></i> Add Service
                            </button>
                        </div>

                        <div id="servicesContainer">
                            @if(old('services'))
                                @foreach(old('services') as $i => $svc)
                                <div class="row service-row mb-2" data-index="{{ $i }}">
                                    <div class="col-md-7">
                                        <input type="text" name="services[{{ $i }}][name]" value="{{ $svc['name'] }}"
                                               class="form-control @error('services.'.$i.'.name') is-invalid @enderror"
                                               placeholder="Service name e.g. Taxation Services">
                                        @error('services.'.$i.'.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="services[{{ $i }}][fee]" value="{{ $svc['fee'] }}"
                                               class="form-control" placeholder="Fee (optional) e.g. PKR 50,000">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-outline-danger btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="row service-row mb-2" data-index="0">
                                <div class="col-md-7">
                                    <input type="text" name="services[0][name]" class="form-control" placeholder="Service name e.g. Taxation Services">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="services[0][fee]" class="form-control" placeholder="Fee (optional) e.g. PKR 50,000">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-danger btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button>
                                </div>
                            </div>
                            @endif
                        </div>

                        <hr>

                        <div class="form-group mb-3">
                            <label><strong>Notes / Additional Terms</strong> <small class="text-muted">(optional)</small></label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Any additional notes or terms...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fe fe-save fe-16 mr-1"></i> Generate Agreement
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
    var rowIndex = {{ old('services') ? count(old('services')) : 1 }};

    document.getElementById('addServiceRow').addEventListener('click', function() {
        var container = document.getElementById('servicesContainer');
        var row = document.createElement('div');
        row.className = 'row service-row mb-2';
        row.dataset.index = rowIndex;
        row.innerHTML = '<div class="col-md-7"><input type="text" name="services[' + rowIndex + '][name]" class="form-control" placeholder="Service name"></div>'
            + '<div class="col-md-4"><input type="text" name="services[' + rowIndex + '][fee]" class="form-control" placeholder="Fee (optional)"></div>'
            + '<div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm removeRow"><i class="fe fe-trash-2 fe-12"></i></button></div>';
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
