<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-locale="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
       <!-- AJOUTEZ CES LIGNES ICI POUR LA PWA -->
    {!! PwaKit::head() !!}
    <!-- FIN AJOUT PWA -->
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
    
    <style>
        :root {
            --primary-dark: #1a1a2e;
            --primary-medium: #16213e;
            --primary-light: #0f3460;
            --accent-gold: #f7d44a;
            --accent-red: #e34234;
            --accent-green: #2c8c3c;
            --accent-blue: #002395;
            --text-light: #e0e0e0;
            --text-dark: #333333;
        }
        
        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Navbar styles */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 50%, var(--primary-light) 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            padding: 12px 0;
        }
        
        .navbar-custom .navbar-brand {
            display: flex;
            align-items: center;
        }
        
        .navbar-custom .navbar-brand img {
            height: 45px;
            width: auto;
            margin-right: 12px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
            transition: transform 0.3s;
        }
        
        .navbar-custom .navbar-brand:hover img {
            transform: scale(1.05);
        }
        
        .navbar-custom .navbar-brand span {
            color: var(--accent-gold);
            font-weight: 700;
            font-size: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .navbar-custom .nav-link {
            color: var(--text-light);
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 30px;
            transition: all 0.3s;
            position: relative;
        }
        
        .navbar-custom .nav-link:hover {
            color: var(--accent-gold);
            background: rgba(247, 212, 74, 0.1);
            transform: translateY(-2px);
        }
        
        .navbar-custom .nav-link.text-warning {
            color: var(--accent-gold) !important;
            border: 1px solid var(--accent-gold);
        }
        
        .navbar-custom .nav-link.text-warning:hover {
            background: var(--accent-gold);
            color: var(--primary-dark) !important;
        }
        
        /* Navbar buttons */
        .nav-btn {
            border: 1px solid var(--accent-gold) !important;
            border-radius: 30px !important;
            padding: 8px 18px !important;
            margin-left: 8px;
        }
        
        .nav-btn i {
            color: var(--accent-gold);
            margin-right: 6px;
        }
        
        .nav-btn:hover {
            background: var(--accent-gold) !important;
            color: var(--primary-dark) !important;
        }
        
        .nav-btn:hover i {
            color: var(--primary-dark) !important;
        }
        
        /* Language switcher */
        .lang-switcher .dropdown-toggle {
            border: 1px solid var(--accent-gold);
            border-radius: 30px;
            padding: 8px 15px !important;
        }
        
        .lang-switcher .dropdown-toggle i {
            color: var(--accent-gold);
        }
        
        .lang-switcher .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            min-width: 150px;
        }
        
        .lang-switcher .dropdown-item {
            padding: 8px 15px;
            transition: all 0.3s;
        }
        
        .lang-switcher .dropdown-item:hover {
            background: rgba(247, 212, 74, 0.1);
            padding-left: 25px;
        }
        
        .lang-switcher .dropdown-item.active {
            background: rgba(247, 212, 74, 0.2);
            color: var(--accent-gold);
            font-weight: 600;
        }
        
        .lang-switcher .dropdown-item i {
            color: var(--accent-gold);
            margin-right: 8px;
            opacity: 0;
        }
        
        .lang-switcher .dropdown-item.active i {
            opacity: 1;
        }
        
        /* Cart badge */
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--accent-gold);
            color: var(--primary-dark);
            font-weight: 600;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            transition: transform 0.3s;
        }
        
        .nav-link:hover .cart-badge {
            transform: scale(1.1);
        }
        
        /* User dropdown */
        .user-dropdown .dropdown-toggle {
            border: 1px solid var(--accent-gold);
            border-radius: 30px;
            padding: 5px 15px !important;
        }
        
        .user-dropdown .dropdown-toggle img {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-right: 8px;
            object-fit: cover;
        }
        
        .user-avatar-placeholder {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--accent-gold);
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 8px;
        }
        
        .user-dropdown .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            min-width: 250px;
        }
        
        .user-dropdown .dropdown-header {
            background: linear-gradient(135deg, rgba(247,212,74,0.1), rgba(227,66,52,0.1));
            border-radius: 10px 10px 0 0;
        }
        
        .user-dropdown .dropdown-item {
            padding: 10px 15px;
            transition: all 0.3s;
        }
        
        .user-dropdown .dropdown-item:hover {
            background: rgba(247, 212, 74, 0.1);
            padding-left: 25px;
        }
        
        .user-dropdown .dropdown-item i {
            width: 20px;
            margin-right: 10px;
        }
        
        .user-dropdown .dropdown-divider {
            border-color: rgba(247, 212, 74, 0.2);
        }
        
        /* Auth buttons */
        .btn-auth-login {
            background: var(--accent-gold);
            color: var(--primary-dark);
            border-radius: 30px;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.3s;
        }
        
        .btn-auth-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
            color: var(--primary-dark);
        }
        
        .btn-auth-register {
            background: transparent;
            color: var(--accent-gold);
            border: 2px solid var(--accent-gold);
            border-radius: 30px;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.3s;
        }
        
        .btn-auth-register:hover {
            background: var(--accent-gold);
            color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
        }
        
        /* Footer styles */
        .footer-custom {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 50%, var(--primary-light) 100%);
            color: var(--text-light);
            margin-top: 60px;
            position: relative;
        }
        
        .footer-custom::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(90deg, var(--accent-gold), var(--accent-red), var(--accent-green), var(--accent-blue));
        }
        
        .footer-custom h5 {
            color: var(--accent-gold);
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-custom h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: var(--accent-gold);
        }
        
        .footer-custom a {
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .footer-custom a:hover {
            color: var(--accent-gold);
            transform: translateX(5px);
        }
        
        .footer-custom ul {
            padding-left: 0;
        }
        
        .footer-custom li {
            list-style: none;
            margin-bottom: 10px;
        }
        
        .footer-custom i {
            color: var(--accent-gold);
            margin-right: 8px;
            width: 20px;
        }
        
        .footer-divider {
            background: linear-gradient(90deg, transparent, var(--accent-gold), var(--accent-red), var(--accent-green), var(--accent-blue), transparent);
            height: 2px;
            border: none;
            margin: 30px 0;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(247, 212, 74, 0.2);
            padding-top: 20px;
            margin-top: 20px;
        }
        
        /* Newsletter */
        .newsletter-input {
            border: none;
            border-radius: 30px 0 0 30px;
            padding: 12px 20px;
            background: rgba(255,255,255,0.1);
            color: var(--text-light);
        }
        
        .newsletter-input::placeholder {
            color: rgba(255,255,255,0.5);
        }
        
        .newsletter-input:focus {
            background: rgba(255,255,255,0.15);
            box-shadow: none;
            outline: none;
        }
        
        .newsletter-btn {
            border-radius: 0 30px 30px 0;
            background: var(--accent-gold);
            color: var(--primary-dark);
            font-weight: 600;
            padding: 12px 25px;
            border: none;
            transition: all 0.3s;
        }
        
        .newsletter-btn:hover {
            background: #e5c03a;
            transform: translateY(-2px);
        }
        
        /* Payment badges */
        .payment-badge {
            background: rgba(255,255,255,0.1);
            padding: 8px 12px;
            border-radius: 8px;
            transition: transform 0.3s;
        }
        
        .payment-badge:hover {
            transform: scale(1.1);
            background: rgba(255,255,255,0.15);
        }
        
        /* Social icons */
        .social-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--accent-gold);
            border-radius: 50%;
            color: var(--accent-gold);
            transition: all 0.3s;
        }
        
        .social-icon:hover {
            background: var(--accent-gold);
            color: var(--primary-dark);
            transform: translateY(-3px);
        }
        
        /* App buttons */
        .app-btn {
            background: rgba(0,0,0,0.3);
            border: 1px solid var(--accent-gold);
            border-radius: 10px;
            padding: 8px 15px;
            transition: all 0.3s;
        }
        
        .app-btn:hover {
            background: var(--accent-gold);
            color: var(--primary-dark);
        }
        
        .app-btn:hover i {
            color: var(--primary-dark);
        }
        
        /* Responsive */
        @media (max-width: 991.98px) {
            .navbar-custom .navbar-nav {
                margin-top: 15px;
            }
            
            .navbar-custom .nav-item {
                margin: 5px 0;
            }
            
            .nav-btn, .lang-switcher .dropdown-toggle {
                margin-left: 0;
                width: 100%;
            }
            
            .user-dropdown .dropdown-toggle {
                width: 100%;
            }
        }
        
        @media (max-width: 767.98px) {
            .newsletter-input {
                border-radius: 30px;
                margin-bottom: 10px;
            }
            
            .newsletter-btn {
                border-radius: 30px;
                width: 100%;
            }
            
            .footer-custom h5::after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .footer-custom .text-center h5::after {
                left: 50%;
                transform: translateX(-50%);
            }
            /* À AJOUTER À LA FIN DE VOTRE STYLE */

/* Améliorations supplémentaires pour mobile */
@media (max-width: 768px) {
    /* Navbar */
    .navbar-custom .navbar-brand span {
        font-size: 1.2rem;
    }
    
    .navbar-custom .navbar-brand img {
        height: 35px;
    }
    
    /* User dropdown */
    .user-dropdown .dropdown-menu {
        min-width: 280px;
        right: -50px !important;
    }
    
    /* Cart badge */
    .cart-badge {
        min-width: 18px;
        height: 18px;
        font-size: 0.7rem;
    }
    
    /* Footer */
    .footer-custom h5 {
        text-align: center;
    }
    
    .footer-custom h5::after {
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
    }
    
    .footer-custom ul {
        text-align: center;
    }
    
    .footer-custom li a {
        justify-content: center;
    }
    
    .social-icon {
        width: 35px;
        height: 35px;
    }
    
    /* App buttons */
    .app-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .navbar-custom .navbar-brand span {
        font-size: 1rem;
    }
    
    .navbar-custom .navbar-brand img {
        height: 30px;
        margin-right: 8px;
    }
    
    .nav-btn {
        padding: 6px 12px !important;
        font-size: 0.9rem;
    }
    
    .btn-auth-login, .btn-auth-register {
        padding: 6px 15px;
        font-size: 0.9rem;
    }
    
    .footer-custom {
        padding: 2rem 0;
    }
    
    .payment-badge {
        padding: 5px 8px;
    }
    
    .payment-badge i {
        font-size: 1.5rem;
    }
}

/* Pour très petits écrans */
@media (max-width: 360px) {
    .navbar-custom .navbar-brand span {
        font-size: 0.9rem;
    }
    
    .nav-link {
        padding: 6px 10px !important;
    }
    
    .user-dropdown .dropdown-toggle span {
        display: none;
    }
}
        }
    </style>
