<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar">
    <ul class="nav">
        {{-- Profile section --}}
        <li class="nav-item nav-profile not-navigation-link">
            <div class="nav-link">
                <div class="user-wrapper">
                    @auth
                        @if (Auth::user()->avatar)
                            <div class="profile-image">
                                <img src="/avatars/{{ Auth::user()->avatar }}" alt="profile image">
                            </div>
                        @endif
                    @endauth
                    <div class="text-wrapper">
                        @auth
                            <p class="profile-name">{{ Auth::user()->username ?? Auth::user()->name }}</p>
                            <div class="dropdown" data-display="static">
                                <a href="#" class="nav-link d-flex user-switch-dropdown-toggler"
                                   id="UsersettingsDropdown" data-toggle="dropdown" aria-expanded="false">
                                    <small class="designation text-muted">
                                        {{ Auth::user()->getRoleNames()->implode(', ') }}
                                    </small>
                                    <span class="status-indicator online"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="UsersettingsDropdown">
                                    <a class="dropdown-item mt-2">Manage Accounts</a>
                                    <a class="dropdown-item">Change Password</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form2').submit();">
                                        Sign Out
                                    </a>
                                </div>
                                <form id="logout-form2" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </li>

        {{-- Navigation menu --}}
        <li class="nav-item {{ Route::is('home') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="menu-icon mdi mdi-home"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('deals.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('deals.index') }}">
                <i class="menu-icon mdi mdi-briefcase"></i>
                <span class="menu-title">Deals</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('produk.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('produk.index') }}">
                <i class="menu-icon mdi mdi-package-variant"></i>
                <span class="menu-title">Produk</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('dokumen.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dokumen.index') }}">
                <i class="menu-icon mdi mdi-file-document"></i>
                <span class="menu-title">Dokumen</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('properties.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('properties.index') }}">
                <i class="menu-icon mdi mdi-home-city"></i>
                <span class="menu-title">Properties</span>
            </a>
        </li>
    </ul>
</nav>
