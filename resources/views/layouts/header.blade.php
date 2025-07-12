<!-- ======= Header Élégant ======= -->
<header id="header" class="header elegant-header fixed-top d-flex align-items-center justify-content-between px-4 py-2">

    <!-- Logo + Toggle Sidebar -->
    <div class="d-flex align-items-center">
        <div class="pe-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-header">
        </div>
        <i class="bi bi-list toggle-sidebar-btn fs-4"></i>
    </div>

    <!-- Application Title -->
    <div class="text-center flex-grow-1">
        <h3 class="app-title mb-0">Gestion Scolaire</h3>
        <small class="app-subtitle text-muted">Plateforme de gestion académique</small>
    </div>

    <!-- User Profile / Navigation -->
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center mb-0">
            @auth
                <li class="nav-item dropdown pe-3">

                    <!-- Dropdown Toggle -->
                    <a class="nav-link nav-profile d-flex align-items-center" href="#" data-bs-toggle="dropdown" aria-expanded="false" id="dropdownMenuLink">
                        <div class="avatar-container me-2">
                            <div class="avatar-circle">
                                {{ strtoupper(substr(auth()->user()->staff->surname, 0, 1)) }}{{ strtoupper(substr(auth()->user()->staff->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="user-info d-none d-md-block">
                            <span class="user-name">{{ auth()->user()->staff->surname }} {{ auth()->user()->staff->name }}</span>
                            <span class="user-role">{{ auth()->user()->staff->role->role_name }}</span>
                        </div>
                        <i class="fas fa-chevron-down ms-2 dropdown-arrow"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle-lg me-3">
                                    {{ strtoupper(substr(auth()->user()->staff->surname, 0, 1)) }}{{ strtoupper(substr(auth()->user()->staff->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6>{{ auth()->user()->staff->surname }} {{ auth()->user()->staff->name }}</h6>
                                    <span class="text-muted small">{{ auth()->user()->staff->role->role_name }}</span>
                                    <div class="text-muted smaller mt-1">
                                        <i class="fas fa-school me-1"></i>
                                        {{ auth()->user()->school->school }}
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Profile -->
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('user.create') }}">
                                <div class="dropdown-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span>Mon Profil</span>
                            </a>
                        </li>

                        <!-- Settings -->
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="dropdown-icon">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <span>Paramètres</span>
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <!-- Logout -->
                        <li>
                            <a class="dropdown-item d-flex align-items-center logout-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <div class="dropdown-icon">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <span>Déconnexion</span>
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

</header>

<style>
/* Styles pour le header élégant */
.elegant-header {
    background: #ffffff;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
    z-index: 1000;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.logo-header {
    max-height: 50px;
    transition: all 0.3s;
}

.toggle-sidebar-btn {
    color: #2c3e50;
    cursor: pointer;
    transition: all 0.3s;
}

.toggle-sidebar-btn:hover {
    color: #3498db;
    transform: scale(1.1);
}

.app-title {
    color: #2c3e50;
    font-weight: 600;
    letter-spacing: 1px;
    font-size: 1.2rem;
    text-transform: uppercase;
}

.app-subtitle {
    font-size: 0.7rem;
    display: block;
    margin-top: -3px;
}

.nav-profile {
    padding: 8px 12px;
    border-radius: 50px;
    transition: all 0.3s;
    position: relative;
}

.nav-profile:hover {
    background: rgba(52, 152, 219, 0.1);
}

.avatar-container {
    position: relative;
}

.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.avatar-circle-lg {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.1rem;
}

.user-info {
    display: flex;
    flex-direction: column;
    text-align: left;
}

.user-name {
    font-weight: 500;
    font-size: 0.9rem;
    color: #2c3e50;
}

.user-role {
    font-size: 0.75rem;
    color: #7f8c8d;
    margin-top: -2px;
}

.dropdown-arrow {
    font-size: 0.7rem;
    color: #7f8c8d;
    transition: transform 0.3s;
}

.nav-profile.show .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-menu {
    border: none;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 10px 0;
    margin-top: 10px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.dropdown-header {
    padding: 10px 15px;
}

.dropdown-header h6 {
    font-weight: 600;
    margin-bottom: 2px;
    color: #2c3e50;
}

.dropdown-divider {
    margin: 5px 0;
    opacity: 0.5;
}

.dropdown-item {
    padding: 8px 15px;
    font-size: 0.9rem;
    color: #34495e;
    transition: all 0.2s;
    display: flex;
    align-items: center;
}

.dropdown-item:hover {
    background: rgba(52, 152, 219, 0.1);
    color: #3498db;
}

.dropdown-icon {
    width: 20px;
    text-align: center;
    margin-right: 10px;
    color: #7f8c8d;
}

.dropdown-item:hover .dropdown-icon {
    color: #3498db;
}

.logout-item {
    color: #e74c3c;
}

.logout-item:hover {
    background: rgba(231, 76, 60, 0.1) !important;
    color: #e74c3c !important;
}

.logout-item:hover .dropdown-icon {
    color: #e74c3c !important;
}

.smaller {
    font-size: 0.75rem;
}
</style>
