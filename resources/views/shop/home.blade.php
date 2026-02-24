@extends('shop.layouts.shop')

@section('title', 'Accueil - Ma Boutique Animalière')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="bg-primary text-white p-5 rounded-3 mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h1 class="display-4">Bienvenue dans notre boutique animalière</h1>
        <p class="lead">Découvrez notre sélection de produits de qualité pour vos animaux de compagnie</p>
        <hr class="my-4 bg-white">
        <p>Livraison gratuite dès 50€ d'achat</p>
        <a href="{{ route('shop.products.index') }}" class="btn btn-light btn-lg">
            <i class="fas fa-paw me-2"></i> Voir tous les produits
        </a>
    </div>

    <!-- Catégories -->
    <h2 class="mb-4">Nos catégories</h2>
    <div class="row mb-5">
        @php
            use App\Helpers\CategoryHelper;
        @endphp
        
        @foreach($categories as $category)
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100 category-card">
                <div class="card-body">
                    <i class="fas fa-{{ CategoryHelper::getIcon($category->name) }} fa-3x mb-3 text-primary"></i>
                    <h5 class="card-title">{{ $category->name }}</h5>
                    <p class="card-text">{{ $category->products_count }} produits</p>
                    <a href="{{ route('shop.products.index', ['category' => $category->slug]) }}" class="btn btn-outline-primary btn-sm">
                        Voir les produits
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Section appel à l'action pour les visiteurs -->
    @guest
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <h3 class="mb-3">Rejoignez notre communauté !</h3>
                    <p class="lead mb-4">Créez un compte pour bénéficier de nombreux avantages :</p>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <i class="fas fa-truck fa-2x text-primary mb-2"></i>
                            <h5>Suivi de commande</h5>
                            <p class="text-muted">Suivez vos commandes en temps réel</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-history fa-2x text-primary mb-2"></i>
                            <h5>Historique</h5>
                            <p class="text-muted">Retrouvez toutes vos commandes</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-bolt fa-2x text-primary mb-2"></i>
                            <h5>Commandes rapides</h5>
                            <p class="text-muted">Commandez en un clic</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">
                            <i class="fas fa-user-plus me-2"></i> Créer un compte
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Nouveautés -->
    <h2 class="mb-4">Nouveautés</h2>
    <div class="row">
        @foreach($newProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm product-card">
                <!-- Badge nouveau -->
                <span class="position-absolute top-0 end-0 badge bg-primary m-2">
                    <i class="fas fa-star me-1"></i> Nouveau
                </span>
                
                <!-- Image du produit -->
                <div class="text-center bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                    @if($product->image)
                        @php
                            $imageName = basename($product->image);
                        @endphp
                        <img src="{{ asset('storage/products/' . $imageName) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid"
                             style="max-width: 100%; max-height: 180px; width: auto; height: auto; object-fit: contain;"
                             onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\'fas fa-box fa-4x text-secondary\'></i>';">
                    @else
                        <i class="fas fa-box fa-4x text-secondary"></i>
                    @endif
                </div>
                
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    @if($product->category)
                        <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                    @endif
                    <p class="card-text text-muted small">{{ $product->short_description ?? Str::limit($product->description, 50) }}</p>
                    <p class="card-text">
                        <strong class="text-primary">{{ number_format($product->price, 2) }} €</strong>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('shop.products.show', $product) }}" class="btn btn-outline-primary w-100">
                        Voir détails
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Meilleures ventes -->
    <h2 class="mb-4 mt-5">Meilleures ventes</h2>
    <div class="row">
        @foreach($featuredProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm product-card">
                <!-- Badge stock limité -->
                @if($product->quantity <= $product->stock_threshold)
                    <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                        <i class="fas fa-exclamation-triangle me-1"></i> Stock limité
                    </span>
                @endif
                
                <!-- Image du produit -->
                <div class="text-center bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                    @if($product->image)
                        @php
                            $imageName = basename($product->image);
                        @endphp
                        <img src="{{ asset('storage/products/' . $imageName) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid"
                             style="max-width: 100%; max-height: 180px; width: auto; height: auto; object-fit: contain;"
                             onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\'fas fa-box fa-4x text-secondary\'></i>';">
                    @else
                        <i class="fas fa-box fa-4x text-secondary"></i>
                    @endif
                </div>
                
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    @if($product->category)
                        <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                    @endif
                    <p class="card-text text-muted small">{{ $product->short_description ?? Str::limit($product->description, 50) }}</p>
                    <p class="card-text">
                        <strong class="text-primary">{{ number_format($product->price, 2) }} €</strong>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('shop.products.show', $product) }}" class="btn btn-outline-primary w-100">
                        Voir détails
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    
    .category-card {
        transition: all 0.3s ease;
    }
    
    .category-card:hover {
        transform: scale(1.05);
        background-color: #f8f9fa;
    }
    
    .badge {
        z-index: 10;
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
    
    .dark .product-card {
        background-color: #2d2d44;
        border-color: #4a4a6a;
    }
    
    .dark .product-card .card-title {
        color: #e0e0e0;
    }
    
    .dark .product-card .text-muted {
        color: #a0a0c0 !important;
    }
    
    .dark .category-card {
        background-color: #2d2d44;
        border-color: #4a4a6a;
    }
    
    .dark .category-card .card-title {
        color: #e0e0e0;
    }
    
    .dark .category-card:hover {
        background-color: #35354a;
    }
    
    .dark .bg-light {
        background-color: #2d2d44 !important;
    }
    
    .dark .bg-light i,
    .dark .bg-light p {
        color: #e0e0e0 !important;
    }
</style>
@endpush