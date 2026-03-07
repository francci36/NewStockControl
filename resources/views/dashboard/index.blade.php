@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-gradient-primary">Tableau de bord</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-right d-flex align-items-center flex-wrap">
                        <span class="badge bg-gradient-info mr-2 mb-2" id="lastUpdateBadge">
                            <i class="fas fa-clock mr-1"></i> <span id="lastUpdateTime">-</span>
                        </span>
                        <span class="badge bg-gradient-success mb-2" id="autoRefreshBadge">
                            <i class="fas fa-sync-alt mr-1"></i> Auto: 30s
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Indicateur de chargement -->
            <div id="loadingOverlay" class="loading-overlay" style="display: none;">
                <div class="loading-content">
                    <div class="spinner-border text-gold" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2 text-white">Mise à jour du tableau de bord...</p>
                </div>
            </div>

            <!-- Petites boîtes de statistiques -->
            <div class="row" id="statsRow">
                <!-- Les stats seront chargées dynamiquement -->
            </div>

            <!-- Tableau des produits à faible stock -->
            <div class="row mt-4" id="lowStockSection">
                <!-- Les produits à faible stock seront chargés dynamiquement -->
            </div>

            <!-- Filtres -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card card-gradient">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-1 text-gold"></i>
                                Filtres
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-outline-gold" id="refreshBtn">
                                    <i class="fas fa-sync-alt mr-1"></i> Actualiser
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success ml-2" id="toggleAutoRefresh">
                                    <i class="fas fa-play mr-1"></i> Auto
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label for="filterPeriod">Période</label>
                                    <select id="filterPeriod" class="form-control">
                                        <option value="7">7 derniers jours</option>
                                        <option value="30" selected>30 derniers jours</option>
                                        <option value="90">90 derniers jours</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label for="filterCategory">Catégorie</label>
                                    <select id="filterCategory" class="form-control">
                                        <option value="all">Toutes les catégories</option>
                                        <!-- Les catégories seront chargées dynamiquement -->
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="filterStatus">Statut stock</label>
                                    <select id="filterStatus" class="form-control">
                                        <option value="all">Tous les statuts</option>
                                        <option value="low">Stock bas uniquement</option>
                                        <option value="out">Rupture de stock</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="row mt-4">
                <!-- Graphique des stocks -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="card card-gradient">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1 text-gold"></i>
                                Évolution des stocks par produit
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus text-white-50"></i>
                                </button>
                                <button id="exportStockChart" class="btn btn-tool">
                                    <i class="fas fa-download text-white-50"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 250px;">
                                <canvas id="stockChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphique en camembert -->
                <div class="col-lg-4">
                    <div class="card card-gradient">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1 text-gold"></i>
                                Répartition des stocks
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus text-white-50"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 250px;">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique des transactions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card card-gradient">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1 text-gold"></i>
                                Évolution des transactions
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus text-white-50"></i>
                                </button>
                                <button id="exportTransactionsChart" class="btn btn-tool mr-2">
                                    <i class="fas fa-download text-white-50"></i>
                                </button>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-gold" id="showAllTransactions">Tous</button>
                                    <button type="button" class="btn btn-sm btn-outline-success" id="showEntries">Entrées</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="showExits">Sorties</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 250px;">
                                <canvas id="transactionsChart"></canvas>
                            </div>
                            
                            <!-- Stats des transactions -->
                            <div id="transactionStats" class="row mt-4">
                                <!-- Chargé dynamiquement -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commandes récentes -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card card-gradient">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-1 text-gold"></i>
                                Commandes récentes
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-gold">
                                    Voir toutes <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <div id="recentOrdersTable">
                                    <!-- Tableau chargé dynamiquement -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>

<style>
/* Gradients */
.text-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #cb356b 0%, #bd3f32 100%) !important;
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #757f9a 0%, #d7dde8 100%) !important;
}

/* Cards */
.card-gradient {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border: none;
    border-bottom: 3px solid #f7d44a;
    color: white;
}

.card-gradient .card-header {
    background: transparent;
    border-bottom: 1px solid rgba(247, 212, 74, 0.3);
    color: white;
}

.card-gradient .card-title {
    color: white;
}

