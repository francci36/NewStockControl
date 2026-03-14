<nav class="main-header navbar navbar-expand navbar-dark navbar-gradient">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        @auth
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white hover-gold">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.stocks.index') }}" class="nav-link text-white hover-gold">
                <i class="fas fa-box mr-1"></i> Stocks
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.products.index') }}" class="nav-link text-white hover-gold">
                <i class="fas fa-cubes mr-1"></i> Produits
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.suppliers.index') }}" class="nav-link text-white hover-gold">
                <i class="fas fa-truck mr-1"></i> Fournisseurs
            </a>
        </li>
        @endauth
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @auth
        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a class="nav-link text-white hover-gold" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @php
                    $unreadNotifications = auth()->user()->unreadNotifications ?? collect([]);
                    $notificationCount = $unreadNotifications->count();
                @endphp
                @if($notificationCount > 0)
                    <span class="badge badge-warning navbar-badge animate-pulse">{{ $notificationCount }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow-lg border-0">
                <div class="dropdown-header bg-gradient-primary text-white rounded-top py-3">
                    <i class="far fa-bell mr-2"></i>
                    <span>{{ $notificationCount }} Notification(s)</span>
                </div>
                <!-- Contenu des notifications -->
                @forelse($unreadNotifications->take(5) as $notification)
                    <div class="dropdown-item notification-item py-2 hover-gold-bg">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <i class="fas fa-info-circle text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span>{{ $notification->data['message'] ?? 'Nouvelle notification' }}</span>
                                <small class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="dropdown-item text-center py-3 text-muted">
                        <i class="far fa-check-circle mr-2"></i> Aucune notification
                    </div>
                @endforelse
                
                @if($notificationCount > 5)
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-center py-2 text-primary hover-gold-bg">
                        Voir toutes les notifications
                    </a>
                @endif
            </div>
        </li>

        <!-- User Menu -->
        <li class="nav-item dropdown user-menu">
            <a class="nav-link dropdown-toggle d-flex align-items-center text-white hover-gold" href="#" id="userDropdown" data-toggle="dropdown">
                <div class="mr-2 d-flex align-items-center">
                    @php
                        $userPhoto = Auth::user()->profile_photo_url 
                            ? (file_exists(public_path('storage/' . Auth::user()->profile_photo_url)) 
                                ? asset('storage/' . Auth::user()->profile_photo_url) 
                                : null)
                            : null;
                    @endphp
                    
                    @if($userPhoto)
                        <img src="{{ $userPhoto }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="img-circle elevation-2 border border-white"
                             style="width: 32px; height: 32px; object-fit: cover;">
                    @else
                        <div class="bg-gold rounded-circle d-flex align-items-center justify-content-center text-dark font-weight-bold"
                             style="width: 32px; height: 32px;">
                            <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <span class="ml-1 d-none d-md-inline font-weight-medium">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right py-0 shadow-lg border-0" style="min-width: 280px;">
                <!-- User Info Header -->
                <div class="bg-gradient-primary text-white px-3 py-3 rounded-top">
                    <div class="d-flex align-items-center">
                        @if($userPhoto)
                            <img src="{{ $userPhoto }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="rounded-circle mr-3 border-2 border-white"
                                 style="width: 48px; height: 48px; object-fit: cover;">
                        @else
                            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold border-2 border-white"
                                 style="width: 48px; height: 48px; font-size: 20px;">
                                <span>{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0 text-white font-weight-bold">{{ Auth::user()->name }}</h6>
                            <small class="text-white-50">{{ Auth::user()->email }}</small>
                            @if(Auth::user()->isAdmin())
                                <div class="mt-1">
                                    <span class="badge bg-gold text-dark">Administrateur</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="py-2">
                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item py-2 hover-gold-bg">
                        <i class="fas fa-user-circle mr-2 text-primary"></i> Mon profil
                    </a>
                    
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.vendors.index') }}" class="dropdown-item py-2 hover-gold-bg">
                            <i class="fas fa-store mr-2 text-warning"></i> Gestion vendeurs
                        </a>
                        
                        <div class="dropdown-divider my-1"></div>
                        
                        <a href="{{ route('shop.home') }}" target="_blank" class="dropdown-item py-2 hover-gold-bg">
                            <i class="fas fa-external-link-alt mr-2 text-info"></i> Voir la boutique
                        </a>
                    @endif
                    
                    <div class="dropdown-divider my-1"></div>
                    
                    <!-- VERSION CORRIGÉE DU FORMULAIRE DE DÉCONNEXION -->
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" class="dropdown-item py-2 text-danger hover-gold-bg" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </a>
                </div>
            </div>
        </li>
        @else
        <!-- Liens pour les visiteurs non connectés -->
        <li class="nav-item">
            <a href="{{ route('login') }}" class="nav-link text-white hover-gold">
                <i class="fas fa-sign-in-alt mr-1"></i> Connexion
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('register') }}" class="nav-link text-white hover-gold">
                <i class="fas fa-user-plus mr-1"></i> Inscription
            </a>
        </li>
        @endauth
    </ul>
</nav>

<style>
.navbar-gradient {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    border-bottom: 3px solid #f7d44a;
}

.hover-gold {
    transition: color 0.3s ease;
}
.hover-gold:hover {
    color: #f7d44a !important;
}

.hover-gold-bg:hover {
    background-color: rgba(247, 212, 74, 0.1) !important;
}

.bg-gold {
    background-color: #f7d44a !important;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
.animate-pulse {
    animation: pulse 2s infinite;
}

.notification-list {
    max-height: 300px;
    overflow-y: auto;
}

/* Responsive */
@media (max-width: 768px) {
    .navbar-nav .dropdown-menu {
        position: absolute !important;
        right: 0 !important;
        left: auto !important;
        min-width: 280px;
        max-width: 320px;
    }
    
    .user-menu .nav-link span {
        display: none;
    }
    
    .user-menu .nav-link img,
    .user-menu .nav-link .bg-gold {
        margin-right: 0 !important;
    }
}

@media (max-width: 480px) {
    .navbar-nav .dropdown-menu {
        min-width: 260px;
    }
    
    .notification-item .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .notification-item .mr-3 {
        margin-right: 0 !important;
        margin-bottom: 8px;
    }
    
    .notification-item .float-right {
        float: none !important;
        display: block;
        margin-bottom: 5px;
    }
}
</style>

<script>
function markAsRead(notificationId) {
    document.getElementById('read-form-' + notificationId).submit();
}

// Ajouter une confirmation de déconnexion (optionnel)
document.addEventListener('DOMContentLoaded', function() {
    const logoutLink = document.querySelector('a[onclick*="logout-form"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
                e.preventDefault();
            }
        });
    }
});
</script>