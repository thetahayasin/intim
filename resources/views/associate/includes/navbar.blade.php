<nav class="topnav navbar navbar-light">
    <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
        <i class="fe fe-menu navbar-toggler-icon"></i>
    </button>
    <ul class="nav">
        <!--<li class="nav-item">-->
        <!--<a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">-->
        <!--    <i class="fe fe-sun fe-16"></i>-->
        <!--</a>-->
        <!--</li>-->
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-muted my-2" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="fe fe-user fe-16">
            </span> {{ Auth::guard('associate')->user()->name }}
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="{{ route('ass.profile') }}" wire:navigate>Profile</a>
                            <!-- Authentication -->
            <form id="logout-form" method="POST" action="{{ route('ass.logout') }}">
                    @csrf
                    <a class="dropdown-item" href="{{ route('ass.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            </form>
        </div>
        </li>
    </ul>
</nav>