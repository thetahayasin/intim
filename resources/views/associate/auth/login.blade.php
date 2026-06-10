@extends('associate.app')

@section('content')
@php $activeTab = old('login_type', 'associate'); @endphp

<div class="col-lg-3 col-md-4 col-sm-9 col-11 mx-auto py-5">
    <div class="text-center mb-4">
        <img class="aalogo" src="{{ asset(\App\Models\Setting::get('site_logo', 'assets/img/logo-full.png')) }}"
             alt="Asif Associates Logo" style="width:60px;height:60px;object-fit:contain;">
    </div>

    {{-- Tab switcher --}}
    <div class="d-flex mb-4" style="border-bottom:2px solid #e0e0e0;">
        <button type="button" class="login-tab-btn flex-fill py-2 font-weight-600
                {{ $activeTab === 'associate' ? 'login-tab-active' : '' }}"
                data-target="tab-associate" style="background:none;border:none;border-bottom:2px solid transparent;margin-bottom:-2px;font-family:'IBM Plex Sans',sans-serif;font-size:14px;color:var(--cds-text-secondary);cursor:pointer;transition:color .15s,border-color .15s;">
            Associate
        </button>
        <button type="button" class="login-tab-btn flex-fill py-2 font-weight-600
                {{ $activeTab === 'admin' ? 'login-tab-active' : '' }}"
                data-target="tab-admin" style="background:none;border:none;border-bottom:2px solid transparent;margin-bottom:-2px;font-family:'IBM Plex Sans',sans-serif;font-size:14px;color:var(--cds-text-secondary);cursor:pointer;transition:color .15s,border-color .15s;">
            Admin
        </button>
    </div>

    {{-- Associate panel --}}
    <div id="tab-associate" class="login-panel" style="{{ $activeTab !== 'associate' ? 'display:none;' : '' }}">
        @include('components.message')
        @if($errors->any() && $activeTab === 'associate')
            <div class="alert alert-danger py-2 mb-3">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('ass') }}">
            @csrf
            <input type="hidden" name="login_type" value="associate">
            <div class="form-group">
                <input type="email" name="email" value="{{ old('email', '') }}" required autofocus
                       autocomplete="username"
                       class="form-control form-control-lg" placeholder="Email address">
            </div>
            <div class="form-group">
                <input type="password" name="password" required autocomplete="current-password"
                       class="form-control form-control-lg" placeholder="Password">
            </div>
            <div class="checkbox mb-3">
                <label><input type="checkbox" name="remember"> Remember me</label>
            </div>
            <button class="btn btn-lg btn-secondary btn-block" type="submit">Login</button>
        </form>
    </div>

    {{-- Admin panel --}}
    <div id="tab-admin" class="login-panel" style="{{ $activeTab !== 'admin' ? 'display:none;' : '' }}">
        @if($errors->any() && $activeTab === 'admin')
            <div class="alert alert-danger py-2 mb-3">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif
        <form method="POST" action="/admin">
            @csrf
            <input type="hidden" name="login_type" value="admin">
            <div class="form-group">
                <input type="email" name="email" value="{{ old('email', '') }}" required
                       autocomplete="username"
                       class="form-control form-control-lg" placeholder="Email address">
            </div>
            <div class="form-group">
                <input type="password" name="password" required autocomplete="current-password"
                       class="form-control form-control-lg" placeholder="Password">
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="checkbox mb-0">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                </div>
                <a class="text-muted small" href="{{ route('password.request') }}">Forgot password?</a>
            </div>
            <button class="btn btn-lg btn-secondary btn-block" type="submit">Login</button>
        </form>
    </div>

    <p class="mt-5 mb-0 text-center small" style="color:#161616;">
        Made with <i class="fe fe-cpu" style="vertical-align:-2px;"></i> by <b>Taha Yasin</b>
    </p>
</div>

<style>
.login-tab-btn.login-tab-active {
    color: var(--cds-text-primary) !important;
    border-bottom-color: #161616 !important;
    font-weight: 600;
}
</style>

<script>
document.querySelectorAll('.login-tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var target = this.getAttribute('data-target');
        document.querySelectorAll('.login-tab-btn').forEach(function(b) { b.classList.remove('login-tab-active'); });
        document.querySelectorAll('.login-panel').forEach(function(p) { p.style.display = 'none'; });
        this.classList.add('login-tab-active');
        document.getElementById(target).style.display = '';
    });
});
</script>
@endsection
