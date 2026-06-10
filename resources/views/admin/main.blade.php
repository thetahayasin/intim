<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset(\App\Models\Setting::get('site_favicon', 'favicon.png')) }}">
    <title>@yield('title', 'Asif Associates')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app-light.css') }}" id="lightTheme">
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}" id="darkTheme" disabled>
    @livewireStyles
    @yield('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  </head>
  <body class="vertical  light  ">
    <div class="wrapper">
    @include('admin.includes.navbar')
    @include('admin.includes.sidebar')
    <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            @yield('content')
          </div>
        </div>
    </main>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/tinycolor-min.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/apps.js') }}"></script>
    <script>
    // ── Sidebar state management ─────────────────────────────────────────────
    // Problem: Livewire morphdom resets <body class="vertical light"> on every
    // navigate, wiping "collapsed" — browser can paint the expanded state before
    // our script re-executes.
    // Fix: MutationObserver on body fires in the microtask queue BEFORE any paint,
    // so it restores "collapsed" the instant morphdom removes it — zero flash.
    (function ($) {
        var KEY = 'sidebar_collapsed';

        // 1. One-time setup (persists across all navigations)
        if (!window.__sidebarInit) {
            window.__sidebarInit = true;

            // MutationObserver: restore collapsed the moment morphdom strips the class
            var observer = new MutationObserver(function () {
                if (sessionStorage.getItem(KEY) === '1') {
                    var v = document.querySelector('.vertical');
                    if (v && !v.classList.contains('collapsed')) {
                        v.classList.add('collapsed');
                    }
                }
            });
            observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });

            // Persist state when toggle is clicked
            document.addEventListener('click', function (e) {
                if (e.target.closest('.collapseSidebar')) {
                    setTimeout(function () {
                        var v = document.querySelector('.vertical');
                        sessionStorage.setItem(KEY, (v && v.classList.contains('collapsed')) ? '1' : '0');
                    }, 50);
                }
            });

            // Strip hover handlers after every navigate
            document.addEventListener('livewire:navigated', function () {
                setTimeout(function () { $('.sidebar-left').off('mouseenter mouseleave'); }, 0);
            });
        }

        // 2. Strip hover on this execution (covers initial load + navigate re-exec)
        setTimeout(function () { $('.sidebar-left').off('mouseenter mouseleave'); }, 0);

        // 3. Apply saved state on initial load
        if (sessionStorage.getItem(KEY) === '1') {
            var v = document.querySelector('.vertical');
            if (v) v.classList.add('collapsed');
        }
    })(jQuery);
    </script>
    <script src="{{ asset('assets/js/chart.v4.min.js') }}"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @livewireScripts
    @yield('scripts')
    @include('components.confirm-modal')
  </body>
</html>