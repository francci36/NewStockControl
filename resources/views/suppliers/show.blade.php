@extends('layouts.app')

@section('title', 'Détails du fournisseur - ' . ($supplier->admin_name ?? $supplier->name))

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg">
        <!-- En-tête avec actions -->
        <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-truck mr-2"></i> Détails du fournisseur
                </h5>
                <small class="text-white-50">Inscrit le {{ $supplier->created_at->format('d/m/Y') }}</small>
            </div>
            <div class="btn-group flex-wrap" role="group">
                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit mr-2"></i><span class="d-none d-sm-inline">Modifier</span>
                </a>
                <a href="{{ route('admin.orders.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-shopping-cart mr-2"></i><span class="d-none d-sm-inline">Nouvelle commande</span>
                </a>
                <a href="{{ route('admin.products.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-box mr-2"></i><span class="d-none d-sm-inline">Nouveau produit</span>
                </a>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i><span class="d-none d-sm-inline">Retour</span>
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Statistiques en cartes -->
            <div class="row mb-4">
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <div class="small-box bg-info h-100">
                        <div class="inner">
                            <h3>{{ $productsCount }}</h3>
                            <p>Produits</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <a href="#products-section" class="small-box-footer">
                            Voir <i class="fas fa-arrow-circle-down ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-3 mb-md-0">
                    <div class="small-box bg-success h-100">
                        <div class="inner">
                            <h3>{{ $ordersCount }}</h3>
                            <p>Commandes</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="#orders-section" class="small-box-footer">
                            Voir <i class="fas fa-arrow-circle-down ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-warning h-100">
                        <div class="inner">
                            <h3>{{ number_format($totalPurchases, 2) }} €</h3>
                            <p>Total achats</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-danger h-100">
                        <div class="inner">
                            <h3>{{ $averageOrderValue ?? 0 }} €</h3>
                            <p>Panier moyen</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="row">
                <!-- Colonne gauche : Infos générales -->
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <!-- Infos générales -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informations générales</h6>
                        </div>
                        <div class="card-body p-0 p-md-3">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th style="width: 40%;" class="pl-3 pl-md-0">Nom :</th>
                                    <td>
                                        <strong>{{ $supplier->admin_name ?? $supplier->name }}</strong>
                                        @if($supplier->is_preferred ?? false)
                                            <span class="badge bg-warning ml-2">
                                                <i class="fas fa-star"></i>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pl-3 pl-md-0">Email :</th>
                                    <td>
                                        <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope mr-1 text-primary"></i>{{ Str::limit($supplier->email, 25) }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="pl-3 pl-md-0">Téléphone :</th>
                                    <td>
                                        <a href="tel:{{ $supplier->telephone }}" class="text-decoration-none">
                                            <i class="fas fa-phone mr-1 text-success"></i>{{ $supplier->telephone }}
                                        </a>
                                    </td>
                                </tr>
                                @if($supplier->website)
                                <tr>
                                    <th class="pl-3 pl-md-0">Site web :</th>
                                    <td>
                                        <a href="{{ $supplier->website }}" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-globe mr-1 text-info"></i>{{ Str::limit($supplier->website, 20) }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="pl-3 pl-md-0">Statut :</th>
                                    <td>
                                        @if($supplier->is_active ?? true)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Adresse -->
                    @if($supplier->address || $supplier->city || $supplier->country)
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Adresse</h6>
                        </div>
                        <div class="card-body">
                            <address class="mb-0">
                                {{ $supplier->address }}<br>
                                @if($supplier->postal_code){{ $supplier->postal_code }}@endif
                                @if($supplier->city) {{ $supplier->city }}<br>@endif
                                @if($supplier->country) {{ $supplier->country }}@endif
                            </address>
                        </div>
                    </div>
                    @endif

                    <!-- Informations professionnelles -->
                    @if($supplier->tax_id || $supplier->payment_terms)
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-building mr-2"></i>Infos professionnelles</h6>
                        </div>
                        <div class="card-body p-0 p-md-3">
                            <table class="table table-sm table-borderless mb-0">
                                @if($supplier->tax_id)
                                <tr>
                                    <th style="width: 40%;" class="pl-3 pl-md-0">N° SIRET/TVA :</th>
                                    <td>{{ $supplier->tax_id }}</td>
                                </tr>
                                @endif
                                @if($supplier->payment_terms)
                                <tr>
                                    <th class="pl-3 pl-md-0">Conditions :</th>
                                    <td>
                                        @switch($supplier->payment_terms)
                                            @case('immediate') Paiement immédiat @break
                                            @case('15_days') 15 jours @break
                                            @case('30_days') 30 jours @break
                                            @case('45_days') 45 jours @break
                                            @case('60_days') 60 jours @break
                                            @default {{ $supplier->payment_terms }}
                                        @endswitch
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($supplier->notes)
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-sticky-note mr-2"></i>Notes</h6>
                        </div>
                        <div class="card-body">
                            {{ $supplier->notes }}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Colonne droite : Produits et Commandes -->
                <div class="col-lg-8">
                    <!-- Graphique des achats -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Achats mensuels</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 250px;">
                                <canvas id="purchasesChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Derniers produits -->
                    <div class="card mb-3" id="products-section">
                        <div class="card-header bg-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                            <h6 class="mb-0"><i class="fas fa-box mr-2"></i>Produits ({{ $productsCount }})</h6>
                            <a href="{{ route('admin.products.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus mr-2"></i>Ajouter
                            </a>
                        </div>
                        <div class="card-body p-0 p-md-3">
                            @if($supplier->products && $supplier->products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nom</th>
                                                <th class="d-none d-md-table-cell">Référence</th>
                                                <th>Prix</th>
                                                <th>Stock</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($supplier->products->take(10) as $product)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-decoration-none">
                                                        {{ Str::limit($product->admin_name ?? $product->name, 20) }}
                                                    </a>
                                                    <span class="d-block d-md-none small text-muted">
                                                        Réf: {{ $product->sku ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="d-none d-md-table-cell">{{ $product->sku ?? '-' }}</td>
                                                <td>{{ number_format($product->price, 2) }} €</td>
                                                <td>
                                                    @php
                                                        // Récupérer la quantité depuis la relation stock ou la propriété directe
                                                        $stockQuantity = 0;
                                                        if ($product->stock) {
                                                            $stockQuantity = $product->stock->quantity;
                                                        } elseif (isset($product->quantity)) {
                                                            $stockQuantity = $product->quantity;
                                                        }
                                                    @endphp
                                                    
                                                    @if($stockQuantity > 10)
                                                        <span class="badge bg-success">{{ $stockQuantity }}</span>
                                                    @elseif($stockQuantity > 0)
                                                        <span class="badge bg-warning">{{ $stockQuantity }}</span>
                                                    @else
                                                        <span class="badge bg-danger">0</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($supplier->products->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('admin.products.index', ['supplier_id' => $supplier->id]) }}" class="btn btn-outline-primary btn-sm">
                                            Voir tous ({{ $supplier->products->count() }})
                                        </a>
                                    </div>
                                @endif
                            @else
                                <p class="text-muted text-center py-3 mb-0">
                                    Aucun produit associé
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Historique des commandes -->
                    <div class="card mb-3" id="orders-section">
                        <div class="card-header bg-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                            <h6 class="mb-0"><i class="fas fa-shopping-cart mr-2"></i>Dernières commandes ({{ $ordersCount }})</h6>
                            <a href="{{ route('admin.orders.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus mr-2"></i>Nouvelle commande
                            </a>
                        </div>
                        <div class="card-body p-0 p-md-3">
                            @if($supplier->orders && $supplier->orders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>N°</th>
                                                <th>Date</th>
                                                <th class="d-none d-md-table-cell">Montant</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($supplier->orders->sortByDesc('created_at')->take(10) as $order)
                                            <tr>
                                                <td>#{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                <td class="d-none d-md-table-cell">{{ number_format($order->total_amount, 2) }} €</td>
                                                <td>
                                                    @switch($order->status)
                                                        @case('pending')
                                                            <span class="badge bg-warning">En attente</span>
                                                            @break
                                                        @case('processing')
                                                            <span class="badge bg-info">Traitement</span>
                                                            @break
                                                        @case('completed')
                                                            <span class="badge bg-success">Terminée</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge bg-danger">Annulée</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                                    @endswitch
                                                    <span class="d-block d-md-none small text-muted">
                                                        {{ number_format($order->total_amount, 2) }} €
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($supplier->orders->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('admin.orders.index', ['supplier_id' => $supplier->id]) }}" class="btn btn-outline-primary btn-sm">
                                            Voir toutes ({{ $supplier->orders->count() }})
                                        </a>
                                    </div>
                                @endif
                            @else
                                <p class="text-muted text-center py-3 mb-0">
                                    Aucune commande passée
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour le graphique -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('purchasesChart').getContext('2d');
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#d1d5db' : '#374151';
    const gridColor = isDarkMode ? '#4b5563' : '#e5e7eb';
    
    // Données pour le graphique
    const months = [];
    const data = [];
    const now = new Date();
    
    @if(isset($monthlyPurchases) && count($monthlyPurchases) > 0)
        @foreach($monthlyPurchases as $month)
            months.push('{{ $month->month }}');
            data.push({{ $month->total }});
        @endforeach
    @else
        for (let i = 11; i >= 0; i--) {
            const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
            months.push(d.toLocaleString('fr-FR', { month: 'short' }));
            data.push(Math.floor(Math.random() * 1000) + 500);
        }
    @endif

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Montant des achats (€)',
                data: data,
                borderColor: '#f7d44a',
                backgroundColor: 'rgba(247, 212, 74, 0.2)',
                tension: 0.1,
                fill: true,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                    titleColor: textColor,
                    bodyColor: textColor,
                    borderColor: gridColor,
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: { 
                        color: textColor,
                        callback: function(value) {
                            return value + ' €';
                        }
                    }
                },
                x: {
                    grid: { color: gridColor },
                    ticks: { color: textColor }
                }
            }
        }
    });
});
</script>

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
.small-box-footer {
    display: block;
    padding: 3px 0;
    text-align: center;
    background: rgba(0,0,0,.1);
    color: #fff;
    text-decoration: none;
    border-radius: 0 0 0.25rem 0.25rem;
    margin-top: 10px;
    font-size: 0.8rem;
}
.small-box-footer:hover {
    background: rgba(0,0,0,.2);
    color: #fff;
}
.text-white-50 {
    color: rgba(255, 255, 255, 0.5);
}
.h-100 {
    height: 100%;
}
.chart-container {
    position: relative;
    width: 100%;
    min-height: 250px;
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
    
    .card-header h6 {
        font-size: 1rem;
    }
    
    .btn-group {
        width: 100%;
    }
    
    .btn-group .btn {
        flex: 1;
        font-size: 0.8rem;
        padding: 6px 8px;
    }
    
    .btn-group .btn i {
        margin-right: 0;
    }
    
    .btn-group .btn span {
        display: none;
    }
    
    .table td, .table th {
        padding: 8px 5px;
        font-size: 0.85rem;
    }
    
    .chart-container {
        height: 200px !important;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }
}

@media (max-width: 576px) {
    .small-box h3 {
        font-size: 1.2rem;
    }
    
    .small-box .icon {
        font-size: 35px;
    }
    
    .small-box p {
        font-size: 0.75rem;
    }
    
    .card-body {
        padding: 12px;
    }
    
    .table td, .table th {
        padding: 6px 3px;
        font-size: 0.8rem;
    }
    
    .chart-container {
        height: 180px !important;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
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
.gap-2 { gap: 0.5rem; }
.gap-3 { gap: 1rem; }
</style>
@endsection