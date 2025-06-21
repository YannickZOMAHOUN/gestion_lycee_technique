<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Accueil -->
        <li class="nav-item">
            <a class="nav-link text-decoration-none" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i>
                <span>&nbsp; Accueil</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <!-- Les Notes -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#notes-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-calculator-fill"></i><span>&nbsp;Les Notes</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="notes-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="" class="text-decoration-none">
                        <i class="fas fa-list"></i><span>&nbsp;Voir les notes</span>
                    </a>
                </li>
                <li>
                    <a href="" class="text-decoration-none">
                        <i class="fas fa-plus"></i><span>&nbsp;Nouvelle Note</span>
                    </a>
                </li>
                <li>
                    <a href="" class="text-decoration-none">
                        <i class="fas fa-list"></i><span>&nbsp;Bulletins</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Notes Nav -->

        <!-- Elèves -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-briefcase-fill"></i><span>&nbsp;Elèves</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="" class="text-decoration-none">
                        <i class="fas fa-list"></i><span>&nbsp;Liste des élèves</span>
                    </a>
                </li>
                <li>
                    <a href="" class="text-decoration-none">
                        <i class="fas fa-plus"></i><span>&nbsp;Nouvel Elève</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Students Nav -->


        <!-- Paramètres -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#settings-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear"></i><span>&nbsp;Paramètres</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="settings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('year.create') }}" class="text-decoration-none">
                        <i class="bi bi-calendar2-day-fill"></i><span>&nbsp;Année Scolaire</span>
                    </a>
                </li>
            </ul>

            <ul id="settings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('sectorbyyear.create') }}" class="text-decoration-none">
                        <i class="bi bi-calendar2-day-fill"></i><span>&nbsp;Filière</span>
                    </a>
                </li>
            </ul>

            <ul id="settings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('promotionbysector.create') }}" class="text-decoration-none">
                        <i class="bi bi-calendar2-day-fill"></i><span>&nbsp;Promotions</span>
                    </a>
                </li>
            </ul>
            <ul id="settings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('year.create') }}" class="text-decoration-none">
                        <i class="bi bi-calendar2-day-fill"></i><span>&nbsp;Classes</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Settings Nav -->

    </ul>
</aside><!-- End Sidebar -->
