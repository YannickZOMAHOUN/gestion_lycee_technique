<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center justify-content-between px-3">

    <!-- Logo + Toggle Sidebar -->
    <div class="d-flex align-items-center">
        <div class="pe-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-header" style="max-height: 60px;">
        </div>
        <i class="bi bi-list toggle-sidebar-btn text-color-avt fs-4"></i>
    </div>
    <!-- End Logo -->

    <!-- Application Title -->
    <div class="text-center flex-grow-1">
        <h3 class="fs-6 text-uppercase font-medium text-color-avt mb-0">Gestion Scolaire</h3>
    </div>

    <!-- User Profile / Navigation -->
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center mb-0">
            @auth
                <li class="nav-item dropdown pe-3">

                    <!-- Dropdown Toggle -->
                    <a class="nav-link nav-profile d-flex align-items-center" href="#" data-bs-toggle="dropdown" aria-expanded="false" id="dropdownMenuLink">
                        <i class="fas fa-user text-color-avt me-1"></i>
                        <span class="d-none d-md-inline text-color-avt">
                            {{ auth()->user()->staff->surname }} {{ auth()->user()->staff->name }}
                        </span>
                    </a>

                    <!-- Role + School Info -->
                    <div class="d-none d-lg-block text-end mt-1">
                        <h6 class="mb-0 text-muted small">
                            {{ auth()->user()->staff->role->role_name }} - {{ auth()->user()->school->school }}
                        </h6>
                    </div>

                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile" aria-labelledby="dropdownMenuLink">
                        <li class="dropdown-header">
                            <h6>{{ auth()->user()->staff->surname }} {{ auth()->user()->staff->name }}</h6>
                            <span class="text-muted small">{{ auth()->user()->staff->role->role_name }}</span>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Profile -->
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('user.create') }}">
                                <i class="fas fa-user"></i>
                                <span class="ms-2">Mon Profil</span>
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Logout -->
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="ms-2">Déconnexion</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            @endauth
        </ul>
    </nav>
    <!-- End Profile Section -->

</header><!-- End Header -->