</head>
<body>
    <!-- Navigation améliorée -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('shop.home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}">
                <span>Carioca & Coquelicot</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.products.index') }}">
                            <i class="fas fa-box me-1"></i> {{ __('messages.products') }}
                        </a>
                    </li>

                    <!-- LIEN DEVENIR VENDEUR (pour les utilisateurs normaux) -->
                    @auth
                        @if(!auth()->user()->isVendor() && !auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{ route('vendor.register') }}">
                                <i class="fas fa-store me-1"></i> {{ __('messages.become_vendor') }}
                            </a>
                        </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Sélecteur de langue amélioré -->
                    <li class="nav-item dropdown lang-switcher">
                        <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe"></i>
                            <span>{{ strtoupper(app()->getLocale()) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach(config('app.available_locales') as $code => $name)
                                <li>
                                    <a class="dropdown-item {{ app()->getLocale() == $code ? 'active' : '' }}" 
                                       href="{{ route('language.switch', $code) }}">
                                        <i class="fas fa-check-circle"></i>
                                        {{ $name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <!-- Panier amélioré -->
                    <li class="nav-item">
                        <a class="nav-link nav-btn position-relative" href="{{ route('shop.cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span>{{ __('messages.cart') }}</span>
                            @if(isset($cartCount) && $cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    
                    @auth
                        <!-- Menu utilisateur connecté amélioré -->
                        <li class="nav-item dropdown user-dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                @php
                                    $userPhoto = Auth::user()->profile_photo_url 
                                        ? (file_exists(public_path('storage/' . Auth::user()->profile_photo_url)) 
                                            ? asset('storage/' . Auth::user()->profile_photo_url) 
                                            : null)
                                        : null;
                                @endphp
                                
                                @if($userPhoto)
                                    <img src="{{ $userPhoto }}" alt="{{ Auth::user()->name }}">
                                @else
                                    <div class="user-avatar-placeholder">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="dropdown-header px-3 py-3">
                                    <div class="d-flex align-items-center">
                                        @if($userPhoto)
                                            <img src="{{ $userPhoto }}" 
                                                 alt="{{ Auth::user()->name }}" 
                                                 style="width: 40px; height: 40px; border-radius: 50%; margin-right: 12px; object-fit: cover;">
                                        @else
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--accent-gold); color: var(--primary-dark); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px; margin-right: 12px;">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0" style="color: var(--accent-gold); font-weight: 700;">{{ Auth::user()->name }}</h6>
                                            <small style="color: var(--text-light); opacity: 0.8;">{{ Auth::user()->email }}</small>
                                        </div>
                                    </div>
                                </li>
                                
                                <li><hr class="dropdown-divider"></li>
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('client.orders.index') }}">
                                        <i class="fas fa-history" style="color: var(--accent-gold);"></i> 
                                        {{ __('messages.my_orders') }}
                                    </a>
                                </li>
                                
                                @if(auth()->user()->isVendor())
                                <li>
                                    <a class="dropdown-item" href="{{ route('vendor.dashboard') }}">
                                        <i class="fas fa-store" style="color: var(--accent-gold);"></i> 
                                        {{ __('messages.vendor_space') }}
                                    </a>
                                </li>
                                @endif
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('client.profile.edit') }}">
                                        <i class="fas fa-user-cog" style="color: var(--accent-gold);"></i> 
                                        {{ __('messages.my_profile') }}
                                    </a>
                                </li>
                                
                                @if(Auth::user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-crown" style="color: var(--accent-gold);"></i> 
                                        {{ __('messages.administration') }}
                                    </a>
                                </li>
                                @endif
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt" style="color: #e34234;"></i> 
                                            {{ __('messages.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Utilisateur non connecté amélioré -->
                        <li class="nav-item">
                            <a class="nav-link btn-auth-login" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2"></i> {{ __('messages.login') }}
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="nav-link btn-auth-register" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-2"></i> {{ __('messages.register') }}
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
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>{{ __('messages.errors') }}:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Contenu principal -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer amélioré -->
    <footer class="footer-custom py-5">
        <div class="container">
            <!-- Newsletter Section -->
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 text-center">
                    <h5 class="mb-3">{{ __('messages.newsletter_title') ?? 'Recevez nos offres exclusives' }}</h5>
                    <p class="mb-4" style="color: var(--text-light); opacity: 0.8;">
                        {{ __('messages.newsletter_subtitle') ?? 'Inscrivez-vous pour recevoir -10% sur votre première commande' }}
                    </p>
                    <form class="row g-2 justify-content-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="email" class="form-control newsletter-input" 
                                       placeholder="{{ __('messages.email_placeholder') ?? 'Votre adresse email' }}">
                                <button class="btn newsletter-btn" type="submit">
                                    {{ __('messages.subscribe') ?? 'S\'inscrire' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row g-4">
                <!-- Colonne 1 - Logo et description -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Carioca & Coquelicot" style="height: 50px; width: auto; margin-right: 15px;">
                        <h4 style="color: var(--accent-gold); font-weight: 700;">Carioca & Coquelicot</h4>
                    </div>
                    <p style="color: var(--text-light); opacity: 0.8; line-height: 1.8;">
                        {{ __('messages.shop_description') }}
                    </p>
                    <div class="mt-4">
                        <h5 style="color: var(--accent-gold); font-size: 1.1rem; margin-bottom: 15px;">
                            <i class="fas fa-credit-card me-2"></i>{{ __('messages.payment_methods') ?? 'Paiement sécurisé' }}
                        </h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="payment-badge"><i class="fab fa-cc-visa fa-2x"></i></span>
                            <span class="payment-badge"><i class="fab fa-cc-mastercard fa-2x"></i></span>
                            <span class="payment-badge"><i class="fab fa-cc-amex fa-2x"></i></span>
                            <span class="payment-badge"><i class="fab fa-cc-paypal fa-2x"></i></span>
                            <span class="payment-badge"><i class="fas fa-barcode fa-2x"></i></span>
                        </div>
                    </div>
                </div>

                <!-- Colonne 2 - Liens rapides -->
                <div class="col-lg-2 col-md-6">
                    <h5>{{ __('messages.quick_links') }}</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('shop.home') }}"><i class="fas fa-chevron-right"></i> {{ __('messages.home') }}</a></li>
                        <li><a href="{{ route('shop.products.index') }}"><i class="fas fa-chevron-right"></i> {{ __('messages.products') }}</a></li>
                        <li><a href="{{ route('shop.cart.index') }}"><i class="fas fa-chevron-right"></i> {{ __('messages.cart') }}</a></li>
                        <li><a href="{{ route('client.orders.index') }}"><i class="fas fa-chevron-right"></i> {{ __('messages.my_orders') }}</a></li>
                        <li><a href="{{ route('client.profile.edit') }}"><i class="fas fa-chevron-right"></i> {{ __('messages.my_profile') }}</a></li>
                    </ul>
                </div>

                <!-- Colonne 3 - Contact et horaires -->
                <div class="col-lg-3 col-md-6">
                    <h5>{{ __('messages.hours') ?? 'Horaires d\'ouverture' }}</h5>
                    <ul class="list-unstyled" style="color: var(--text-light); opacity: 0.8;">
                        <li><i class="fas fa-calendar-check"></i> {{ __('messages.weekdays') ?? 'Lundi - Vendredi' }}: 9h00 - 19h00</li>
                        <li><i class="fas fa-calendar-check"></i> {{ __('messages.saturday') ?? 'Samedi' }}: 10h00 - 18h00</li>
                        <li><i class="fas fa-calendar-times"></i> {{ __('messages.sunday') ?? 'Dimanche' }}: Fermé</li>
                    </ul>
                    
                    <h5 class="mt-4">{{ __('messages.support') ?? 'Support client' }}</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone-alt"></i> <a href="tel:0123456789">01 23 45 67 89</a></li>
                        <li><i class="fas fa-envelope"></i> <a href="mailto:contact@carioca-coquelicot.com">contact@carioca-coquelicot.com</a></li>
                    </ul>
                </div>

                <!-- Colonne 4 - Réseaux sociaux et app stores -->
                <div class="col-lg-3 col-md-6">
                    <h5>{{ __('messages.follow_us') ?? 'Suivez-nous' }}</h5>
                    <div class="d-flex gap-2 mb-4">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-pinterest"></i></a>
                    </div>
                    
                    <h5 class="mt-4">{{ __('messages.download_app') ?? 'Téléchargez l\'app' }}</h5>
                    <div class="d-flex flex-column gap-2">
                        <a href="#" class="app-btn d-flex align-items-center">
                            <i class="fab fa-apple fa-2x me-3"></i>
                            <div class="text-start">
                                <small style="font-size: 0.7rem; opacity: 0.8;">{{ __('messages.download_on') ?? 'Télécharger sur' }}</small>
                                <div style="font-weight: 600;">App Store</div>
                            </div>
                        </a>
                        <a href="#" class="app-btn d-flex align-items-center">
                            <i class="fab fa-google-play fa-2x me-3"></i>
                            <div class="text-start">
                                <small style="font-size: 0.7rem; opacity: 0.8;">{{ __('messages.download_on') ?? 'Télécharger sur' }}</small>
                                <div style="font-weight: 600;">Google Play</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Séparateur -->
            <hr class="footer-divider">

            <!-- Bottom footer -->
            <div class="row align-items-center footer-bottom">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0" style="color: var(--text-light); opacity: 0.7;">
                        &copy; {{ date('Y') }} <strong style="color: var(--accent-gold);">Carioca & Coquelicot</strong>. 
                        {{ __('messages.all_rights_reserved') }}
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="#" style="color: var(--text-light); opacity: 0.7; text-decoration: none;">{{ __('messages.privacy_policy') ?? 'Politique de confidentialité' }}</a>
                        </li>
                        <li class="list-inline-item">
                            <span style="color: var(--accent-gold);">|</span>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" style="color: var(--text-light); opacity: 0.7; text-decoration: none;">{{ __('messages.terms_of_use') ?? 'Conditions d\'utilisation' }}</a>
                        </li>
                        <li class="list-inline-item">
                            <span style="color: var(--accent-gold);">|</span>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" style="color: var(--text-light); opacity: 0.7; text-decoration: none;">{{ __('messages.cookies') ?? 'Cookies' }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    
    <script>
        // Animation pour les alertes
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(function(alert) {
                    if (alert && !alert.classList.contains('alert-permanent')) {
                        alert.classList.add('fade');
                        setTimeout(function() {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 500);
                    }
                });
            }, 5000);
        });

        // Gestionnaire pour le changement de langue
        document.querySelectorAll('.dropdown-item[href*="language/"]').forEach(link => {
            link.addEventListener('click', function(e) {
                // Permet à la requête de s'exécuter normalement
            });
        });
    </script>
     <!-- AJOUTEZ CES LIGNES POUR LA PWA -->
    {!! PwaKit::scripts() !!}
    <!-- FIN AJOUT PWA -->
    
    @stack('scripts')
</body>
</html>