/* Gold color */
.text-gold {
    color: #f7d44a !important;
}
.bg-gold {
    background-color: #f7d44a !important;
}
.border-gold {
    border-color: #f7d44a !important;
}
.btn-outline-gold {
    color: #f7d44a;
    border-color: #f7d44a;
}
.btn-outline-gold:hover {
    background-color: #f7d44a;
    color: #1a1a2e;
}

/* Small boxes */
.small-box {
    border-radius: 0.5rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    border-bottom: 3px solid #f7d44a;
}

.small-box > .inner {
    padding: 15px;
}

.small-box > .small-box-footer {
    background: rgba(0,0,0,0.1);
    color: rgba(255,255,255,0.8);
    display: block;
    padding: 8px 0;
    position: relative;
    text-align: center;
    text-decoration: none;
    z-index: 10;
}

.small-box > .small-box-footer:hover {
    background: rgba(0,0,0,0.15);
    color: #fff;
}

.small-box .icon {
    color: rgba(255,255,255,0.15);
    position: absolute;
    right: 10px;
    top: 10px;
    z-index: 0;
}

.small-box .icon > i {
    font-size: 70px;
}

/* Loading overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    backdrop-filter: blur(3px);
}

.loading-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

/* Badges */
#autoRefreshBadge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.6; }
    100% { opacity: 1; }
}

/* Table dans les cartes */
.card-gradient .table {
    color: white;
    margin-bottom: 0;
}

.card-gradient .table thead th {
    border-bottom-color: rgba(247, 212, 74, 0.3);
    color: #f7d44a;
}

.card-gradient .table td,
.card-gradient .table th {
    border-top-color: rgba(247, 212, 74, 0.1);
    vertical-align: middle;
}

.card-gradient .table tbody tr:hover {
    background-color: rgba(247, 212, 74, 0.1);
}

/* Text white with opacity */
.text-white-50 {
    color: rgba(255, 255, 255, 0.5) !important;
}

/* Formulaires dans les cartes */
.card-gradient .form-control {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(247, 212, 74, 0.3);
    color: white;
}

.card-gradient .form-control:focus {
    background-color: rgba(255, 255, 255, 0.15);
    border-color: #f7d44a;
    box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25);
}

.card-gradient .form-control option {
    background-color: #1a1a2e;
    color: white;
}

.card-gradient label {
    color: #f7d44a;
    font-weight: 500;
}

/* Boutons du groupe */
.btn-group {
    flex-wrap: wrap;
}

.btn-group .btn {
    border-radius: 0;
    margin: 2px 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}

/* Chart container */
.chart-container {
    position: relative;
    width: 100%;
    min-height: 250px;
}

/* ===== RESPONSIVE FIXES ===== */
@media (max-width: 768px) {
    .small-box .icon > i {
        font-size: 50px;
    }
    
    .small-box > .inner {
        padding: 10px;
    }
    
    .small-box h3 {
        font-size: 1.5rem;
    }
    
    .btn-group {
        width: 100%;
        margin-top: 10px;
    }
    
    .btn-group .btn {
        flex: 1;
        font-size: 0.8rem;
        padding: 6px 8px;
    }
    
    .card-header {
        flex-wrap: wrap;
    }
    
    .card-tools {
        margin-top: 10px;
        width: 100%;
    }
    
    .card-tools .btn-group {
        width: 100%;
    }
    
    .card-tools .btn-group .btn {
        width: 33.33%;
    }
    
    .card-gradient .table {
        font-size: 0.85rem;
    }
    
    .card-gradient .table td,
    .card-gradient .table th {
        padding: 8px 5px;
    }
    
    .card-gradient .btn-sm {
        padding: 4px 6px;
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .small-box .icon > i {
        font-size: 40px;
    }
    
    .small-box h3 {
        font-size: 1.2rem;
    }
    
    .small-box p {
        font-size: 0.8rem;
    }
    
    .card-gradient .table {
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 4px 6px;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 0.7rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // État de l'application
    let autoRefresh = true;
    let refreshInterval = 30000; // 30 secondes
    let lastUpdate = null;
    let charts = {};

    // Initialisation
    initCharts();
    loadDashboardData();
    loadTransactionsData();
    startAutoRefresh();

    // Écouteurs d'événements
    document.getElementById('refreshBtn').addEventListener('click', function() {
        refreshAllData();
    });

    document.getElementById('toggleAutoRefresh').addEventListener('click', function() {
        autoRefresh = !autoRefresh;
        const btn = this;
        
        if (autoRefresh) {
            btn.innerHTML = '<i class="fas fa-play mr-1"></i> Auto';
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-outline-success');
            startAutoRefresh();
        } else {
            btn.innerHTML = '<i class="fas fa-pause mr-1"></i> Auto';
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-outline-danger');
            stopAutoRefresh();
        }
    });

    document.getElementById('filterPeriod').addEventListener('change', function() {
        if (autoRefresh) {
            refreshAllData();
        }
    });

    // Boutons de contrôle du graphique des transactions
    document.getElementById('showAllTransactions').addEventListener('click', function() {
        showTransactionDataset(0);
    });
    
    document.getElementById('showEntries').addEventListener('click', function() {
        showTransactionDataset(1);
    });
    
    document.getElementById('showExits').addEventListener('click', function() {
        showTransactionDataset(2);
    });

    // Export des graphiques
    document.getElementById('exportStockChart').addEventListener('click', function() {
        exportChart(charts.stock, 'graphique_stocks.png');
    });

    document.getElementById('exportTransactionsChart').addEventListener('click', function() {
        exportChart(charts.transactions, 'graphique_transactions.png');
    });

    // Fonctions principales
    function initCharts() {
        const isDarkMode = document.documentElement.classList.contains('dark');
        const gridColor = 'rgba(247, 212, 74, 0.1)';
        const tickColor = '#f7d44a';

        // Configuration commune
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: { color: tickColor }
                },
                x: {
                    grid: { color: gridColor },
                    ticks: { color: tickColor }
                }
            },
            plugins: {
                legend: { 
                    labels: { color: tickColor },
                    onClick: function(e, legendItem, legend) {
                        const index = legendItem.datasetIndex;
                        const ci = legend.chart;
                        const meta = ci.getDatasetMeta(index);
                        meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;
                        ci.update();
                    }
                },
                tooltip: {
                    backgroundColor: '#1a1a2e',
                    titleColor: '#f7d44a',
                    bodyColor: '#fff',
                    borderColor: '#f7d44a',
                    borderWidth: 1
                }
            }
        };

        // Graphique des stocks (barres)
        charts.stock = new Chart(document.getElementById('stockChart'), {
            type: 'bar',
            data: { 
                labels: [], 
                datasets: [{
                    label: 'Quantité en stock',
                    data: [],
                    backgroundColor: [],
                    borderWidth: 1,
                    borderColor: '#f7d44a'
                }]
            },
            options: commonOptions
        });

        // Graphique en camembert
        charts.pie = new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: { 
                labels: [], 
                datasets: [{
                    data: [],
                    backgroundColor: [
                        '#f7d44a',
                        '#667eea',
                        '#38ef7d',
                        '#f5576c',
                        '#00d2ff',
                        '#764ba2'
                    ],
                    borderWidth: 1,
                    borderColor: '#1a1a2e'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    legend: { position: 'top', labels: { color: tickColor } }
                }
            }
        });

        // Graphique des transactions
        charts.transactions = new Chart(document.getElementById('transactionsChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Toutes les transactions',
                        data: [],
                        borderColor: '#f7d44a',
                        backgroundColor: 'rgba(247, 212, 74, 0.1)',
                        tension: 0.4,
                        borderWidth: 2
                    },
                    {
                        label: 'Entrées',
                        data: [],
                        borderColor: '#38ef7d',
                        backgroundColor: 'rgba(56, 239, 125, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        hidden: true
                    },
                    {
                        label: 'Sorties',
                        data: [],
                        borderColor: '#f5576c',
                        backgroundColor: 'rgba(245, 87, 108, 0.1)',
                        tension: 0.4,
                        borderWidth: 2,
                        hidden: true
                    }
                ]
            },
            options: commonOptions
        });
    }

    function loadDashboardData() {
        showLoading();
        
        fetch('/admin/dashboard/data')
            .then(response => response.json())
            .then(data => {
                updateStats(data.stats);
                updateLowStock(data.lowStockProducts);
                updateRecentOrders(data.recentOrders);
                updateStockCharts(data.stockChart);
                
                if (data.lastUpdate) {
                    lastUpdate = data.lastUpdate;
                    document.getElementById('lastUpdateTime').textContent = lastUpdate;
                }
                
                hideLoading();
            })
            .catch(error => {
                console.error('Erreur chargement dashboard:', error);
                hideLoading();
            });
    }

    function loadTransactionsData() {
        const period = document.getElementById('filterPeriod').value;
        
        fetch(`/admin/dashboard/transactions-data?period=${period}`)
            .then(response => response.json())
            .then(data => {
                updateTransactionsChart(data);
                updateTransactionStats(data.totals);
            })
            .catch(error => console.error('Erreur chargement transactions:', error));
    }

    function refreshAllData() {
        loadDashboardData();
        loadTransactionsData();
    }

    function updateStats(stats) {
        const statsHtml = `
            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-gradient-info">
                    <div class="inner">
                        <h3>${stats.totalSuppliers}</h3>
                        <p>Fournisseurs</p>
                    </div>
                    <div class="icon"><i class="fas fa-truck"></i></div>
                    <a href="{{ route('admin.suppliers.index') }}" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-gradient-success">
                    <div class="inner">
                        <h3>${stats.totalProducts}</h3>
                        <p>Produits (catalogue)</p>
                    </div>
                    <div class="icon"><i class="fas fa-box"></i></div>
                    <a href="{{ route('admin.products.index') }}" class="small-box-footer">Voir catalogue <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-gradient-primary">
                    <div class="inner">
                        <h3>${stats.totalStockQuantity}</h3>
                        <p>Unités en stock</p>
                    </div>
                    <div class="icon"><i class="fas fa-warehouse"></i></div>
                    <a href="{{ route('admin.stocks.index') }}" class="small-box-footer">Gérer les stocks <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-gradient-warning">
                    <div class="inner">
                        <h3>${stats.pendingOrders}</h3>
                        <p>Commandes en attente</p>
                    </div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                    <a href="{{ route('admin.orders.index') }}" class="small-box-footer">Voir commandes <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-gradient-danger">
                    <div class="inner">
                        <h3>${stats.lowStockCount}</h3>
                        <p>Produits à réapprovisionner</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <a href="#lowStockSection" class="small-box-footer">Voir détails <i class="fas fa-arrow-circle-down"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12">
                <div class="small-box bg-gradient-secondary">
                    <div class="inner">
                        <h3>${stats.completedOrders}</h3>
                        <p>Commandes arrivées</p>
                    </div>
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                    <a href="{{ route('admin.orders.index') }}" class="small-box-footer">Voir historique <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        `;
        
        document.getElementById('statsRow').innerHTML = statsHtml;
    }

    function updateLowStock(products) {
        if (!products || products.length === 0) {
            document.getElementById('lowStockSection').innerHTML = '';
            return;
        }

        const productsHtml = `
            <div class="col-12">
                <div class="card card-gradient" id="lowStockTable">
                    <div class="card-header bg-gradient-danger">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Produits à réapprovisionner (${products.length})
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus text-white"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Stock</th>
                                        <th>Seuil</th>
                                        <th>Fournisseur</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${products.map(p => `
                                        <tr>
                                            <td>${p.admin_name || p.name}</td>
                                            <td>
                                                <span class="badge ${p.quantity <= 0 ? 'bg-danger' : 'bg-warning'}">
                                                    ${p.quantity}
                                                </span>
                                            </td>
                                            <td>${p.stock_threshold || 5}</td>
                                            <td>${p.supplier ? (p.supplier.admin_name || p.supplier.name) : 'N/A'}</td>
                                            <td>
                                                <span class="badge ${p.quantity <= 0 ? 'bg-danger' : 'bg-warning'}">
                                                    ${p.quantity <= 0 ? 'Rupture' : 'Stock bas'}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/admin/orders/create/${p.supplier_id}" class="btn btn-sm btn-outline-gold">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('lowStockSection').innerHTML = productsHtml;
    }

    function updateRecentOrders(orders) {
        if (!orders || orders.length === 0) {
            document.getElementById('recentOrdersTable').innerHTML = '<p class="text-center p-3 text-white-50">Aucune commande récente</p>';
            return;
        }

        const hasClientOrders = orders.some(o => o.type === 'client');
        const hasSupplierOrders = orders.some(o => o.type === 'supplier');
        
        let columnTitle = 'Fournisseur/Client';
        if (hasClientOrders && !hasSupplierOrders) {
            columnTitle = 'Client';
        } else if (!hasClientOrders && hasSupplierOrders) {
            columnTitle = 'Fournisseur';
        }

        const ordersHtml = `
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>${columnTitle}</th>
                        <th>Date</th>
                        <th>Produits</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    ${orders.map(o => {
                        const displayName = o.type === 'client' 
                            ? (o.client_name || o.supplier_name || 'Client')
                            : (o.supplier_name || 'Fournisseur');
                        
                        const icon = o.type === 'client' 
                            ? '<i class="fas fa-user text-info mr-1"></i>'
                            : '<i class="fas fa-truck text-primary mr-1"></i>';
                        
                        return `
                            <tr>
                                <td>#${o.id}</td>
                                <td>
                                    ${icon} ${displayName}
                                </td>
                                <td>${o.date}</td>
                                <td>${o.products_count} produit(s)</td>
                                <td>
                                    <span class="badge ${o.status === 'pending' ? 'bg-warning' : 'bg-success'}">
                                        ${o.status === 'pending' ? 'En attente' : 'Arrivé'}
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/orders/${o.id}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
        `;
        
        document.getElementById('recentOrdersTable').innerHTML = ordersHtml;
    }

    function updateStockCharts(stockData) {
        const colors = stockData.quantities.map(q => 
            q < 4 ? '#f5576c' : 
            q < 10 ? '#f7d44a' : 
            '#38ef7d'
        );

        charts.stock.data.labels = stockData.labels;
        charts.stock.data.datasets[0].data = stockData.quantities;
        charts.stock.data.datasets[0].backgroundColor = colors;
        charts.stock.update();

        charts.pie.data.labels = stockData.labels;
        charts.pie.data.datasets[0].data = stockData.quantities;
        charts.pie.update();
    }

    function updateTransactionsChart(transactionData) {
        charts.transactions.data.labels = transactionData.labels;
        charts.transactions.data.datasets[0].data = transactionData.counts;
        charts.transactions.data.datasets[1].data = transactionData.entries;
        charts.transactions.data.datasets[2].data = transactionData.exits;
        charts.transactions.update();
    }

    function updateTransactionStats(totals) {
        const statsDiv = document.getElementById('transactionStats');
        if (!statsDiv) return;
        
        statsDiv.innerHTML = `
            <div class="col-md-4 col-12 mb-3 mb-md-0">
                <div class="small-box bg-gradient-info">
                    <div class="inner">
                        <h3 class="text-white">${totals.total}</h3>
                        <p class="text-white-50">Total</p>
                    </div>
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>
            <div class="col-md-4 col-12 mb-3 mb-md-0">
                <div class="small-box bg-gradient-success">
                    <div class="inner">
                        <h3 class="text-white">${totals.entries}</h3>
                        <p class="text-white-50">Entrées</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-up"></i></div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="small-box bg-gradient-danger">
                    <div class="inner">
                        <h3 class="text-white">${totals.exits}</h3>
                        <p class="text-white-50">Sorties</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-down"></i></div>
                </div>
            </div>
        `;
    }

    function showTransactionDataset(index) {
        charts.transactions.data.datasets.forEach((dataset, i) => {
            charts.transactions.data.datasets[i].hidden = (i !== index);
        });
        charts.transactions.update();
    }

    function exportChart(chart, filename) {
        const link = document.createElement('a');
        link.href = chart.toBase64Image();
        link.download = filename;
        link.click();
    }

    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'block';
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    function startAutoRefresh() {
        if (window.refreshTimer) clearInterval(window.refreshTimer);
        window.refreshTimer = setInterval(refreshAllData, refreshInterval);
    }

    function stopAutoRefresh() {
        if (window.refreshTimer) {
            clearInterval(window.refreshTimer);
            window.refreshTimer = null;
        }
    }
});
</script> 
@endsection