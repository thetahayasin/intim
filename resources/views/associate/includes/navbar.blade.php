<nav class="topnav navbar navbar-expand">
    <button type="button" class="navbar-toggler collapseSidebar" style="border:none;background:none;padding:0 8px 0 0;cursor:pointer;">
        <i class="fe fe-menu fe-18" style="color:#525252;"></i>
    </button>
    <span class="navbar-brand-text">Asif Associates</span>
    <ul class="nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="assDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fe fe-user fe-16"></i>&nbsp; {{ Auth::guard('associate')->user()->name }}
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="assDropdown">
                <a class="dropdown-item" href="{{ route('ass.profile') }}" wire:navigate>Profile</a>
                <form id="logout-form" method="POST" action="{{ route('ass.logout') }}">
                    @csrf
                    <a class="dropdown-item" href="{{ route('ass.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </form>
            </div>
        </li>
    </ul>
</nav>
