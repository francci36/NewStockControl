{{-- resources/views/shop/cart.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Mon Panier - Ma Boutique Animalière')

@section('content')
<div class="container">
    <h1 class="mb-4">Mon Panier</h1>

    @if($cartItems->isEmpty())
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart fa-4x mb-3"></i>
            <h3>Votre panier est vide</h3>
            <p>Découvrez nos produits de qualité pour vos animaux de compagnie !</p>
            <a href="{{ route('shop.products.index') }}" class="btn btn-primary btn-lg mt-3">
                <i class="fas fa-paw me-2"></i> Voir les produits
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                @foreach($cartItems as $item)
                <div class="card mb-3 cart-item">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Image du produit - CORRIGÉ AVEC STORAGE -->
                            <div class="col-md-2">
                                @if($item->product->image)
                                    @php
                                        $imagePath = str_contains($item->product->image, 'products/') 
                                            ? $item->product->image 
                                            : 'products/' . $item->product->image;
                                    @endphp
                                    <img src="{{ asset('storage/' . $imagePath) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="img-fluid rounded"
                                         style="max-height: 80px; width: auto; object-fit: contain;"
                                         onerror="this.onerror=null; this.parentNode.innerHTML='<div class=\'bg-light text-center py-3 rounded\'><i class=\'fas fa-box fa-2x text-secondary\'></i></div>';">
                                @else
                                    <div class="bg-light text-center py-3 rounded">
                                        <i class="fas fa-box fa-2x text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Infos produit -->
                            <div class="col-md-4">
                                <h5 class="card-title">{{ $item->product->name }}</h5>
                                @if($item->product->category)
                                    <span class="badge bg-secondary mb-2">{{ $item->product->category->name }}</span>
                                @endif
                                @if($item->product->supplier)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-truck me-1"></i> {{ $item->product->supplier->name }}
                                    </small>
                                @endif
                                <p class="text-muted small mb-0 mt-2">{{ Str::limit($item->product->short_description ?? $item->product->description, 50) }}</p>
                            </div>
                            
                            <!-- Prix unitaire -->
                            <div class="col-md-2">
                                <strong class="text-primary">{{ number_format($item->product->price, 2) }} €</strong>
                                <small class="d-block text-muted">/ unité</small>
                            </div>
                            
                            <!-- Quantité -->
                            <div class="col-md-2">
                                <form action="{{ route('shop.cart.update', $item) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group input-group-sm">
                                        <input type="number" 
                                               name="quantity" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               max="{{ $item->product->quantity }}"
                                               class="form-control text-center"
                                               style="width: 60px;">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </form>
                                @if($item->quantity >= $item->product->quantity)
                                    <small class="text-danger d-block mt-1">
                                        <i class="fas fa-exclamation-circle"></i> Stock max
                                    </small>
                                @endif
                            </div>
                            
                            <!-- Total ligne et suppression -->
                            <div class="col-md-2 text-end">
                                <strong class="text-primary fs-5">{{ number_format($item->total, 2) }} €</strong>
                                <form action="{{ route('shop.cart.remove', $item) }}" method="POST" class="mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Retirer cet article du panier ?')">
                                        <i class="fas fa-trash-alt me-1"></i> Retirer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="{{ route('shop.products.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i> Continuer mes achats
                    </a>
                    
                    <form action="{{ route('shop.cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')">
                            <i class="fas fa-trash-alt me-2"></i> Vider le panier
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> Récapitulatif</h5>
                    </div>
                    <div class="card-body">
                        <!-- Détails du panier -->
                        <div class="mb-3">
                            <small class="text-muted">{{ $cartItems->count() }} article(s)</small>
                        </div>

                        <table class="table table-borderless">
                            <tr>
                                <td>Sous-total:</td>
                                <td class="text-end">{{ number_format($total, 2) }} €</td>
                            </tr>
                            <tr>
                                <td>Livraison:</td>
                                <td class="text-end">
                                    @if($total >= 50)
                                        <span class="text-success">Gratuite</span>
                                    @else
                                        <span>{{ number_format(5.99, 2) }} €</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="fw-bold fs-5">
                                <td>Total TTC:</td>
                                <td class="text-end text-primary">
                                    @php
                                        $shipping = $total >= 50 ? 0 : 5.99;
                                        $finalTotal = $total + $shipping;
                                    @endphp
                                    {{ number_format($finalTotal, 2) }} €
                                </td>
                            </tr>
                        </table>

                        @if($total < 50)
                        <div class="alert alert-info py-2 mb-3">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Plus que {{ number_format(50 - $total, 2) }} € pour la livraison gratuite !
                            </small>
                        </div>
                        @endif

                        @auth
                            <a href="{{ route('shop.checkout.index') }}" class="btn btn-success btn-lg w-100 mb-2">
                                <i class="fas fa-credit-card me-2"></i> Passer la commande
                            </a>
                            <small class="text-muted d-block text-center">
                                <i class="fas fa-lock me-1"></i> Paiement sécurisé
                            </small>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Vous devez être connecté pour passer commande.
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus me-2"></i> Créer un compte
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .cart-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .cart-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    }
    
    .input-group {
        max-width: 120px;
    }
    
    .input-group input {
        text-align: center;
        border-right: none;
    }
    
    .input-group button {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    /* Mode sombre */
    .dark .cart-item {
        background-color: #2d2d44;
        border-color: #4a4a6a;
    }
    
    .dark .cart-item .card-title {
        color: #e0e0e0;
    }
    
    .dark .cart-item .text-muted {
        color: #a0a0c0 !important;
    }
    
    .dark .bg-light {
        background-color: #35354a !important;
    }
    
    .dark .bg-light i {
        color: #a0a0c0 !important;
    }
    
    .dark .alert-info {
        background-color: #1e3a4a;
        border-color: #2a6a8b;
        color: #d1ecf1;
    }
</style>
@endpush

@push('scripts')
<script>
    // Confirmation avant suppression
    document.querySelectorAll('form[onsubmit]').forEach(form => {
        form.removeAttribute('onsubmit');
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
                e.preventDefault();
            }
        });
    });

    // Auto-submit quand on change la quantité (optionnel)
    document.querySelectorAll('input[name="quantity"]').forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endpush