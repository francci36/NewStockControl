<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', __('messages.vendor_dashboard')); ?> - <?php echo e(config('app.name')); ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        :root {
            --primary-dark: #1a1a2e;
            --primary-medium: #16213e;
            --primary-light: #0f3460;
            --accent-gold: #f7d44a;
            --accent-red: #e34234;
            --accent-green: #2c8c3c;
            --accent-blue: #002395;
            --text-light: #e0e0e0;
        }
        
        /* Personnalisation AdminLTE avec vos couleurs */
        .main-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 50%, var(--primary-light) 100%) !important;
        }
        
        .main-header .navbar-nav .nav-link {
            color: var(--text-light) !important;
            transition: color 0.3s;
        }
        
        .main-header .navbar-nav .nav-link:hover {
            color: var(--accent-gold) !important;
        }
        
        .main-sidebar {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 100%) !important;
        }
        
        /* Brand Link amélioré - NE COUPE PLUS LE NOM */
        .brand-link {
            display: flex !important;
            align-items: center !important;
            padding: 15px 10px !important;
            min-height: 80px !important;
            background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-light) 100%) !important;
            border-bottom: 3px solid var(--accent-gold) !important;
            text-decoration: none !important;
        }
        
        .brand-link .brand-image {
            width: 45px !important;
            height: 45px !important;
            object-fit: contain !important;
            margin-right: 12px !important;
            border: 2px solid var(--accent-gold) !important;
            background-color: white !important;
            padding: 3px !important;
            flex-shrink: 0 !important;
        }
        
        .brand-text-container {
            display: flex !important;
            flex-direction: column !important;
            line-height: 1.3 !important;
            overflow: visible !important;
        }
        
        .brand-line-1 {
            color: var(--accent-gold) !important;
            font-weight: 700 !important;
            font-size: 1.2rem !important;
            white-space: nowrap !important;
        }
        
        .brand-line-2 {
            color: white !important;
            font-weight: 500 !important;
            font-size: 1.1rem !important;
            white-space: nowrap !important;
        }
        
        /* Version tablette */
        @media (max-width: 991px) {
            .brand-line-1 {
                font-size: 1rem !important;
            }
            .brand-line-2 {
                font-size: 0.9rem !important;
            }
            .brand-link .brand-image {
                width: 40px !important;
                height: 40px !important;
            }
        }
        
        /* Version mobile */
        @media (max-width: 768px) {
            .brand-link {
                padding: 10px 5px !important;
                min-height: 70px !important;
            }
            .brand-line-1 {
                font-size: 0.9rem !important;
            }
            .brand-line-2 {
                font-size: 0.8rem !important;
            }
            .brand-link .brand-image {
                width: 35px !important;
                height: 35px !important;
                margin-right: 8px !important;
            }
        }
        
        /* Version très petite */
        @media (max-width: 360px) {
            .brand-line-1 {
                font-size: 0.8rem !important;
            }
            .brand-line-2 {
                font-size: 0.7rem !important;
            }
        }
        
        /* Logo dans le header */
        .shop-logo-mini {
            height: 35px !important;
            width: auto !important;
            margin-right: 10px !important;
            border-radius: 50% !important;
            border: 2px solid var(--accent-gold) !important;
            padding: 2px !important;
            background-color: white !important;
        }
        
        .header-shop-name {
            color: var(--accent-gold) !important;
            font-weight: 600 !important;
            font-size: 1.1rem !important;
            white-space: nowrap !important;
        }
        
        @media (max-width: 480px) {
            .header-shop-name {
                font-size: 0.9rem !important;
            }
            .shop-logo-mini {
                height: 30px !important;
                margin-right: 5px !important;
            }
        }
        
        .user-panel {
            border-bottom: 1px solid rgba(247, 212, 74, 0.2) !important;
            display: flex !important;
            align-items: center !important;
        }
        
        .user-panel .image {
            flex-shrink: 0 !important;
        }
        
        .user-panel .info {
            min-width: 0 !important;
            flex: 1 !important;
        }
        
        .user-panel .info a {
            color: var(--accent-gold) !important;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }
        
        .user-panel .info .badge {
            background: var(--accent-green) !important;
        }
        
        .nav-sidebar .nav-item .nav-link {
            color: var(--text-light) !important;
            transition: all 0.3s;
        }
        
        .nav-sidebar .nav-item .nav-link:hover {
            background: rgba(247, 212, 74, 0.1) !important;
            color: var(--accent-gold) !important;
            transform: translateX(5px);
        }
        
        .nav-sidebar .nav-item .nav-link.active {
            background: var(--accent-gold) !important;
            color: var(--primary-dark) !important;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(247, 212, 74, 0.3);
        }
        
        .nav-sidebar .nav-item .nav-link.active i {
            color: var(--primary-dark) !important;
        }
        
        .nav-sidebar .nav-item .nav-link i {
            color: var(--accent-gold);
            width: 25px !important;
        }
        
        .content-wrapper {
            background-color: #f8f9fa;
        }
        
        .content-header h1 {
            color: var(--primary-dark);
            font-weight: 600;
        }
        
        .main-footer {
            background: white !important;
            border-top: 2px solid var(--accent-gold) !important;
        }
        
        /* Personnalisation des badges */
        .badge-success {
            background: var(--accent-green) !important;
        }
        
        .badge-warning {
            background: var(--accent-gold) !important;
            color: var(--primary-dark) !important;
        }
        
        .badge-danger {
            background: var(--accent-red) !important;
        }
        
        .badge-info {
            background: var(--accent-blue) !important;
        }
        
        /* Boutons personnalisés */
        .btn-gold {
            background: var(--accent-gold);
            color: var(--primary-dark);
            border: none;
            transition: all 0.3s;
        }
        
        .btn-gold:hover {
            background: #e5c03a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
        }
        
        /* Cartes personnalisées */
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-medium) 100%);
            color: var(--accent-gold);
            font-weight: 600;
            border-bottom: 2px solid var(--accent-gold);
        }
        
        .card-header i {
            color: var(--accent-gold);
        }
        
        /* ===== STYLES POUR LE SÉLECTEUR DE LANGUE ===== */
        .language-dropdown .dropdown-item {
            padding: 8px 15px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .language-dropdown .dropdown-item:hover {
            background: rgba(247, 212, 74, 0.1);
            color: var(--accent-gold);
        }
        
        .language-dropdown .dropdown-item.active {
            background: var(--accent-gold);
            color: var(--primary-dark);
            font-weight: 600;
        }
        
        .flag-icon {
            width: 20px;
            height: 15px;
            display: inline-block;
            margin-right: 8px;
            border-radius: 3px;
        }
        
        /* ===== STYLES RESPONSIVES AJOUTÉS ===== */
        
        /* Overlay pour mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        
        .sidebar-open .sidebar-overlay {
            display: block !important;
        }
        
        @media (max-width: 768px) {
            /* Sidebar en overlay */
            .main-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                position: fixed !important;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 1050;
                width: 280px !important;
            }
            
            .sidebar-open .main-sidebar {
                transform: translateX(0) !important;
            }
            
            /* Content wrapper */
            .content-wrapper, .main-footer {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            /* Navbar */
            .main-header {
                width: 100% !important;
                margin-left: 0 !important;
            }
            
            .navbar-nav {
                flex-direction: row !important;
                align-items: center;
            }
            
            .navbar-nav.ml-auto {
                flex-direction: row !important;
            }
            
            .nav-item.d-none.d-sm-inline-block {
                display: none !important;
            }
            
            .header-shop-name {
                font-size: 0.9rem !important;
            }
            
            .shop-logo-mini {
                height: 30px !important;
            }
            
            /* User panel dans sidebar */
            .user-panel .info a {
                max-width: 180px !important;
            }
            
            /* Content header */
            .content-header h1 {
                font-size: 1.3rem !important;
                text-align: center;
            }
            
            .content-header .col-sm-6:last-child {
                text-align: center !important;
            }
            
            /* Breadcrumb */
            .breadcrumb {
                justify-content: center;
                background: transparent;
                padding: 0;
            }
        }
        
        @media (max-width: 576px) {
            .content-header h1 {
                font-size: 1.1rem !important;
            }
            
            .navbar-nav .nav-link span:not(.badge) {
                display: none;
            }
            
            .navbar-nav .nav-link i {
                margin-right: 0 !important;
            }
            
            .user-panel .info a {
                max-width: 120px !important;
                font-size: 0.9rem;
            }
            
            .main-footer {
                font-size: 0.85rem;
                text-align: center;
            }
            
            .main-footer .float-right {
                float: none !important;
                display: block;
                margin-top: 5px;
            }
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="hold-transition sidebar-mini">
    <!-- Overlay pour mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="wrapper">
        <!-- Navbar avec logo de la boutique -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#">
                        <i class="fas fa-bars" style="color: var(--accent-gold);"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?php echo e(route('vendor.dashboard')); ?>" class="nav-link d-flex align-items-center">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="shop-logo-mini">
                        <span class="header-shop-name">Carioca & Coquelicot</span>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <!-- SÉLECTEUR DE LANGUE -->
                <li class="nav-item dropdown language-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" data-toggle="dropdown">
                        <i class="fas fa-globe" style="color: var(--accent-gold);"></i>
                        <span class="d-none d-sm-inline ml-1">
                            <?php switch(app()->getLocale()):
                                case ('fr'): ?> 🇫🇷 <?php break; ?>
                                <?php case ('en'): ?> 🇬🇧 <?php break; ?>
                                <?php case ('pt_BR'): ?> 🇧🇷 <?php break; ?>
                                <?php case ('es'): ?> 🇪🇸 <?php break; ?>
                                <?php case ('it'): ?> 🇮🇹 <?php break; ?>
                                <?php case ('de'): ?> 🇩🇪 <?php break; ?>
                                <?php default: ?> <?php echo e(strtoupper(app()->getLocale())); ?>

                            <?php endswitch; ?>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="<?php echo e(route('language.switch', 'fr')); ?>" class="dropdown-item <?php echo e(app()->getLocale() == 'fr' ? 'active' : ''); ?>">
                            <span class="flag-icon">🇫🇷</span> Français
                        </a>
                        <a href="<?php echo e(route('language.switch', 'en')); ?>" class="dropdown-item <?php echo e(app()->getLocale() == 'en' ? 'active' : ''); ?>">
                            <span class="flag-icon">🇬🇧</span> English
                        </a>
                        <a href="<?php echo e(route('language.switch', 'pt_BR')); ?>" class="dropdown-item <?php echo e(app()->getLocale() == 'pt_BR' ? 'active' : ''); ?>">
                            <span class="flag-icon">🇧🇷</span> Português (Brasil)
                        </a>
                        <a href="<?php echo e(route('language.switch', 'es')); ?>" class="dropdown-item <?php echo e(app()->getLocale() == 'es' ? 'active' : ''); ?>">
                            <span class="flag-icon">🇪🇸</span> Español
                        </a>
                        <a href="<?php echo e(route('language.switch', 'it')); ?>" class="dropdown-item <?php echo e(app()->getLocale() == 'it' ? 'active' : ''); ?>">
                            <span class="flag-icon">🇮🇹</span> Italiano
                        </a>
                        <a href="<?php echo e(route('language.switch', 'de')); ?>" class="dropdown-item <?php echo e(app()->getLocale() == 'de' ? 'active' : ''); ?>">
                            <span class="flag-icon">🇩🇪</span> Deutsch
                        </a>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="<?php echo e(route('shop.home')); ?>" class="nav-link" target="_blank">
                        <i class="fas fa-eye" style="color: var(--accent-gold);"></i> 
                        <span class="d-none d-sm-inline"><?php echo e(__('messages.vendor_view_store')); ?></span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                        <i class="fas fa-user-circle" style="color: var(--accent-gold);"></i> 
                        <span class="d-none d-sm-inline"><?php echo e(Auth::user()->name); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="<?php echo e(route('vendor.settings')); ?>" class="dropdown-item">
                            <i class="fas fa-cog" style="color: var(--accent-gold);"></i> 
                            <?php echo e(__('messages.vendor_settings')); ?>

                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt" style="color: var(--accent-red);"></i> 
                                <?php echo e(__('messages.logout')); ?>

                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Sidebar avec le logo et nom du vendeur -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Link -->
            <a href="<?php echo e(route('vendor.dashboard')); ?>" class="brand-link">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="brand-image img-circle elevation-3">
                <div class="brand-text-container">
                    <span class="brand-line-1">Carioca</span>
                    <span class="brand-line-2">& Coquelicot</span>
                </div>
            </a>

            <div class="sidebar">
                <!-- Informations du vendeur avec logo -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <?php
                            $vendor = Auth::user()->vendor;
                            $logoPath = null;
                            $vendorName = $vendor->translated_shop_name ?? $vendor->shop_name ?? Auth::user()->name;
                            
                            if ($vendor && $vendor->logo) {
                                if (Storage::disk('public')->exists($vendor->logo)) {
                                    $logoPath = asset('storage/' . $vendor->logo);
                                }
                            }
                        ?>
                        
                        <?php if($logoPath): ?>
                            <img src="<?php echo e($logoPath); ?>" class="img-circle elevation-2" alt="<?php echo e($vendorName); ?>">
                        <?php else: ?>
                            <div class="img-circle elevation-2 d-flex align-items-center justify-content-center" 
                                 style="width: 38px; height: 38px; background: var(--accent-gold); color: var(--primary-dark); font-weight: bold; font-size: 1.2rem;">
                                <?php echo e(substr($vendorName, 0, 1)); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">
                            <?php echo e($vendorName); ?>

                        </a>
                        <span class="badge bg-success"><?php echo e(__('messages.vendor_space')); ?></span>
                    </div>
                </div>

                <!-- Menu de navigation -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="<?php echo e(route('vendor.dashboard')); ?>" 
                               class="nav-link <?php echo e(request()->routeIs('vendor.dashboard') ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p><?php echo e(__('messages.vendor_dashboard')); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('vendor.products.index')); ?>" 
                               class="nav-link <?php echo e(request()->routeIs('vendor.products.*') ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-box"></i>
                                <p><?php echo e(__('messages.vendor_products')); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo e(route('vendor.orders.index')); ?>" 
                               class="nav-link <?php echo e(request()->routeIs('vendor.orders.*') ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p><?php echo e(__('messages.vendor_orders')); ?></p>
                            </a>
                        </li>

                        <!--lien vers les catégories des vendeurs -->
                        <li class="nav-item">
                            <a href="<?php echo e(route('vendor.categories.index')); ?>" 
                               class="nav-link <?php echo e(request()->routeIs('vendor.categories.*') ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-tags"></i>
                                <p><?php echo e(__('messages.vendor_categories')); ?></p>
                            </a>
                        </li>

                        
                        <li class="nav-item">
                            <a href="<?php echo e(route('vendor.settings')); ?>" 
                               class="nav-link <?php echo e(request()->routeIs('vendor.settings') ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-cog"></i>
                                <p><?php echo e(__('messages.vendor_settings')); ?></p>
                            </a>
                        </li>
                        
                        <!-- Lien vers la boutique publique -->
                        <li class="nav-item mt-4">
                            <a href="<?php echo e(route('shop.home')); ?>" class="nav-link" target="_blank">
                                <i class="nav-icon fas fa-store"></i>
                                <p><?php echo e(__('messages.vendor_view_store')); ?></p>
                            </a>
                        </li>
                        
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><?php echo $__env->yieldContent('page-title', __('messages.vendor_dashboard')); ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <?php echo $__env->yieldContent('breadcrumb'); ?>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>
                &copy; <?php echo e(date('Y')); ?> 
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" style="height: 20px; width: auto; margin: 0 5px; border-radius: 50%;">
                Carioca & Coquelicot.
            </strong> 
            <?php echo e(__('messages.all_rights_reserved')); ?>

        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    <script>
        // Animation pour les alertes
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.classList.add('fade');
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            });
        }, 5000);
        
        // Gestion responsive du sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.querySelector('[data-widget="pushmenu"]');
            
            if (menuBtn && overlay) {
                menuBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    if (window.innerWidth <= 768) {
                        // Mode mobile
                        if (document.body.classList.contains('sidebar-open')) {
                            document.body.classList.remove('sidebar-open');
                            overlay.style.display = 'none';
                        } else {
                            document.body.classList.add('sidebar-open');
                            overlay.style.display = 'block';
                        }
                    } else {
                        // Mode desktop
                        if (document.body.classList.contains('sidebar-collapse')) {
                            document.body.classList.remove('sidebar-collapse');
                        } else {
                            document.body.classList.add('sidebar-collapse');
                        }
                    }
                });
                
                overlay.addEventListener('click', function() {
                    document.body.classList.remove('sidebar-open');
                    overlay.style.display = 'none';
                });
            }
            
            // Gestion du redimensionnement
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth > 768) {
                        document.body.classList.remove('sidebar-open');
                        if (overlay) overlay.style.display = 'none';
                    }
                }, 250);
            });
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\franc\NewStockControl\resources\views/vendor/layouts/app.blade.php ENDPATH**/ ?>