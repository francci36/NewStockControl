

<?php $__env->startSection('title', __('messages.products_title')); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- En-tête de la page -->
    <div class="section-header mb-4">
        <h1 class="section-title"><?php echo e(__('messages.products')); ?></h1>
        <p class="text-muted"><?php echo e($products->total()); ?> <?php echo e(__('messages.products_found')); ?></p>
    </div>

    <!-- Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filtres et recherche -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-gradient-dark text-white py-3">
            <h6 class="mb-0"><i class="fas fa-filter me-2 text-gold"></i> <?php echo e(__('messages.filter')); ?> & <?php echo e(__('messages.search')); ?></h6>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('shop.products.index')); ?>" method="GET">
                <div class="row g-3">
                    <!-- Recherche -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-uppercase text-gold"><?php echo e(__('messages.search')); ?></label>
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control border-gold" 
                                   placeholder="<?php echo e(__('messages.search_placeholder')); ?>" 
                                   value="<?php echo e(request('search')); ?>">
                            <button type="submit" class="btn btn-gold">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Filtre par catégorie -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-uppercase text-gold"><?php echo e(__('messages.category')); ?></label>
                        <select name="category" class="form-select border-gold" onchange="this.form.submit()">
                            <option value=""><?php echo e(__('messages.all_categories')); ?></option>
                            <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->slug); ?>" 
                                    <?php echo e(request('category') == $category->slug ? 'selected' : ''); ?>>
                                    <?php echo e($category->translated_name); ?> (<?php echo e($category->products_count ?? 0); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Filtre par vendeur/fournisseur -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-uppercase text-gold"><?php echo e(__('messages.sold_by')); ?></label>
                        <select name="vendor" class="form-select border-gold" onchange="this.form.submit()">
                            <option value=""><?php echo e(__('messages.all_vendors')); ?></option>
                            <?php $__currentLoopData = $vendors ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($vendor->id); ?>" 
                                    <?php echo e(request('vendor') == $vendor->id ? 'selected' : ''); ?>>
                                    🏪 <?php echo e($vendor->shop_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $suppliers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="supplier_<?php echo e($supplier->id); ?>" 
                                    <?php echo e(request('supplier') == $supplier->id ? 'selected' : ''); ?>>
                                    🚚 <?php echo e($supplier->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Filtre par prix -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold small text-uppercase text-gold"><?php echo e(__('messages.price')); ?></label>
                        <div class="d-flex gap-2">
                            <input type="number" 
                                   name="min_price" 
                                   class="form-control border-gold" 
                                   placeholder="<?php echo e(__('messages.min')); ?>" 
                                   value="<?php echo e(request('min_price')); ?>"
                                   min="0"
                                   step="1">
                            <input type="number" 
                                   name="max_price" 
                                   class="form-control border-gold" 
                                   placeholder="<?php echo e(__('messages.max')); ?>" 
                                   value="<?php echo e(request('max_price')); ?>"
                                   min="0"
                                   step="1">
                        </div>
                    </div>

                    <!-- Filtre par stock et tri -->
                    <div class="col-md-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-gold"><?php echo e(__('messages.stock')); ?></label>
                                <select name="stock" class="form-select border-gold" onchange="this.form.submit()">
                                    <option value=""><?php echo e(__('messages.all')); ?></option>
                                    <option value="low" <?php echo e(request('stock') == 'low' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.low_stock')); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-gold"><?php echo e(__('messages.sort')); ?></label>
                                <select name="sort" class="form-select border-gold" onchange="this.form.submit()">
                                    <option value="latest" <?php echo e(request('sort', 'latest') == 'latest' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.latest')); ?>

                                    </option>
                                    <option value="price_asc" <?php echo e(request('sort') == 'price_asc' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.price_asc')); ?>

                                    </option>
                                    <option value="price_desc" <?php echo e(request('sort') == 'price_desc' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.price_desc')); ?>

                                    </option>
                                    <option value="name_asc" <?php echo e(request('sort') == 'name_asc' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.name_asc')); ?>

                                    </option>
                                    <option value="name_desc" <?php echo e(request('sort') == 'name_desc' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.name_desc')); ?>

                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <?php if(request()->anyFilled(['search', 'category', 'vendor', 'supplier', 'min_price', 'max_price', 'stock'])): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <hr class="border-gold-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-filter me-2 text-gold"></i>
                                <span class="fw-bold"><?php echo e(__('messages.active_filters')); ?>:</span>
                                <?php $__currentLoopData = request()->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($value && !in_array($key, ['page', 'sort'])): ?>
                                        <span class="badge bg-gold text-dark me-1">
                                            <?php echo e($key); ?>: <?php echo e($value); ?>

                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <a href="<?php echo e(route('shop.products.index')); ?>" class="btn btn-outline-gold btn-sm">
                                <i class="fas fa-times me-1"></i> <?php echo e(__('messages.clear_filters')); ?>

                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Grille de produits -->
    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm product-card border-0">
                <!-- Badges -->
                <?php if($product->quantity <= $product->stock_threshold): ?>
                    <span class="position-absolute top-0 start-0 badge bg-danger m-2">
                        <i class="fas fa-exclamation-triangle me-1"></i> <?php echo e(__('messages.low_stock')); ?>

                    </span>
                <?php endif; ?>
                
                <!-- Image du produit -->
                <div class="product-image-wrapper text-center bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                    <?php if($product->image): ?>
                        <?php
                            $imageName = basename($product->image);
                        ?>
                        <img src="<?php echo e(asset('storage/products/' . $imageName)); ?>" 
                             alt="<?php echo e($product->translated_name); ?>" 
                             class="img-fluid product-image"
                             style="max-width: 100%; max-height: 180px; width: auto; height: auto; object-fit: contain;"
                             onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\'fas fa-box fa-4x text-secondary\'></i>';">
                    <?php else: ?>
                        <i class="fas fa-box fa-4x text-secondary"></i>
                    <?php endif; ?>
                </div>
                
                <div class="card-body">
                    <!-- Nom et catégorie -->
                    <h5 class="card-title fw-bold"><?php echo e($product->translated_name); ?></h5>
                    <?php if($product->category): ?>
                        <span class="badge bg-light-gold text-dark mb-2"><?php echo e($product->category->translated_name); ?></span>
                    <?php endif; ?>
                    
                    <!-- Description courte -->
                    <p class="card-text text-muted small">
                        <?php echo e($product->translated_short_description ?? Str::limit($product->translated_description ?? $product->description, 50)); ?>

                    </p>
                    
                    <!-- Vendeur / Fournisseur -->
                    <div class="mb-2">
                        <?php if($product->vendor): ?>
                            <small class="text-success">
                                <i class="fas fa-store me-1"></i> <?php echo e(__('messages.sold_by')); ?>: <?php echo e($product->vendor->shop_name); ?>

                            </small>
                        <?php elseif($product->supplier): ?>
                            <small class="text-info">
                                <i class="fas fa-truck me-1"></i> <?php echo e(__('messages.supplier')); ?>: <?php echo e($product->supplier->name); ?>

                            </small>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Prix -->
                    <p class="card-text">
                        <strong class="text-gold fs-4"><?php echo e(number_format($product->price, 2)); ?> €</strong>
                    </p>
                    
                    <!-- Stock -->
                    <p class="card-text">
                        <small class="text-<?php echo e($product->quantity > 5 ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger')); ?>">
                            <i class="fas fa-<?php echo e($product->quantity > 5 ? 'check-circle' : ($product->quantity > 0 ? 'exclamation-circle' : 'times-circle')); ?> me-1"></i>
                            <?php if($product->quantity > 0): ?>
                                <?php echo e($product->quantity); ?> <?php echo e(__('messages.in_stock')); ?>

                            <?php else: ?>
                                <?php echo e(__('messages.out_of_stock')); ?>

                            <?php endif; ?>
                        </small>
                    </p>
                </div>
                
                <div class="card-footer bg-transparent border-0 pb-3">
                    <a href="<?php echo e(route('shop.products.show', $product)); ?>" 
                       class="btn btn-outline-gold w-100">
                        <i class="fas fa-eye me-2"></i> <?php echo e(__('messages.view_details')); ?>

                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="empty-state text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-box-open fa-5x text-gold"></i>
                </div>
                <h3 class="fw-bold"><?php echo e(__('messages.no_products_found')); ?></h3>
                <p class="text-muted mb-4"><?php echo e(__('messages.try_again')); ?></p>
                <a href="<?php echo e(route('shop.products.index')); ?>" class="btn btn-gold btn-lg">
                    <i class="fas fa-redo-alt me-2"></i> <?php echo e(__('messages.reset_filters')); ?>

                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($products->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                
                <?php if($products->onFirstPage()): ?>
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                <?php else: ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo e($products->previousPageUrl()); ?>" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                <?php endif; ?>

                
                <?php $__currentLoopData = $products->getUrlRange(1, $products->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $products->currentPage()): ?>
                        <li class="page-item active">
                            <span class="page-link"><?php echo e($page); ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php if($products->hasMorePages()): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo e($products->nextPageUrl()); ?>" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>

    <!-- Résumé -->
    <div class="row mt-3">
        <div class="col-12">
            <p class="text-center text-muted small">
                <i class="fas fa-box me-1 text-gold" style="font-size: 0.8rem;"></i>
                <span><?php echo e(__('messages.showing')); ?> <?php echo e($products->firstItem() ?? 0); ?> <?php echo e(__('messages.to')); ?> <?php echo e($products->lastItem() ?? 0); ?> <?php echo e(__('messages.of')); ?> <?php echo e($products->total()); ?> <?php echo e(__('messages.results')); ?></span>
                <?php if($products->total() > 0): ?>
                    <span class="mx-2" style="color: rgba(247, 212, 74, 0.3);">|</span>
                    <span><?php echo e(__('messages.page')); ?> <?php echo e($products->currentPage()); ?>/<?php echo e($products->lastPage()); ?></span>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Couleurs du thème */
    :root {
        --primary-dark: #1a1a2e;
        --primary-medium: #16213e;
        --primary-light: #0f3460;
        --accent-gold: #f7d44a;
        --text-light: #e0e0e0;
    }

    /* En-tête de section */
    .section-header {
        border-bottom: 2px solid var(--accent-gold);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    .section-title {
        color: var(--primary-dark);
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .dark .section-title {
        color: var(--text-light);
    }

    /* Gradient dark */
    .bg-gradient-dark {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 50%, var(--primary-light) 100%) !important;
    }

    /* Bordures gold */
    .border-gold {
        border-color: var(--accent-gold) !important;
    }
    
    .border-gold:focus {
        box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25);
    }
    
    .border-gold-20 {
        border-color: rgba(247, 212, 74, 0.2) !important;
    }

    /* Boutons gold */
    .btn-gold {
        background-color: var(--accent-gold);
        color: var(--primary-dark);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .btn-gold:hover {
        background-color: #e5c03a;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
    }
    
    .btn-outline-gold {
        background-color: transparent;
        color: var(--accent-gold);
        border: 2px solid var(--accent-gold);
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .btn-outline-gold:hover {
        background-color: var(--accent-gold);
        color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
    }

    /* Badges */
    .bg-gold {
        background-color: var(--accent-gold) !important;
    }
    
    .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.15) !important;
        color: var(--primary-dark) !important;
        font-weight: 500;
    }
    
    .dark .bg-light-gold {
        color: var(--text-light) !important;
    }

    /* Texte gold */
    .text-gold {
        color: var(--accent-gold) !important;
    }

    /* Cartes produits */
    .product-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(247, 212, 74, 0.15) !important;
        border-bottom: 3px solid var(--accent-gold);
    }
    
    .product-image-wrapper {
        overflow: hidden;
        position: relative;
    }
    
    .product-image {
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    /* État vide */
    .empty-state {
        opacity: 0.8;
    }
    
    .empty-state-icon {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Pagination minimaliste */
    .pagination {
        gap: 5px;
    }

    .pagination .page-link {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid rgba(247, 212, 74, 0.2);
        color: var(--primary-dark);
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .pagination .page-link i {
        font-size: 0.7rem;
    }

    .pagination .page-link:hover {
        background-color: rgba(247, 212, 74, 0.1);
        border-color: var(--accent-gold);
        color: var(--accent-gold);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--accent-gold);
        border-color: var(--accent-gold);
        color: var(--primary-dark);
        font-weight: 600;
    }

    .pagination .page-item.disabled .page-link {
        background-color: #f8f9fa;
        border-color: rgba(247, 212, 74, 0.1);
        color: #adb5bd;
        opacity: 0.6;
    }

    /* Mode sombre */
    .dark .pagination .page-link {
        background-color: #2d2d44;
        color: var(--text-light);
        border-color: rgba(247, 212, 74, 0.15);
    }

    .dark .pagination .page-item.disabled .page-link {
        background-color: #2d2d44;
        border-color: rgba(247, 212, 74, 0.1);
        color: #6c757d;
    }

    /* Badge position */
    .badge {
        z-index: 10;
        padding: 0.5em 0.8em;
        font-weight: 500;
    }

    /* Formulaires */
    .form-control:focus, .form-select:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25);
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('shop.layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\franc\NewStockControl\resources\views/shop/products/index.blade.php ENDPATH**/ ?>