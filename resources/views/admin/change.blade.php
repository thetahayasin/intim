@extends('admin.main')

@section('title', 'Asif Associates | Change Password')

@section('content')

<div class="col-md-12 container-fluid">

    <div class="row">
        <div class="col-md-6 my-4">
            <div class="card shadow">
                <div class="card-body att-body">
                    <h5 class="card-title">Change Admin Panel Password</h5>
                    @include('components.message')
                    <form method="post" action="{{ route('e.password.update') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback text-left">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                            @error('new_password')
                                <div class="invalid-feedback text-left">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="new_password_confirmation" class="form-control @error('new_password_confirmation') is-invalid @enderror">
                            @error('confirm_password')
                                <div class="invalid-feedback text-left">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection