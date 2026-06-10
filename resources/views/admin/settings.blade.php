@extends('admin.main')

@section('title', 'Asif Associates | Settings')

@section('content')
<div class="col-md-12 container-fluid">

    @php $activeTab = request('tab', 'password'); @endphp

    <div class="row my-4">
        <div class="col-md-8">
            <h4 class="mb-0" style="color:var(--cds-text-primary);font-weight:600;">Settings</h4>
            <p class="text-muted small mb-0">Manage password, email delivery, and site branding.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header p-0" style="border-bottom:1px solid #e0e0e0;">
            <nav class="nav" id="settingsTabs" style="gap:0;">
                <a href="{{ route('e.settings', ['tab' => 'password']) }}"
                   class="nav-link px-4 py-3 {{ $activeTab === 'password' ? 'active' : '' }}"
                   style="{{ $activeTab === 'password' ? 'border-bottom:2px solid #161616;color:#161616;font-weight:600;' : 'color:#525252;border-bottom:2px solid transparent;' }}">
                    <i class="fe fe-lock fe-14 mr-1"></i> Password
                </a>
                <a href="{{ route('e.settings', ['tab' => 'smtp']) }}"
                   class="nav-link px-4 py-3 {{ $activeTab === 'smtp' ? 'active' : '' }}"
                   style="{{ $activeTab === 'smtp' ? 'border-bottom:2px solid #161616;color:#161616;font-weight:600;' : 'color:#525252;border-bottom:2px solid transparent;' }}">
                    <i class="fe fe-mail fe-14 mr-1"></i> Email / SMTP
                </a>
                <a href="{{ route('e.settings', ['tab' => 'branding']) }}"
                   class="nav-link px-4 py-3 {{ $activeTab === 'branding' ? 'active' : '' }}"
                   style="{{ $activeTab === 'branding' ? 'border-bottom:2px solid #161616;color:#161616;font-weight:600;' : 'color:#525252;border-bottom:2px solid transparent;' }}">
                    <i class="fe fe-image fe-14 mr-1"></i> Branding
                </a>
            </nav>
        </div>
        <div class="card-body">

            @include('components.message')

            {{-- ─── PASSWORD TAB ─── --}}
            @if($activeTab === 'password')
            <div class="row">
                <div class="col-md-5">
                    <h6 class="mb-3" style="color:var(--cds-text-primary);font-weight:600;">Change Password</h6>
                    <form method="POST" action="{{ route('e.settings.password') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="small text-muted mb-1">Current Password</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="small text-muted mb-1">New Password</label>
                            <input type="password" name="new_password"
                                   class="form-control @error('new_password') is-invalid @enderror">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label class="small text-muted mb-1">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation"
                                   class="form-control">
                        </div>
                        <button type="submit" class="btn btn-secondary">Update Password</button>
                    </form>
                </div>
            </div>
            @endif

            {{-- ─── SMTP TAB ─── --}}
            @if($activeTab === 'smtp')
            <div class="row">
                <div class="col-md-7">
                    <h6 class="mb-3" style="color:var(--cds-text-primary);font-weight:600;">Email / SMTP Settings</h6>
                    <p class="text-muted small mb-4">These settings override the <code>.env</code> mail configuration at runtime. Emails are sent using these values immediately after saving.</p>
                    <form method="POST" action="{{ route('e.settings.smtp') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-8 form-group mb-3">
                                <label class="small text-muted mb-1">SMTP Host</label>
                                <input type="text" name="smtp_host"
                                       value="{{ old('smtp_host', $settings->get('smtp_host')) }}"
                                       class="form-control @error('smtp_host') is-invalid @enderror"
                                       placeholder="smtp.gmail.com">
                                @error('smtp_host')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label class="small text-muted mb-1">Port</label>
                                <input type="number" name="smtp_port"
                                       value="{{ old('smtp_port', $settings->get('smtp_port', 587)) }}"
                                       class="form-control @error('smtp_port') is-invalid @enderror"
                                       placeholder="587">
                                @error('smtp_port')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small text-muted mb-1">Username</label>
                            <input type="text" name="smtp_username"
                                   value="{{ old('smtp_username', $settings->get('smtp_username')) }}"
                                   class="form-control @error('smtp_username') is-invalid @enderror"
                                   placeholder="your@email.com">
                            @error('smtp_username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="small text-muted mb-1">Password</label>
                            <input type="password" name="smtp_password"
                                   value="{{ old('smtp_password', $settings->get('smtp_password')) }}"
                                   class="form-control @error('smtp_password') is-invalid @enderror">
                            @error('smtp_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="small text-muted mb-1">Encryption</label>
                            <select name="smtp_encryption" class="form-control">
                                <option value="tls" {{ $settings->get('smtp_encryption', 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ $settings->get('smtp_encryption') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="starttls" {{ $settings->get('smtp_encryption') === 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="small text-muted mb-1">From Name</label>
                                <input type="text" name="smtp_from_name"
                                       value="{{ old('smtp_from_name', $settings->get('smtp_from_name')) }}"
                                       class="form-control @error('smtp_from_name') is-invalid @enderror"
                                       placeholder="Asif Associates">
                                @error('smtp_from_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="small text-muted mb-1">From Address</label>
                                <input type="email" name="smtp_from_address"
                                       value="{{ old('smtp_from_address', $settings->get('smtp_from_address')) }}"
                                       class="form-control @error('smtp_from_address') is-invalid @enderror"
                                       placeholder="noreply@example.com">
                                @error('smtp_from_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary">Save Email Settings</button>
                    </form>
                </div>
            </div>
            @endif

            {{-- ─── BRANDING TAB ─── --}}
            @if($activeTab === 'branding')
            @php
                $currentLogo    = $settings->get('site_logo');
                $currentFavicon = $settings->get('site_favicon');
            @endphp
            <div class="row">
                <div class="col-md-7">
                    <h6 class="mb-3" style="color:var(--cds-text-primary);font-weight:600;">Site Branding</h6>
                    <form method="POST" action="{{ route('e.settings.branding') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Logo --}}
                        <div class="mb-4">
                            <label class="small text-muted mb-1 d-block">Sidebar Logo</label>
                            @if($currentLogo)
                            <div class="d-flex align-items-center mb-2" style="gap:12px;">
                                <img src="{{ asset($currentLogo) }}" alt="Current Logo"
                                     style="height:40px;width:auto;border:1px solid #e0e0e0;padding:4px;border-radius:4px;background:#f4f4f4;">
                                <span class="small text-muted">Current logo</span>
                                <button type="submit" name="delete_logo" value="1"
                                        class="btn btn-sm btn-outline-secondary"
                                        data-confirm-delete="Remove the current logo and revert to default?">
                                    <i class="fe fe-trash-2 fe-12"></i> Remove
                                </button>
                            </div>
                            @endif
                            <input type="file" name="site_logo" class="form-control-file"
                                   accept="image/png,image/jpeg,image/svg+xml,image/webp">
                            <small class="text-muted">PNG, JPG, SVG or WebP · max 2 MB. Displayed at 40px height in sidebar.</small>
                            @error('site_logo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Favicon --}}
                        <div class="mb-4">
                            <label class="small text-muted mb-1 d-block">Favicon</label>
                            @if($currentFavicon)
                            <div class="d-flex align-items-center mb-2" style="gap:12px;">
                                <img src="{{ asset($currentFavicon) }}" alt="Current Favicon"
                                     style="height:32px;width:32px;border:1px solid #e0e0e0;padding:4px;border-radius:4px;background:#f4f4f4;">
                                <span class="small text-muted">Current favicon</span>
                                <button type="submit" name="delete_favicon" value="1"
                                        class="btn btn-sm btn-outline-secondary"
                                        data-confirm-delete="Remove the current favicon and revert to default?">
                                    <i class="fe fe-trash-2 fe-12"></i> Remove
                                </button>
                            </div>
                            @endif
                            <input type="file" name="site_favicon" class="form-control-file"
                                   accept="image/png,image/jpeg,image/x-icon">
                            <small class="text-muted">PNG, JPG or ICO · max 512 KB. Displayed as browser tab icon.</small>
                            @error('site_favicon')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-secondary">Save Branding</button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection
