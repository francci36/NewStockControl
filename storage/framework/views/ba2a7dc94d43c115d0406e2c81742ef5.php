

<?php $__env->startSection('title', __('messages.vendor_dashboard')); ?>

<?php $__env->startSection('page-title', __('messages.vendor_dashboard')); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistiques -->
<div class="row g-3 mb-4">
    <!-- Produits -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info h-100">
            <div class="inner">
                <h3 class="stats-number"><?php echo e($stats['total_products']); ?></h3>
                <p class="stats-label"><?php echo e(__('messages.vendor_products')); ?></p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <a href="<?php echo e(route('vendor.products.index')); ?>" class="small-box-footer">
                <span class="d-none d-sm-inline"><?php echo e(__('messages.view_details')); ?></span>
                <span class="d-sm-none"><i class="fas fa-arrow-right"></i></span>
            </a>
        </div>
    </div>

    <!-- Commandes totales -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success h-100">
            <div class="inner">
                <h3 class="stats-number"><?php echo e($stats['total_orders']); ?></h3>
                <p class="stats-label"><?php echo e(__('messages.vendor_orders')); ?></p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="<?php echo e(route('vendor.orders.index')); ?>" class="small-box-footer">
                <span class="d-none d-sm-inline"><?php echo e(__('messages.view_details')); ?></span>
                <span class="d-sm-none"><i class="fas fa-arrow-right"></i></span>
            </a>
        </div>
    </div>

    <!-- Chiffre d'affaires mensuel -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning h-100">
            <div class="inner">
                <h3 class="stats-number revenue-number"><?php echo e(number_format($stats['revenue_month'], 2)); ?> €</h3>
                <p class="stats-label"><?php echo e(__('messages.monthly_revenue')); ?></p>
                <small class="text-white-50 d-block">
                    Total global: <?php echo e(number_format($stats['revenue_total'], 2)); ?> €
                </small>
                <?php if($stats['revenue_last_month'] > 0): ?>
                <small class="text-white-50">
                    Mois dernier: <?php echo e(number_format($stats['revenue_last_month'], 2)); ?> €
                </small>
                <?php endif; ?>
            </div>
            <div class="icon">
                <i class="fas fa-euro-sign"></i>
            </div>
        </div>
    </div>

    <!-- Commandes en attente -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger h-100">
            <div class="inner">
                <h3 class="stats-number"><?php echo e($stats['pending_orders']); ?></h3>
                <p class="stats-label"><?php echo e(__('messages.pending_orders')); ?></p>
                <?php if($stats['processing_orders'] > 0): ?>
                <small class="text-white-50">En préparation: <?php echo e($stats['processing_orders']); ?></small>
                <?php endif; ?>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques supplémentaires -->
<?php if($stats['avg_order_value'] > 0 || $stats['unique_customers'] > 0): ?>
<div class="row g-3 mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-chart-line mr-2 text-gold"></i> Statistiques avancées</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 border rounded">
                            <span class="text-muted d-block">Panier moyen</span>
                            <strong class="h4 text-gold"><?php echo e(number_format($stats['avg_order_value'], 2)); ?> €</strong>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 border rounded">
                            <span class="text-muted d-block">Clients uniques</span>
                            <strong class="h4 text-info"><?php echo e($stats['unique_customers']); ?></strong>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 border rounded">
                            <span class="text-muted d-block">Commandes traitées</span>
                            <strong class="h4 text-success"><?php echo e($stats['shipped_orders'] + $stats['delivered_orders']); ?></strong>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 border rounded">
                            <span class="text-muted d-block">Taux de conversion</span>
                            <strong class="h4 text-primary">
                                <?php
                                    $total = $stats['total_orders'] ?: 1;
                                    $conversion = (($stats['delivered_orders'] / $total) * 100);
                                    echo number_format($conversion, 1) . '%';
                                ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Graphiques -->
