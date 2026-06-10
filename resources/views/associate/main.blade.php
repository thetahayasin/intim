<!DOCTYPE html>
<html lang="en" @if(($_COOKIE['sidebar_collapsed'] ?? '0') === '1') class="sidebar-collapsed" @endif>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'Asif Associates')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">
    @yield('styles')

    <link rel="stylesheet" href="{{ asset('assets/css/app-light.css') }}" id="lightTheme">
    <link rel="stylesheet" href="{{ asset('assets/css/app-dark.css') }}" id="darkTheme" disabled>

    @livewireStyles
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
  </head>
  <body class="vertical  light  ">
    <div class="wrapper">
    @include('associate.includes.navbar')
    @include('associate.includes.sidebar')
    <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            @yield('content')
          </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/tinycolor-min.js') }}"></script>
    @yield('scripts')
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/apps.js') }}"></script>
    <script>
    // ── Sidebar collapse ──────────────────────────────────────────────────────
    (function ($) {
        var html = document.documentElement;

        function syncBodyClass() {
            var v = document.querySelector('.vertical');
            if (!v) return;
            if (html.classList.contains('sidebar-collapsed')) v.classList.add('collapsed');
            else v.classList.remove('collapsed');
        }
        syncBodyClass();

        setTimeout(function () { $('.sidebar-left').off('mouseenter mouseleave'); }, 0);

        if (!window.__sidebarInit) {
            window.__sidebarInit = true;

            document.addEventListener('click', function (e) {
                if (e.target.closest('.collapseSidebar')) {
                    setTimeout(function () {
                        var v = document.querySelector('.vertical');
                        var isCollapsed = v && v.classList.contains('collapsed');
                        html.classList.toggle('sidebar-collapsed', isCollapsed);
                        document.cookie = 'sidebar_collapsed=' + (isCollapsed ? '1' : '0') +
                                          '; path=/; SameSite=Strict; max-age=31536000';
                    }, 50);
                }
            });

            document.addEventListener('livewire:navigated', function () {
                syncBodyClass();
                setTimeout(function () { $('.sidebar-left').off('mouseenter mouseleave'); }, 0);
            });
        }
    })(jQuery);
    </script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @livewireScripts
    @include('components.confirm-modal')
  </body>
</html>