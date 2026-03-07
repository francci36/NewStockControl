@extends('layouts.app')

@section('title', 'Gestion des commandes')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart mr-2"></i> Gestion des commandes
            </h5>
            <div>
                <a href="{{ route('admin.orders.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus mr-2"></i> <span class="d-none d-sm-inline">Nouvelle commande fournisseur</span>
                    <span class="d-sm-none">Nouvelle</span>
                </a>
            </div>
        </div>

        <!-- Tabs pour filtrer -->
        <div class="card-body border-bottom p-0 p-md-3">
            <ul class="nav nav-tabs flex-column flex-sm-row">
                <li class="nav-item flex-sm-fill text-center">
                    <a class="nav-link {{ !request('type') || request('type') == 'supplier' ? 'active' : '' }}" 
                       href="{{ route('admin.orders.index', ['type' => 'supplier']) }}">
                        <i class="fas fa-truck mr-2"></i> 
                        <span class="d-none d-sm-inline">Commandes fournisseurs</span>
                        <span class="d-sm-none">Fournisseurs</span>
                    </a>
                </li>
                <li class="nav-item flex-sm-fill text-center">
                    <a class="nav-link {{ request('type') == 'client' ? 'active' : '' }}" 
                       href="{{ route('admin.orders.index', ['type' => 'client']) }}">
                        <i class="fas fa-user mr-2"></i> 
                        <span class="d-none d-sm-inline">Commandes clients</span>
                        <span class="d-sm-none">Clients</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Statistiques rapides -->
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                    <div class="small-box bg-info h-100">
                        <div class="inner">
                            <h3>{{ $orders->total() }}</h3>
                            <p>Total commandes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                    <div class="small-box bg-success h-100">
                        <div class="inner">
                            <h3>{{ number_format($totalAmount, 2) }} €</h3>
                            <p>Montant total</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mx-auto">
                    <div class="small-box bg-warning h-100">
                        <div class="inner">
                            <h3>{{ $pendingCount }}</h3>
                            <p>En attente</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="form-row g-2 align-items-end">
                <input type="hidden" name="type" value="{{ request('type', 'supplier') }}">
                
                <div class="col-md-5">
                    <label for="search" class="sr-only">Recherche</label>
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Rechercher par ID, client ou fournisseur..." 
                               value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="status" class="sr-only">Statut</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payée</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livrée</option>
                        <option value="arrived" {{ request('status') == 'arrived' ? 'selected' : '' }}>Arrivée</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Remboursée</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-2"></i> Filtrer
                    </button>
                </div>

                <div class="col-md-2">
                    @if(request('search') || request('status'))
                    <a href="{{ route('admin.orders.index', ['type' => request('type', 'supplier')]) }}" 
                       class="btn btn-secondary btn-block">
                        <i class="fas fa-times mr-2"></i> Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tableau des commandes -->
        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>{{ request('type') == 'client' ? 'Client' : 'Fournisseur' }}</th>
                            <th>Date</th>
                            <th class="d-none d-sm-table-cell">Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="font-weight-bold">#{{ $order->id }}</td>
                            <td>
                                @if($order->type == 'client')
                                    <div>
                                        <strong>{{ Str::limit($order->user->name ?? 'Client inconnu', 20) }}</strong>
                                        <span class="d-block d-md-none small text-muted">
                                            {{ $order->user->email ?? '' }}
                                        </span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center">
                                        @if($order->supplier && $order->supplier->logo)
                                            <img src="{{ asset('storage/' . $order->supplier->logo) }}" 
                                                 alt="{{ $order->supplier->admin_name ?? $order->supplier->name }}" 
                                                 class="rounded-circle mr-2 d-none d-sm-block" style="width: 30px; height: 30px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ Str::limit($order->supplier->admin_name ?? $order->supplier->name ?? 'Fournisseur inconnu', 20) }}</strong>
                                            <span class="d-block d-md-none small text-muted">
                                                {{ $order->supplier->email ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $order->date->format('d/m/Y') }}</td>
                            <td class="font-weight-bold text-success d-none d-sm-table-cell">{{ number_format($order->total_amount, 2) }} €</td>
                            <td>
                                @php
                                    $statusLabels = [
                                        'pending' => 'En attente',
                                        'paid' => 'Payée',
                                        'processing' => 'En traitement',
                                        'shipped' => 'Expédiée',
                                        'delivered' => 'Livrée',
                                        'arrived' => 'Arrivée',
                                        'cancelled' => 'Annulée',
                                        'refunded' => 'Remboursée',
                                    ];
                                    
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'paid' => 'success',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'arrived' => 'success',
                                        'cancelled' => 'danger',
                                        'refunded' => 'secondary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} px-2 py-1">
                                    <i class="fas 
                                        @if($order->status == 'pending') fa-clock
                                        @elseif($order->status == 'processing') fa-cog
                                        @elseif($order->status == 'shipped') fa-truck
                                        @elseif($order->status == 'delivered') fa-check-circle
                                        @elseif($order->status == 'arrived') fa-check-circle
                                        @elseif($order->status == 'cancelled') fa-times-circle
                                        @elseif($order->status == 'paid') fa-credit-card
                                        @else fa-question-circle
                                        @endif mr-1 d-none d-sm-inline"></i>
                                    <span class="d-inline">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                                </span>
                                <span class="d-block d-sm-none small text-muted mt-1">
                                    {{ number_format($order->total_amount, 2) }} €
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Bouton Voir - TOUJOURS visible -->
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="btn btn-info" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Boutons conditionnels selon le type et statut -->
                                    @if($order->type == 'supplier')
                                        <!-- Bouton Modifier - visible seulement pour les commandes fournisseur en attente -->
                                        @if($order->status == 'pending')
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                           class="btn btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        
                                        <!-- Bouton Réceptionner - visible pour les commandes fournisseur non encore arrivées -->
                                        @if(in_array($order->status, ['pending', 'processing', 'shipped']))
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="arrived">
                                            <button type="submit" class="btn btn-success" 
                                                    onclick="return confirm('Confirmer la réception ? Le stock sera augmenté.')"
                                                    title="Réceptionner">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif

                                        <!-- Bouton Annuler - visible pour les commandes annulables -->
                                        @if(in_array($order->status, ['pending', 'processing']))
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn btn-secondary" 
                                                    onclick="return confirm('Annuler cette commande ?')"
                                                    title="Annuler">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                        @endif

                                        <!-- Bouton Supprimer - visible seulement pour les commandes annulées ou en attente -->
                                        @if(in_array($order->status, ['pending', 'cancelled']))
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Supprimer définitivement cette commande ?')"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    @endif

                                    <!-- Boutons pour les commandes clients -->
                                    @if($order->type == 'client')
                                        @if(in_array($order->status, ['pending', 'processing']))
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="shipped">
                                            <button type="submit" class="btn btn-primary" 
                                                    onclick="return confirm('Marquer comme expédiée ?')"
                                                    title="Expédier">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                        </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune commande trouvée</p>
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
                    Affichage de {{ $orders->firstItem() ?? 0 }} à {{ $orders->lastItem() ?? 0 }} sur {{ $orders->total() }} commandes
                </div>
                <div>
                    {{ $orders->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

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
.h-100 {
    height: 100%;
}
.nav-tabs .nav-link {
    border: none;
    color: #495057;
    padding: 12px 15px;
    transition: all 0.3s;
}
.nav-tabs .nav-link.active {
    color: #007bff;
    font-weight: 600;
    border-bottom: 2px solid #007bff;
    background: transparent;
}
.nav-tabs .nav-link:hover {
    background-color: rgba(0,123,255,0.05);
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
    
    /* Tabs responsives */
    .nav-tabs {
        display: flex;
        width: 100%;
    }
    
    .nav-tabs .nav-item {
        flex: 1;
        text-align: center;
    }
    
    .nav-tabs .nav-link {
        padding: 10px 5px;
        font-size: 0.85rem;
    }
    
    .nav-tabs .nav-link i {
        margin-right: 0;
        font-size: 1rem;
    }
    
    /* Table compacte */
    .table td, .table th {
        padding: 8px 5px;
        font-size: 0.85rem;
    }
    
    .btn-group-sm .btn {
        padding: 4px 6px;
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }
    
    /* Groupe de boutons */
    .btn-group {
        display: flex;
        flex-wrap: wrap;
        min-width: 80px;
    }
    
    .btn-group .btn {
        flex: 1;
        margin: 1px;
        border-radius: 4px !important;
    }
    
    /* Montant total visible sur mobile */
    .d-block.d-sm-none.small {
        background: rgba(0,0,0,0.03);
        padding: 2px 5px;
        border-radius: 3px;
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
        padding: 12px;
    }
    
    .table td, .table th {
        padding: 6px 3px;
        font-size: 0.8rem;
    }
    
    .nav-tabs .nav-link {
        font-size: 0.75rem;
        padding: 8px 3px;
    }
    
    .btn-group .btn {
        padding: 3px 4px;
        font-size: 0.7rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.4rem;
    }
    
    /* Icônes seulement pour les actions */
    .btn-group .btn i {
        margin: 0;
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
        font-size: 0.9rem;
    }
}

/* Gap utilities */
.g-2 { gap: 0.5rem; }
.g-3 { gap: 1rem; }
</style>
@endsection