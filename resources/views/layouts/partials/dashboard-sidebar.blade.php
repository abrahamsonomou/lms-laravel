@php($user = auth()->user())
<nav class="navbar-vertical navbar">
    <div class="vh-100" data-simplebar>
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('geeks/assets/images/brand/logo/logo-inverse.svg') }}" alt="{{ config('app.name') }}" />
        </a>

        <ul class="navbar-nav flex-column" id="sideNavbar">
            @if ($user?->isAdmin())
                {{-- ===== Backoffice / Administration ===== --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="nav-icon fe fe-home me-2"></i> Tableau de bord
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.rapports.*') ? 'active' : '' }}" href="{{ route('admin.rapports.index') }}">
                        <i class="nav-icon fe fe-bar-chart-2 me-2"></i> Rapports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.bi.*') ? 'active' : '' }}" href="{{ route('admin.bi.index') }}">
                        <i class="nav-icon fe fe-pie-chart me-2"></i> BI / Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.studio.*') ? 'active' : '' }}" href="{{ route('admin.studio.index') }}">
                        <i class="nav-icon fe fe-layout me-2"></i> Studio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('support.*') ? 'active' : '' }}" href="{{ route('support.index') }}">
                        <i class="nav-icon fe fe-message-square me-2"></i> Support
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <div class="navbar-heading">Identité &amp; Accès (IAM)</div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="nav-icon fe fe-users me-2"></i> Utilisateurs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                        <i class="nav-icon fe fe-shield me-2"></i> Rôles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}">
                        <i class="nav-icon fe fe-key me-2"></i> Permissions
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <div class="navbar-heading">Organisation</div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.organisations.*') ? 'active' : '' }}" href="{{ route('admin.organisations.index') }}">
                        <i class="nav-icon fe fe-briefcase me-2"></i> Organisations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.etablissements.*') ? 'active' : '' }}" href="{{ route('admin.etablissements.index') }}">
                        <i class="nav-icon fe fe-home me-2"></i> Établissements
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <div class="navbar-heading">Catalogue</div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="nav-icon fe fe-grid me-2"></i> Catégories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.formations.*') ? 'active' : '' }}" href="{{ route('admin.formations.index') }}">
                        <i class="nav-icon fe fe-book me-2"></i> Formations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.certificats.*') ? 'active' : '' }}" href="{{ route('admin.certificats.index') }}">
                        <i class="nav-icon fe fe-award me-2"></i> Certificats
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <div class="navbar-heading">Facturation</div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.factures.*') ? 'active' : '' }}" href="{{ route('admin.factures.index') }}">
                        <i class="nav-icon fe fe-file-text me-2"></i> Factures
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
                        <i class="nav-icon fe fe-credit-card me-2"></i> Transactions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.remboursements.*') ? 'active' : '' }}" href="{{ route('admin.remboursements.index') }}">
                        <i class="nav-icon fe fe-corner-up-left me-2"></i> Remboursements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                        <i class="nav-icon fe fe-tag me-2"></i> Coupons
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}" href="{{ route('admin.plans.index') }}">
                        <i class="nav-icon fe fe-layers me-2"></i> Plans d'abonnement
                    </a>
                </li>
            @elseif ($user?->isFormateur())
                {{-- ===== Espace formateur ===== --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                        <i class="nav-icon fe fe-home me-2"></i> Tableau de bord
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <div class="navbar-heading">Mon compte</div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('teacher.formations.*') ? 'active' : '' }}" href="{{ route('teacher.formations.index') }}">
                        <i class="nav-icon fe fe-book-open me-2"></i> Mes formations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('teacher.profile.*') ? 'active' : '' }}" href="{{ route('teacher.profile.edit') }}">
                        <i class="nav-icon fe fe-user me-2"></i> Mon profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('support.*') ? 'active' : '' }}" href="{{ route('support.index') }}">
                        <i class="nav-icon fe fe-message-square me-2"></i> Support
                    </a>
                </li>
            @elseif ($user?->isEtudiant())
                {{-- ===== Espace étudiant ===== --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                        <i class="nav-icon fe fe-home me-2"></i> Tableau de bord
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <div class="navbar-heading">Mon compte</div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.formations.*') ? 'active' : '' }}" href="{{ route('student.formations.index') }}">
                        <i class="nav-icon fe fe-book-open me-2"></i> Mes formations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.certificats.*') ? 'active' : '' }}" href="{{ route('student.certificats.index') }}">
                        <i class="nav-icon fe fe-award me-2"></i> Mes certificats
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.abonnements.*') ? 'active' : '' }}" href="{{ route('student.abonnements.index') }}">
                        <i class="nav-icon fe fe-layers me-2"></i> Mon abonnement
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.factures.*') ? 'active' : '' }}" href="{{ route('student.factures.index') }}">
                        <i class="nav-icon fe fe-file-text me-2"></i> Mes factures
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('student.profile.*') ? 'active' : '' }}" href="{{ route('student.profile.edit') }}">
                        <i class="nav-icon fe fe-user me-2"></i> Mon profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('support.*') ? 'active' : '' }}" href="{{ route('support.index') }}">
                        <i class="nav-icon fe fe-message-square me-2"></i> Support
                    </a>
                </li>
            @endif
        </ul>
    </div>
</nav>
