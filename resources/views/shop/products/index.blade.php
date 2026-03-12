@extends('shop.layouts.shop')

@section('title', __('messages.products_title'))

@section('content')
<div class="container py-4">
    <!-- En-tête de la page -->
    <div class="section-header mb-4">
        <h1 class="section-title">{{ __('messages.products') }}</h1>
        <p class="text-muted">{{ $products->total() }} {{ __('messages.products_found') }}</p>
    </div>

    <!-- Messages -->
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

    <!-- Filtres et recherche -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-gradient-dark text-white py-3">
            <h6 class="mb-0"><i class="fas fa-filter me-2 text-gold"></i> {{ __('messages.filter_search') }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('shop.products.index') }}" method="GET">
                <div class="row g-3">
                    <!-- Recherche -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-uppercase text-gold">{{ __('messages.search') }}</label>
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control border-gold" 
                                   placeholder="{{ __('messages.search_placeholder') }}" 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-gold">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Filtre par catégorie -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-uppercase text-gold">{{ __('messages.filter_by_category') }}</label>
                        <select name="category" class="form-select border-gold" onchange="this.form.submit()">
                            <option value="">{{ __('messages.all_categories') }}</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->slug }}" 
                                    {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->translated_name }} ({{ $category->products_count ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre par vendeur/fournisseur -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-uppercase text-gold">{{ __('messages.filter_by_vendor') }}</label>
                        <select name="vendor" class="form-select border-gold" onchange="this.form.submit()">
                            <option value="">{{ __('messages.all_vendors') }}</option>
                            @foreach($vendors ?? [] as $vendor)
                                <option value="{{ $vendor->id }}" 
                                    {{ request('vendor') == $vendor->id ? 'selected' : '' }}>
                                    🏪 {{ $vendor->shop_name }}
                                </option>
                            @endforeach
                            @foreach($suppliers ?? [] as $supplier)
                                <option value="supplier_{{ $supplier->id }}" 
                                    {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                    🚚 {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre par prix -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-uppercase text-gold">{{ __('messages.price') }}</label>
                        <div class="d-flex gap-2">
                            <input type="number" 
                                   name="min_price" 
                                   class="form-control border-gold" 
                                   placeholder="{{ __('messages.min') }}" 
                                   value="{{ request('min_price') }}"
                                   min="0"
                                   step="1">
                            <input type="number" 
                                   name="max_price" 
                                   class="form-control border-gold" 
                                   placeholder="{{ __('messages.max') }}" 
                                   value="{{ request('max_price') }}"
                                   min="0"
                                   step="1">
                        </div>
                    </div>

                    <!-- Filtre par stock et tri -->
                    <div class="col-md-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-gold">{{ __('messages.filter_by_stock') }}</label>
                                <select name="stock" class="form-select border-gold" onchange="this.form.submit()">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>
                                        {{ __('messages.low_stock') }}
                                    </option>
                                    <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>
                                        {{ __('messages.in_stock_only') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-gold">{{ __('messages.sort_by') }}</label>
                                <select name="sort" class="form-select border-gold" onchange="this.form.submit()">
                                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                                        {{ __('messages.latest') }}
                                    </option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                        {{ __('messages.price_asc') }}
                                    </option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                        {{ __('messages.price_desc') }}
                                    </option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                        {{ __('messages.name_asc') }}
                                    </option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                        {{ __('messages.name_desc') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                @if(request()->anyFilled(['search', 'category', 'vendor', 'supplier', 'min_price', 'max_price', 'stock']))
                <div class="row mt-3">
                    <div class="col-12">
                        <hr class="border-gold-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-filter me-2 text-gold"></i>
                                <span class="fw-bold">{{ __('messages.active_filters') }}:</span>
                                @foreach(request()->all() as $key => $value)
                                    @if($value && !in_array($key, ['page', 'sort']))
                                        <span class="badge bg-gold text-dark me-1">
                                            {{ $key }}: {{ $value }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            <a href="{{ route('shop.products.index') }}" class="btn btn-outline-gold btn-sm">
                                <i class="fas fa-times me-1"></i> {{ __('messages.clear_filters') }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Grille de produits -->
    <div class="row">
        @forelse($products as $product)
        @php
            // Fonction helper pour nettoyer les traductions (définie UNE SEULE fois avant la boucle)
            // Mais comme on ne peut pas définir de fonction dans une boucle, on utilise un simple if
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
        
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm product-card border-0">
                <!-- Badges -->
                @if($product->quantity <= $product->stock_threshold && $product->quantity > 0)
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
                             alt="{{ $productName }}" 
                             class="img-fluid product-image"
                             style="max-width: 100%; max-height: 180px; width: auto; height: auto; object-fit: contain;"
                             onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\'fas fa-box fa-4x text-secondary\'></i>';">
                    @else
                        <i class="fas fa-box fa-4x text-secondary"></i>
                    @endif
                </div>
                
                <div class="card-body">
                    <!-- Nom et catégorie -->
                    <h5 class="card-title fw-bold">{{ $productName }}</h5>
                    @if($categoryName)
                        <span class="badge bg-light-gold text-dark mb-2">{{ $categoryName }}</span>
                    @endif
                    
                    <!-- Description courte avec fallback -->
                    <p class="card-text text-muted small">
                        @if(!empty($shortDesc) && $shortDesc !== '[]' && $shortDesc !== 'null')
                            {{ $shortDesc }}
                        @elseif(!empty($fullDesc) && $fullDesc !== '[]' && $fullDesc !== 'null')
                            {{ Str::limit($fullDesc, 50) }}
                        @endif
                    </p>
                    
                    <!-- Vendeur / Fournisseur -->
                    <div class="mb-2">
                        @if($product->vendor)
                            <small class="text-success">
                                <i class="fas fa-store me-1"></i> {{ __('messages.sold_by') }}: {{ $product->vendor->shop_name }}
                            </small>
                        @elseif($product->supplier)
                            <small class="text-info">
                                <i class="fas fa-truck me-1"></i> {{ __('messages.supplier') }}: {{ $product->supplier->name }}
                            </small>
                        @endif
                    </div>
                    
                    <!-- Prix -->
                    <p class="card-text">
                        <strong class="text-gold fs-4">{{ number_format($product->price, 2) }} €</strong>
                    </p>
                    
                    <!-- Stock -->
                    <p class="card-text">
                        @php
                            $stockClass = $product->quantity > 5 ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger');
                            $stockIcon = $product->quantity > 5 ? 'check-circle' : ($product->quantity > 0 ? 'exclamation-circle' : 'times-circle');
                        @endphp
                        <small class="text-{{ $stockClass }}">
                            <i class="fas fa-{{ $stockIcon }} me-1"></i>
                            @if($product->quantity > 0)
                                {{ $product->quantity }} {{ __('messages.in_stock') }}
                            @else
                                {{ __('messages.out_of_stock') }}
                            @endif
                        </small>
                    </p>
                </div>
                
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="{{ route('shop.products.show', $product) }}" 
                       class="btn btn-outline-gold w-100">
                        <i class="fas fa-eye me-2"></i> {{ __('messages.view_details') }}
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-box-open fa-5x text-gold"></i>
                </div>
                <h3 class="fw-bold">{{ __('messages.no_products_found') }}</h3>
                <p class="text-muted mb-4">{{ __('messages.try_again') }}</p>
                <a href="{{ route('shop.products.index') }}" class="btn btn-gold btn-lg">
                    <i class="fas fa-redo-alt me-2"></i> {{ __('messages.reset_filters') }}
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if($products->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if($page == $products->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if($products->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif

    <!-- Résumé -->
    <div class="row mt-3">
        <div class="col-12">
            <p class="text-center text-muted small">
                <i class="fas fa-box me-1 text-gold" style="font-size: 0.8rem;"></i>
                <span>{{ __('messages.showing') }} {{ $products->firstItem() ?? 0 }} {{ __('messages.to') }} {{ $products->lastItem() ?? 0 }} {{ __('messages.of') }} {{ $products->total() }} {{ __('messages.results') }}</span>
                @if($products->total() > 0)
                    <span class="mx-2" style="color: rgba(247, 212, 74, 0.3);">|</span>
                    <span>{{ __('messages.page') }} {{ $products->currentPage() }}/{{ $products->lastPage() }}</span>
                @endif
            </p>
        </div>
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

    /* Bordures gold */
    .border-gold {
        border-color: var(--accent-gold) !important;
    }
    
    .border-gold:focus {
        box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25);
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

    /* Cartes produits */
    .product-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(247, 212, 74, 0.15) !important;
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

    /* Pagination minimaliste */
    .pagination {
        gap: 5px;
    }

    .pagination .page-link {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid rgba(247, 212, 74, 0.2);
        color: var(--primary-dark);
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .pagination .page-link i {
        font-size: 0.7rem;
    }

    .pagination .page-link:hover {
        background-color: rgba(247, 212, 74, 0.1);
        border-color: var(--accent-gold);
        color: var(--accent-gold);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--accent-gold);
        border-color: var(--accent-gold);
        color: var(--primary-dark);
        font-weight: 600;
    }

    .pagination .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: rgba(247, 212, 74, 0.1);
        color: #adb5bd;
        opacity: 0.6;
    }

    /* Mode sombre */
    .dark .pagination .page-link {
        background-color: #2d2d44;
        color: var(--text-light);
        border-color: rgba(247, 212, 74, 0.15);
    }

    .dark .pagination .page-item.disabled .page-link {
        background-color: #2d2d44;
        border-color: rgba(247, 212, 74, 0.1);
        color: #6c757d;
    }

    /* Badge position */
    .badge {
        z-index: 10;
        padding: 0.5em 0.8em;
        font-weight: 500;
    }

    /* Formulaires */
    .form-control:focus, .form-select:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25);
    }
</style>
@endpush