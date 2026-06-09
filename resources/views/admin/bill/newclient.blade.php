@extends('admin.main')

@section('title', 'Asif Associates | New Client')

@section('content')

<div class="col-md-12 container-fluid">
    <a wire:navigate href="{{ route('e.client') }}" class="btn btn-secondary"><i class="fe fe-arrow-left fe-16"></i> Back</a> 
    <div class="row">
        <div class="col-md-6 my-4">
            <div class="card shadow">
                <div class="card-header">
                    <strong class="card-title">New Client</strong>
                </div>
                <div class="card-body att-body">
                    <form action="{{ route('e.client.add') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" value="{{ old('email') }}" name="email" class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="client_representative">Client Representative</label>
                                    <input type="text" id="client_representative" value="{{ old('client_representative') }}" name="client_representative" class="form-control @error('client_representative') is-invalid @enderror">
                                    @error('client_representative')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="representative_contact">Representative Contact</label>
                                    <input type="text" id="representative_contact" value="{{ old('representative_contact') }}" name="representative_contact" class="form-control @error('representative_contact') is-invalid @enderror">
                                    @error('representative_contact')
                                        <div class="invalid-feedback text-left">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                        <input type="submit" value="Create" class="btn btn-primary btn-lg float-right">
                    </form>

                </div>
            </div>
        </div>
    </div>


</div>


@endsection


