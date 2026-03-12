@extends('layouts.app')

@section('title', 'Gestion des stocks')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg">
        <!-- En-tête avec bouton de synchronisation -->
        <div class="card-header bg-gradient-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0">
                <i class="fas fa-boxes mr-2"></i>
                Gestion des stocks
            </h5>
            <div>
                <button type="button" id="syncStocksBtn" class="btn btn-light btn-sm mr-2">
                    <i class="fas fa-sync-alt mr-2"></i> Synchroniser
                </button>
                <a href="{{ route('admin.products.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus mr-2"></i> Nouveau produit
                </a>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Statistiques -->
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                    <div class="small-box bg-info h-100">
                        <div class="inner">
                            <h3>{{ $stocks->total() }}</h3>
                            <p>Total produits</p>
                        </div>
                        <div class="icon"><i class="fas fa-box"></i></div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                    <div class="small-box bg-success h-100">
                        <div class="inner">
                            <h3>{{ $stocks->sum('quantity') }}</h3>
                            <p>Unités en stock</p>
                        </div>
                        <div class="icon"><i class="fas fa-cubes"></i></div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mx-auto">
                    <div class="small-box bg-danger h-100">
                        <div class="inner">
                            @php
                                $lowStockCount = $stocks->filter(function($stock) { 
                                    return $stock->quantity <= ($stock->product->stock_threshold ?? 5); 
                                })->count();
                            @endphp
                            <h3>{{ $lowStockCount }}</h3>
                            <p>Stock faible</p>
                        </div>
                        <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTRES -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.stocks.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="search" class="form-label fw-bold">
                            <i class="fas fa-search mr-2"></i>Rechercher un produit
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   class="form-control" 
                                   placeholder="Nom du produit, SKU ou description..." 
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label fw-bold">
                            <i class="fas fa-filter mr-2"></i>Statut du stock
                        </label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Tous les statuts</option>
                            <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>⚠️ Stock bas (≤ seuil)</option>
                            <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>✅ Normal</option>
                            <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>❌ Rupture</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">
                            <i class="fas fa-sort-alpha-down mr-2"></i>Tri
                        </label>
                        <select name="sort" id="sort" class="form-control">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                            <option value="quantity_asc" {{ request('sort') == 'quantity_asc' ? 'selected' : '' }}>Stock (croissant)</option>
                            <option value="quantity_desc" {{ request('sort') == 'quantity_desc' ? 'selected' : '' }}>Stock (décroissant)</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter mr-2"></i> Appliquer
                        </button>
                    </div>
                </div>
            </form>

            <!-- Filtres actifs -->
            @if(request('search') || request('status') || request('sort'))
            <div class="mt-3">
                <span class="text-muted small">Filtres actifs :</span>
                <div class="d-flex flex-wrap gap-2 mt-1">
                    @if(request('search'))
                    <span class="badge bg-primary p-2">
                        Recherche: "{{ request('search') }}"
                        <a href="{{ route('admin.stocks.index', array_merge(request()->except('search', 'page'))) }}" 
                           class="text-white ml-2 text-decoration-none">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('status'))
                    <span class="badge bg-info p-2">
                        Statut: 
                        @if(request('status') == 'low') Stock bas
                        @elseif(request('status') == 'normal') Normal
                        @elseif(request('status') == 'out') Rupture
                        @endif
                        <a href="{{ route('admin.stocks.index', array_merge(request()->except('status', 'page'))) }}" 
                           class="text-white ml-2 text-decoration-none">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('sort'))
                    <span class="badge bg-warning text-dark p-2">
                        Tri: 
                        @if(request('sort') == 'name_asc') A-Z
                        @elseif(request('sort') == 'name_desc') Z-A
                        @elseif(request('sort') == 'quantity_asc') Stock +
                        @elseif(request('sort') == 'quantity_desc') Stock -
                        @endif
                        <a href="{{ route('admin.stocks.index', array_merge(request()->except('sort', 'page'))) }}" 
                           class="text-dark ml-2 text-decoration-none">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('search') || request('status') || request('sort'))
                    <a href="{{ route('admin.stocks.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times mr-1"></i> Tout effacer
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Tableau -->
        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th class="d-none d-md-table-cell">ID</th>
                            <th>Produit</th>
                            <th class="d-none d-md-table-cell">Fournisseur / Vendeur</th>
                            <th>Qté</th>
                            <th class="d-none d-md-table-cell">Seuil</th>
                            <th>Statut</th>
                            <th class="d-none d-lg-table-cell">Dernière MAJ</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ $stock->id }}</td>
                            
                            <!-- COLONNE PRODUIT - VERSION CORRIGÉE SANS BOUCLE INFINIE -->
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $hasImage = $stock->product && $stock->product->image;
                                        $imageUrl = $hasImage ? asset('storage/products/' . basename($stock->product->image)) : null;
                                        $productName = $stock->product->admin_name ?? $stock->product->name ?? 'Produit inconnu';
                                    @endphp
                                    
                                    @if($hasImage)
                                        <div class="position-relative" style="width: 30px; height: 30px; margin-right: 0.5rem;">
                                            <img src="{{ $imageUrl }}" 
                                                 alt="{{ $productName }}" 
                                                 class="rounded-circle position-absolute" 
                                                 style="width: 30px; height: 30px; object-fit: cover;"
                                                 onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center position-absolute" 
                                                 style="width: 30px; height: 30px; display: none;">
                                                <i class="fas fa-box text-white" style="font-size: 14px;"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mr-2" 
                                             style="width: 30px; height: 30px;">
                                            <i class="fas fa-box text-white" style="font-size: 14px;"></i>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <strong>{{ Str::limit($productName, 25) }}</strong>
                                        @if($stock->product && $stock->product->sku)
                                            <br><small class="text-muted">SKU: {{ $stock->product->sku }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- COLONNE FOURNISSEUR / VENDEUR -->
                            <td class="d-none d-md-table-cell">
                                @if($stock->product && $stock->product->supplier)
                                    <a href="{{ route('admin.suppliers.show', $stock->product->supplier->id) }}" class="text-decoration-none">
                                        {{ Str::limit($stock->product->supplier->admin_name ?? $stock->product->supplier->name, 20) }}
                                    </a>
                                @elseif($stock->product && $stock->product->vendor)
                                    <div>
                                        <a href="{{ route('admin.vendors.show', $stock->product->vendor->id) }}" class="text-decoration-none text-info">
                                            <i class="fas fa-store mr-1"></i> {{ Str::limit($stock->product->vendor->admin_name ?? $stock->product->vendor->shop_name, 20) }}
                                        </a>
                                        <span class="badge bg-info text-white ml-1">Marketplace</span>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            
                            <td class="font-weight-bold {{ $stock->quantity <= ($stock->product->stock_threshold ?? 5) ? 'text-danger' : 'text-success' }}">
                                {{ $stock->quantity }}
                            </td>
                            <td class="d-none d-md-table-cell">{{ $stock->product->stock_threshold ?? 5 }}</td>
                            <td>
                                @if($stock->quantity <= 0)
                                    <span class="badge bg-danger px-2 py-1">Rupture</span>
                                @elseif($stock->quantity <= ($stock->product->stock_threshold ?? 5))
                                    <span class="badge bg-warning px-2 py-1">Stock bas</span>
                                @else
                                    <span class="badge bg-success px-2 py-1">Normal</span>
                                @endif
                            </td>
                            <td class="d-none d-lg-table-cell">{{ $stock->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="openStockModal({{ $stock->id }}, '{{ addslashes($productName) }}', {{ $stock->quantity }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                <p class="text-muted">Aucun stock disponible</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i> Ajouter un produit
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($stocks instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $stocks->hasPages())
        <div class="card-footer">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    Affichage de {{ $stocks->firstItem() }} à {{ $stocks->lastItem() }} sur {{ $stocks->total() }} produits
                </div>
                <div>
                    {{ $stocks->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- MODALE -->
<div id="stockModal" class="custom-modal" style="display: none;">
    <div class="custom-modal-overlay" onclick="closeStockModal()"></div>
    <div class="custom-modal-container">
        <div class="custom-modal-header bg-primary text-white">
            <h5 class="custom-modal-title">
                <i class="fas fa-edit mr-2"></i> Ajuster le stock
            </h5>
            <button type="button" class="custom-modal-close" onclick="closeStockModal()">×</button>
        </div>
        <form id="stockForm" method="POST">
            @csrf
            @method('PUT')
            <div class="custom-modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Produit</label>
                    <input type="text" class="form-control" id="modalProductName" readonly>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Nouvelle quantité</label>
                    <input type="number" class="form-control" id="modalQuantity" name="quantity" min="0" required>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeStockModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Styles pour la modale */
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
}
.custom-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}
.custom-modal-container {
    position: relative;
    width: 500px;
    max-width: 100%;
    background: white;
    border-radius: 8px;
    z-index: 10000;
}
.custom-modal-header {
    padding: 15px 20px;
    border-radius: 8px 8px 0 0;
}
.custom-modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 28px;
    cursor: pointer;
}
.custom-modal-body {
    padding: 20px;
}
.custom-modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}
.badge.bg-info {
    background-color: #17a2b8 !important;
    font-size: 0.7rem;
    padding: 0.2rem 0.4rem;
}
@media (max-width: 768px) {
    .custom-modal-footer {
        flex-direction: column-reverse;
    }
    .custom-modal-footer .btn {
        width: 100%;
    }
}
</style>

<script>
// Variables globales
let syncBtn = document.getElementById('syncStocksBtn');
let searchInput = document.getElementById('search');
let statusSelect = document.getElementById('status');
let sortSelect = document.getElementById('sort');

// Synchronisation
if (syncBtn) {
    syncBtn.addEventListener('click', function() {
        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Synchronisation...';
        btn.disabled = true;
        
        fetch('{{ route("admin.stocks.sync") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la synchronisation');
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    });
}

// Soumission manuelle des filtres
if (searchInput) {
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filterForm').submit();
        }
    });
}

// Empêcher la soumission multiple du formulaire
document.getElementById('filterForm').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    if (submitBtn && !submitBtn.disabled) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Filtrage...';
    }
});

function openStockModal(stockId, productName, currentQuantity) {
    document.getElementById('modalProductName').value = productName;
    document.getElementById('modalQuantity').value = currentQuantity;
    document.getElementById('stockForm').action = '/admin/stocks/' + stockId;
    document.getElementById('stockModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeStockModal() {
    document.getElementById('stockModal').style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeStockModal();
    }
});
</script>
@endsection