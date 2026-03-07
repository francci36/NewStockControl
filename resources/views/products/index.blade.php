@extends('layouts.app')

@section('title', 'Gestion des produits')

@section('content')
<div class="container-fluid py-3 py-md-6">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0">
                <i class="fas fa-box mr-2"></i>
                Gestion des produits
            </h5>
            <a href="{{ route('admin.products.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus mr-2"></i> Ajouter
            </a>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Stats rapides -->
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $products->total() }}</h3>
                            <p>Total produits</p>
                        </div>
                        <div class="icon"><i class="fas fa-box"></i></div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($products->avg('price') ?? 0, 2) }} €</h3>
                            <p>Prix moyen</p>
                        </div>
                        <div class="icon"><i class="fas fa-tag"></i></div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mx-auto">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{{ $products->groupBy('supplier_id')->count() + $products->groupBy('vendor_id')->count() }}</h3>
                            <p>Fournisseurs/Vendeurs</p>
                        </div>
                        <div class="icon"><i class="fas fa-truck"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTRES AVEC RECHERCHE EN TEMPS RÉEL -->
        <div class="card-body border-bottom">
            <div class="row g-3">
                <!-- Barre de recherche en temps réel -->
                <div class="col-md-6">
                    <label for="searchInput" class="form-label fw-bold text-gold">
                        <i class="fas fa-search mr-2"></i>Recherche instantanée
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-gold"></i>
                        </span>
                        <input type="text" 
                               id="searchInput" 
                               class="form-control" 
                               placeholder="Tapez pour rechercher (nom, SKU, description)..." 
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <span class="input-group-text bg-light" id="searchSpinner" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Les résultats s'affichent automatiquement pendant que vous tapez
                    </small>
                </div>

                <div class="col-md-3">
                    <label for="supplierFilter" class="form-label fw-bold">
                        <i class="fas fa-truck mr-2"></i>Fournisseur / Vendeur
                    </label>
                    <select id="supplierFilter" class="form-control">
                        <option value="">Tous les fournisseurs et vendeurs</option>
                        
                        <!-- Fournisseurs classiques -->
                        @foreach($suppliers ?? [] as $supplier)
                            <option value="supplier_{{ $supplier->id }}" {{ request('supplier') == 'supplier_' . $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }} (Fournisseur)
                            </option>
                        @endforeach
                        
                        <!-- Vendeurs marketplace -->
                        @foreach($vendors ?? [] as $vendor)
                            <option value="vendor_{{ $vendor->id }}" {{ request('supplier') == 'vendor_' . $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->shop_name }} (Marketplace)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="stockFilter" class="form-label fw-bold">
                        <i class="fas fa-cubes mr-2"></i>Stock
                    </label>
                    <select id="stockFilter" class="form-control">
                        <option value="">Tous les stocks</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stock bas (≤ seuil)</option>
                        <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Rupture de stock</option>
                        <option value="available" {{ request('stock') == 'available' ? 'selected' : '' }}>Disponible</option>
                    </select>
                </div>
            </div>

            <!-- Filtres actifs -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="text-muted small">Filtres actifs :</span>
                        
                        @if(request('search'))
                        <span class="badge bg-primary p-2">
                            Recherche: "{{ request('search') }}"
                            <a href="{{ route('admin.products.index', array_merge(request()->except('search', 'page'), ['supplier' => request('supplier'), 'stock' => request('stock')])) }}" 
                               class="text-white ml-2 text-decoration-none">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif

                        @if(request('supplier'))
                        <span class="badge bg-info p-2">
                            @php
                                $supplierValue = request('supplier');
                                $supplierName = '';
                                
                                if (strpos($supplierValue, 'supplier_') === 0) {
                                    $supplierId = str_replace('supplier_', '', $supplierValue);
                                    $supplier = $suppliers->firstWhere('id', $supplierId);
                                    $supplierName = $supplier ? $supplier->name . ' (Fournisseur)' : $supplierValue;
                                } elseif (strpos($supplierValue, 'vendor_') === 0) {
                                    $vendorId = str_replace('vendor_', '', $supplierValue);
                                    $vendor = $vendors->firstWhere('id', $vendorId);
                                    $supplierName = $vendor ? $vendor->shop_name . ' (Marketplace)' : $supplierValue;
                                }
                            @endphp
                            Source: {{ $supplierName }}
                            <a href="{{ route('admin.products.index', array_merge(request()->except('supplier', 'page'), ['search' => request('search'), 'stock' => request('stock')])) }}" 
                               class="text-white ml-2 text-decoration-none">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif

                        @if(request('stock'))
                        <span class="badge bg-warning p-2">
                            Stock: 
                            @if(request('stock') == 'low') Stock bas
                            @elseif(request('stock') == 'out') Rupture
                            @elseif(request('stock') == 'available') Disponible
                            @endif
                            <a href="{{ route('admin.products.index', array_merge(request()->except('stock', 'page'), ['search' => request('search'), 'supplier' => request('supplier')])) }}" 
                               class="text-white ml-2 text-decoration-none">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                        @endif

                        @if(request()->anyFilled(['search', 'supplier', 'stock']))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times mr-1"></i> Tout effacer
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- COMPTEUR DE RÉSULTATS -->
        <div class="card-body py-2 bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <span>
                    <strong>{{ $products->total() }}</strong> produit(s) trouvé(s)
                    @if(request('search'))
                        pour "<strong>{{ request('search') }}</strong>"
                    @endif
                </span>
                <span class="text-muted small">
                    <i class="fas fa-sort-alpha-down mr-1"></i> Tri alphabétique
                </span>
            </div>
        </div>

        <!-- Tableau des produits -->
        <div class="card-body p-0 p-md-3" id="productsTableContainer">
            @include('products._table', ['products' => $products])
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="card-footer" id="paginationContainer">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    Affichage de {{ $products->firstItem() }} à {{ $products->lastItem() }} sur {{ $products->total() }} produits
                </div>
                <div id="paginationLinks">
                    {{ $products->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.bg-purple { background-color: #6f42c1 !important; color: white; }
.text-gold { color: #f7d44a !important; }
.small-box {
    border-radius: 0.25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 20px;
    position: relative;
    color: #fff;
    padding: 15px;
    transition: transform 0.3s;
}
.small-box:hover { transform: translateY(-5px); }
.small-box h3 { font-size: 1.8rem; font-weight: 700; margin: 0 0 5px; }
.small-box p { font-size: 0.9rem; margin-bottom: 5px; }
.small-box .icon {
    color: rgba(0,0,0,.15);
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 50px;
}
@media (max-width: 768px) {
    .small-box h3 { font-size: 1.4rem; }
    .small-box .icon { font-size: 40px; }
    .btn-group { display: flex; }
    .btn-group .btn { flex: 1; }
}
</style>

<script>
let searchTimeout;

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const supplierFilter = document.getElementById('supplierFilter');
    const stockFilter = document.getElementById('stockFilter');
    const searchSpinner = document.getElementById('searchSpinner');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            if (searchSpinner) searchSpinner.style.display = 'inline-block';
            
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 500);
        });
    }
    
    if (supplierFilter) {
        supplierFilter.addEventListener('change', performSearch);
    }
    
    if (stockFilter) {
        stockFilter.addEventListener('change', performSearch);
    }
    
    function performSearch() {
        let url = new URL(window.location.href);
        
        if (searchInput && searchInput.value.trim()) {
            url.searchParams.set('search', searchInput.value.trim());
        } else {
            url.searchParams.delete('search');
        }
        
        if (supplierFilter && supplierFilter.value) {
            url.searchParams.set('supplier', supplierFilter.value);
        } else {
            url.searchParams.delete('supplier');
        }
        
        if (stockFilter && stockFilter.value) {
            url.searchParams.set('stock', stockFilter.value);
        } else {
            url.searchParams.delete('stock');
        }
        
        url.searchParams.set('page', 1);
        
        fetch(url.pathname + url.search, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const tableContainer = document.getElementById('productsTableContainer');
            if (tableContainer) {
                tableContainer.innerHTML = html;
            }
            
            window.history.pushState({}, '', url.pathname + url.search);
            
            if (searchSpinner) searchSpinner.style.display = 'none';
        })
        .catch(error => {
            console.error('Erreur AJAX:', error);
            if (searchSpinner) searchSpinner.style.display = 'none';
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                performSearch();
            }
        });
    }
});

function confirmDelete(id) {
    if (confirm('Supprimer ce produit ? Action irréversible.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection