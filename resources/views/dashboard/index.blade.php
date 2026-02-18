@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tableau de bord</h1>
                </div>
                <div class="col-sm-6">
                    <div class="float-right">
                        <span class="badge bg-info" id="lastUpdateBadge">
                            <i class="fas fa-clock mr-1"></i> <span id="lastUpdateTime">-</span>
                        </span>
                        <span class="badge bg-success" id="autoRefreshBadge">
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
            <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; text-align: center;">
                    <div class="spinner-border text-light" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2">Mise à jour du tableau de bord...</p>
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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-1"></i>
                                Filtres
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-primary" id="refreshBtn">
                                    <i class="fas fa-sync-alt mr-1"></i> Actualiser
                                </button>
                                <button type="button" class="btn btn-sm btn-success" id="toggleAutoRefresh">
                                    <i class="fas fa-play mr-1"></i> Auto
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="filterPeriod">Période</label>
                                    <select id="filterPeriod" class="form-control">
                                        <option value="7">7 derniers jours</option>
                                        <option value="30" selected>30 derniers jours</option>
                                        <option value="90">90 derniers jours</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="filterCategory">Catégorie</label>
                                    <select id="filterCategory" class="form-control">
                                        <option value="all">Toutes les catégories</option>
                                        <!-- Les catégories seront chargées dynamiquement -->
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
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
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Évolution des stocks par produit
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button id="exportStockChart" class="btn btn-tool">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="stockChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Graphique en camembert -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Répartition des stocks
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique des transactions -->
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                Évolution des transactions
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button id="exportTransactionsChart" class="btn btn-tool">
                                    <i class="fas fa-download"></i>
                                </button>
                                <div class="btn-group ml-2" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="showAllTransactions">Tous</button>
                                    <button type="button" class="btn btn-sm btn-outline-success" id="showEntries">Entrées</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="showExits">Sorties</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="transactionsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            
                            <!-- Stats des transactions -->
                            <div id="transactionStats" class="mt-4 grid grid-cols-3 gap-4 text-center">
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                    <div class="text-sm text-gray-500">Chargement...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commandes récentes -->
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-1"></i>
                                Commandes récentes
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">
                                    Voir toutes <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>

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
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-success');
            startAutoRefresh();
        } else {
            btn.innerHTML = '<i class="fas fa-pause mr-1"></i> Auto';
            btn.classList.remove('btn-success');
            btn.classList.add('btn-danger');
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
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        const tickColor = isDarkMode ? 'rgba(255, 255, 255, 0.8)' : 'rgba(0, 0, 0, 0.8)';

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
                        
                        // Afficher/masquer le dataset cliqué
                        const meta = ci.getDatasetMeta(index);
                        meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;
                        ci.update();
                    }
                },
                tooltip: {
                    backgroundColor: isDarkMode ? 'rgba(0,0,0,0.8)' : 'rgba(255,255,255,0.8)',
                    titleColor: tickColor,
                    bodyColor: tickColor
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
                    borderWidth: 1
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
                    backgroundColor: [],
                    borderWidth: 1
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
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4
                    },
                    {
                        label: 'Entrées',
                        data: [],
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        tension: 0.4,
                        hidden: true
                    },
                    {
                        label: 'Sorties',
                        data: [],
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.2)',
                        tension: 0.4,
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
            <!-- Fournisseurs -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>${stats.totalSuppliers}</h3>
                        <p>Fournisseurs</p>
                    </div>
                    <div class="icon"><i class="fas fa-truck"></i></div>
                    <a href="{{ route('admin.suppliers.index') }}" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Produits (catalogue) -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>${stats.totalProducts}</h3>
                        <p>Produits (catalogue)</p>
                    </div>
                    <div class="icon"><i class="fas fa-box"></i></div>
                    <a href="{{ route('admin.products.index') }}" class="small-box-footer">Voir catalogue <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Unités en stock -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>${stats.totalStockQuantity}</h3>
                        <p>Unités en stock</p>
                    </div>
                    <div class="icon"><i class="fas fa-warehouse"></i></div>
                    <a href="{{ route('admin.stocks.index') }}" class="small-box-footer">Gérer les stocks <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Commandes en attente -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>${stats.pendingOrders}</h3>
                        <p>Commandes en attente</p>
                    </div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                    <a href="{{ route('admin.orders.index') }}" class="small-box-footer">Voir commandes <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Deuxième ligne -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>${stats.lowStockCount}</h3>
                        <p>Produits à réapprovisionner</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <a href="#lowStockSection" class="small-box-footer">Voir détails <i class="fas fa-arrow-circle-down"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
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
            <div class="col-lg-12">
                <div class="card" id="lowStockTable">
                    <div class="card-header bg-danger text-white">
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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Stock actuel</th>
                                    <th>Seuil</th>
                                    <th>Fournisseur</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${products.map(p => `
                                    <tr>
                                        <td>${p.name}</td>
                                        <td>
                                            <span class="badge ${p.quantity <= 0 ? 'bg-danger' : 'bg-warning'}">
                                                ${p.quantity}
                                            </span>
                                        </td>
                                        <td>${p.threshold}</td>
                                        <td>${p.supplier_name}</td>
                                        <td>
                                            <span class="badge ${p.quantity <= 0 ? 'bg-danger' : 'bg-warning'}">
                                                ${p.quantity <= 0 ? 'Rupture' : 'Stock bas'}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/admin/orders/create/${p.supplier_id}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-shopping-cart"></i> Commander
                                            </a>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('lowStockSection').innerHTML = productsHtml;
    }

    function updateRecentOrders(orders) {
        if (!orders || orders.length === 0) {
            document.getElementById('recentOrdersTable').innerHTML = '<p class="text-center p-3">Aucune commande récente</p>';
            return;
        }

        // Analyser les types de commandes pour déterminer le titre de colonne approprié
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
                                    ${o.type === 'client' ? '<small class="text-muted ml-1">(client)</small>' : ''}
                                </td>
                                <td>${o.date}</td>
                                <td>${o.products_count} produit(s)</td>
                                <td>
                                    <span class="badge ${o.status === 'en attente' ? 'bg-warning' : 'bg-success'}">
                                        ${o.status === 'en attente' ? 'En attente' : 'Arrivé'}
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/orders/${o.id}" class="btn btn-sm btn-info">
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
        // Couleurs dynamiques
        const colors = stockData.quantities.map(q => 
            q < 4 ? 'rgba(255, 99, 132, 0.8)' : 
            q < 10 ? 'rgba(255, 206, 86, 0.8)' : 
            'rgba(75, 192, 192, 0.8)'
        );

        // Mettre à jour le graphique des stocks
        charts.stock.data.labels = stockData.labels;
        charts.stock.data.datasets[0].data = stockData.quantities;
        charts.stock.data.datasets[0].backgroundColor = colors;
        charts.stock.update();

        // Mettre à jour le camembert
        charts.pie.data.labels = stockData.labels;
        charts.pie.data.datasets[0].data = stockData.quantities;
        charts.pie.data.datasets[0].backgroundColor = colors;
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
            <div class="bg-blue-50 dark:bg-blue-900 p-3 rounded-lg">
                <div class="text-sm text-gray-600 dark:text-gray-300">Total</div>
                <div class="text-xl font-bold text-blue-700 dark:text-blue-400">${totals.total}</div>
            </div>
            <div class="bg-green-50 dark:bg-green-900 p-3 rounded-lg">
                <div class="text-sm text-gray-600 dark:text-gray-300">Entrées</div>
                <div class="text-xl font-bold text-green-700 dark:text-green-400">${totals.entries}</div>
            </div>
            <div class="bg-red-50 dark:bg-red-900 p-3 rounded-lg">
                <div class="text-sm text-gray-600 dark:text-gray-300">Sorties</div>
                <div class="text-xl font-bold text-red-700 dark:text-red-400">${totals.exits}</div>
            </div>
        `;
    }

    function showTransactionDataset(index) {
        // Masquer tous les datasets
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

<style>
.small-box {
    border-radius: 0.25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 20px;
    position: relative;
}

.small-box > .inner {
    padding: 10px;
}

.small-box > .small-box-footer {
    background: rgba(0,0,0,0.1);
    color: rgba(255,255,255,0.8);
    display: block;
    padding: 3px 0;
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
    color: rgba(0,0,0,0.15);
    position: absolute;
    right: 10px;
    top: 10px;
    z-index: 0;
}

.small-box .icon > i {
    font-size: 70px;
}

#autoRefreshBadge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.6; }
    100% { opacity: 1; }
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}
</style>
@endsection