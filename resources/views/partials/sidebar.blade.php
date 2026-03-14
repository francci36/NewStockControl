<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-gradient">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link brand-gradient border-bottom-gold">
        <img src="{{ asset('storage/images/mon-design.png') }}" 
            alt="Carioca & Coquelicot" 
            class="brand-image img-circle elevation-3 border-gold"
            style="height: 40px; width: auto;">
                <span class="brand-text font-weight-light brand-text-gold">Carioca & Coquelicot</span>
    </a>

    <div class="sidebar">
        <!-- User Info Mini -->
        @auth
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center border-bottom border-white-20">
            <div class="image">
                @php
                    $userPhoto = Auth::user()->profile_photo_url 
                        ? (file_exists(public_path('storage/' . Auth::user()->profile_photo_url)) 
                            ? asset('storage/' . Auth::user()->profile_photo_url) 
                            : null)
                        : null;
                @endphp
                
                @if($userPhoto)
                    <img src="{{ $userPhoto }}" 
                         class="img-circle elevation-2 border-2 border-gold" 
                         alt="{{ Auth::user()->name }}"
                         style="width: 40px; height: 40px; object-fit: cover;">
                @else
                    <div class="bg-gold rounded-circle d-flex align-items-center justify-content-center text-dark img-circle elevation-2 border-2 border-white"
                         style="width: 40px; height: 40px;">
                        <span class="font-weight-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="info ml-3">
                <a href="{{ route('admin.profile.edit') }}" class="d-block text-white font-weight-bold hover-gold">{{ Auth::user()->name }}</a>
                <span class="text-white-50 small">
                    @if(Auth::user()->isAdmin())
                        <i class="fas fa-crown text-gold mr-1"></i> Administrateur
                    @else
                        <i class="fas fa-user text-info mr-1"></i> Utilisateur
                    @endif
                </span>
            </div>
        </div>
        @endauth

        <!-- Navigation Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active gold-active' : 'hover-gold-bg' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Groupe Gestion Stock -->
                <li class="nav-header text-uppercase small text-white-50">
                    <i class="fas fa-circle mr-1 text-gold" style="font-size: 8px;"></i> Gestion
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.stocks.index') }}" class="nav-link {{ request()->routeIs('admin.stocks*') ? 'active gold-active' : 'hover-gold-bg' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Stocks</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active gold-active' : 'hover-gold-bg' }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>Produits</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ request()->routeIs('admin.suppliers*') ? 'active gold-active' : 'hover-gold-bg' }}">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Fournisseurs</p>
                    </a>
                </li>

                <!-- LIEN VENDEURS -->
                <li class="nav-item">
                    <a href="{{ route('admin.vendors.index') }}" class="nav-link {{ request()->routeIs('admin.vendors*') ? 'active gold-active' : 'hover-gold-bg' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Vendeurs</p>
                        @php
                            $pendingVendors = App\Models\Vendor::where('status', 'pending')->count();
                        @endphp
                        @if($pendingVendors > 0)
                            <span class="badge bg-gold text-dark right animate-pulse">{{ $pendingVendors }}</span>
                        @endif
                    </a>
                </li>

                <!-- Groupe Commandes -->
                <li class="nav-header text-uppercase small text-white-50 mt-3">
                    <i class="fas fa-circle mr-1 text-gold" style="font-size: 8px;"></i> Commandes
                </li>

                <li class="nav-item has-treeview {{ request()->routeIs('admin.orders*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link hover-gold-bg">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Commandes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.orders.index', ['type' => 'supplier']) }}" 
                               class="nav-link {{ request()->fullUrlIs('*orders*type=supplier*') ? 'active gold-active' : 'hover-gold-bg' }}">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>Fournisseurs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.orders.index', ['type' => 'client']) }}" 
                               class="nav-link {{ request()->fullUrlIs('*orders*type=client*') ? 'active gold-active' : 'hover-gold-bg' }}">
                                <i class="fas fa-user nav-icon"></i>
                                <p>Clients</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Groupe Finances -->
                <!-- Finance -  -->
                <li class="nav-item {{ request()->routeIs('admin.finance*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.finance*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Finance
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.finance.index') }}" class="nav-link {{ request()->routeIs('admin.finance.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard financier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.finance.transactions') }}" class="nav-link {{ request()->routeIs('admin.finance.transactions') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Transactions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.finance.commissions') }}" class="nav-link {{ request()->routeIs('admin.finance.commissions') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Commissions</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!--transactions-->

                <li class="nav-item">
                    <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions*') && !request()->routeIs('admin.transactions.create') ? 'active gold-active' : 'hover-gold-bg' }}">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Transactions</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.transactions.create') }}" class="nav-link {{ request()->routeIs('admin.transactions.create') ? 'active gold-active' : 'hover-gold-bg' }}">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <p>Nouvelle transaction</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.rapports.index') }}" class="nav-link {{ request()->routeIs('admin.rapports*') ? 'active gold-active' : 'hover-gold-bg' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Rapports</p>
                    </a>
                </li>

                <!-- Boutique -->
                <li class="nav-header text-uppercase small text-white-50 mt-3">
                    <i class="fas fa-circle mr-1 text-gold" style="font-size: 8px;"></i> Boutique
                </li>
                <li class="nav-item">
                    <a href="{{ route('shop.home') }}" class="nav-link hover-gold-bg" target="_blank">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Voir la boutique <i class="fas fa-external-link-alt ml-1 small"></i></p>
                    </a>
                </li>

                <!-- Mode sombre -->
                <li class="nav-item mt-4">
                    <div class="px-3">
                        <button id="darkModeToggle" class="btn btn-outline-gold btn-sm w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-moon mr-2"></i>
                            <span id="darkModeText">Mode Sombre</span>
                        </button>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<style>
.sidebar-gradient {
    background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
}

.brand-gradient {
    background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
    border-bottom: 3px solid #f7d44a;
}

.text-gold {
    color: #f7d44a !important;
}
.bg-gold {
    background-color: #f7d44a !important;
}
.border-gold {
    border-color: #f7d44a !important;
}
.btn-outline-gold {
    color: #f7d44a;
    border-color: #f7d44a;
}
.btn-outline-gold:hover {
    background-color: #f7d44a;
    color: #1a1a2e;
}

.brand-text-gold {
    color: #f7d44a !important;
    font-weight: 600;
}

.border-bottom-gold {
    border-bottom: 3px solid #f7d44a !important;
}
.border-white-20 {
    border-color: rgba(255, 255, 255, 0.2) !important;
}
.border-2 {
    border-width: 2px !important;
}

.hover-gold-bg:hover {
    background-color: rgba(247, 212, 74, 0.1) !important;
    color: #f7d44a !important;
}
.hover-gold:hover {
    color: #f7d44a !important;
}

.nav-sidebar .nav-link.active.gold-active {
    background-color: #f7d44a !important;
    color: #1a1a2e !important;
    font-weight: 600;
    box-shadow: 0 2px 10px rgba(247, 212, 74, 0.3);
}
.nav-sidebar .nav-link.active.gold-active i {
    color: #1a1a2e !important;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
.animate-pulse {
    animation: pulse 2s infinite;
}

.nav-treeview .nav-link {
    padding-left: 3rem !important;
}

/* Responsive sidebar */
@media (max-width: 768px) {
    .main-sidebar {
        position: fixed !important;
        left: -280px !important;
        width: 280px !important;
        transition: left 0.3s ease !important;
        z-index: 1050 !important;
        height: 100vh !important;
        overflow-y: auto !important;
    }
    
    .sidebar-open .main-sidebar {
        left: 0 !important;
    }
    
    .sidebar {
        height: calc(100vh - 57px);
        overflow-y: auto;
        padding-bottom: 80px;
    }
    
    .brand-text {
        font-size: 1rem !important;
    }
    
    .user-panel {
        padding: 0.5rem 1rem !important;
    }
    
    .nav-sidebar .nav-link {
        padding: 0.6rem 1rem !important;
    }
    
    .nav-treeview .nav-link {
        padding-left: 2.2rem !important;
    }
    
    .nav-header {
        padding: 0.8rem 1rem 0.3rem !important;
        font-size: 0.7rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (!darkModeToggle) return;
    
    const darkModeText = document.getElementById('darkModeText');
    const icon = darkModeToggle.querySelector('i');

    function updateThemeUI(isDark) {
        if (isDark) {
            document.documentElement.classList.add('dark');
            icon.classList.replace('fa-moon', 'fa-sun');
            darkModeText.textContent = 'Mode Clair';
        } else {
            document.documentElement.classList.remove('dark');
            icon.classList.replace('fa-sun', 'fa-moon');
            darkModeText.textContent = 'Mode Sombre';
        }
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }

    const savedTheme = localStorage.getItem('theme');
    updateThemeUI(savedTheme === 'dark');

    darkModeToggle.addEventListener('click', () => {
        updateThemeUI(!document.documentElement.classList.contains('dark'));
    });
});
</script>