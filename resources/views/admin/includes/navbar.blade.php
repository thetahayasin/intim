<nav class="topnav navbar navbar-expand">
    <button type="button" class="navbar-toggler collapseSidebar" style="border:none;background:none;padding:0 8px 0 0;">
        <i class="fe fe-menu fe-18" style="color:#c6c6c6;"></i>
    </button>
    <span class="navbar-brand-text">Asif Associates</span>
    <ul class="nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fe fe-user fe-16"></i>&nbsp; Admin
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="adminDropdown">
                <a class="dropdown-item" href="{{ route('e.password') }}" wire:navigate>Change Password</a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </form>
            </div>
        </li>
    </ul>
</nav>
