@section('title', 'Asif Associates | Admin Login')
<x-guest-layout>
    <!-- Session Status -->
    

    <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="POST" action="{{ route('login') }}">
    @csrf
        <img class="aalogo" src="{{ asset('assets/img/logo-full.png') }}" alt="Asif Associates Logo">
    @include('components.message')
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="form-group">
    <label for="email" class="sr-only">Email</label>
    <input type="email" name="email" :value="old('email')" required autofocus autocomplete="username" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Email address">
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <div class="form-group">
    <label for="password" class="sr-only">Password</label>
    <input type="password" name="password" required autocomplete="current-password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Password">
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>
    <div class="checkbox mb-3 remember-password mt-3">
    <label>
        <input id="remember_me" type="checkbox" name="remember"> Remember me </label>
    </div>
    <a class="forget-password mt-3" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
    <p class="mt-5 mb-3 text-muted">
        Made with 🧠 by <span style="color:grey"><b>Taha Yasin</b></span>
    </p>
</form>
</x-guest-layout>
