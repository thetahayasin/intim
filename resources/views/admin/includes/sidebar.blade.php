<aside class="sidebar-left" id="leftSidebar" data-simplebar>
  <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
    <i class="fe fe-x"><span class="sr-only"></span></i>
  </a>
  <nav class="vertnav navbar navbar-light">
    <!-- nav bar -->
    <div class="sidebar-logo-full w-100 mb-4 d-flex">
      <a wire:navigate class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ route('e.dash') }}">
        <img class="aalogo logo-side" src="{{ asset(\App\Models\Setting::get('site_logo', 'assets/img/logo-mini.png')) }}" alt="Asif Associates Logo">
      </a>
    </div>
    <div class="sidebar-mini-brand">
      <a wire:navigate href="{{ route('e.dash') }}">
        <img src="{{ asset(\App\Models\Setting::get('site_logo', 'assets/img/logo-mini.png')) }}" alt="">
      </a>
    </div>
    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item">
        <a href="{{ route('e.dash') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.dash') ? 'side-select' : '' }}">
          <i class="fe fe-home fe-16"></i>
          <span class="ml-3 item-text">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('e.reports') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.reports') ? 'side-select' : '' }}">
          <i class="fe fe-bar-chart-2 fe-16"></i>
          <span class="ml-3 item-text">Financial Reports</span>
        </a>
      </li>
    </ul>
    <p class="text-muted nav-heading mt-4 mb-1">
      <span>Associates</span>
    </p>
    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item dropdown">
        <a href="{{ route('e.associate') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.associate') || Route::currentRouteNamed('e.associate.create') || Route::currentRouteNamed('e.associate.edit') ? 'side-select' : '' }}">
          <i class="fe fe-user-check fe-16"></i>
          <span class="ml-3 item-text">Management</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a href="{{ route('e.leave') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.leave') || Route::currentRouteNamed('e.leave.view') ? 'side-select' : '' }}">
          <i class="fe fe-check-circle fe-16"></i>
          <span class="ml-3 item-text">Leave Approvals</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a href="{{ route('e.progress') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.progress') || Route::currentRouteNamed('e.progress.breakup')  ? 'side-select' : '' }}">
          <i class="fe fe-trending-up fe-16"></i>
          <span class="ml-3 item-text">Progress</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a href="{{ route('calender.holidays') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('calender.holidays')  ? 'side-select' : '' }}">
          <i class="fe fe-heart fe-16"></i>
          <span class="ml-3 item-text">Holidays</span>
        </a>
      </li>
    </ul>
    <p class="text-muted nav-heading mt-4 mb-1">
      <span>Billing</span>
    </p>
    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item dropdown">
        <a href="{{ route('e.client') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.client') || Route::currentRouteNamed('e.client.create') || Route::currentRouteNamed('e.client.stats') || Route::currentRouteNamed('e.client.edit') ? 'side-select' : '' }}">
          <i class="fe fe-user-plus fe-16"></i>
          <span class="ml-3 item-text">Clients</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a href="{{ route('e.billings') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.billings') || Route::currentRouteNamed('e.billing.create') || Route::currentRouteNamed('e.billing.edit') || Route::currentRouteNamed('e.billing.stats') ? 'side-select' : '' }}">
          <i class="fe fe-dollar-sign fe-16"></i>
          <span class="ml-3 item-text">Billings</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a href="{{ route('e.receipts') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.receipts') || Route::currentRouteNamed('e.receipts.create') ? 'side-select' : '' }}">
          <i class="fe fe-shopping-cart fe-16"></i>
          <span class="ml-3 item-text">Receipts</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a href="{{ route('e.duplicates') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.duplicates') ? 'side-select' : '' }}">
          <i class="fe fe-copy fe-16"></i>
          <span class="ml-3 item-text">Dedup Clients <span class="cds-tag" style="background:#da1e28;">Temp</span></span>
        </a>
      </li>
    </ul>
    <p class="text-muted nav-heading mt-4 mb-1">
      <span>Documents</span>
    </p>
    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item dropdown">
        <a href="{{ route('e.documents') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.documents') || Route::currentRouteNamed('e.documents.create') || Route::currentRouteNamed('e.documents.view') ? 'side-select' : '' }}">
          <i class="fe fe-file fe-16"></i>
          <span class="ml-3 item-text">Agreements <span class="cds-tag">Beta</span></span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a href="{{ route('e.resources') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.resources') || Route::currentRouteNamed('e.resources.create') ? 'side-select' : '' }}">
          <i class="fe fe-folder fe-16"></i>
          <span class="ml-3 item-text">Resources <span class="cds-tag">Beta</span></span>
        </a>
      </li>
    </ul>
    <p class="text-muted nav-heading mt-4 mb-1">
      <span>Settings</span>
    </p>
    <ul class="navbar-nav flex-fill w-100 mb-2">
      <li class="nav-item dropdown">
        <a href="{{ route('e.settings') }}" wire:navigate class="nav-link {{ Route::currentRouteNamed('e.settings') ? 'side-select' : '' }}">
          <i class="fe fe-settings fe-16"></i>
          <span class="ml-3 item-text">Settings</span>
        </a>
      </li>

    </ul>

  </nav>
</aside>