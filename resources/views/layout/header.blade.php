<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ url('/') }}">
            <img src="{{ url('assets/images/picture1.jpg') }}" alt="logo" /> </a>
        <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
            <img src="{{ url('assets/images/logomini.jpg') }}" alt="logo" /> </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
            @auth
                <!-- The user is authenticated -->
                <li class="nav-item dropdown d-none d-xl-inline-block">
                    <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span class="profile-text d-none d-md-inline-flex">{{ Auth::user()->username }}</span>
                        @if (Auth::user()->avatar)
                            <img class="img-xs rounded-circle ms-2" src="/avatars/{{ Auth::user()->avatar }}"
                                alt="Profile image">
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end navbar-dropdown" aria-labelledby="UserDropdown">
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">Sign Out</button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <!-- The user is not authenticated -->
                <li class="nav-item dropdown d-none d-xl-inline-block">
                    <a class="nav-link" href="{{ route('login') }}">
                        <span class="profile-text d-none d-md-inline-flex">Login</span>
                        <!-- <img class="img-xs rounded-circle" src="{{ url('assets/images/faces-clipart/pic-1.png') }}"> -->
                    </a>
                </li>
            @endauth
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-menu icon-menu"></span>
        </button>
    </div>
</nav>
