{{-- resources/views/shop/home.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Accueil - Ma Boutique')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="bg-primary text-white p-5 rounded-3 mb-5">
        <h1 class="display-4">Bienvenue dans notre boutique</h1>
        <p class="lead">Découvrez notre sélection de produits de qualité</p>
        <hr class="my-4 bg-white">
        <p>Connectez-vous pour passer commande et suivre vos achats</p>
        <a href="{{ route('shop.products.index') }}" class="btn btn-light btn-lg">
            <i class="fas fa-shopping-bag mr-2"></i> Voir tous les produits
        </a>
        @guest
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg ms-2">
                <i class="fas fa-user-plus mr-2"></i> Créer un compte
            </a>
        @endguest
    </div>

    <!-- Produits en vedette -->
    <h2 class="mb-4">Produits en vedette</h2>
    <div class="row">
        @forelse($featuredProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-light text-center py-5">
                        <i class="fas fa-box fa-4x text-secondary"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($product->description, 50) }}</p>
                    <p class="card-text">
                        <strong class="text-primary">{{ number_format($product->price, 2) }} €</strong>
                    </p>
                    <p class="card-text">
                        <small class="text-muted">
                            <i class="fas fa-check-circle text-success"></i>
                            En stock: {{ $product->quantity }}
                        </small>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('shop.products.show', $product) }}" class="btn btn-outline-primary w-100">
                        Voir détails
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                Aucun produit disponible pour le moment.
            </div>
        </div>
        @endforelse
    </div>

    <!-- Nouveaux produits -->
    @if($newProducts->count() > 0)
    <h2 class="mb-4 mt-5">Nouveautés</h2>
    <div class="row">
        @foreach($newProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($product->description, 50) }}</p>
                    <p class="card-text">
                        <strong class="text-primary">{{ number_format($product->price, 2) }} €</strong>
                    </p>
                    <p class="card-text">
                        <small class="text-muted">
                            <i class="fas fa-check-circle text-success"></i>
                            En stock: {{ $product->quantity }}
                        </small>
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
    @endif

    <!-- Lien vers tous les produits -->
    <div class="text-center mt-4">
        <a href="{{ route('shop.products.index') }}" class="btn btn-primary btn-lg">
            Voir tous les produits <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
</div>
@endsection