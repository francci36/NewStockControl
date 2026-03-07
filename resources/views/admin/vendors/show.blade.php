@extends('layouts.app')

@section('title', 'Détails de la demande - ' . ($vendor->admin_shop_name ?? $vendor->shop_name ?? $vendor->name))

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-store mr-2"></i> Détails de la demande - {{ $vendor->admin_shop_name ?? $vendor->shop_name ?? $vendor->name }}
                </h5>
                <small class="text-white-50 d-block d-md-inline mt-1 mt-md-0">
                    Demande créée le {{ $vendor->created_at->format('d/m/Y H:i') }}
                </small>
            </div>
            <div class="btn-group flex-wrap" role="group">
                @if($vendor->status === 'pending')
                <!-- Formulaire d'approbation (PATCH) -->
                <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approuver cette demande vendeur ?')">
                        <i class="fas fa-check mr-2"></i> <span class="d-none d-sm-inline">Approuver</span>
                        <span class="d-sm-none">✔️</span>
                    </button>
                </form>
                
                <!-- Formulaire de rejet (PATCH) -->
                <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Rejeter cette demande vendeur ?')">
                        <i class="fas fa-times mr-2"></i> <span class="d-none d-sm-inline">Rejeter</span>
                        <span class="d-sm-none">✖️</span>
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i> <span class="d-none d-sm-inline">Retour</span>
                    <span class="d-sm-none">←</span>
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- STATISTIQUES -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="small-box bg-info h-100">
                        <div class="inner">
                            <h3>{{ $stats['products_count'] ?? $vendor->products_count ?? 0 }}</h3>
                            <p>Produits</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-success h-100">
                        <div class="inner">
                            <h3>{{ $stats['orders_count'] ?? $vendor->orders_count ?? 0 }}</h3>
                            <p>Commandes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-warning h-100">
                        <div class="inner">
                            <h3>{{ $stats['pending_orders'] ?? 0 }}</h3>
                            <p>En attente</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-primary h-100">
                        <div class="inner">
                            <h3>{{ number_format($stats['revenue'] ?? 0, 2) }} €</h3>
                            <p>Chiffre d'affaires</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Colonne gauche - Infos boutique et professionnelles -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <!-- Informations de la boutique -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-store mr-2"></i> Informations de la boutique</h6>
                        </div>
                        <div class="card-body p-0 p-md-3">
                            <div class="info-grid">
                                <div class="info-row">
                                    <span class="info-label">Nom de la boutique</span>
                                    <span class="info-value"><strong>{{ $vendor->admin_shop_name ?? $vendor->shop_name ?? $vendor->name }}</strong></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Slug</span>
                                    <span class="info-value">{{ $vendor->slug ?? '-' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Description</span>
                                    <span class="info-value">{{ Str::limit($vendor->admin_description ?? $vendor->description ?? 'Aucune description', 100) }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Commission</span>
                                    <span class="info-value"><span class="badge bg-info">{{ number_format($vendor->commission_rate ?? 10, 2) }}%</span></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Statut</span>
                                    <span class="info-value">
                                        @if($vendor->status === 'active')
                                            <span class="badge bg-success">Actif</span>
                                        @elseif($vendor->status === 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($vendor->status === 'rejected')
                                            <span class="badge bg-danger">Rejeté</span>
                                        @elseif($vendor->status === 'suspended')
                                            <span class="badge bg-secondary">Suspendu</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Date de demande</span>
                                    <span class="info-value">{{ $vendor->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($vendor->approved_at)
                                <div class="info-row">
                                    <span class="info-label">Approuvé le</span>
                                    <span class="info-value">{{ $vendor->approved_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @endif
                                @if($vendor->rejection_reason)
                                <div class="info-row">
                                    <span class="info-label">Motif rejet</span>
                                    <span class="info-value text-danger">{{ $vendor->rejection_reason }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Informations professionnelles -->
                    @if($vendor->tax_id || $vendor->iban || $vendor->bic)
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-briefcase mr-2"></i> Informations professionnelles</h6>
                        </div>
                        <div class="card-body p-0 p-md-3">
                            <div class="info-grid">
                                @if($vendor->tax_id)
                                <div class="info-row">
                                    <span class="info-label">N° SIRET / Tax ID</span>
                                    <span class="info-value">{{ $vendor->tax_id }}</span>
                                </div>
                                @endif
                                @if($vendor->iban)
                                <div class="info-row">
                                    <span class="info-label">IBAN</span>
                                    <span class="info-value">{{ $vendor->iban }}</span>
                                </div>
                                @endif
                                @if($vendor->bic)
                                <div class="info-row">
                                    <span class="info-label">BIC</span>
                                    <span class="info-value">{{ $vendor->bic }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Colonne droite - Infos vendeur et adresse -->
                <div class="col-lg-6">
                    <!-- Informations du vendeur -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-user mr-2"></i> Informations du vendeur</h6>
                        </div>
                        <div class="card-body p-0 p-md-3">
                            <div class="info-grid">
                                <div class="info-row">
                                    <span class="info-label">Nom</span>
                                    <span class="info-value">{{ $vendor->user->name ?? $vendor->name }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">
                                        <a href="mailto:{{ $vendor->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope mr-1 text-primary"></i>{{ $vendor->email }}
                                        </a>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Téléphone</span>
                                    <span class="info-value">
                                        <a href="tel:{{ $vendor->phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone mr-1 text-success"></i>{{ $vendor->phone ?? 'Non renseigné' }}
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Adresse complète -->
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i> Adresse complète</h6>
                        </div>
                        <div class="card-body">
                            @if($vendor->address || $vendor->city || $vendor->country)
                                <address class="mb-0">
                                    {{ $vendor->address ?? '' }}<br>
                                    @if($vendor->postal_code){{ $vendor->postal_code }} @endif
                                    {{ $vendor->city ?? '' }}<br>
                                    {{ $vendor->country ?? '' }}
                                </address>
                            @else
                                <p class="text-muted mb-0">Aucune adresse renseignée</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Derniers produits du vendeur -->
            @if(isset($recentProducts) && $recentProducts->count() > 0)
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-box mr-2"></i> Derniers produits ({{ $recentProducts->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentProducts as $product)
                                <tr>
                                    <td>
                                        <strong>{{ Str::limit($product->admin_name ?? $product->name, 40) }}</strong>
                                        <span class="d-block d-md-none small text-muted mt-1">
                                            Prix: {{ number_format($product->price, 2) }} € | 
                                            Stock: {{ $product->quantity }}
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ number_format($product->price, 2) }} €</td>
                                    <td class="d-none d-md-table-cell">
                                        @php
                                            $stockQuantity = 0;
                                            if ($product->stock) {
                                                $stockQuantity = $product->stock->quantity;
                                            } elseif (isset($product->quantity)) {
                                                $stockQuantity = $product->quantity;
                                            }
                                        @endphp
                                        <span class="badge {{ $stockQuantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $stockQuantity }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Message si aucun produit -->
            @if(isset($recentProducts) && $recentProducts->count() === 0)
            <div class="card mt-4">
                <div class="card-body text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Ce vendeur n'a pas encore de produits</p>
                </div>
            </div>
            @endif

            <!-- Dernières commandes -->
            @if(isset($recentOrders) && $recentOrders->count() > 0)
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-shopping-cart mr-2"></i> Dernières commandes ({{ $recentOrders->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>{{ number_format($order->total_amount, 2) }} €</td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <span class="badge bg-warning text-dark p-2">
                                                <i class="fas fa-clock mr-1"></i> En attente
                                            </span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-info p-2">
                                                <i class="fas fa-spinner mr-1"></i> Traitement
                                            </span>
                                        @elseif($order->status == 'completed' || $order->status == 'arrived')
                                            <span class="badge bg-success p-2">
                                                <i class="fas fa-check-circle mr-1"></i> Terminée
                                            </span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger p-2">
                                                <i class="fas fa-times-circle mr-1"></i> Annulée
                                            </span>
                                        @elseif($order->status == 'shipped')
                                            <span class="badge bg-primary p-2">
                                                <i class="fas fa-shipping-fast mr-1"></i> Expédiée
                                            </span>
                                        @elseif($order->status == 'delivered')
                                            <span class="badge bg-success p-2">
                                                <i class="fas fa-check-circle mr-1"></i> Livrée
                                            </span>
                                        @elseif($order->status == 'refunded')
                                            <span class="badge bg-secondary p-2">
                                                <i class="fas fa-undo mr-1"></i> Remboursée
                                            </span>
                                        @else
                                            <span class="badge bg-secondary p-2">
                                                <i class="fas fa-question-circle mr-1"></i> {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Formulaire de suspension pour les vendeurs actifs -->
            @if($vendor->status === 'active')
            <div class="card mt-4">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0"><i class="fas fa-ban mr-2"></i> Suspendre le vendeur</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vendors.suspend', $vendor) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group mb-3">
                            <label for="suspend_reason">Motif de la suspension <span class="text-danger">*</span></label>
                            <textarea name="reason" id="suspend_reason" class="form-control" rows="3" required placeholder="Expliquez la raison de la suspension..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Suspendre ce vendeur ?')">
                            <i class="fas fa-ban mr-2"></i> Suspendre le vendeur
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Formulaire de mise à jour de commission -->
            @if($vendor->status === 'active')
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-percentage mr-2"></i> Commission</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vendors.commission', $vendor) }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group w-100">
                            <input type="number" name="commission_rate" class="form-control" 
                                   value="{{ $vendor->commission_rate ?? 10 }}" min="0" max="100" step="0.1" required>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Bouton retour supplémentaire pour mobile -->
            <div class="mt-4 text-center d-block d-md-none">
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.text-white-50 {
    color: rgba(255, 255, 255, 0.5);
}

/* ===== STYLES POUR LA GRILLE D'INFORMATIONS ===== */
.info-grid {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.info-row {
    display: flex;
    flex-wrap: wrap;
    padding: 12px 15px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    align-items: flex-start;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    width: 140px;
    color: #495057;
    flex-shrink: 0;
}

.info-value {
    flex: 1;
    min-width: 0;
    word-wrap: break-word;
    overflow-wrap: break-word;
    max-width: calc(100% - 140px);
}

/* ===== STYLES POUR LES SMALL BOX ===== */
.small-box {
    border-radius: 0.5rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
    color: #fff;
    padding: 15px;
    transition: transform 0.3s;
    height: 100%;
}
.small-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.small-box .inner {
    padding: 5px 5px 5px 0;
}
.small-box h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 5px;
    line-height: 1.2;
    word-break: break-word;
}
.small-box p {
    font-size: 0.9rem;
    margin-bottom: 0;
    opacity: 0.9;
}
.small-box .icon {
    color: rgba(255,255,255,0.15);
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 50px;
}

/* ===== MODE SOMBRE ===== */
.dark .card {
    background: #2d2d44;
    border-color: #4a4a6a;
}
.dark .card-header {
    background: #3a3a5a;
    color: #e0e0e0;
}
.dark .info-label {
    color: #c0c0e0;
}
.dark .info-value {
    color: #e0e0e0;
}
.dark .info-row {
    border-bottom-color: rgba(255,255,255,0.1);
}
.dark .bg-light {
    background: #35354a !important;
}
.dark .table {
    color: #e0e0e0;
}
.dark .table thead th {
    background: #3a3a5a;
    color: #e0e0e0;
    border-bottom-color: #4a4a6a;
}
.dark .table td {
    border-color: #4a4a6a;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .info-row {
        flex-direction: column;
        padding: 10px 12px;
    }
    
    .info-label {
        width: 100%;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
    
    .info-value {
        width: 100%;
        max-width: 100%;
        padding-left: 0;
    }
    
    .small-box {
        padding: 12px;
    }
    .small-box h3 {
        font-size: 1.4rem;
    }
    .small-box .icon {
        font-size: 40px;
    }
    .table th, .table td {
        padding: 8px 5px;
        font-size: 0.85rem;
    }
}

@media (max-width: 576px) {
    .small-box h3 {
        font-size: 1.2rem;
    }
    .small-box .icon {
        font-size: 35px;
    }
    .btn-group .btn {
        padding: 5px 8px;
    }
}

/* Classes utilitaires */
.h-100 { height: 100%; }
.g-3 { gap: 1rem; }
</style>
@endsection