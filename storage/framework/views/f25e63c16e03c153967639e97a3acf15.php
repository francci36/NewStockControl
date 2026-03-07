<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', __('auth.login_title') . ' - ' . config('app.name')); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-light">
    <!-- Navigation simplifiée -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('shop.home')); ?>">
                <img src="<?php echo e(asset('images/logo.png')); ?>" 
                     alt="<?php echo e(config('app.name')); ?>" 
                     style="height: 35px; width: auto; margin-right: 10px;">
                <span style="color: #f7d44a; font-weight: 600;">Carioca & Coquelicot</span>
            </a>
            <div class="navbar-nav ms-auto">
                <a href="<?php echo e(route('shop.home')); ?>" class="nav-link text-white">
                    <i class="fas fa-home me-2" style="color: #f7d44a;"></i><?php echo e(__('auth.back_to_shop')); ?>

                </a>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="py-5">
        <div class="container">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <!-- Footer simple -->
    <footer class="bg-white py-3 mt-5 border-top">
        <div class="container text-center">
            <p class="text-muted mb-0">
                &copy; <?php echo e(date('Y')); ?> <strong>Carioca & Coquelicot</strong> - 
                <a href="<?php echo e(route('shop.home')); ?>" class="text-primary"><?php echo e(__('auth.back_to_shop')); ?></a>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\franc\NewStockControl\resources\views/layouts/auth.blade.php ENDPATH**/ ?>