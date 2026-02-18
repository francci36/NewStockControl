{{-- resources/views/shop/layouts/shop.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Boutique') - Ma Boutique</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('shop.home') }}">
                <i class="fas fa-store"></i> Ma Boutique
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.products.index') }}">
                            <i class="fas fa-box"></i> Produits
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <!-- Panier -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('shop.cart.index') }}">
                            <i class="fas fa-shopping-cart"></i> Panier
                            @if(isset($cartCount) && $cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                        @auth
                            @if(Auth::user()->isAdmin())
                                <!-- Admin connecté -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-cog"></i> Administration
                                    </a>
                                </li>
                            @else
                                <!-- Client connecté -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('client.orders.index') }}">
                                        <i class="fas fa-history"></i> Mes commandes
                                    </a>
                                </li>
                            @endif
                            <!-- Menu utilisateur -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('client.profile.edit') }}">
                                            <i class="fas fa-user-cog"></i> Mon profil
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Erreur(s):</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Contenu principal -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Ma Boutique - Tous droits réservés</p>
            <p class="mb-0 mt-2">
                <small>
                    <a href="{{ route('shop.home') }}" class="text-white-50">Accueil</a> | 
                    <a href="{{ route('shop.products.index') }}" class="text-white-50">Produits</a> | 
                    <a href="{{ route('shop.cart.index') }}" class="text-white-50">Panier</a>
                </small>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>