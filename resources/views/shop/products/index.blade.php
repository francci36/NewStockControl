{{-- resources/views/shop/products/index.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Nos produits - Ma Boutique')

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
        <div class="col-md-8">
            <form action="{{ route('shop.products.index') }}" method="GET" class="d-flex">
                <input type="text" 
                       name="search" 
                       class="form-control me-2" 
                       placeholder="Rechercher un produit..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </form>
        </div>
        <div class="col-md-4">
            <select name="sort" class="form-select" onchange="window.location.href = this.value">
                <option value="{{ route('shop.products.index', ['sort' => 'latest']) }}" 
                    {{ request('sort') == 'latest' ? 'selected' : '' }}>
                    Plus récents
                </option>
                <option value="{{ route('shop.products.index', ['sort' => 'price_asc']) }}" 
                    {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                    Prix croissant
                </option>
                <option value="{{ route('shop.products.index', ['sort' => 'price_desc']) }}" 
                    {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                    Prix décroissant
                </option>
                <option value="{{ route('shop.products.index', ['sort' => 'name_asc']) }}" 
                    {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                    Nom A-Z
                </option>
            </select>
        </div>
    </div>

    <!-- Grille de produits -->
    <div class="row">
        @forelse($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         class="card-img-top" 
                         alt="{{ $product->name }}" 
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="bg-light text-center py-5">
                        <i class="fas fa-box fa-4x text-secondary"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted">
                        {{ Str::limit($product->description, 50) }}
                    </p>
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
            <div class="alert alert-info text-center py-5">
                <i class="fas fa-box-open fa-4x mb-3"></i>
                <h3>Aucun produit trouvé</h3>
                <p>Essayez de modifier vos critères de recherche.</p>
                <a href="{{ route('shop.products.index') }}" class="btn btn-primary">
                    Voir tous les produits
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection