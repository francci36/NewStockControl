{{-- resources/views/shop/products/show.blade.php --}}
@extends('shop.layouts.shop')

@section('title', $product->name . ' - Ma Boutique')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.products.index') }}">Produits</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Image du produit -->
        <div class="col-md-6 mb-4">
            <div class="card">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="max-height: 400px; object-fit: contain;">
                @else
                    <div class="bg-light text-center py-5">
                        <i class="fas fa-box fa-5x text-secondary"></i>
                        <p class="mt-2 text-muted">Aucune image disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Détails du produit -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title h2 mb-3">{{ $product->name }}</h1>
                    
                    <div class="mb-3">
                        <span class="badge bg-info">Fournisseur: {{ $product->supplier->name }}</span>
                        @if($product->quantity > 10)
                            <span class="badge bg-success">En stock</span>
                        @elseif($product->quantity > 0)
                            <span class="badge bg-warning">Stock limité ({{ $product->quantity }})</span>
                        @else
                            <span class="badge bg-danger">Rupture de stock</span>
                        @endif
                    </div>

                    <h2 class="text-primary mb-3">{{ number_format($product->price, 2) }} €</h2>

                    <div class="mb-4">
                        <h5>Description</h5>
                        <p class="text-muted">{{ $product->description ?? 'Aucune description disponible.' }}</p>
                    </div>

                    @if($product->quantity > 0)
                        <form action="{{ route('shop.cart.add') }}" method="POST" class="mb-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="row g-3">
                                <div class="col-auto">
                                    <label for="quantity" class="visually-hidden">Quantité</label>
                                    <input type="number" 
                                           name="quantity" 
                                           id="quantity" 
                                           class="form-control" 
                                           value="1" 
                                           min="1" 
                                           max="{{ $product->quantity }}"
                                           style="width: 100px;">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-cart-plus me-2"></i> Ajouter au panier
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">Stock disponible: {{ $product->quantity }}</small>
                        </form>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i> Ce produit n'est pas disponible actuellement.
                        </div>
                    @endif

                    <div class="mt-4">
                        <h5>Caractéristiques</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-tag me-2 text-primary"></i> Réf: #{{ $product->id }}</li>
                            <li><i class="fas fa-calendar me-2 text-primary"></i> Ajouté le: {{ $product->created_at->format('d/m/Y') }}</li>
                            <li><i class="fas fa-sync me-2 text-primary"></i> Dernière mise à jour: {{ $product->updated_at->format('d/m/Y') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Produits similaires -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">Produits similaires</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($relatedProduct->description, 50) }}</p>
                        <p class="card-text">
                            <strong class="text-primary">{{ number_format($relatedProduct->price, 2) }} €</strong>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('shop.products.show', $relatedProduct) }}" class="btn btn-outline-primary w-100">
                            Voir détails
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection