

<?php $__env->startSection('title', __('messages.vendor_orders')); ?>
<?php $__env->startSection('page-title', __('messages.vendor_orders')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart mr-2"></i> <?php echo e(__('messages.vendor_orders')); ?>

            </h5>
            <span class="badge bg-light text-dark"><?php echo e($orders->total()); ?> commande(s)</span>
        </div>
        
        <div class="card-body">
            <!-- Filtres -->
            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <form method="GET" action="<?php echo e(route('vendor.orders.index')); ?>" class="form-inline w-100">
                        <div class="input-group w-100">
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="all"><?php echo e(__('Tous les statuts')); ?></option>
                                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>En attente</option>
                                <option value="processing" <?php echo e(request('status') == 'processing' ? 'selected' : ''); ?>>En préparation</option>
                                <option value="shipped" <?php echo e(request('status') == 'shipped' ? 'selected' : ''); ?>>Expédiée</option>
                                <option value="delivered" <?php echo e(request('status') == 'delivered' ? 'selected' : ''); ?>>Livrée</option>
                                <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Annulée</option>
                            </select>
                            <?php if(request('status') && request('status') != 'all'): ?>
                            <div class="input-group-append">
                                <a href="<?php echo e(route('vendor.orders.index')); ?>" class="btn btn-outline-secondary" title="Réinitialiser">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            
            <?php if($orders->isEmpty()): ?>
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <p class="text-muted"><?php echo e(__('messages.vendor_no_orders')); ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th class="d-none d-md-table-cell">ID</th>
                                <th>Date</th>
                                <th>Client</th>
                                <th class="d-none d-sm-table-cell">Produits</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="d-none d-md-table-cell font-weight-bold">#<?php echo e($order->id); ?></td>
                                <td><?php echo e($order->created_at->format('d/m/Y')); ?></td>
                                <td>
                                    <div>
                                        <strong><?php echo e($order->user->name ?? 'Client inconnu'); ?></strong>
                                        <span class="d-block d-md-none small text-muted mt-1">
                                            <?php echo e($order->user->email ?? ''); ?>

                                        </span>
                                        <!-- Infos supplémentaires sur mobile -->
                                        <?php
                                            $vendorItems = $order->items->filter(function($item) use ($vendor) {
                                                return $item->vendor_id == $vendor->id;
                                            });
                                            $productCount = $vendorItems->count();
                                            $unitCount = $vendorItems->sum('quantity');
                                        ?>
                                        <span class="d-block d-md-none small text-muted mt-1">
                                            <span class="badge bg-info"><?php echo e($productCount); ?> prod.</span>
                                            <span class="badge bg-secondary"><?php echo e($unitCount); ?> unités</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <?php
                                        $vendorItems = $order->items->filter(function($item) use ($vendor) {
                                            return $item->vendor_id == $vendor->id;
                                        });
                                        $productCount = $vendorItems->count();
                                        $unitCount = $vendorItems->sum('quantity');
                                    ?>
                                    <span class="badge bg-info"><?php echo e($productCount); ?> produit(s)</span>
                                    <span class="badge bg-secondary"><?php echo e($unitCount); ?> unité(s)</span>
                                </td>
                                <td>
                                    <?php
                                        $vendorTotal = $vendorItems->sum(function($item) {
                                            return $item->quantity * $item->price;
                                        });
                                    ?>
                                    <strong class="text-success"><?php echo e(number_format($vendorTotal, 2)); ?> €</strong>
                                </td>
                                <td>
                                    <?php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'En attente',
                                            'processing' => 'En prépa',
                                            'shipped' => 'Expédiée',
                                            'delivered' => 'Livrée',
                                            'cancelled' => 'Annulée',
                                        ];
                                    ?>
                                    <span class="badge bg-<?php echo e($statusColors[$order->status] ?? 'secondary'); ?> status-badge">
                                        <i class="fas 
                                            <?php if($order->status == 'pending'): ?> fa-clock
                                            <?php elseif($order->status == 'processing'): ?> fa-cog
                                            <?php elseif($order->status == 'shipped'): ?> fa-truck
                                            <?php elseif($order->status == 'delivered'): ?> fa-check-circle
                                            <?php elseif($order->status == 'cancelled'): ?> fa-times-circle
                                            <?php endif; ?> mr-1 d-none d-sm-inline"></i>
                                        <?php echo e($statusLabels[$order->status] ?? $order->status); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo e(route('vendor.orders.show', $order->id)); ?>" 
                                           class="btn btn-info" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($order->status == 'pending'): ?>
                                        <button type="button" class="btn btn-success" 
                                                onclick="updateStatus(<?php echo e($order->id); ?>, 'processing')"
                                                title="Passer en préparation">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($orders->appends(request()->except('page'))->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

/* Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    font-size: 0.85rem;
    white-space: nowrap;
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
.dark .badge.bg-info {
    background: #0f3460 !important;
}
.dark .badge.bg-secondary {
    background: #4a4a6a !important;
}

/* ===== STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    .table thead th {
        padding: 10px 5px;
        font-size: 0.85rem;
    }
    
    .table tbody td {
        padding: 10px 5px;
        font-size: 0.85rem;
    }
    
    .status-badge {
        padding: 5px 8px;
        font-size: 0.8rem;
    }
    
    .btn-group .btn {
        padding: 5px 8px;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 4px 6px;
    }
}

@media (max-width: 576px) {
    .table thead th {
        padding: 8px 3px;
        font-size: 0.8rem;
    }
    
    .table tbody td {
        padding: 8px 3px;
        font-size: 0.8rem;
    }
    
    .status-badge {
        padding: 4px 6px;
        font-size: 0.75rem;
    }
    
    .btn-group .btn {
        padding: 4px 6px;
    }
    
    .btn-group .btn i {
        margin: 0;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 3px 5px;
    }
}

/* Classes utilitaires */
.gap-3 { gap: 1rem; }
</style>

<script>
function updateStatus(orderId, status) {
    if (confirm('Confirmer le changement de statut ?')) {
        fetch(`/vendor/orders/${orderId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors du changement de statut');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('vendor.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\franc\NewStockControl\resources\views/vendor/orders/index.blade.php ENDPATH**/ ?>