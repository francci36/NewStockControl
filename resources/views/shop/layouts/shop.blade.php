{{-- resources/views/shop/layouts/shop.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ma Boutique Animalière')</title>
    
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
                <i class="fas fa-paw"></i> Ma Boutique Animalière
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

                    <!-- LIEN DEVENIR VENDEUR (pour les utilisateurs normaux) -->
                    @auth
                        @if(!auth()->user()->isVendor() && !auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{ route('vendor.register') }}">
                                <i class="fas fa-store"></i> Devenir vendeur
                            </a>
                        </li>
                        @endif
                    @endauth
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
                        <!-- Menu utilisateur connecté -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <!-- Options pour tous les utilisateurs connectés -->
                                <li>
                                    <a class="dropdown-item" href="{{ route('client.orders.index') }}">
                                        <i class="fas fa-history"></i> Mes commandes
                                    </a>
                                </li>
                                
                                <!-- 🔥 ESPACE VENDEUR (pour les vendeurs) -->
                                @if(auth()->user()->isVendor())
                                <li>
                                    <a class="dropdown-item text-success" href="{{ route('vendor.dashboard') }}">
                                        <i class="fas fa-store"></i> Mon espace vendeur
                                    </a>
                                </li>
                                @endif
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('client.profile.edit') }}">
                                        <i class="fas fa-user-cog"></i> Mon profil
                                    </a>
                                </li>
                                
                                <!-- Option admin visible uniquement pour les admins -->
                                @if(Auth::user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-primary" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-crown"></i> Administration
                                    </a>
                                </li>
                                @endif
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Utilisateur non connecté -->
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm px-3 me-2" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary btn-sm px-3" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Inscription
                            </a>
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
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-paw me-2"></i> Ma Boutique Animalière</h5>
                    <p>Votre boutique en ligne pour tous vos animaux de compagnie.</p>
                </div>
                <div class="col-md-4">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('shop.home') }}" class="text-white-50">Accueil</a></li>
                        <li><a href="{{ route('shop.products.index') }}" class="text-white-50">Produits</a></li>
                        <li><a href="{{ route('shop.cart.index') }}" class="text-white-50">Panier</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> contact@boutique.com</li>
                        <li><i class="fas fa-phone me-2"></i> 01 23 45 67 89</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <p class="text-center mb-0">&copy; {{ date('Y') }} Ma Boutique Animalière - Tous droits réservés</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>