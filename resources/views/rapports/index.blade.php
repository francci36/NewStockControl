@extends('layouts.app')

@section('title', 'Rapports et Analyses')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0" style="background: #ffffff;">
        <!-- En-tête -->
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0">
                <i class="fas fa-chart-bar mr-2"></i> Rapports et Analyses
            </h5>
        </div>

        <div class="card-body">
            <!-- Messages de notification -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- ===== SECTION 1 : INDICATEURS GLOBAUX ===== -->
            <div class="row mb-4">
                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="small-box bg-success h-100">
                        <div class="inner">
                            <h3>{{ number_format($chiffreAffaires, 0, ',', ' ') }} €</h3>
                            <p>Chiffre d'affaires</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                        <span class="small-box-footer">Ventes du mois</span>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="small-box bg-warning h-100">
                        <div class="inner">
                            <h3>{{ number_format($totalAchats, 0, ',', ' ') }} €</h3>
                            <p>Achats fournisseurs</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <span class="small-box-footer">Approvisionnements</span>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="small-box bg-info h-100">
                        <div class="inner">
                            <h3>{{ number_format($benefice, 0, ',', ' ') }} €</h3>
                            <p>Bénéfice estimé</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="small-box-footer">Marge brute</span>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-0">
                    <div class="small-box bg-danger h-100">
                        <div class="inner">
                            <h3>{{ $stockCritique }}</h3>
                            <p>Stock critique</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <span class="small-box-footer">Produits à risque</span>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION 2 : KPI CLIENTS ===== -->
            <div class="row mb-4">
                <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                    <div class="small-box bg-purple h-100">
                        <div class="inner">
                            <h3>{{ $clientsActifs }}</h3>
                            <p>Clients actifs</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="small-box-footer">Acheteurs enregistrés</span>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                    <div class="small-box bg-indigo h-100">
                        <div class="inner">
                            <h3>{{ $nombreCommandesClients }}</h3>
                            <p>Commandes clients</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <span class="small-box-footer">Depuis le début</span>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 mx-auto">
                    <div class="small-box bg-pink h-100">
                        <div class="inner">
                            <h3>{{ number_format($panierMoyen, 2, ',', ' ') }} €</h3>
                            <p>Panier moyen</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <span class="small-box-footer">Par commande client</span>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION 3 : GRAPHIQUES ===== -->
            <div class="row mb-4">
                <!-- Graphique des commandes par statut -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-chart-pie mr-2"></i> Commandes par statut</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 250px;">
                                <canvas id="commandesParStatutChart"></canvas>
                            </div>
                            <!-- Légende supplémentaire sur mobile -->
                            <div class="row mt-4 g-2">
                                @foreach($commandesParStatut as $statut)
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-warning',
                                        'processing' => 'bg-info',
                                        'shipped' => 'bg-primary',
                                        'delivered' => 'bg-success',
                                        'arrived' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'En attente',
                                        'processing' => 'En traitement',
                                        'shipped' => 'Expédiée',
                                        'delivered' => 'Livrée',
                                        'arrived' => 'Arrivée',
                                        'cancelled' => 'Annulée',
                                    ];
                                @endphp
                                <div class="col-6 col-md-4 mb-2">
                                    <div class="p-2 rounded {{ $statusColors[$statut->status] ?? 'bg-secondary' }} text-white text-center">
                                        <small class="d-block d-sm-inline">{{ $statusLabels[$statut->status] ?? ucfirst($statut->status) }}</small>
                                        <span class="d-block font-weight-bold">{{ $statut->count }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphique des produits en stock -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-chart-bar mr-2"></i> Top 10 produits en stock</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 250px;">
                                <canvas id="produitsEnStockChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION 4 : STATISTIQUES FOURNISSEURS ===== -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-truck mr-2"></i> Statistiques des fournisseurs</h6>
                    <span class="badge bg-light text-dark">{{ $fournisseurs->count() ?? 0 }} fournisseurs</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Fournisseur</th>
                                    <th class="d-none d-md-table-cell">Email</th>
                                    <th class="d-none d-md-table-cell">Téléphone</th>
                                    <th>Nb commandes</th>
                                    <th>Montant total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($fournisseurs as $fournisseur)
                                <tr>
                                    <td class="font-weight-bold">
                                        {{ $fournisseur->admin_name ?? $fournisseur->name }}
                                        @if($fournisseur->is_preferred)
                                            <span class="badge bg-warning ml-1"><i class="fas fa-star"></i></span>
                                        @endif
                                        <span class="d-block d-md-none small text-muted">
                                            {{ $fournisseur->email }}<br>
                                            {{ $fournisseur->telephone }}
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $fournisseur->email }}</td>
                                    <td class="d-none d-md-table-cell">{{ $fournisseur->telephone }}</td>
                                    <td>
                                        <span class="badge bg-info p-2">
                                            {{ $fournisseur->orders_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format($fournisseur->orders_sum_total_amount ?? 0, 2) }} €
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun fournisseur trouvé</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION 5 : STATISTIQUES VENDEURS MARKETPLACE (NOUVEAU) ===== -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-store mr-2"></i> Statistiques des vendeurs marketplace</h6>
                    <span class="badge bg-light text-dark">{{ $vendeurs->count() ?? 0 }} vendeurs</span>
                </div>
                <div class="card-body p-0">
                    <!-- Sous-totaux marketplace -->
                    <div class="row p-3 bg-light border-bottom">
                        <div class="col-6 col-md-3">
                            <div class="text-center">
                                <span class="text-muted d-block">CA Marketplace</span>
                                <span class="font-weight-bold text-success h5">{{ number_format($caMarketplace, 2) }} €</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center">
                                <span class="text-muted d-block">Commandes</span>
                                <span class="font-weight-bold h5">{{ $commandesMarketplace }}</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center">
                                <span class="text-muted d-block">Commission moyenne</span>
                                <span class="font-weight-bold h5">{{ $commissionMoyenne }}%</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="text-center">
                                <span class="text-muted d-block">Revenu commissions</span>
                                <span class="font-weight-bold text-primary h5">{{ number_format($revenuCommissions, 2) }} €</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Boutique</th>
                                    <th class="d-none d-md-table-cell">Vendeur</th>
                                    <th class="d-none d-lg-table-cell">Email</th>
                                    <th>Commission</th>
                                    <th>Produits</th>
                                    <th>Commandes</th>
                                    <th>CA</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vendeurs as $vendeur)
                                <tr>
                                    <td class="font-weight-bold">
                                        {{ $vendeur->admin_shop_name ?? $vendeur->shop_name }}
                                        <span class="d-block d-md-none small text-muted">
                                            {{ $vendeur->user->name ?? 'N/A' }}<br>
                                            Comm: {{ $vendeur->commission_rate }}%
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $vendeur->user->name ?? 'N/A' }}</td>
                                    <td class="d-none d-lg-table-cell">
                                        <a href="mailto:{{ $vendeur->email }}">{{ $vendeur->email }}</a>
                                    </td>
                                    <td><span class="badge bg-info">{{ $vendeur->commission_rate }}%</span></td>
                                    <td class="text-center">{{ $vendeur->products_count ?? 0 }}</td>
                                    <td class="text-center">{{ $vendeur->orders_count ?? 0 }}</td>
                                    <td class="font-weight-bold text-success">{{ number_format($vendeur->orders_sum_total_amount ?? 0, 2) }} €</td>
                                    <td>
                                        @if($vendeur->status == 'active')
                                            <span class="badge bg-success">Actif</span>
                                        @elseif($vendeur->status == 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $vendeur->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-store fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun vendeur marketplace trouvé</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ===== SECTION 6 : RAPPORTS GÉNÉRÉS ===== -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-file-alt mr-2"></i> Rapports générés</h6>
                </div>
                <div class="card-body">
                    @if($reports->count() > 0)
                        <div class="row g-3">
                            @foreach($reports as $report)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-gold">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="text-primary font-weight-bold">{{ $report->name }}</h6>
                                                <p class="text-muted small">{{ Str::limit($report->description, 80) }}</p>
                                                
                                                <div class="row small g-2">
                                                    <div class="col-6">
                                                        <span class="text-muted">Transactions:</span>
                                                        <span class="ml-1 font-weight-bold">{{ $report->transactions->count() }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <span class="text-muted">Stocks critiques:</span>
                                                        <span class="ml-1 font-weight-bold">{{ $report->stocks->count() }}</span>
                                                    </div>
                                                    <div class="col-6 mt-2">
                                                        <span class="text-muted">Créé le:</span>
                                                        <span class="ml-1 font-weight-bold">{{ $report->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div class="col-6 mt-2">
                                                        <span class="text-muted">Statut:</span>
                                                        <span class="ml-1 badge bg-success">Actif</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.rapports.export', $report->id) }}" 
                                               class="btn btn-sm btn-outline-primary ml-2"
                                               target="_blank">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                            <p class="text-muted mb-4">Aucun rapport généré pour le moment.</p>
                            <form action="{{ route('admin.rapports.generate') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync-alt mr-2"></i> Générer les rapports
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du mode sombre
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#d1d5db' : '#374151';
    const gridColor = isDarkMode ? '#4b5563' : '#e5e7eb';
    
    // Graphique des commandes par statut
    const commandesParStatutElement = document.getElementById('commandesParStatutChart');
    if (commandesParStatutElement) {
        const commandesParStatutCtx = commandesParStatutElement.getContext('2d');
        
        // Données PHP
        const commandesLabels = @json($commandesParStatut->pluck('status'));
        const commandesData = @json($commandesParStatut->pluck('count'));
        
        // Traduire les statuts
        const translatedLabels = commandesLabels.map(label => {
            const translations = {
                'pending': 'En attente',
                'processing': 'En traitement',
                'shipped': 'Expédiée',
                'delivered': 'Livrée',
                'arrived': 'Arrivée',
                'cancelled': 'Annulée',
                'paid': 'Payée',
                'refunded': 'Remboursée'
            };
            return translations[label] || label;
        });
        
        new Chart(commandesParStatutCtx, {
            type: 'pie',
            data: {
                labels: translatedLabels,
                datasets: [{
                    data: commandesData,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                    borderColor: isDarkMode ? '#1f2937' : '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                        titleColor: textColor,
                        bodyColor: textColor,
                        borderColor: gridColor,
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Graphique des produits en stock
    const produitsEnStockElement = document.getElementById('produitsEnStockChart');
    if (produitsEnStockElement) {
        const produitsEnStockCtx = produitsEnStockElement.getContext('2d');
        
        // Données PHP avec noms traduits
        const produitsLabels = @json($produitsEnStock->map(function($stock) {
            return $stock->product ? ($stock->product->admin_name ?? $stock->product->name) : 'Produit inconnu';
        }));
        const produitsQuantities = @json($produitsEnStock->pluck('quantity'));
        
        new Chart(produitsEnStockCtx, {
            type: 'bar',
            data: {
                labels: produitsLabels,
                datasets: [{
                    label: 'Quantité en stock',
                    data: produitsQuantities,
                    backgroundColor: produitsQuantities.map(qty => {
                        if (qty < 5) return '#ef4444';
                        if (qty < 10) return '#f59e0b';
                        return '#10b981';
                    }),
                    borderColor: isDarkMode ? '#374151' : '#9ca3af',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: textColor },
                        title: {
                            display: true,
                            text: 'Quantité',
                            color: textColor
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: textColor,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }
});

// Rafraîchir les données périodiquement
let refreshInterval = setInterval(function() {
    if (document.visibilityState === 'visible') {
        fetch('/admin/rapports/refresh-data', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.changed) {
                location.reload();
            }
        })
        .catch(() => {});
    }
}, 60000);

// Nettoyer l'intervalle
window.addEventListener('beforeunload', function() {
    clearInterval(refreshInterval);
});
</script>

<style>
/* ===== STYLES EXISTANTS ===== */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-purple {
    background-color: #6f42c1 !important;
    color: white;
}
.bg-indigo {
    background-color: #6610f2 !important;
    color: white;
}
.bg-pink {
    background-color: #e83e8c !important;
    color: white;
}
.small-box {
    border-radius: 0.25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
    color: #fff;
    padding: 15px;
    transition: transform 0.3s;
}
.small-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.small-box > .inner {
    padding: 10px;
}
.small-box h3 {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0 0 5px;
}
.small-box p {
    font-size: 1rem;
    margin-bottom: 0;
}
.small-box .icon {
    color: rgba(255,255,255,0.15);
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 70px;
}
.small-box-footer {
    display: block;
    padding: 3px 0;
    text-align: center;
    background: rgba(0,0,0,0.1);
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    border-radius: 0 0 0.25rem 0.25rem;
    margin-top: 10px;
    font-size: 0.85rem;
}
.border-gold {
    border-left: 4px solid #f7d44a !important;
}

/* ===== NOUVEAUX STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    .small-box {
        padding: 10px;
        margin-bottom: 0;
    }
    
    .small-box h3 {
        font-size: 1.6rem;
    }
    
    .small-box .icon {
        font-size: 50px;
    }
    
    .small-box-footer {
        font-size: 0.75rem;
        padding: 2px 0;
    }
    
    .card-header {
        padding: 12px 15px;
    }
    
    .card-header h6 {
        font-size: 1rem;
    }
    
    .chart-container {
        height: 200px !important;
    }
    
    /* Table fournisseurs */
    .table td, .table th {
        padding: 8px;
        font-size: 0.85rem;
    }
    
    /* Légende des statuts */
    .col-6 {
        padding: 0 5px;
    }
    
    .col-6 div {
        padding: 5px !important;
    }
    
    .col-6 small {
        font-size: 0.7rem;
    }
    
    .col-6 span {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .small-box h3 {
        font-size: 1.3rem;
    }
    
    .small-box p {
        font-size: 0.8rem;
    }
    
    .small-box .icon {
        font-size: 40px;
        right: 5px;
        top: 5px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .chart-container {
        height: 180px !important;
    }
    
    /* Bouton générer */
    .btn-primary {
        width: 100%;
        padding: 12px;
    }
    
    /* Pagination */
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .pagination .page-link {
        padding: 5px 10px;
        font-size: 0.85rem;
    }
}

/* Hauteur uniforme pour les small-box */
.h-100 {
    height: 100%;
}

/* Gap utility */
.g-2 { gap: 0.5rem; }
.g-3 { gap: 1rem; }
</style>
@endsection