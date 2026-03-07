

<?php $__env->startSection('title', $product->translated_name . ' - ' . __('messages.shop_name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Fil d'Ariane -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-light-gold p-3 rounded-3">
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('shop.home')); ?>" class="text-decoration-none text-gold">
                    <i class="fas fa-home me-1"></i> <?php echo e(__('messages.home')); ?>

                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?php echo e(route('shop.products.index')); ?>" class="text-decoration-none text-gold">
                    <i class="fas fa-box me-1"></i> <?php echo e(__('messages.products')); ?>

                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e($product->translated_name); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Image du produit -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-lg product-image-card">
                <!-- Badge stock limité -->
                <?php if($product->quantity <= $product->stock_threshold && $product->quantity > 0): ?>
                    <span class="position-absolute top-0 start-0 badge bg-danger m-3" style="z-index: 10;">
                        <i class="fas fa-exclamation-triangle me-1"></i> <?php echo e(__('messages.low_stock')); ?>

                    </span>
                <?php endif; ?>
                
                <!-- Image du produit -->
                <div class="product-image-wrapper text-center p-4">
                    <?php if($product->image): ?>
                        <?php
                            $imageName = basename($product->image);
                        ?>
                        <img src="<?php echo e(asset('storage/products/' . $imageName)); ?>" 
                             alt="<?php echo e($product->translated_name); ?>" 
                             class="img-fluid product-main-image"
                             style="max-height: 400px; width: auto; object-fit: contain;"
                             onerror="this.onerror=null; this.parentNode.innerHTML='<div class=\'text-center py-5\'><i class=\'fas fa-box fa-5x text-gold\'></i><p class=\'mt-3 text-muted\'><?php echo e(__('messages.image_not_available')); ?></p></div>';">
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-box fa-5x text-gold"></i>
                            <p class="mt-3 text-muted"><?php echo e(__('messages.no_image')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Détails du produit -->
        <div class="col-md-6">
            <div class="card border-0 shadow-lg product-details-card">
                <div class="card-body p-4">
                    <!-- Catégorie et badges -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <?php if($product->category): ?>
                            <span class="badge bg-light-gold text-dark px-3 py-2">
                                <i class="fas fa-tag me-1 text-gold"></i> <?php echo e($product->category->translated_name); ?>

                            </span>
                        <?php endif; ?>
                        
                        <!-- Vendeur ou Fournisseur -->
                        <?php if($product->vendor): ?>
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-store me-1"></i> <?php echo e(__('messages.sold_by')); ?>: <?php echo e($product->vendor->shop_name); ?>

                            </span>
                        <?php elseif($product->supplier): ?>
                            <span class="badge bg-info px-3 py-2">
                                <i class="fas fa-truck me-1"></i> <?php echo e(__('messages.supplier')); ?>: <?php echo e($product->supplier->name); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Titre -->
                    <h1 class="card-title h2 mb-3 fw-bold"><?php echo e($product->translated_name); ?></h1>
                    
                    <!-- Prix -->
                    <div class="mb-4">
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="text-gold fs-1 fw-bold"><?php echo e(number_format($product->price, 2)); ?> €</span>
                            <?php if($product->quantity > 0): ?>
                                <small class="text-muted"><?php echo e(__('messages.tax_included')); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Statut stock -->
                    <div class="mb-4">
                        <?php if($product->quantity > 10): ?>
                            <div class="alert alert-success border-0 py-3">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong><?php echo e(__('messages.in_stock')); ?></strong> - <?php echo e($product->quantity); ?> <?php echo e(__('messages.units_available')); ?>

                            </div>
                        <?php elseif($product->quantity > 0): ?>
                            <div class="alert alert-warning border-0 py-3">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong><?php echo e(__('messages.low_stock')); ?></strong> - <?php echo e($product->quantity); ?> <?php echo e(__('messages.units_available')); ?>

                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger border-0 py-3">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong><?php echo e(__('messages.out_of_stock')); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Description courte -->
                    <?php if($product->short_description || $product->translated_short_description): ?>
                    <div class="mb-4">
                        <h5 class="fw-bold mb-2"><?php echo e(__('messages.short_description')); ?></h5>
                        <p class="text-muted fst-italic border-start border-gold ps-3 py-2">
                            <?php echo e($product->translated_short_description ?? $product->short_description); ?>

                        </p>
                    </div>
                    <?php endif; ?>

                    <!-- Description complète -->
                    <?php if($product->description || $product->translated_description): ?>
                    <div class="mb-4">
                        <h5 class="fw-bold mb-2"><?php echo e(__('messages.full_description')); ?></h5>
                        <div class="text-muted description-content">
                            <?php echo e($product->translated_description ?? $product->description ?? __('messages.no_description')); ?>

                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Formulaire d'achat -->
                    <?php if($product->quantity > 0): ?>
                        <form action="<?php echo e(route('shop.cart.add')); ?>" method="POST" class="mb-4">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                            
                            <div class="row g-3 align-items-end">
                                <div class="col-auto">
                                    <label for="quantity" class="form-label fw-bold"><?php echo e(__('messages.quantity')); ?></label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-gold" onclick="decrementQuantity()">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               name="quantity" 
                                               id="quantity" 
                                               class="form-control form-control-lg text-center border-gold" 
                                               value="1" 
                                               min="1" 
                                               max="<?php echo e($product->quantity); ?>"
                                               style="width: 80px;">
                                        <button type="button" class="btn btn-outline-gold" onclick="incrementQuantity()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-auto flex-grow-1">
                                    <button type="submit" class="btn btn-gold btn-lg w-100">
                                        <i class="fas fa-cart-plus me-2"></i> <?php echo e(__('messages.add_to_cart')); ?>

                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>

                    <!-- Caractéristiques -->
                    <div class="mt-4 p-4 bg-light-gold rounded-3">
                        <h5 class="fw-bold mb-3"><i class="fas fa-info-circle text-gold me-2"></i> <?php echo e(__('messages.features')); ?></h5>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-hashtag text-gold"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block"><?php echo e(__('messages.reference')); ?></small>
                                        <strong>#<?php echo e($product->id); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-tag text-gold"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block"><?php echo e(__('messages.category')); ?></small>
                                        <strong><?php echo e($product->category->translated_name ?? __('messages.uncategorized')); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-building text-gold"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block"><?php echo e(__('messages.sold_by')); ?></small>
                                        <strong>
                                            <?php if($product->vendor): ?>
                                                <?php echo e($product->vendor->shop_name); ?>

                                            <?php elseif($product->supplier): ?>
                                                <?php echo e($product->supplier->name); ?>

                                            <?php else: ?>
                                                <?php echo e(__('messages.not_specified')); ?>

                                            <?php endif; ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-calendar-plus text-gold"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block"><?php echo e(__('messages.added_on')); ?></small>
                                        <strong><?php echo e($product->created_at->format('d/m/Y')); ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Partager -->
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3"><?php echo e(__('messages.share')); ?></h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(request()->url())); ?>" 
                               target="_blank" 
                               class="btn btn-outline-gold rounded-circle social-btn"
                               title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(request()->url())); ?>&text=<?php echo e(urlencode($product->translated_name)); ?>" 
                               target="_blank" 
                               class="btn btn-outline-gold rounded-circle social-btn"
                               title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text=<?php echo e(urlencode($product->translated_name . ' - ' . request()->url())); ?>" 
                               target="_blank" 
                               class="btn btn-outline-gold rounded-circle social-btn"
                               title="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Produits similaires -->
    <?php if(isset($relatedProducts) && $relatedProducts->count() > 0): ?>
    <div class="mt-5">
        <h3 class="section-title mb-4"><?php echo e(__('messages.similar_products')); ?></h3>
        <div class="row">
            <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <!-- Image du produit similaire -->
                    <div class="product-image-wrapper text-center bg-light p-3" style="height: 180px;">
                        <?php if($relatedProduct->image): ?>
                            <?php
                                $imageName = basename($relatedProduct->image);
                            ?>
                            <img src="<?php echo e(asset('storage/products/' . $imageName)); ?>" 
                                 alt="<?php echo e($relatedProduct->translated_name); ?>" 
                                 class="img-fluid product-image"
                                 style="max-height: 140px; width: auto; object-fit: contain;"
                                 onerror="this.onerror=null; this.parentNode.innerHTML='<i class=\'fas fa-box fa-3x text-gold\'></i>';">
                        <?php else: ?>
                            <i class="fas fa-box fa-3x text-gold"></i>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo e($relatedProduct->translated_name); ?></h5>
                        <?php if($relatedProduct->category): ?>
                            <span class="badge bg-light-gold text-dark mb-2"><?php echo e($relatedProduct->category->translated_name); ?></span>
                        <?php endif; ?>
                        <p class="card-text text-muted small"><?php echo e(Str::limit($relatedProduct->translated_description ?? $relatedProduct->description, 50)); ?></p>
                        <p class="card-text">
                            <strong class="text-gold"><?php echo e(number_format($relatedProduct->price, 2)); ?> €</strong>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3">
                        <a href="<?php echo e(route('shop.products.show', $relatedProduct)); ?>" class="btn btn-outline-gold w-100 btn-sm">
                            <?php echo e(__('messages.view_details')); ?>

                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
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

    /* Fil d'Ariane */
    .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.1) !important;
    }
    
    .breadcrumb {
        margin-bottom: 0;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: var(--accent-gold);
    }

    /* Cartes */
    .product-image-card, .product-details-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .product-image-card:hover, .product-details-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(247, 212, 74, 0.15) !important;
    }

    /* Image produit */
    .product-main-image {
        transition: transform 0.5s ease;
    }
    
    .product-main-image:hover {
        transform: scale(1.05);
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
    .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.15) !important;
        color: var(--primary-dark) !important;
    }
    
    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
        font-size: 0.85rem;
    }

    /* Texte gold */
    .text-gold {
        color: var(--accent-gold) !important;
    }

    /* Bordures */
    .border-gold {
        border-color: var(--accent-gold) !important;
    }

    /* Boutons sociaux */
    .social-btn {
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    /* Description */
    .description-content {
        line-height: 1.8;
    }

    /* Icônes de caractéristiques */
    .feature-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background-color: rgba(247, 212, 74, 0.15);
    }

    /* Cartes produits similaires */
    .product-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(247, 212, 74, 0.15) !important;
        border-bottom: 3px solid var(--accent-gold);
    }
    
    .product-image-wrapper {
        overflow: hidden;
    }
    
    .product-image {
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    /* Section title */
    .section-title {
        position: relative;
        padding-bottom: 10px;
        color: var(--primary-dark);
        font-weight: 700;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: var(--accent-gold);
    }

    /* Mode sombre */
    .dark .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.1) !important;
        color: var(--text-light) !important;
    }
    
    .dark .bg-light {
        background-color: #2d2d44 !important;
    }
    
    .dark .card {
        background-color: #2d2d44;
        border-color: #4a4a6a;
    }
    
    .dark .card-title,
    .dark .h1, .dark .h2, .dark .h3, .dark .h4, .dark .h5, .dark .h6 {
        color: var(--text-light);
    }
    
    .dark .text-muted {
        color: #a0a0c0 !important;
    }
    
    .dark .bg-white {
        background-color: #2d2d44 !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Fonctions pour incrémenter/décrémenter la quantité
    function incrementQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.getAttribute('max')) || 999;
        let value = parseInt(input.value) || 1;
        if (value < max) {
            input.value = value + 1;
        }
    }
    
    function decrementQuantity() {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value) || 1;
        if (value > 1) {
            input.value = value - 1;
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('shop.layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\franc\NewStockControl\resources\views/shop/products/show.blade.php ENDPATH**/ ?>