<nav class="navbar navbar-dark navbar-custom">
    <div class="container-fluid">
        <button class="btn btn-link text-white d-lg-none me-2" id="sidebarToggle">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <div class="header-logo">
                <div class="header-logo-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div>RESPA-Jo</div>
            </div>
        </a>
        <div class="ms-auto d-flex align-items-center">
            <ul class="navbar-nav flex-row align-items-center">
                <li class="nav-item dropdown user-menu">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="user-dropdown">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>{{ auth()->user()->nama ?? 'Admin' }} <i class="fas fa-caret-down"></i></div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="GET" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="sidebar-overlay" id="sidebarOverlay"></div>