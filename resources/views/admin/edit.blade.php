@extends('admin.main')

@section('title', 'Asif Associates | Edit Associate')

@section('content')

<div class="col-md-12 container-fluid">
    <a wire:navigate href="{{ route('e.associate') }}" class="btn btn-secondary"><i class="fe fe-arrow-left fe-16"></i> Back</a>
    <div class="row">
        <div class="col-md-12 my-4">
            <div class="card shadow">
                <div class="card-header">
                    <strong class="card-title">Edit Associate</strong>
                </div>
                <div class="card-body att-body">
                    <form action="{{ route('e.associate.update', $associate->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name">Full Name</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $associate->name) }}" class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" value="{{ old('email', $associate->email) }}" name="email" class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password">Password <small class="text-muted">(leave blank to keep current)</small></label>
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                                    @error('password')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
<div class="form-group mb-3">
    <label for="opening_presents">Opening Presents</label>
    <input type="number" id="opening_presents" name="opening_presents"
           value="{{ old('opening_presents', $associate->opening_presents) }}"
           class="form-control @error('opening_presents') is-invalid @enderror">
    @error('opening_presents')
        <div class="invalid-feedback text-left">{{ $message }}</div>
    @enderror
</div>

<div class="form-group mb-3">
    <label for="opening_leaves">Opening Leaves</label>
    <input type="number" id="opening_leaves" name="opening_leaves"
           value="{{ old('opening_leaves', $associate->opening_leaves) }}"
           class="form-control @error('opening_leaves') is-invalid @enderror">
    @error('opening_leaves')
        <div class="invalid-feedback text-left">{{ $message }}</div>
    @enderror
</div>

<div class="form-group mb-3">
    <label for="opening_absents">Opening Absents</label>
    <input type="number" id="opening_absents" name="opening_absents"
           value="{{ old('opening_absents', $associate->opening_absents) }}"
           class="form-control @error('opening_absents') is-invalid @enderror">
    @error('opening_absents')
        <div class="invalid-feedback text-left">{{ $message }}</div>
    @enderror
</div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="crn">CRN</label>
                                    <input type="number" id="crn" value="{{ old('crn', $associate->crn) }}" name="crn" class="form-control @error('crn') is-invalid @enderror">
                                    @error('crn')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="fts">FTS</label>
                                    <input type="number" value="{{ old('fts', $associate->fts) }}" id="fts" name="fts" class="form-control @error('fts') is-invalid @enderror">
                                    @error('fts')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="period">Period</label>
                                    <input type="text" id="period" value="{{ old('period', $associate->period) }}" name="period" class="form-control @error('period') is-invalid @enderror">
                                    @error('period')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="date_joined">Joining Date</label>
                                    <input type="date" id="date_joined" name="date_joined" value="{{ old('date_joined', $associate->date_joined) }}" class="form-control @error('date_joined') is-invalid @enderror">
                                    @error('date_joined')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                        <input type="submit" value="Update" class="btn btn-primary btn-lg float-right">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection