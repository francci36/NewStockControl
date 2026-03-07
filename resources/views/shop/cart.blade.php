@extends('shop.layouts.shop')

@section('title', __('messages.cart_title'))

@section('content')
<div class="container py-4">
    <div class="section-header mb-4">
        <h1 class="section-title">{{ __('messages.my_cart') }}</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('stock_errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-exclamation-triangle fa-2x me-3 text-gold"></i>
                <h5 class="mb-0">{{ __('messages.stock_insufficient') }}</h5>
            </div>
            <ul class="mb-0 mt-2">
                @foreach(session('stock_errors') as $error)
                    <li class="mb-1">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="empty-state text-center py-5">
            <div class="empty-state-icon mb-4">
                <i class="fas fa-shopping-cart fa-5x text-gold"></i>
            </div>
            <h3 class="fw-bold">{{ __('messages.empty_cart') }}</h3>
            <p class="text-muted mb-4">{{ __('messages.empty_cart_message') }}</p>
            <a href="{{ route('shop.products.index') }}" class="btn btn-gold btn-lg">
                <i class="fas fa-paw me-2"></i> {{ __('messages.view_products') }}
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8">
                <!-- Liste des articles -->
                @foreach($cartItems as $item)
                <div class="card border-0 shadow-sm mb-3 cart-item">
                    <div class="card-body p-3">
                        <div class="row align-items-center g-3">
                            <!-- Image du produit -->
                            <div class="col-md-2 col-4">
                                <div class="product-image-wrapper">
                                    @if($item->product->image)
                                        @php
                                            $imagePath = str_contains($item->product->image, 'products/') 
                                                ? $item->product->image 
                                                : 'products/' . $item->product->image;
                                        @endphp
                                        <img src="{{ asset('storage/' . $imagePath) }}" 
                                             alt="{{ $item->product->translated_name }}" 
                                             class="img-fluid rounded product-image"
                                             style="max-height: 80px; width: auto; object-fit: contain;"
                                             onerror="this.onerror=null; this.parentNode.innerHTML='<div class=\'bg-light-gold text-center py-3 rounded\'><i class=\'fas fa-box fa-2x text-gold\'></i></div>';">
                                    @else
                                        <div class="bg-light-gold text-center py-3 rounded">
                                            <i class="fas fa-box fa-2x text-gold"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Infos produit -->
                            <div class="col-md-4 col-8">
                                <h5 class="card-title fw-bold mb-1">{{ $item->product->translated_name }}</h5>
                                @if($item->product->category)
                                    <span class="badge bg-light-gold text-dark mb-2">{{ $item->product->category->translated_name }}</span>
                                @endif
                                @if($item->product->supplier)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-truck me-1 text-gold"></i> {{ $item->product->supplier->name }}
                                    </small>
                                @endif
                                <p class="text-muted small mb-0 mt-2 d-none d-md-block">
                                    {{ $item->product->translated_short_description ?? Str::limit($item->product->translated_description ?? $item->product->description, 50) }}
                                </p>
                            </div>
                            
                            <!-- Prix unitaire -->
                            <div class="col-md-2 col-4 text-md-center">
                                <div class="price-wrapper">
                                    <strong class="text-gold">{{ number_format($item->product->price, 2) }} €</strong>
                                    <small class="d-block text-muted">{{ __('messages.per_unit') }}</small>
                                </div>
                            </div>
                            
                            <!-- Quantité -->
                            <div class="col-md-2 col-4">
                                <form action="{{ route('shop.cart.update', $item) }}" method="POST" class="quantity-form">
                                    @csrf
                                    @method('PATCH')
                                    <label class="form-label small text-muted">{{ __('messages.quantity') }}</label>
                                    <div class="input-group input-group-sm">
                                        <button type="button" class="btn btn-outline-gold quantity-btn" onclick="decrementQuantity(this)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               name="quantity" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               max="{{ $item->product->quantity }}"
                                               class="form-control text-center border-gold quantity-input"
                                               style="width: 50px;">
                                        <button type="button" class="btn btn-outline-gold quantity-btn" onclick="incrementQuantity(this)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </form>
                                @if($item->quantity >= $item->product->quantity)
                                    <small class="text-danger d-block mt-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ __('messages.max_stock') }}
                                    </small>
                                @endif
                            </div>
                            
                            <!-- Total ligne et suppression -->
                            <div class="col-md-2 col-4 text-md-end">
                                <div class="total-wrapper">
                                    <strong class="text-gold fs-5">{{ number_format($item->total, 2) }} €</strong>
                                    <form action="{{ route('shop.cart.remove', $item) }}" method="POST" class="mt-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger remove-btn" 
                                                onclick="return confirm('{{ __('messages.confirm_remove') }}')">
                                            <i class="fas fa-trash-alt me-1"></i> {{ __('messages.remove') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Actions du panier -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="{{ route('shop.products.index') }}" class="btn btn-outline-gold">
                        <i class="fas fa-arrow-left me-2"></i> {{ __('messages.continue_shopping') }}
                    </a>
                    
                    <form action="{{ route('shop.cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('{{ __('messages.confirm_clear_cart') }}')">
                            <i class="fas fa-trash-alt me-2"></i> {{ __('messages.clear_cart') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Résumé de la commande -->
                <div class="card border-0 shadow-lg summary-card sticky-top" style="top: 20px;">
                    <div class="card-header bg-gradient-dark text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2 text-gold"></i> {{ __('messages.summary') }}</h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Détails du panier -->
                        <div class="mb-3">
                            <small class="text-muted">{{ $cartItems->count() }} {{ __('messages.items') }}</small>
                        </div>

                        <div class="summary-details">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ __('messages.subtotal') }}:</span>
                                <span class="fw-bold">{{ number_format($total, 2) }} €</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ __('messages.shipping') }}:</span>
                                <span class="fw-bold">
                                    @if($total >= 50)
                                        <span class="text-success">{{ __('messages.free') }}</span>
                                    @else
                                        {{ number_format(5.99, 2) }} €
                                    @endif
                                </span>
                            </div>
                            
                            <hr class="border-gold-20">
                            
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold fs-5">{{ __('messages.total_ttc') }}:</span>
                                @php
                                    $shipping = $total >= 50 ? 0 : 5.99;
                                    $finalTotal = $total + $shipping;
                                @endphp
                                <span class="text-gold fw-bold fs-4">{{ number_format($finalTotal, 2) }} €</span>
                            </div>
                        </div>

                        @if($total < 50)
                        <div class="alert alert-gold py-2 mb-3">
                            <small class="d-flex align-items-center">
                                <i class="fas fa-truck text-gold me-2"></i>
                                {{ __('messages.free_shipping_remaining', ['amount' => number_format(50 - $total, 2)]) }}
                            </small>
                        </div>
                        @endif

                        @auth
                            <a href="{{ route('shop.checkout.index') }}" class="btn btn-gold btn-lg w-100 mb-2">
                                <i class="fas fa-credit-card me-2"></i> {{ __('messages.checkout') }}
                            </a>
                            <small class="text-muted d-block text-center">
                                <i class="fas fa-lock me-1 text-gold"></i> {{ __('messages.secure_payment') }}
                            </small>
                        @else
                            <div class="alert alert-gold text-center py-3 mb-3">
                                <i class="fas fa-exclamation-triangle text-gold me-2"></i>
                                {{ __('messages.login_to_checkout') }}
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-gold w-100 mb-2">
                                <i class="fas fa-sign-in-alt me-2"></i> {{ __('messages.login') }}
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-gold w-100">
                                <i class="fas fa-user-plus me-2"></i> {{ __('messages.create_account') }}
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
    /* Couleurs du thème */
    :root {
        --primary-dark: #1a1a2e;
        --primary-medium: #16213e;
        --primary-light: #0f3460;
        --accent-gold: #f7d44a;
        --text-light: #e0e0e0;
    }

    /* En-tête de section */
    .section-header {
        border-bottom: 2px solid var(--accent-gold);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    .section-title {
        color: var(--primary-dark);
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .dark .section-title {
        color: var(--text-light);
    }

    /* Gradient dark */
    .bg-gradient-dark {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 50%, var(--primary-light) 100%) !important;
    }

    /* Alertes gold */
    .alert-gold {
        background-color: rgba(247, 212, 74, 0.1);
        border: 1px solid var(--accent-gold);
        color: var(--primary-dark);
    }
    
    .dark .alert-gold {
        background-color: rgba(247, 212, 74, 0.15);
        color: var(--text-light);
    }

    /* Badges */
    .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.15) !important;
        color: var(--primary-dark) !important;
    }
    
    .dark .bg-light-gold {
        color: var(--text-light) !important;
    }

    /* Bordures */
    .border-gold {
        border-color: var(--accent-gold) !important;
    }
    
    .border-gold-20 {
        border-color: rgba(247, 212, 74, 0.2) !important;
    }

    /* Boutons gold */
    .btn-gold {
        background-color: var(--accent-gold);
        color: var(--primary-dark);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .btn-gold:hover {
        background-color: #e5c03a;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
    }
    
    .btn-outline-gold {
        background-color: transparent;
        color: var(--accent-gold);
        border: 2px solid var(--accent-gold);
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .btn-outline-gold:hover {
        background-color: var(--accent-gold);
        color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
    }

    /* Texte gold */
    .text-gold {
        color: var(--accent-gold) !important;
    }

    /* État vide */
    .empty-state {
        opacity: 0.8;
    }
    
    .empty-state-icon {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Cartes produit */
    .cart-item {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .cart-item:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 25px rgba(247, 212, 74, 0.15) !important;
        border-left: 4px solid var(--accent-gold);
    }
    
    .product-image-wrapper {
        overflow: hidden;
        border-radius: 8px;
    }
    
    .product-image {
        transition: transform 0.3s ease;
    }
    
    .cart-item:hover .product-image {
        transform: scale(1.05);
    }

    /* Contrôles de quantité */
    .quantity-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .quantity-input {
        max-width: 60px;
        padding: 0.25rem;
    }
    
    .quantity-input:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25);
    }

    /* Bouton de suppression */
    .remove-btn {
        font-size: 0.8rem;
        padding: 0.2rem 0.5rem;
    }

    /* Carte résumé */
    .summary-card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .summary-card:hover {
        box-shadow: 0 15px 30px rgba(247, 212, 74, 0.15) !important;
    }

    /* Prix */
    .price-wrapper, .total-wrapper {
        text-align: center;
    }

    /* Mode sombre */
    .dark .cart-item {
        background-color: #2d2d44;
        border-color: #4a4a6a;
    }
    
    .dark .cart-item .card-title {
        color: var(--text-light);
    }
    
    .dark .cart-item .text-muted {
        color: #a0a0c0 !important;
    }
    
    .dark .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.1) !important;
    }
    
    .dark .bg-light-gold i {
        color: var(--accent-gold) !important;
    }
    
    .dark .summary-card {
        background-color: #2d2d44;
    }
</style>
@endpush

@push('scripts')
<script>
    // Fonctions pour incrémenter/décrémenter la quantité
    function incrementQuantity(btn) {
        const input = btn.closest('.input-group').querySelector('.quantity-input');
        const max = parseInt(input.getAttribute('max')) || 999;
        let value = parseInt(input.value) || 1;
        if (value < max) {
            input.value = value + 1;
            input.dispatchEvent(new Event('change'));
        }
    }
    
    function decrementQuantity(btn) {
        const input = btn.closest('.input-group').querySelector('.quantity-input');
        let value = parseInt(input.value) || 1;
        if (value > 1) {
            input.value = value - 1;
            input.dispatchEvent(new Event('change'));
        }
    }

    // Auto-submit quand on change la quantité
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Confirmation avant suppression
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('{{ __('messages.confirm_remove') }}')) {
                e.preventDefault();
            }
        });
    });

    document.querySelector('form[action*="clear"]').addEventListener('submit', function(e) {
        if (!confirm('{{ __('messages.confirm_clear_cart') }}')) {
            e.preventDefault();
        }
    });
</script>
@endpush