@extends('layouts.app')

@section('title', 'Gestion des fournisseurs')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0">
                <i class="fas fa-truck mr-2"></i> Gestion des fournisseurs
            </h5>
            <a href="{{ route('admin.suppliers.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus mr-2"></i> Nouveau fournisseur
            </a>
        </div>

        <!-- Statistiques rapides -->
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                    <div class="small-box bg-info h-100">
                        <div class="inner">
                            <h3>{{ $suppliers->total() }}</h3>
                            <p>Total fournisseurs</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                    <div class="small-box bg-success h-100">
                        <div class="inner">
                            <h3>{{ $totalProducts ?? 0 }}</h3>
                            <p>Produits associés</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mx-auto">
                    <div class="small-box bg-warning h-100">
                        <div class="inner">
                            <h3>{{ $totalOrders ?? 0 }}</h3>
                            <p>Commandes en cours</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.suppliers.index') }}" class="form-row align-items-center g-2">
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher un fournisseur..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-redo"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Tableau des fournisseurs -->
        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th class="d-none d-md-table-cell">ID</th>
                            <th>Nom</th>
                            <th class="d-none d-lg-table-cell">Email</th>
                            <th class="d-none d-lg-table-cell">Téléphone</th>
                            <th class="d-none d-xl-table-cell">Adresse</th>
                            <th class="d-none d-xl-table-cell">Ville</th>
                            <th class="d-none d-xl-table-cell">Pays</th>
                            <th class="d-none d-sm-table-cell text-center">Prod.</th>
                            <th class="d-none d-sm-table-cell text-center">Cmd.</th>
                            <th class="d-none d-md-table-cell">Création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ $supplier->id }}</td>
                            <td>
                                <strong>{{ $supplier->admin_name ?? $supplier->name }}</strong>
                                @if($supplier->is_preferred)
                                    <span class="badge bg-warning ml-1"><i class="fas fa-star"></i></span>
                                @endif
                                <!-- Infos supplémentaires sur mobile -->
                                <span class="d-block d-lg-none small text-muted mt-1">
                                    <i class="fas fa-envelope mr-1"></i>{{ Str::limit($supplier->email, 20) }}<br>
                                    <i class="fas fa-phone mr-1"></i>{{ $supplier->telephone }}
                                    @if($supplier->city && $supplier->city != 'Non renseignée')
                                        <br><i class="fas fa-city mr-1"></i>{{ $supplier->city }}
                                    @endif
                                </span>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                    <i class="fas fa-envelope mr-1 text-primary"></i>{{ Str::limit($supplier->email, 20) }}
                                </a>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <a href="tel:{{ $supplier->telephone }}" class="text-decoration-none">
                                    <i class="fas fa-phone mr-1 text-success"></i>{{ $supplier->telephone }}
                                </a>
                            </td>
                            <td class="d-none d-xl-table-cell">
                                @if($supplier->address && $supplier->address != 'Non renseignée')
                                    <i class="fas fa-map-marker-alt mr-1 text-danger"></i>
                                    {{ Str::limit($supplier->address, 20) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="d-none d-xl-table-cell">
                                @if($supplier->city && $supplier->city != 'Non renseignée')
                                    <i class="fas fa-city mr-1 text-info"></i>{{ $supplier->city }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="d-none d-xl-table-cell">
                                @if($supplier->country && $supplier->country != 'Non renseigné')
                                    <i class="fas fa-globe mr-1 text-info"></i>{{ $supplier->country }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $supplier->products_count ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">{{ $supplier->orders_count ?? 0 }}</span>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $supplier->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.suppliers.show', $supplier->id) }}" 
                                       class="btn btn-info" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" 
                                       class="btn btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.products.create', ['supplier_id' => $supplier->id]) }}" 
                                       class="btn btn-success" title="Ajouter produit">
                                        <i class="fas fa-box"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.create', ['supplier_id' => $supplier->id]) }}" 
                                       class="btn btn-primary" title="Créer commande">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" 
                                            onclick="confirmDelete({{ $supplier->id }}, '{{ addslashes($supplier->admin_name ?? $supplier->name) }}')" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $supplier->id }}" 
                                      action="{{ route('admin.suppliers.destroy', $supplier->id) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun fournisseur trouvé</p>
                                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Ajouter un fournisseur
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    Affichage de {{ $suppliers->firstItem() ?? 0 }} à {{ $suppliers->lastItem() ?? 0 }} sur {{ $suppliers->total() }} fournisseurs
                </div>
                <div>
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Style -->
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.small-box {
    border-radius: 0.25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 0;
    position: relative;
    color: #fff;
    padding: 15px;
    transition: transform 0.3s;
}
.small-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
.small-box h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 5px;
}
.small-box p {
    font-size: 0.9rem;
    margin-bottom: 5px;
}
.small-box .icon {
    color: rgba(0,0,0,.15);
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 50px;
}
.text-white-50 {
    color: rgba(255, 255, 255, 0.5);
}
.h-100 {
    height: 100%;
}

/* ===== NOUVEAUX STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    .small-box {
        padding: 10px;
    }
    
    .small-box h3 {
        font-size: 1.4rem;
    }
    
    .small-box .icon {
        font-size: 40px;
        right: 5px;
        top: 5px;
    }
    
    .small-box p {
        font-size: 0.8rem;
    }
    
    .card-header {
        padding: 12px 15px;
    }
    
    .card-header h5 {
        font-size: 1.1rem;
    }
    
    .table td, .table th {
        padding: 8px 5px;
        font-size: 0.8rem;
    }
    
    .btn-group-sm .btn {
        padding: 4px 6px;
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.5rem;
    }
    
    /* Groupe de boutons */
    .btn-group {
        display: flex;
        flex-wrap: wrap;
        min-width: 100px;
    }
    
    .btn-group .btn {
        flex: 1;
        margin: 1px;
        border-radius: 4px !important;
    }
    
    /* Info fournisseur sur mobile */
    .d-block.d-lg-none.small {
        background: rgba(0,0,0,0.03);
        padding: 5px;
        border-radius: 4px;
        margin-top: 5px;
    }
}

@media (max-width: 576px) {
    .small-box h3 {
        font-size: 1.2rem;
    }
    
    .small-box .icon {
        font-size: 35px;
    }
    
    .card-body {
        padding: 10px;
    }
    
    .table td, .table th {
        padding: 6px 3px;
        font-size: 0.75rem;
    }
    
    .btn-group .btn {
        padding: 3px 4px;
        font-size: 0.7rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.4rem;
    }
    
    /* Icônes seulement sur très petit */
    .btn-group .btn i {
        margin: 0;
    }
    
    .btn-group .btn .fa-box,
    .btn-group .btn .fa-shopping-cart {
        font-size: 0.8rem;
    }
}

/* Pour les tablettes */
@media (min-width: 769px) and (max-width: 991px) {
    .small-box h3 {
        font-size: 1.5rem;
    }
    
    .small-box .icon {
        font-size: 45px;
    }
    
    .table td, .table th {
        padding: 8px;
        font-size: 0.85rem;
    }
}

/* Gap utility */
.gap-2 {
    gap: 0.5rem;
}
.g-2 {
    gap: 0.5rem;
}
</style>

<!-- Script pour la confirmation de suppression -->
<script>
function confirmDelete(id, name) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le fournisseur "${name}" ? Cette action est irréversible.`)) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Gestion du redimensionnement pour ajuster l'affichage
document.addEventListener('DOMContentLoaded', function() {
    // Rien de spécial pour l'instant
});
</script>
@endsection