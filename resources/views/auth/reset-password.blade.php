
<x-guest-layout>
    <form class="col-lg-3 col-md-4 col-10 mx-auto" method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">
        
    <div class="mx-auto my-4 text-center">
    <img class="aalogo" src="{{ asset('assets/img/logo-full.png') }}" alt="Asif Associates Logo">

        <h2 class="my-3">Reset Password</h2>
    </div>
    @include('components.message')
    <div class="form-group">
        <label for="inputEmail4">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter Your Email Address" id="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username">
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <hr class="my-4">
    <div class="row mb-4">
        <div class="col-md-6">
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" type="password"
                        name="password"
                        required autocomplete="new-password" placeholder="Enter New Password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm New Password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        </div>
        <div class="col-md-6">
        <p class="mb-2">Password requirements</p>
        <p class="small text-muted mb-2"> To create a new password, you have to meet all of the following requirements: </p>
        <ul class="small text-muted pl-4 mb-0">
            <li> Minimum 8 character </li>
            <li>At least one special character</li>
            <li>At least one number</li>
        </ul>
        </div>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>
    <center>
        <p class="mt-5 mb-3 text-muted">
            Made with 🧠 by <a href="https://www.linkedin.com/in/thetahayasin/" target="_blank">Taha Yasin</a>
        </p>
    </center>
    </form>
</x-guest-layout>