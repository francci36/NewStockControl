@extends('layouts.app')

@section('title', 'Dashboard Financier')

@section('page-title', 'Dashboard Financier')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
    <li class="breadcrumb-item active">Finance</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Période selector -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <form method="GET" class="form-inline justify-content-end">
                        <label class="mr-2 mb-2 mb-sm-0">Période :</label>
                        <select name="period" class="form-control" onchange="this.form.submit()">
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Cette semaine</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Ce mois</option>
                            <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>Ce trimestre</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Cette année</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($stats['revenus'], 2) }} €</h3>
                    <p>Revenus</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <a href="{{ route('admin.finance.transactions', ['type' => 'income']) }}" class="small-box-footer">
                    Voir détails <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($stats['depenses'], 2) }} €</h3>
                    <p>Dépenses</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <a href="{{ route('admin.finance.transactions', ['type' => 'expense']) }}" class="small-box-footer">
                    Voir détails <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box {{ $stats['benefice'] >= 0 ? 'bg-info' : 'bg-warning' }}">
                <div class="inner">
                    <h3>{{ number_format($stats['benefice'], 2) }} €</h3>
                    <p>Bénéfice net</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Période en cours <i class="fas fa-info-circle"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ number_format($stats['commissions'], 2) }} €</h3>
                    <p>Commissions payées</p>
                    <small class="text-white">{{ number_format($stats['commissions_pending'], 2) }} € en attente</small>
                </div>
                <div class="icon">
                    <i class="fas fa-percent"></i>
                </div>
                <a href="{{ route('admin.finance.commissions') }}" class="small-box-footer">
                    Gérer les commissions <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Graphique des revenus -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-2"></i>
                        Évolution des revenus (12 derniers mois)
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Répartition des dépenses
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 250px;">
                        <canvas id="expensePieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top produits et commissions -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-crown mr-2"></i>
                        Top 10 produits
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-right">Qté vendue</th>
                                    <th class="text-right">CA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProduits as $produit)
                                <tr>
                                    <td>{{ Str::limit($produit->formatted_name, 40) }}</td>
                                    <td class="text-right">{{ $produit->total_quantity }}</td>
                                    <td class="text-right text-success font-weight-bold">
                                        {{ number_format($produit->total_revenue, 2) }} €
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3">Aucune donnée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-store mr-2"></i>
                        Top vendeurs (commissions)
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.finance.commissions') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-cog mr-1"></i> Gérer
                        </a>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Vendeur</th>
                                    <th class="text-right">Commissions</th>
                                    <th class="text-center">En attente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commissionsParVendeur as $vendeur)
                                <tr>
                                    <td>
                                        {{ $vendeur->admin_shop_name ?? $vendeur->shop_name }}
                                    </td>
                                    <td class="text-right text-success font-weight-bold">
                                        {{ number_format($vendeur->commissions_sum_amount ?? 0, 2) }} €
                                    </td>
                                    <td class="text-center">
                                        @if($vendeur->commissions_count > 0)
                                            <span class="badge bg-warning">{{ $vendeur->commissions_count }}</span>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3">Aucune donnée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>
                        Dernières transactions
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.finance.transactions') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-list mr-1"></i> Voir tout
                        </a>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Référence</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th class="text-right">Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_date->format('d/m/Y H:i') }}</td>
                                    <td><code>{{ $transaction->reference }}</code></td>
                                    <td>{{ Str::limit($transaction->description, 40) }}</td>
                                    <td>
                                        @if($transaction->type == 'income')
                                            <span class="badge bg-success">Revenu</span>
                                        @else
                                            <span class="badge bg-danger">Dépense</span>
                                        @endif
                                    </td>
                                    <td class="text-right {{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }} font-weight-bold">
                                        {{ $transaction->type == 'income' ? '+' : '-' }} {{ number_format($transaction->total, 2) }} €
                                    </td>
                                    <td>
                                        @if($transaction->status == 'completed')
                                            <span class="badge bg-success">Complété</span>
                                        @elseif($transaction->status == 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($transaction->status == 'cancelled')
                                            <span class="badge bg-danger">Annulé</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $transaction->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">Aucune transaction</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .small-box {
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .small-box .inner {
        padding: 15px;
    }
    .small-box h3 {
        font-size: 2.2rem;
    }
    .small-box .icon {
        font-size: 70px;
    }
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    .chart-container {
        width: 100%;
        min-height: 250px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .small-box h3 {
            font-size: 1.6rem;
        }
        .small-box .icon {
            font-size: 50px;
        }
        .card-header .card-tools {
            margin-top: 10px;
        }
    }
    
    @media (max-width: 576px) {
        .small-box h3 {
            font-size: 1.4rem;
        }
        .small-box .inner {
            padding: 10px;
        }
        .table {
            font-size: 0.85rem;
        }
        .badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du mode sombre
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#d1d5db' : '#374151';
    const gridColor = isDarkMode ? '#4b5563' : '#e5e7eb';

    // Graphique des revenus
    const months = @json($revenusParMois->map(function($item) {
        return \Carbon\Carbon::createFromDate($item->year, $item->month, 1)->format('M Y');
    }));
    
    const revenues = @json($revenusParMois->pluck('total'));

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenus (€)',
                data: revenues,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: { color: textColor }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: textColor, maxRotation: 45, minRotation: 45 }
                }
            }
        }
    });

    // Graphique des dépenses par catégorie (données statiques pour éviter l'appel AJAX)
    @php
        $expenseCategories = App\Models\FinancialTransaction::where('type', 'expense')
            ->select('category', DB::raw('SUM(total) as total'))
            ->groupBy('category')
            ->get();
    @endphp
    
    const expenseLabels = @json($expenseCategories->pluck('category'));
    const expenseValues = @json($expenseCategories->pluck('total'));

    if (expenseLabels.length > 0) {
        new Chart(document.getElementById('expensePieChart'), {
            type: 'doughnut',
            data: {
                labels: expenseLabels,
                datasets: [{
                    data: expenseValues,
                    backgroundColor: ['#dc3545', '#fd7e14', '#ffc107', '#17a2b8', '#6c757d', '#20c997', '#e83e8c']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColor }
                    }
                }
            }
        });
    } else {
        document.getElementById('expensePieChart').parentElement.innerHTML = 
            '<p class="text-center text-muted py-5">Données non disponibles</p>';
    }
});
</script>
@endpush