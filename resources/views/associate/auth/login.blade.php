@extends('associate.app')

@section('content')

<form class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="POST" action="{{ route('ass') }}">
    @csrf
    <img class="aalogo" src="{{ asset('assets/img/logo-full.png') }}" alt="Asif Associates Logo">
    <hr>
    <b>If Your are an Admin - <a href="https://emp.asifassociatesca.com/admin">Login Here</a></b>
    <hr>
    @include('components.message')
    <div class="form-group">
        <label for="email" class="sr-only">Email</label>
        <input type="email" name="email" value="{{old('email')}}" required autofocus autocomplete="username" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email address">
        @error('email')
            <div class="invalid-feedback text-left">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="password" class="sr-only">Password</label>
        <input type="password" name="password" required autocomplete="current-password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password">
        @error('password')
            <div class="invalid-feedback text-left">{{ $message }}</div>
        @enderror
    </div>
    <div class="checkbox mb-3 remember-password mt-3">
    <label>
        <input id="remember_me" type="checkbox" name="remember"> Remember me 
    </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block aacolor" type="submit">Login</button>
    <p class="mt-5 mb-3 text-muted">
        Made with 🧠 by <span style="color:grey"><b>Taha Yasin</b></span>
    </p>
</form>
@endsection