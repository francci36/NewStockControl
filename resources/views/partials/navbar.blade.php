{{-- resources/views/partials/navbar.blade.php --}}
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
        @guest
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('shop.home') }}" class="nav-link">
                    <i class="fas fa-store mr-1"></i> Boutique
                </a>
            </li>
        @endguest
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications (optionnel) -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">3 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> Nouveau message
                    <span class="float-right text-muted text-sm">2 min</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Voir toutes les notifications</a>
            </div>
        </li>
        
        @guest
            <!-- Liens pour les invités -->
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link">
                    <i class="fas fa-sign-in-alt mr-1"></i> Connexion
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('register') }}" class="nav-link">
                    <i class="fas fa-user-plus mr-1"></i> Inscription
                </a>
            </li>
        @else
            <!-- Menu utilisateur connecté -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                    <i class="far fa-user-circle"></i>
                    <span class="ml-1">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Mon profil
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                            <i class="fas fa-tachometer-alt mr-2"></i> Administration
                        </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </li>
        @endguest
    </ul>
</nav>