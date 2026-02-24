@extends('shop.layouts.shop')

@section('title', 'Nos produits - Ma Boutique Animalière')

@section('content')
<div class="container">
    <h1 class="mb-4">Nos produits</h1>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres et recherche -->
    <div class="row mb-4">
        <div class="col-md-12">
            <form action="{{ route('shop.products.index') }}" method="GET" class="row g-3">
                <!-- Recherche -->
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Rechercher un produit..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Filtre par catégorie -->
                <div class="col-md-2">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes catégories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->slug }}" 
                                {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->products_count ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre par vendeur/fournisseur -->
                <div class="col-md-2">
                    <select name="vendor" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les vendeurs</option>
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

                <!-- Filtre par prix min -->
                <div class="col-md-1">
                    <input type="number" 
                           name="min_price" 
                           class="form-control" 
                           placeholder="Min €" 
                           value="{{ request('min_price') }}"
                           min="0"
                           step="1">
                </div>

                <!-- Filtre par prix max -->
                <div class="col-md-1">
                    <input type="number" 
                           name="max_price" 
                           class="form-control" 
                           placeholder="Max €" 
                           value="{{ request('max_price') }}"
                           min="0"
                           step="1">
                </div>

                <!-- Filtre par stock -->
                <div class="col-md-1">
                    <select name="stock" class="form-select" onchange="this.form.submit()">
                        <option value="">Stock</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>
                            Stock bas
                        </option>
                    </select>
                </div>

                <!-- Tri -->
                <div class="col-md-2">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                            Plus récents
                        </option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                            Prix croissant
                        </option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                            Prix décroissant
                        </option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                            Nom A-Z
                        </option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                            Nom Z-A
                        </option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats de recherche -->
    @if(request()->anyFilled(['search', 'category', 'vendor', 'min_price', 'max_price', 'stock']))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-filter me-2"></i>
                    Filtres actifs : 
                    @foreach(request()->all() as $key => $value)
                        @if($value && !in_array($key, ['page', 'sort']))
                            <span class="badge bg-primary me-1">{{ $key }}: {{ $value }}</span>
                        @endif
                    @endforeach
                </span>
                <a href="{{ route('shop.products.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times"></i> Effacer les filtres
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Grille de produits -->
    <div class="row">
        @forelse($products as $product)
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
                    <!-- Nom et catégorie -->
                    <h5 class="card-title">{{ $product->name }}</h5>
                    @if($product->category)
                        <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                    @endif
                    
                    <!-- Description courte -->
                    <p class="card-text text-muted small">
                        {{ $product->short_description ?? Str::limit($product->description, 50) }}
                    </p>
                    
                    <!-- Vendeur / Fournisseur -->
                    <div class="mb-2">
                        @if($product->vendor)
                            <small class="text-success">
                                <i class="fas fa-store me-1"></i> Vendu par: {{ $product->vendor->shop_name }}
                            </small>
                        @elseif($product->supplier)
                            <small class="text-info">
                                <i class="fas fa-truck me-1"></i> Fournisseur: {{ $product->supplier->name }}
                            </small>
                        @endif
                    </div>
                    
                    <!-- Prix -->
                    <p class="card-text h5">
                        <strong class="text-primary">{{ number_format($product->price, 2) }} €</strong>
                    </p>
                    
                    <!-- Stock -->
                    <p class="card-text">
                        <small class="text-{{ $product->quantity > 5 ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger') }}">
                            <i class="fas fa-{{ $product->quantity > 5 ? 'check-circle' : ($product->quantity > 0 ? 'exclamation-circle' : 'times-circle') }} me-1"></i>
                            {{ $product->quantity > 0 ? $product->quantity . ' en stock' : 'Rupture' }}
                        </small>
                    </p>
                </div>
                
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('shop.products.show', $product) }}" 
                       class="btn btn-outline-primary w-100">
                        <i class="fas fa-eye me-2"></i> Voir détails
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-box-open fa-4x mb-3"></i>
                <h3>Aucun produit trouvé</h3>
                <p class="mb-4">Essayez de modifier vos critères de recherche.</p>
                <a href="{{ route('shop.products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-redo-alt me-2"></i> Réinitialiser les filtres
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination avec flèches corrigées -->
    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <style>
            .pagination svg {
                width: 18px !important;
                height: 18px !important;
            }
            .pagination .page-link {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0.5rem 0.75rem;
            }
        </style>
        {{ $products->appends(request()->except('page'))->links() }}
    </div>
    @endif

    <!-- Résumé -->
    <div class="row mt-4">
        <div class="col-12">
            <p class="text-muted text-center">
                {{ $products->total() }} produit(s) trouvé(s)
                @if($products->total() > 0)
                    - Page {{ $products->currentPage() }} sur {{ $products->lastPage() }}
                @endif
            </p>
        </div>
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
    
    .badge.bg-danger {
        z-index: 10;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.8; }
        100% { opacity: 1; }
    }
    
    .form-select, .form-control {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.2s;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,0.25);
    }
    
    /* Correction globale des flèches de pagination */
    .pagination svg {
        width: 18px;
        height: 18px;
    }
    
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Pour les flèches spécifiques de Laravel */
    .pagination .page-item .page-link svg,
    .pagination a svg,
    .pagination span svg {
        width: 16px;
        height: 16px;
    }
    
    /* Pour Bootstrap pagination */
    .pagination .page-link .w-5.h-5 {
        width: 16px !important;
        height: 16px !important;
    }
    
    /* Mode sombre */
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
    
    .dark .alert-info {
        background-color: #1e3a4a;
        border-color: #2a6a8b;
        color: #d1ecf1;
    }
    
    /* Correction pour le mode sombre */
    .dark .pagination .page-link {
        background-color: #2d2d44;
        border-color: #4a4a6a;
        color: #e0e0e0;
    }
    
    .dark .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .dark .pagination svg {
        color: #e0e0e0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const autoSubmitSelects = document.querySelectorAll('select[onchange]');
        autoSubmitSelects.forEach(select => {
            select.removeAttribute('onchange');
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    });
</script>
@endpush