<div class="row g-3 mb-4">
    <!-- Graphique des ventes mensuelles -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line mr-2 text-gold"></i>
                    Évolution des ventes (6 derniers mois)
                </h6>
                <span class="badge bg-gold text-dark">Total: <?php echo e(number_format(array_sum($monthlySales), 2)); ?> €</span>
            </div>
            <div class="card-body p-3">
                <?php if(count($monthlySales) > 0 && array_sum($monthlySales) > 0): ?>
                    <div style="position: relative; width: 100%; height: 250px;">
                        <canvas id="salesChart" style="display: block; width: 100%; height: 100%;"></canvas>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune donnée de vente pour les 6 derniers mois</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Répartition des statuts -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie mr-2 text-gold"></i>
                    Répartition des commandes
                </h6>
            </div>
            <div class="card-body p-3">
                <?php $totalStatus = array_sum($statusDistribution); ?>
                <?php if($totalStatus > 0): ?>
                    <div style="position: relative; width: 100%; height: 200px;">
                        <canvas id="statusChart" style="display: block; width: 100%; height: 100%;"></canvas>
                    </div>
                    <div class="mt-3 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span><span class="badge bg-warning" style="width: 12px; height: 12px; display: inline-block;"></span> En attente</span>
                            <span class="fw-bold"><?php echo e($statusDistribution['pending']); ?> (<?php echo e(round(($statusDistribution['pending']/$totalStatus)*100)); ?>%)</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span><span class="badge bg-info" style="width: 12px; height: 12px; display: inline-block;"></span> En préparation</span>
                            <span class="fw-bold"><?php echo e($statusDistribution['processing']); ?> (<?php echo e(round(($statusDistribution['processing']/$totalStatus)*100)); ?>%)</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span><span class="badge bg-primary" style="width: 12px; height: 12px; display: inline-block;"></span> Expédiée</span>
                            <span class="fw-bold"><?php echo e($statusDistribution['shipped']); ?> (<?php echo e(round(($statusDistribution['shipped']/$totalStatus)*100)); ?>%)</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span><span class="badge bg-success" style="width: 12px; height: 12px; display: inline-block;"></span> Livrée</span>
                            <span class="fw-bold"><?php echo e($statusDistribution['delivered']); ?> (<?php echo e(round(($statusDistribution['delivered']/$totalStatus)*100)); ?>%)</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span><span class="badge bg-danger" style="width: 12px; height: 12px; display: inline-block;"></span> Annulée</span>
                            <span class="fw-bold"><?php echo e($statusDistribution['cancelled']); ?> (<?php echo e(round(($statusDistribution['cancelled']/$totalStatus)*100)); ?>%)</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune commande</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Top produits -->
