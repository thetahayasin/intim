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
    // ── Sidebar collapse ──────────────────────────────────────────────────────
    // CSS is driven by html.sidebar-collapsed — Livewire morphdom never touches
    // <html>, so the class (and layout) survives every wire:navigate with no flinch.
    // apps.js still toggles .collapsed on <body> for its own logic; we mirror that.
    (function ($) {
        var KEY = 'sidebar_collapsed';
        var html = document.documentElement;

        // Apply saved state immediately (runs on load and on each navigate re-exec)
        if (sessionStorage.getItem(KEY) === '1') {
            html.classList.add('sidebar-collapsed');
            var v = document.querySelector('.vertical');
            if (v) v.classList.add('collapsed');
        }

        // Strip apps.js hover handlers after they bind
        setTimeout(function () { $('.sidebar-left').off('mouseenter mouseleave'); }, 0);

        // One-time bindings — never re-add across navigations
        if (!window.__sidebarInit) {
            window.__sidebarInit = true;

            // Mirror apps.js toggle: sync html class + persist to sessionStorage
            document.addEventListener('click', function (e) {
                if (e.target.closest('.collapseSidebar')) {
                    setTimeout(function () {
                        var isCollapsed = document.querySelector('.vertical') &&
                                          document.querySelector('.vertical').classList.contains('collapsed');
                        if (isCollapsed) {
                            html.classList.add('sidebar-collapsed');
                            sessionStorage.setItem(KEY, '1');
                        } else {
                            html.classList.remove('sidebar-collapsed');
                            sessionStorage.setItem(KEY, '0');
                        }
                    }, 50);
                }
            });

            // After navigate: re-apply body class (html class already survived morphdom)
            document.addEventListener('livewire:navigated', function () {
                if (sessionStorage.getItem(KEY) === '1') {
                    var v = document.querySelector('.vertical');
                    if (v) v.classList.add('collapsed');
                }
                setTimeout(function () { $('.sidebar-left').off('mouseenter mouseleave'); }, 0);
            });
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