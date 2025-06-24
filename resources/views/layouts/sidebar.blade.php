<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Accueil -->
        <li class="nav-item">
            <a class="nav-link text-decoration-none" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i>
                <span>&nbsp;Accueil</span>
            </a>
        </li>

        <!-- Les Notes -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#notes-nav" data-bs-toggle="collapse" href="#">
                <i class="fas fa-clipboard-list"></i><span>&nbsp;Les Notes</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="notes-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="#">
                        <i class="fas fa-eye"></i><span>&nbsp;Voir les notes</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-plus-circle"></i><span>&nbsp;Nouvelle Note</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-file-alt"></i><span>&nbsp;Bulletins</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Elèves -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
                <i class="fas fa-user-graduate"></i><span>&nbsp;Elèves</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="#">
                        <i class="fas fa-list-ul"></i><span>&nbsp;Liste des élèves</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.create') }}">
                        <i class="fas fa-user-plus"></i><span>&nbsp;Nouvel Elève</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Paramètres -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#settings-nav" data-bs-toggle="collapse" href="#">
                <i class="fas fa-cogs"></i><span>&nbsp;Paramètres</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="settings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('year.create') }}">
                        <i class="fas fa-calendar-alt"></i><span>&nbsp;Année Scolaire</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sectorbyyear.create') }}">
                        <i class="fas fa-project-diagram"></i><span>&nbsp;Filières</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('promotionbysector.create') }}">
                        <i class="fas fa-layer-group"></i><span>&nbsp;Promotions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('promotion-classrooms.create') }}">
                        <i class="fas fa-school"></i><span>&nbsp;Classes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('subject.create') }}">
                        <i class="fas fa-school"></i><span>&nbsp;Matières</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('ratio.create') }}">
                        <i class="fas fa-school"></i><span>&nbsp;Coefficient</span>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</aside><!-- End Sidebar -->