<?php if(isset($topProducts) && $topProducts->count() > 0): ?>
<div class="row g-3 mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-trophy mr-2 text-gold"></i>
                    Top 5 des produits les plus vendus
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center p-2 border rounded">
                            <div class="me-3 text-center" style="width: 40px;">
                                <?php if($index == 0): ?>
                                    <i class="fas fa-crown text-warning fa-2x"></i>
                                <?php elseif($index == 1): ?>
                                    <i class="fas fa-medal text-secondary fa-2x"></i>
                                <?php elseif($index == 2): ?>
                                    <i class="fas fa-medal" style="color: #cd7f32; font-size: 2rem;"></i>
                                <?php else: ?>
                                    <span class="badge bg-secondary rounded-circle p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;"><?php echo e($index + 1); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <strong><?php echo e(Str::limit($product->translated_name, 30)); ?></strong>
                                <div class="small text-muted"><?php echo e($product->total_sold); ?> unité(s) vendue(s)</div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Derniers produits -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-box mr-2 text-gold"></i><?php echo e(__('messages.recent_products')); ?>

            <small class="text-muted ml-2">(<?php echo e($stats['total_products']); ?> total)</small>
        </h5>
        <a href="<?php echo e(route('vendor.products.create')); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> 
            <span class="d-none d-sm-inline"><?php echo e(__('messages.vendor_add_product')); ?></span>
        </a>
    </div>
    <div class="card-body p-0 p-md-3">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="product-col"><?php echo e(__('messages.vendor_product_name')); ?></th>
                        <th class="price-col"><?php echo e(__('messages.vendor_product_price')); ?></th>
                        <th class="stock-col"><?php echo e(__('messages.vendor_product_stock')); ?></th>
                        <th class="status-col"><?php echo e(__('messages.vendor_product_status')); ?></th>
                        <th class="action-col"><?php echo e(__('messages.actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="product-col">
                            <div class="product-info">
                                <strong><?php echo e(Str::limit($product->translated_name, 30)); ?></strong>
                                <?php if($product->category): ?>
                                    <br><small class="text-muted"><?php echo e($product->category->translated_name ?? $product->category->name); ?></small>
                                <?php endif; ?>
                                <!-- Infos supplémentaires sur mobile -->
                                <span class="d-block d-md-none small text-muted mt-1">
                                    Prix: <?php echo e(number_format($product->price, 2)); ?> € | 
                                    Stock: <?php echo e($product->quantity); ?>

                                </span>
                            </div>
                        </td>
                        <td class="price-col d-none d-md-table-cell"><?php echo e(number_format($product->price, 2)); ?> €</td>
                        <td class="stock-col">
                            <span class="badge <?php echo e($product->quantity > 0 ? ($product->quantity <= ($product->stock_threshold ?? 5) ? 'bg-warning' : 'bg-success') : 'bg-danger'); ?> badge-stock">
                                <?php echo e($product->quantity); ?>

                            </span>
                        </td>
                        <td class="status-col">
                            <?php if($product->is_active): ?>
                                <span class="badge bg-success badge-status"><?php echo e(__('messages.active')); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary badge-status"><?php echo e(__('messages.inactive')); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="action-col">
                            <a href="<?php echo e(route('vendor.products.edit', $product)); ?>" class="btn btn-sm btn-info" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted"><?php echo e(__('messages.vendor_no_products')); ?></p>
                            <a href="<?php echo e(route('vendor.products.create')); ?>" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> <?php echo e(__('messages.vendor_add_product')); ?>

                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Dernières commandes -->
<?php if(isset($recentOrders) && $recentOrders->count() > 0): ?>
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-shopping-cart mr-2 text-gold"></i>Dernières commandes
            <small class="text-muted ml-2">(<?php echo e($stats['total_orders']); ?> total)</small>
        </h5>
        <a href="<?php echo e(route('vendor.orders.index')); ?>" class="btn btn-outline-info btn-sm">
            <span class="d-none d-sm-inline">Voir toutes</span>
            <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="card-body p-0 p-md-3">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>#<?php echo e($order->id); ?></td>
                        <td><?php echo e($order->user->name ?? 'Client'); ?></td>
                        <td><?php echo e($order->created_at->format('d/m/Y')); ?></td>
                        <td class="font-weight-bold text-success"><?php echo e(number_format($order->total_amount, 2)); ?> €</td>
                        <td>
                            <?php
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
                                    'processing' => 'En prépa',
                                    'shipped' => 'Expédiée',
                                    'delivered' => 'Livrée',
                                    'arrived' => 'Arrivée',
                                    'cancelled' => 'Annulée',
                                ];
                            ?>
                            <span class="badge <?php echo e($statusColors[$order->status] ?? 'bg-secondary'); ?> status-badge">
                                <?php echo e($statusLabels[$order->status] ?? $order->status); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
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
    padding: 5px 5px 10px 0;
}
.small-box .stats-number {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 3px;
    line-height: 1.2;
    word-break: break-word;
}
.small-box .stats-label {
    font-size: 0.9rem;
    margin-bottom: 0;
    opacity: 0.9;
}
.small-box .icon {
    color: rgba(255,255,255,0.15);
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 60px;
}
.small-box-footer {
    display: block;
    padding: 5px 0;
    text-align: center;
    background: rgba(0,0,0,0.1);
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    border-radius: 0 0 0.25rem 0.25rem;
    margin-top: 5px;
    font-size: 0.85rem;
}
.small-box-footer:hover {
    background: rgba(0,0,0,0.15);
    color: #fff;
}

/* ===== STYLES POUR LE TABLEAU ===== */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    min-width: 600px;
    border-collapse: collapse;
}

.table thead th {
    background: #f8f9fa;
    padding: 12px 8px;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.table tbody td {
    padding: 12px 8px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

/* Largeurs des colonnes */
.product-col { min-width: 200px; }
.price-col { min-width: 100px; }
.stock-col { min-width: 80px; text-align: center; }
.status-col { min-width: 100px; text-align: center; }
.action-col { min-width: 60px; text-align: center; }

/* Badges */
.badge-stock {
    font-size: 0.9rem;
    padding: 0.5rem 0.8rem;
}
.badge-status {
    font-size: 0.9rem;
    padding: 0.5rem 0.8rem;
}
.status-badge {
    font-size: 0.8rem;
    padding: 0.3rem 0.6rem;
}

/* ===== STYLES POUR LES GRAPHIQUES ===== */
.chart-container {
    position: relative;
    width: 100%;
    height: 250px;
}
.chart-container-sm {
    position: relative;
    width: 100%;
    height: 200px;
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
.dark .table {
    color: #e0e0e0;
}
.dark .table thead th {
    background: #3a3a5a;
    color: #e0e0e0;
    border-bottom-color: #4a4a6a;
}
.dark .table tbody td {
    border-bottom-color: #4a4a6a;
}
.dark .bg-light {
    background: #35354a !important;
}
.dark .text-gold {
    color: #f7d44a !important;
}

/* ===== STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    /* Stats cards */
    .small-box {
        padding: 12px;
    }
    .small-box .stats-number {
        font-size: 1.4rem;
    }
    .small-box .stats-label {
        font-size: 0.8rem;
    }
    .small-box .icon {
        font-size: 45px;
    }
    .small-box-footer {
        font-size: 0.8rem;
        padding: 4px 0;
    }
    
    /* Tableau produits */
    .price-col {
        display: none;
    }
    .product-col {
        min-width: 180px;
    }
    .stock-col {
        min-width: 70px;
    }
    .status-col {
        min-width: 90px;
    }
    .badge-stock, .badge-status {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
    
    /* Graphiques */
    .chart-container {
        height: 220px;
    }
    .chart-container-sm {
        height: 180px;
    }
}

@media (max-width: 576px) {
    /* Stats cards */
    .small-box .stats-number {
        font-size: 1.2rem;
    }
    .small-box .stats-label {
        font-size: 0.7rem;
    }
    .small-box .icon {
        font-size: 40px;
        right: 5px;
        top: 5px;
    }
    .small-box-footer {
        font-size: 0.7rem;
    }
    
    /* Tableau */
    .table tbody td {
        padding: 8px 5px;
    }
    .product-col {
        min-width: 150px;
    }
    .action-col {
        min-width: 50px;
    }
    .btn-sm {
        padding: 4px 6px;
    }
    .btn-sm i {
        margin: 0;
    }
    
    /* Prix et stock affichés sous le produit */
    .product-info .small.text-muted {
        font-size: 0.7rem !important;
    }
    
    /* Graphiques */
    .chart-container {
        height: 200px;
    }
    .chart-container-sm {
        height: 160px;
    }
}

/* Classes utilitaires */
.h-100 { height: 100%; }
.g-3 { gap: 1rem; }
.text-gold { color: #f7d44a !important; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des ventes mensuelles
    const salesCanvas = document.getElementById('salesChart');
    if (salesCanvas) {
        const monthlySales = <?php echo json_encode($monthlySales, 15, 512) ?>;
        const hasSalesData = monthlySales && monthlySales.length > 0 && monthlySales.some(v => v > 0);
        
        if (hasSalesData) {
            try {
                const salesCtx = salesCanvas.getContext('2d');
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($months, 15, 512) ?>,
                        datasets: [{
                            label: 'Chiffre d\'affaires (€)',
                            data: monthlySales,
                            borderColor: '#f7d44a',
                            backgroundColor: 'rgba(247, 212, 74, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#f7d44a',
                            pointBorderColor: '#1a1a2e',
                            pointRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'CA: ' + context.raw.toFixed(2) + ' €';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)' },
                                ticks: {
                                    callback: function(value) {
                                        return value + ' €';
                                    }
                                }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            } catch (e) {
                console.error('Erreur graphique ventes:', e);
            }
        }
    }

    // Graphique des statuts
    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        const statusData = [
            <?php echo e($statusDistribution['pending']); ?>,
            <?php echo e($statusDistribution['processing']); ?>,
            <?php echo e($statusDistribution['shipped']); ?>,
            <?php echo e($statusDistribution['delivered']); ?>,
            <?php echo e($statusDistribution['cancelled']); ?>

        ];
        const hasStatusData = statusData.some(v => v > 0);
        
        if (hasStatusData) {
            try {
                const statusCtx = statusCanvas.getContext('2d');
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['En attente', 'En préparation', 'Expédiée', 'Livrée', 'Annulée'],
                        datasets: [{
                            data: statusData,
                            backgroundColor: [
                                '#ffc107',
                                '#17a2b8',
                                '#007bff',
                                '#28a745',
                                '#dc3545'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.raw + ' commande(s)';
                                    }
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            } catch (e) {
                console.error('Erreur graphique statuts:', e);
            }
        }
    }
    
    // Forcer le redimensionnement après chargement
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.dispatchEvent(new Event('resize'));
        }, 100);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('vendor.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\franc\NewStockControl\resources\views/vendor/dashboard.blade.php ENDPATH**/ ?>