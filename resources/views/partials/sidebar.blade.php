{{-- resources/views/partials/sidebar.blade.php --}}
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">NewStockControl</span>
    </a>
    
    <div class="sidebar">
        @auth
        <!-- User Account -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img 
                    src="{{ Auth::user()->profile_photo_url ? asset('storage/' . Auth::user()->profile_photo_url) : asset('images/default-profile.png') }}" 
                    class="img-circle elevation-2" 
                    alt="{{ Auth::user()->name }}">
            </div>
            <div class="info">
                <a href="{{ route('admin.profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                <span class="text-white-50 small">
                    @if(isset(Auth::user()->role) && Auth::user()->role === 'admin')
                        <i class="fas fa-crown text-warning"></i> Administrateur
                    @else
                        <i class="fas fa-user text-info"></i> Utilisateur
                    @endif
                </span>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="fas fa-sign-out-alt mr-1"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>
        @endauth

        <!-- Navigation Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <!-- Stocks -->
                <li class="nav-item">
                    <a href="{{ route('admin.stocks.index') }}" class="nav-link {{ request()->routeIs('admin.stocks*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Stocks</p>
                    </a>
                </li>
                
                <!-- Commandes -->
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Commandes</p>
                    </a>
                </li>
                
                <!-- Fournisseurs -->
                <li class="nav-item">
                    <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ request()->routeIs('admin.suppliers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Fournisseurs</p>
                    </a>
                </li>
                
                <!-- Produits -->
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>Produits</p>
                    </a>
                </li>
                
                <!-- Rapports -->
                <li class="nav-item">
                    <a href="{{ route('admin.rapports.index') }}" class="nav-link {{ request()->routeIs('admin.rapports*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Rapports</p>
                    </a>
                </li>
                
                <!-- Transactions -->
                <li class="nav-item">
                    <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions*') && !request()->routeIs('admin.transactions.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Transactions</p>
                    </a>
                </li>

                <!-- Nouvelle Transaction -->
                <li class="nav-item">
                    <a href="{{ route('admin.transactions.create') }}" class="nav-link {{ request()->routeIs('admin.transactions.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>Nouvelle Transaction</p>
                    </a>
                </li>

                <!-- Lien vers la Boutique (pour les admins) -->
                <li class="nav-header mt-3">BOUTIQUE</li>
                <li class="nav-item">
                    <a href="{{ route('shop.home') }}" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Voir la boutique</p>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Bouton Mode Sombre -->
        <div class="mt-4 p-3 d-flex justify-content-center">
            <button id="darkModeToggle" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center">
                <i class="fas fa-moon mr-2"></i>
                <span id="darkModeText">Mode Sombre</span>
            </button>
        </div>
    </div>
</aside>

<!-- Script pour le mode sombre -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('darkModeToggle');
    
    if (!darkModeToggle) {
        console.error('Bouton mode sombre non trouvé');
        return;
    }
    
    const darkModeText = document.getElementById('darkModeText');
    const icon = darkModeToggle.querySelector('i');

    // Fonction pour mettre à jour l'interface selon le thème
    function updateThemeUI(isDark) {
        if (isDark) {
            document.documentElement.classList.add('dark');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
            darkModeText.textContent = 'Mode Clair';
        } else {
            document.documentElement.classList.remove('dark');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
            darkModeText.textContent = 'Mode Sombre';
        }
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    // Vérifier le thème sauvegardé
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        updateThemeUI(true);
    } else {
        updateThemeUI(false);
    }

    // Écouter le changement de thème système (optionnel)
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    prefersDarkScheme.addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            updateThemeUI(e.matches);
        }
    });

    // Fonction pour activer/désactiver le mode sombre
    const toggleDarkMode = () => {
        const isDark = !document.documentElement.classList.contains('dark');
        updateThemeUI(isDark);
    };

    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
    }
});
</script>

<!-- Style additionnel pour le mode sombre -->
<style>
    /* Mode sombre pour les éléments AdminLTE */
    .dark .main-sidebar,
    .dark .navbar {
        background-color: #1e1e2f !important;
    }
    
    .dark .content-wrapper {
        background-color: #2d2d44 !important;
    }
    
    .dark .card {
        background-color: #252537 !important;
        border-color: #3a3a5a !important;
    }
    
    .dark .table {
        color: #e0e0e0 !important;
    }
    
    .dark .breadcrumb {
        background-color: #2d2d44 !important;
    }
    
    .dark .breadcrumb-item a {
        color: #8ab4f8 !important;
    }
    
    .dark .breadcrumb-item.active {
        color: #e0e0e0 !important;
    }
    
    .dark .form-control,
    .dark .form-select {
        background-color: #2d2d44 !important;
        border-color: #4a4a6a !important;
        color: #e0e0e0 !important;
    }
    
    .dark .form-control:focus,
    .dark .form-select:focus {
        background-color: #35354a !important;
        border-color: #6a6a9a !important;
        color: #ffffff !important;
    }
    
    .dark .alert-success {
        background-color: #1e3a2e !important;
        border-color: #2a6b4e !important;
        color: #c3e6cb !important;
    }
    
    .dark .alert-danger {
        background-color: #4a1e2e !important;
        border-color: #8b3a4e !important;
        color: #f8d7da !important;
    }
    
    .dark .alert-warning {
        background-color: #4a3a1e !important;
        border-color: #8b6a2e !important;
        color: #fff3cd !important;
    }
    
    .dark .alert-info {
        background-color: #1e3a4a !important;
        border-color: #2a6a8b !important;
        color: #d1ecf1 !important;
    }
    
    .dark,
    .dark * {
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }
</style>