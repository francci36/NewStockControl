@extends('shop.layouts.shop')

@section('title', $product->translated_name . ' - ' . __('messages.shop_name'))

@section('content')
@php
    // Fonction helper pour nettoyer les traductions qui pourraient être du JSON
    $productName = $product->translated_name;
    if (is_string($productName) && str_starts_with($productName, '{') && str_ends_with($productName, '}')) {
        $decoded = json_decode($productName, true);
        if (is_array($decoded)) {
            $productName = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $productName;
        }
    }
    
    $categoryName = null;
    if ($product->category) {
        $categoryName = $product->category->translated_name;
        if (is_string($categoryName) && str_starts_with($categoryName, '{') && str_ends_with($categoryName, '}')) {
            $decoded = json_decode($categoryName, true);
            if (is_array($decoded)) {
                $categoryName = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $categoryName;
            }
        }
    }
    
    $shortDesc = $product->translated_short_description ?? '';
    if (is_string($shortDesc) && str_starts_with($shortDesc, '{') && str_ends_with($shortDesc, '}')) {
        $decoded = json_decode($shortDesc, true);
        if (is_array($decoded)) {
            $shortDesc = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $shortDesc;
        }
    }
    
    $fullDesc = $product->translated_description ?? '';
    if (is_string($fullDesc) && str_starts_with($fullDesc, '{') && str_ends_with($fullDesc, '}')) {
        $decoded = json_decode($fullDesc, true);
        if (is_array($decoded)) {
            $fullDesc = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $fullDesc;
        }
    }
@endphp

