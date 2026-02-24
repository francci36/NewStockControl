@extends('shop.layouts.shop')

@section('title', $product->name . ' - Ma Boutique Animalière')

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
            <div class="card position-relative">
                <!-- Badge stock limité -->
                @if($product->quantity <= $product->stock_threshold && $product->quantity > 0)
                    <span class="position-absolute top-0 start-0 badge bg-danger m-3" style="z-index: 10;">
                        <i class="fas fa-exclamation-triangle me-1"></i> Stock limité
                    </span>
                @endif
                
                <!-- Image du produit -->
                <div class="text-center p-3">
                    @if($product->image)
                        @php
                            $imageName = basename($product->image);
                        @endphp
                        <img src="{{ asset('storage/products/' . $imageName) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid"
                             style="max-height: 400px; width: auto; object-fit: contain;"
                             onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\'fas fa-box fa-5x text-secondary\'></i><p class=\'mt-2 text-muted\'>Image non disponible</p>';">
                    @else
                        <i class="fas fa-box fa-5x text-secondary"></i>
                        <p class="mt-2 text-muted">Aucune image disponible</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Détails du produit -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <!-- Catégorie -->
                    @if($product->category)
                    <div class="mb-2">
                        <span class="badge bg-secondary">{{ $product->category->name }}</span>
                    </div>
                    @endif
                    
                    <h1 class="card-title h2 mb-3">{{ $product->name }}</h1>
                    
                    <div class="mb-3">
                        <!-- Vendeur ou Fournisseur -->
                        @if($product->vendor)
                            <span class="badge bg-success">
                                <i class="fas fa-store me-1"></i> {{ $product->vendor->shop_name }}
                            </span>
                        @elseif($product->supplier)
                            <span class="badge bg-info">
                                <i class="fas fa-truck me-1"></i> {{ $product->supplier->name }}
                            </span>
                        @endif
                        
                        <!-- Statut stock -->
                        @if($product->quantity > 10)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i> En stock ({{ $product->quantity }})
                            </span>
                        @elseif($product->quantity > 0)
                            <span class="badge bg-warning">
                                <i class="fas fa-exclamation-circle me-1"></i> Stock limité ({{ $product->quantity }})
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i> Rupture de stock
                            </span>
                        @endif
                    </div>

                    <!-- Prix -->
                    <div class="mb-4">
                        <h2 class="text-primary mb-0">{{ number_format($product->price, 2) }} €</h2>
                        @if($product->quantity > 0)
                            <small class="text-muted">TVA incluse</small>
                        @endif
                    </div>

                    <!-- Description courte -->
                    @if($product->short_description)
                    <div class="mb-3">
                        <h5>En bref</h5>
                        <p class="text-muted fst-italic">{{ $product->short_description }}</p>
                    </div>
                    @endif

                    <!-- Description complète -->
                    <div class="mb-4">
                        <h5>Description détaillée</h5>
                        <p class="text-muted">{{ $product->description ?? 'Aucune description disponible.' }}</p>
                    </div>

                    <!-- Formulaire d'achat -->
                    @if($product->quantity > 0)
                        <form action="{{ route('shop.cart.add') }}" method="POST" class="mb-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="row g-3 align-items-end">
                                <div class="col-auto">
                                    <label for="quantity" class="form-label fw-bold">Quantité</label>
                                    <input type="number" 
                                           name="quantity" 
                                           id="quantity" 
                                           class="form-control form-control-lg" 
                                           value="1" 
                                           min="1" 
                                           max="{{ $product->quantity }}"
                                           style="width: 120px;">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-cart-plus me-2"></i> Ajouter au panier
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-box me-1"></i> {{ $product->quantity }} unités disponibles
                                </small>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i> Ce produit n'est pas disponible actuellement.
                        </div>
                    @endif

                    <!-- Caractéristiques -->
                    <div class="mt-4 p-3 bg-light dark:bg-gray-700 rounded">
                        <h5 class="mb-3">Caractéristiques</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-hashtag me-2 text-primary"></i>
                                <strong>Référence:</strong> #{{ $product->id }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-tag me-2 text-primary"></i>
                                <strong>Catégorie:</strong> {{ $product->category->name ?? 'Non catégorisé' }}
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-building me-2 text-primary"></i>
                                <strong>Vendu par:</strong> 
                                @if($product->vendor)
                                    {{ $product->vendor->shop_name }}
                                @elseif($product->supplier)
                                    {{ $product->supplier->name }}
                                @else
                                    Non spécifié
                                @endif
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-calendar-plus me-2 text-primary"></i>
                                <strong>Ajouté le:</strong> {{ $product->created_at->format('d/m/Y') }}
                            </li>
                            @if($product->updated_at != $product->created_at)
                            <li class="mb-2">
                                <i class="fas fa-sync me-2 text-primary"></i>
                                <strong>Dernière mise à jour:</strong> {{ $product->updated_at->format('d/m/Y') }}
                            </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Partager -->
                    <div class="mt-4">
                        <h6 class="mb-2">Partager ce produit</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}" 
                               target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . request()->url()) }}" 
                               target="_blank" class="btn btn-sm btn-outline-success">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
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
                <div class="card h-100 product-card">
                    <!-- Image du produit similaire -->
                    <div class="text-center bg-light p-2" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                        @if($relatedProduct->image)
                            @php
                                $imageName = basename($relatedProduct->image);
                            @endphp
                            <img src="{{ asset('storage/products/' . $imageName) }}" 
                                 alt="{{ $relatedProduct->name }}" 
                                 class="img-fluid"
                                 style="max-height: 130px; width: auto; object-fit: contain;"
                                 onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\'fas fa-box fa-3x text-secondary\'></i>';">
                        @else
                            <i class="fas fa-box fa-3x text-secondary"></i>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($relatedProduct->description, 50) }}</p>
                        <p class="card-text">
                            <strong class="text-primary">{{ number_format($relatedProduct->price, 2) }} €</strong>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('shop.products.show', $relatedProduct) }}" class="btn btn-outline-primary w-100 btn-sm">
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

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
    
    .dark .bg-light {
        background-color: #2d2d44 !important;
    }
    
    .dark .bg-light i,
    .dark .bg-light p {
        color: #e0e0e0 !important;
    }
    
    .dark .card {
        background-color: #2d2d44;
        border-color: #4a4a6a;
    }
    
    .dark .card-title {
        color: #e0e0e0;
    }
    
    .dark .text-muted {
        color: #a0a0c0 !important;
    }
</style>
@endpush