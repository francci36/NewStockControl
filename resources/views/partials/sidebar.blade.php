{{-- resources/views/partials/sidebar.blade.php --}}
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link text-center py-3">
        <span class="brand-text font-weight-light h5">📦 NewStock</span>
    </a>

    <div class="sidebar">
        <!-- User Info Mini -->
        @auth
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
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
                         class="img-circle elevation-2" 
                         alt="{{ Auth::user()->name }}"
                         style="width: 40px; height: 40px; object-fit: cover;">
                @else
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white img-circle elevation-2"
                         style="width: 40px; height: 40px;">
                        <span class="font-weight-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="info ml-3">
                <a href="{{ route('admin.profile.edit') }}" class="d-block text-white font-weight-bold">{{ Auth::user()->name }}</a>
                <span class="text-white-50 small">
                    @if(Auth::user()->isAdmin())
                        <i class="fas fa-crown text-warning mr-1"></i> Administrateur
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Groupe Gestion Stock -->
                <li class="nav-header text-uppercase small text-white-50">Gestion</li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.stocks.index') }}" class="nav-link {{ request()->routeIs('admin.stocks*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Stocks</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>Produits</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.suppliers.index') }}" class="nav-link {{ request()->routeIs('admin.suppliers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Fournisseurs</p>
                    </a>
                </li>

                <!-- NOUVEAU LIEN VENDEURS -->
                <li class="nav-item">
                    <a href="{{ route('admin.vendors.index') }}" class="nav-link {{ request()->routeIs('admin.vendors*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Vendeurs</p>
                        @php
                            $pendingVendors = App\Models\Vendor::where('status', 'pending')->count();
                        @endphp
                        @if($pendingVendors > 0)
                            <span class="badge bg-warning right">{{ $pendingVendors }}</span>
                        @endif
                    </a>
                </li>

                <!-- Groupe Commandes -->
                <li class="nav-header text-uppercase small text-white-50 mt-3">Commandes</li>

                <li class="nav-item has-treeview {{ request()->routeIs('admin.orders*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Commandes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.orders.index', ['type' => 'supplier']) }}" 
                               class="nav-link {{ request()->fullUrlIs('*orders*type=supplier*') ? 'active' : '' }}">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>Fournisseurs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.orders.index', ['type' => 'client']) }}" 
                               class="nav-link {{ request()->fullUrlIs('*orders*type=client*') ? 'active' : '' }}">
                                <i class="fas fa-user nav-icon"></i>
                                <p>Clients</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Groupe Finances -->
                <li class="nav-header text-uppercase small text-white-50 mt-3">Finances</li>

                <li class="nav-item">
                    <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions*') && !request()->routeIs('admin.transactions.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Transactions</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.transactions.create') }}" class="nav-link {{ request()->routeIs('admin.transactions.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <p>Nouvelle transaction</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.rapports.index') }}" class="nav-link {{ request()->routeIs('admin.rapports*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Rapports</p>
                    </a>
                </li>

                <!-- Boutique -->
                <li class="nav-header text-uppercase small text-white-50 mt-3">Boutique</li>
                <li class="nav-item">
                    <a href="{{ route('shop.home') }}" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Voir la boutique <i class="fas fa-external-link-alt ml-1 small"></i></p>
                    </a>
                </li>

                <!-- Mode sombre -->
                <li class="nav-item mt-4">
                    <div class="px-3">
                        <button id="darkModeToggle" class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-moon mr-2"></i>
                            <span id="darkModeText">Mode Sombre</span>
                        </button>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</aside>

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