<div class="container py-4">
    <!-- Fil d'Ariane -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light-gold p-3 rounded-3">
            <li class="breadcrumb-item">
                <a href="{{ route('shop.home') }}" class="text-decoration-none text-gold">
                    <i class="fas fa-home me-1"></i> {{ __('messages.home') }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('shop.products.index') }}" class="text-decoration-none text-gold">
                    <i class="fas fa-box me-1"></i> {{ __('messages.products') }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $productName }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Image du produit -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-lg product-image-card">
                <!-- Badge stock limité -->
                @if($product->quantity <= $product->stock_threshold && $product->quantity > 0)
                    <span class="position-absolute top-0 start-0 badge bg-danger m-3" style="z-index: 10;">
                        <i class="fas fa-exclamation-triangle me-1"></i> {{ __('messages.low_stock') }}
                    </span>
                @endif
                
                <!-- Image du produit -->
                <div class="product-image-wrapper text-center p-4">
                    @if($product->image)
                        @php
                            $imageName = basename($product->image);
                        @endphp
                        <img src="{{ asset('storage/products/' . $imageName) }}" 
                             alt="{{ $productName }}" 
                             class="img-fluid product-main-image"
                             style="max-height: 400px; width: auto; object-fit: contain;"
                             onerror="this.onerror=null; this.parentNode.innerHTML='<div class=\'text-center py-5\'><i class=\'fas fa-box fa-5x text-gold\'></i><p class=\'mt-3 text-muted\'>{{ __('messages.image_not_available') }}</p></div>';">
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-box fa-5x text-gold"></i>
                            <p class="mt-3 text-muted">{{ __('messages.no_image') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Détails du produit -->
        <div class="col-md-6">
            <div class="card border-0 shadow-lg product-details-card">
                <div class="card-body p-4">
                    <!-- Catégorie et badges -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if($product->category)
                            <span class="badge bg-light-gold text-dark px-3 py-2">
                                <i class="fas fa-tag me-1 text-gold"></i> {{ $categoryName }}
                            </span>
                        @endif
                        
                        <!-- Vendeur ou Fournisseur -->
                        @if($product->vendor)
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-store me-1"></i> {{ __('messages.sold_by') }}: {{ $product->vendor->shop_name }}
                            </span>
                        @elseif($product->supplier)
                            <span class="badge bg-info px-3 py-2">
                                <i class="fas fa-truck me-1"></i> {{ __('messages.supplier') }}: {{ $product->supplier->name }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- Titre -->
                    <h1 class="card-title h2 mb-3 fw-bold">{{ $productName }}</h1>
                    
                    <!-- Prix -->
                    <div class="mb-4">
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="text-gold fs-1 fw-bold">{{ number_format($product->price, 2) }} €</span>
                            @if($product->quantity > 0)
                                <small class="text-muted">{{ __('messages.tax_included') }}</small>
                            @endif
                        </div>
                    </div>

                    <!-- Statut stock -->
                    <div class="mb-4">
                        @if($product->quantity > 10)
                            <div class="alert alert-success border-0 py-3">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>{{ __('messages.in_stock') }}</strong> - {{ $product->quantity }} {{ __('messages.units_available') }}
                            </div>
                        @elseif($product->quantity > 0)
                            <div class="alert alert-warning border-0 py-3">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>{{ __('messages.low_stock') }}</strong> - {{ $product->quantity }} {{ __('messages.units_available') }}
                            </div>
                        @else
                            <div class="alert alert-danger border-0 py-3">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>{{ __('messages.out_of_stock') }}</strong>
                            </div>
                        @endif
                    </div>

                    <!-- Description courte -->
                    @if(!empty($shortDesc) && $shortDesc !== '[]' && $shortDesc !== 'null')
                    <div class="mb-4">
                        <h5 class="fw-bold mb-2">{{ __('messages.short_description') }}</h5>
                        <p class="text-muted fst-italic border-start border-gold ps-3 py-2">
                            {{ $shortDesc }}
                        </p>
                    </div>
                    @endif

                    <!-- Description complète -->
                    @if(!empty($fullDesc) && $fullDesc !== '[]' && $fullDesc !== 'null')
                    <div class="mb-4">
                        <h5 class="fw-bold mb-2">{{ __('messages.full_description') }}</h5>
                        <div class="text-muted description-content">
                            {{ $fullDesc }}
                        </div>
                    </div>
                    @endif

                    <!-- Formulaire d'achat -->
                    @if($product->quantity > 0)
                        <form action="{{ route('shop.cart.add') }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="row g-3 align-items-end">
                                <div class="col-auto">
                                    <label for="quantity" class="form-label fw-bold">{{ __('messages.quantity') }}</label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-gold" onclick="decrementQuantity()">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               name="quantity" 
                                               id="quantity" 
                                               class="form-control form-control-lg text-center border-gold" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $product->quantity }}"
                                               style="width: 80px;">
                                        <button type="button" class="btn btn-outline-gold" onclick="incrementQuantity()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-auto flex-grow-1">
                                    <button type="submit" class="btn btn-gold btn-lg w-100">
                                        <i class="fas fa-cart-plus me-2"></i> {{ __('messages.add_to_cart') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                    <!-- Caractéristiques -->
                    <div class="mt-4 p-4 bg-light-gold rounded-3">
                        <h5 class="fw-bold mb-3"><i class="fas fa-info-circle text-gold me-2"></i> {{ __('messages.features') }}</h5>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-hashtag text-gold"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">{{ __('messages.reference') }}</small>
                                        <strong>#{{ $product->id }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-tag text-gold"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">{{ __('messages.category') }}</small>
                                        <strong>{{ $categoryName ?? __('messages.uncategorized') }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-building text-gold"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">{{ __('messages.sold_by') }}</small>
                                        <strong>
                                            @if($product->vendor)
                                                {{ $product->vendor->shop_name }}
                                            @elseif($product->supplier)
                                                {{ $product->supplier->name }}
                                            @else
                                                {{ __('messages.not_specified') }}
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-calendar-plus text-gold"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">{{ __('messages.added_on') }}</small>
                                        <strong>{{ $product->created_at->format('d/m/Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Partager -->
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">{{ __('messages.share') }}</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               target="_blank" 
                               class="btn btn-outline-gold rounded-circle social-btn"
                               title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($productName) }}" 
                               target="_blank" 
                               class="btn btn-outline-gold rounded-circle social-btn"
                               title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($productName . ' - ' . request()->url()) }}" 
                               target="_blank" 
                               class="btn btn-outline-gold rounded-circle social-btn"
                               title="WhatsApp">
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
        <h3 class="section-title mb-4">{{ __('messages.similar_products') }}</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
            @php
                $relatedProductName = $relatedProduct->translated_name;
                if (is_string($relatedProductName) && str_starts_with($relatedProductName, '{') && str_ends_with($relatedProductName, '}')) {
                    $decoded = json_decode($relatedProductName, true);
                    if (is_array($decoded)) {
                        $relatedProductName = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $relatedProductName;
                    }
                }
                
                $relatedCategoryName = null;
                if ($relatedProduct->category) {
                    $relatedCategoryName = $relatedProduct->category->translated_name;
                    if (is_string($relatedCategoryName) && str_starts_with($relatedCategoryName, '{') && str_ends_with($relatedCategoryName, '}')) {
                        $decoded = json_decode($relatedCategoryName, true);
                        if (is_array($decoded)) {
                            $relatedCategoryName = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $relatedCategoryName;
                        }
                    }
                }
                
                $relatedShortDesc = $relatedProduct->translated_short_description ?? '';
                if (is_string($relatedShortDesc) && str_starts_with($relatedShortDesc, '{') && str_ends_with($relatedShortDesc, '}')) {
                    $decoded = json_decode($relatedShortDesc, true);
                    if (is_array($decoded)) {
                        $relatedShortDesc = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $relatedShortDesc;
                    }
                }
                
                $relatedFullDesc = $relatedProduct->translated_description ?? '';
                if (is_string($relatedFullDesc) && str_starts_with($relatedFullDesc, '{') && str_ends_with($relatedFullDesc, '}')) {
                    $decoded = json_decode($relatedFullDesc, true);
                    if (is_array($decoded)) {
                        $relatedFullDesc = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $relatedFullDesc;
                    }
                }
            @endphp
            
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <!-- Image du produit similaire -->
                    <div class="product-image-wrapper text-center bg-light p-3" style="height: 180px;">
                        @if($relatedProduct->image)
                            @php
                                $imageName = basename($relatedProduct->image);
                            @endphp
                            <img src="{{ asset('storage/products/' . $imageName) }}" 
                                 alt="{{ $relatedProductName }}" 
                                 class="img-fluid product-image"
                                 style="max-height: 140px; width: auto; object-fit: contain;"
                                 onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\'fas fa-box fa-3x text-gold\'></i>';">
                        @else
                            <i class="fas fa-box fa-3x text-gold"></i>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $relatedProductName }}</h5>
                        @if($relatedCategoryName)
                            <span class="badge bg-light-gold text-dark mb-2">{{ $relatedCategoryName }}</span>
                        @endif
                        <p class="card-text text-muted small">
                            @if(!empty($relatedShortDesc) && $relatedShortDesc !== '[]' && $relatedShortDesc !== 'null')
                                {{ $relatedShortDesc }}
                            @elseif(!empty($relatedFullDesc) && $relatedFullDesc !== '[]' && $relatedFullDesc !== 'null')
                                {{ Str::limit($relatedFullDesc, 50) }}
                            @endif
                        </p>
                        <p class="card-text">
                            <strong class="text-gold">{{ number_format($relatedProduct->price, 2) }} €</strong>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <a href="{{ route('shop.products.show', $relatedProduct) }}" class="btn btn-outline-gold w-100 btn-sm">
                            {{ __('messages.view_details') }}
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
    /* Couleurs du thème */
    :root {
        --primary-dark: #1a1a2e;
        --primary-medium: #16213e;
        --primary-light: #0f3460;
        --accent-gold: #f7d44a;
        --text-light: #e0e0e0;
    }

    /* Fil d'Ariane */
    .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.1) !important;
    }
    
    .breadcrumb {
        margin-bottom: 0;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: var(--accent-gold);
    }

    /* Cartes */
    .product-image-card, .product-details-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .product-image-card:hover, .product-details-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(247, 212, 74, 0.15) !important;
    }

    /* Image produit */
    .product-main-image {
        transition: transform 0.5s ease;
    }
    
    .product-main-image:hover {
        transform: scale(1.05);
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

    /* Badges */
    .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.15) !important;
        color: var(--primary-dark) !important;
    }
    
    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
        font-size: 0.85rem;
    }

    /* Texte gold */
    .text-gold {
        color: var(--accent-gold) !important;
    }

    /* Bordures */
    .border-gold {
        border-color: var(--accent-gold) !important;
    }

    /* Boutons sociaux */
    .social-btn {
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    /* Description */
    .description-content {
        line-height: 1.8;
    }

    /* Icônes de caractéristiques */
    .feature-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background-color: rgba(247, 212, 74, 0.15);
    }

    /* Cartes produits similaires */
    .product-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(247, 212, 74, 0.15) !important;
        border-bottom: 3px solid var(--accent-gold);
    }
    
    .product-image-wrapper {
        overflow: hidden;
    }
    
    .product-image {
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    /* Section title */
    .section-title {
        position: relative;
        padding-bottom: 10px;
        color: var(--primary-dark);
        font-weight: 700;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: var(--accent-gold);
    }

    /* Mode sombre */
    .dark .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.1) !important;
        color: var(--text-light) !important;
    }
    
    .dark .bg-light {
        background-color: #2d2d44 !important;
    }
    
    .dark .card {
        background-color: #2d2d44;
        border-color: #4a4a6a;
    }
    
    .dark .card-title,
    .dark .h1, .dark .h2, .dark .h3, .dark .h4, .dark .h5, .dark .h6 {
        color: var(--text-light);
    }
    
    .dark .text-muted {
        color: #a0a0c0 !important;
    }
    
    .dark .bg-white {
        background-color: #2d2d44 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Fonctions pour incrémenter/décrémenter la quantité
    function incrementQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.getAttribute('max')) || 999;
        let value = parseInt(input.value) || 1;
        if (value < max) {
            input.value = value + 1;
        }
    }
    
    function decrementQuantity() {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value) || 1;
        if (value > 1) {
            input.value = value - 1;
        }
    }
</script>
@endpush