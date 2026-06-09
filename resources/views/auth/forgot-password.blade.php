

<x-guest-layout>
    <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="POST" action="{{ route('password.email') }}">
        @csrf
    <div class="mx-auto text-center my-4">

        <img class="aalogo" src="{{ asset('assets/img/logo-full.png') }}" alt="Asif Associates Logo">
        <h2 class="my-3">Reset Password</h2>
    </div>
    @include('components.message')
    <p class="text-muted">Enter your email address and we'll send you an email with instructions to reset your password</p>
    <div class="form-group">
        <label for="email" class="sr-only">Email address</label>
        <input class="form-control" placeholder="Email Address" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus>
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>
    <p class="mt-5 mb-3 text-muted">
        Made with 🧠 by <a href="https://www.linkedin.com/in/thetahayasin/" target="_blank">Taha Yasin</a>
    </p>
    </form>
</x-guest-layout>
