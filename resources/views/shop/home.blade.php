@extends('shop.layouts.shop')

@section('title', __('messages.home_title'))

@section('content')
<div class="container py-4">
    <!-- Hero Section avec gradient personnalisé -->
    <div class="hero-section text-white p-5 rounded-3 mb-5" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); border-bottom: 3px solid #f7d44a;">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold" style="color: #f7d44a;">{{ __('messages.welcome') }}</h1>
                <p class="lead text-white-50">{{ __('messages.hero_subtitle') }}</p>
                <hr class="my-4" style="border-color: #f7d44a; opacity: 0.3;">
                <p class="mb-4"><i class="fas fa-truck text-gold me-2"></i> {{ __('messages.free_shipping') }}</p>
                <a href="{{ route('shop.products.index') }}" class="btn btn-gold btn-lg">
                    <i class="fas fa-paw me-2"></i> {{ __('messages.view_all_products') }}
                </a>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <img src="{{ asset('images/logo.png') }}" alt="Carioca & Coquelicot" style="max-width: 200px; opacity: 0.9;">
            </div>
        </div>
    </div>

    <!-- Catégories -->
    <h2 class="mb-4 section-title">{{ __('messages.categories') }}</h2>
    <div class="row mb-5">
        @php
            use App\Helpers\CategoryHelper;
        @endphp
        
        @foreach($categories as $category)
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100 category-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="category-icon-wrapper mb-3">
                        <i class="fas fa-{{ App\Helpers\CategoryHelper::getIcon($category) }} fa-3x text-gold"></i>
                    </div>
                    <h5 class="card-title text-dark fw-bold">{{ $category->translated_name }}</h5>
                    
                    <!-- Calcul du nombre total de produits (catégorie principale + sous-catégories) -->
                    @php
                        $totalProducts = $category->products_count ?? 0;
                        if ($category->children && $category->children->count() > 0) {
                            foreach($category->children as $child) {
                                $totalProducts += $child->products_count ?? 0;
                            }
                        }
                    @endphp
                    <p class="card-text text-muted">{{ $totalProducts }} {{ __('messages.products_count') }}</p>
                    
                    <!-- Afficher les sous-catégories si elles existent -->
                    @if($category->children && $category->children->isNotEmpty())
                    <div class="subcategories mt-2">
                        @foreach($category->children as $sub)
                            @php
                                $subCount = $sub->products_count ?? 0;
                            @endphp
                            <a href="{{ route('shop.products.index', ['category' => $sub->slug]) }}" 
                               class="badge bg-light-gold text-dark mr-1 mb-1 p-2 text-decoration-none">
                                {{ $sub->translated_name }} @if($subCount > 0)({{ $subCount }})@endif
                            </a>
                        @endforeach
                    </div>
                    @endif
                    
                    <a href="{{ route('shop.products.index', ['category' => $category->slug]) }}" 
                       class="btn btn-outline-gold btn-sm mt-3">
                        {{ __('messages.view_products') }}
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
            <div class="card bg-gradient-dark text-white border-0 shadow-lg">
                <div class="card-body text-center py-5">
                    <h3 class="mb-3 text-gold">{{ __('messages.join_community') }}</h3>
                    <p class="lead mb-4 text-white-50">{{ __('messages.join_benefits') }}</p>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="benefit-icon mb-2">
                                <i class="fas fa-truck fa-2x text-gold"></i>
                            </div>
                            <h5 class="text-white">{{ __('messages.order_tracking') }}</h5>
                            <p class="text-white-50">{{ __('messages.tracking_desc') }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="benefit-icon mb-2">
                                <i class="fas fa-history fa-2x text-gold"></i>
                            </div>
                            <h5 class="text-white">{{ __('messages.order_history') }}</h5>
                            <p class="text-white-50">{{ __('messages.history_desc') }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="benefit-icon mb-2">
                                <i class="fas fa-bolt fa-2x text-gold"></i>
                            </div>
                            <h5 class="text-white">{{ __('messages.quick_checkout') }}</h5>
                            <p class="text-white-50">{{ __('messages.quick_desc') }}</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('register') }}" class="btn btn-gold btn-lg me-2">
                            <i class="fas fa-user-plus me-2"></i> {{ __('messages.create_account') }}
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-gold btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i> {{ __('messages.login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Nouveautés -->
    <h2 class="mb-4 section-title">{{ __('messages.new_arrivals') }}</h2>
    <div class="row">
        @foreach($newProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm product-card border-0">
                <!-- Badge nouveau -->
                <span class="position-absolute top-0 end-0 badge bg-gold text-dark m-2">
                    <i class="fas fa-star me-1"></i> {{ __('messages.new') }}
                </span>
                
                <!-- Image du produit -->
                <div class="product-image-wrapper text-center bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                    @if($product->image)
                        @php
                            $imageName = basename($product->image);
                        @endphp
                        <img src="{{ asset('storage/products/' . $imageName) }}" 
                             alt="{{ $product->translated_name }}" 
                             class="img-fluid product-image"
                             style="max-width: 100%; max-height: 180px; width: auto; height: auto; object-fit: contain;"
                             onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\'fas fa-box fa-4x text-secondary\'></i>';">
                    @else
                        <i class="fas fa-box fa-4x text-secondary"></i>
                    @endif
                </div>
                
                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $product->translated_name }}</h5>
                    @if($product->category)
                        <span class="badge bg-light-gold text-dark mb-2">{{ $product->category->translated_name }}</span>
                    @endif
                    <p class="card-text text-muted small">{{ $product->translated_short_description ?? Str::limit($product->translated_description ?? $product->description, 50) }}</p>
                    <p class="card-text">
                        <strong class="text-gold fs-4">{{ number_format($product->price, 2) }} €</strong>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('shop.products.show', $product) }}" class="btn btn-outline-gold w-100">
                        {{ __('messages.view_details') }}
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Meilleures ventes -->
    <h2 class="mb-4 mt-5 section-title">{{ __('messages.best_sellers') }}</h2>
    <div class="row">
        @foreach($featuredProducts as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm product-card border-0">
                <!-- Badge stock limité -->
                @if($product->quantity <= $product->stock_threshold)
                    <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                        <i class="fas fa-exclamation-triangle me-1"></i> {{ __('messages.low_stock') }}
                    </span>
                @endif
                
                <!-- Image du produit -->
                <div class="product-image-wrapper text-center bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                    @if($product->image)
                        @php
                            $imageName = basename($product->image);
                        @endphp
                        <img src="{{ asset('storage/products/' . $imageName) }}" 
                             alt="{{ $product->translated_name }}" 
                             class="img-fluid product-image"
                             style="max-width: 100%; max-height: 180px; width: auto; height: auto; object-fit: contain;"
                             onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\'fas fa-box fa-4x text-secondary\'></i>';">
                    @else
                        <i class="fas fa-box fa-4x text-secondary"></i>
                    @endif
                </div>
                
                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $product->translated_name }}</h5>
                    @if($product->category)
                        <span class="badge bg-light-gold text-dark mb-2">{{ $product->category->translated_name }}</span>
                    @endif
                    <p class="card-text text-muted small">{{ $product->translated_short_description ?? Str::limit($product->translated_description ?? $product->description, 50) }}</p>
                    <p class="card-text">
                        <strong class="text-gold fs-4">{{ number_format($product->price, 2) }} €</strong>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('shop.products.show', $product) }}" class="btn btn-outline-gold w-100">
                        {{ __('messages.view_details') }}
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
    /* Couleurs du thème */
    :root {
        --primary-dark: #1a1a2e;
        --primary-medium: #16213e;
        --primary-light: #0f3460;
        --accent-gold: #f7d44a;
        --text-light: #e0e0e0;
    }

    /* Hero section */
    .hero-section {
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.05"><path d="M10,10 L90,10 L90,90 L10,90 Z" fill="none" stroke="%23f7d44a" stroke-width="2"/></svg>') repeat;
        pointer-events: none;
    }

    /* Titres de section */
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
    
    .dark .section-title {
        color: var(--text-light);
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
    .bg-gold {
        background-color: var(--accent-gold) !important;
    }
    
    .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.15) !important;
        color: var(--primary-dark) !important;
        font-weight: 500;
    }
    
    .dark .bg-light-gold {
        color: var(--text-light) !important;
    }

    /* Texte gold */
    .text-gold {
        color: var(--accent-gold) !important;
    }
    
    .text-white-50 {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    /* Gradient dark */
    .bg-gradient-dark {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 50%, var(--primary-light) 100%) !important;
    }

    /* Cartes de catégories */
    .category-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(247, 212, 74, 0.2) !important;
        border-bottom: 3px solid var(--accent-gold);
    }
    
    .category-icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(247, 212, 74, 0.1);
        transition: all 0.3s ease;
    }
    
    .category-card:hover .category-icon-wrapper {
        background: rgba(247, 212, 74, 0.2);
        transform: scale(1.1);
    }

    /* Cartes produits */
    .product-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        border-bottom: 3px solid var(--accent-gold);
    }
    
    .product-image-wrapper {
        overflow: hidden;
        position: relative;
    }
    
    .product-image {
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    /* Icônes de bénéfices */
    .benefit-icon {
        transition: all 0.3s ease;
    }
    
    .benefit-icon:hover {
        transform: scale(1.1);
    }

    /* Mode sombre */
    .dark .product-card {
        background-color: #2d2d44;
    }
    
    .dark .product-card .card-title {
        color: var(--text-light);
    }
    
    .dark .product-card .text-muted {
        color: #a0a0c0 !important;
    }
    
    .dark .category-card {
        background-color: #2d2d44;
    }
    
    .dark .category-card .card-title {
        color: var(--text-light);
    }
    
    .dark .category-card:hover {
        background-color: #35354a;
    }
    
    .dark .bg-light {
        background-color: #2d2d44 !important;
    }
    
    .dark .bg-light i,
    .dark .bg-light p {
        color: var(--text-light) !important;
    }
    
    /* Badge position */
    .badge {
        z-index: 10;
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
</style>
@endpush