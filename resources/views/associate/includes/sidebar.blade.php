<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
  <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
    <i class="fe fe-x"><span class="sr-only"></span></i>
  </a>
  <nav class="vertnav navbar navbar-light">
    <!-- nav bar -->
    <div class="w-100 mb-4 d-flex">
      <a wire:navigate class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ route('ass.dash') }}">
        <img class="aalogo logo-side" src="{{ asset('assets/img/logo-mini.png') }}" alt="Asif Associates Logo">
      </a>
    </div>
    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item">
        <a href="{{ route('ass.dash') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('ass.dash') ? 'side-select' : '' }}">
          <i class="fe fe-home fe-16"></i>
          <span class="ml-3 item-text">Dashboard</span>
        </a>
      </li>
    </ul>
    <p class="text-muted nav-heading mt-4 mb-1">
      <span>Time Sheet</span>
    </p>
    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item dropdown">
        <a href="{{ route('ass.attendance') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('ass.attendance') ? 'side-select' : '' }}">
          <i class="fe fe-check-circle fe-16"></i>
          <span class="ml-3 item-text">Attendance</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a href="{{ route('ass.leave') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('ass.leave') ? 'side-select' : '' }}">
          <i class="fe fe-file-text fe-16"></i>
          <span class="ml-3 item-text">Leave Application</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a href="{{ route('ass.progress') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('ass.progress') ? 'side-select' : '' }}">
          <i class="fe fe-trending-up fe-16"></i>
          <span class="ml-3 item-text">Progress</span>
        </a>
      </li>

    </ul>
    <p class="text-muted nav-heading mt-4 mb-1">
      <span>Resources</span>
    </p>
    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item dropdown">
        <a href="{{ route('ass.resources') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('ass.resources') ? 'side-select' : '' }}">
          <i class="fe fe-folder fe-16"></i>
          <span class="ml-3 item-text">Resources <span class="badge badge-warning" style="font-size:9px;vertical-align:middle;">Beta</span></span>
        </a>
      </li>
    </ul>
    <p class="text-muted nav-heading mt-4 mb-1">
      <span>Settings</span>
    </p>
    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item dropdown">
        <a href="{{ route('ass.profile') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('ass.profile') ? 'side-select' : '' }}">
          <i class="fe fe-user fe-16"></i>
          <span class="ml-3 item-text">Profile</span>
        </a>
      </li>

    </ul>
    <div class="btn-box w-100 mt-4 mb-1">
      <a href="https://wa.me/923011130751" target="_blank" class="btn mb-2 btn-primary btn-lg btn-block">
        <i class="fe fe-smartphone fe-12 mx-2"></i><span class="small">Contact Admin</span>
      </a>
    </div>
  </nav>
</aside>