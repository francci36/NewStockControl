<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- PWA -->
    {!! PwaKit::head() !!}
    
    <title>@yield('title', 'NewStockControl')</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <!-- Fichier CSS personnalisé -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
    
    <style>
        /* Mode sombre */
        .dark .content-wrapper {
            background-color: #1a1a2e !important;
        }
        
        /* ===== RESPONSIVE AMÉLIORÉ ===== */
        @media (max-width: 768px) {
            /* Sidebar */
            .main-sidebar {
                position: fixed !important;
                left: -280px !important;
                width: 280px !important;
                transition: left 0.3s ease !important;
                z-index: 1050 !important;
                box-shadow: 2px 0 10px rgba(0,0,0,0.3);
                height: 100vh !important;
                overflow-y: auto !important;
            }
            
            .sidebar-open .main-sidebar {
                left: 0 !important;
            }
            
            /* Overlay */
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
            
            /* Content */
            .content-wrapper, .main-footer {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            /* Navbar - garder la photo de profil */
            .nav-item.d-none.d-sm-inline-block {
                display: none !important;
            }
            
            .navbar-nav {
                flex-direction: row !important;
                align-items: center;
            }
            
            .navbar-nav.ml-auto {
                flex-direction: row !important;
            }
            
            /* Menu utilisateur - toujours visible */
            .user-menu .nav-link span {
                display: none;
            }
            
            .user-menu .nav-link img,
            .user-menu .nav-link .bg-gold {
                margin-right: 0 !important;
            }
            
            /* Dropdowns */
            .dropdown-menu {
                position: absolute !important;
                right: 0 !important;
                left: auto !important;
                min-width: 280px;
            }
            
            /* Mobile bottom nav */
            .mobile-bottom-nav {
                display: flex !important;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                border-top: 3px solid #f7d44a;
                z-index: 1030;
                height: 65px;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            }
            
            .mobile-bottom-nav-items {
                display: flex;
                width: 100%;
                justify-content: space-around;
                align-items: center;
            }
            
            .mobile-nav-item {
                text-align: center;
                color: #6c757d;
                font-size: 0.7rem;
                flex: 1;
                padding: 8px 5px;
                text-decoration: none;
                transition: all 0.3s;
            }
            
            .mobile-nav-item i {
                font-size: 1.3rem;
                display: block;
                margin-bottom: 4px;
            }
            
            .mobile-nav-item.active {
                color: #f7d44a;
            }
            
            .mobile-nav-item:hover {
                color: #f7d44a;
                text-decoration: none;
            }
            
            /* Footer */
            .main-footer {
                padding-bottom: 80px !important;
                text-align: center;
            }
            
            /* Cards */
            .card {
                margin-bottom: 15px;
            }
            
            /* Tables */
            .table-responsive {
                display: block !important;
                width: 100% !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                margin-bottom: 1rem;
            }
            
            .table {
                min-width: 600px;
            }
            
            /* Stats boxes */
            .small-box {
                margin-bottom: 15px;
            }
            
            /* Formulaires */
            .form-control {
                font-size: 16px !important;
            }
            
            /* Boutons */
            .btn {
                width: 100%;
                margin-bottom: 5px;
            }
            
            .btn-group {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
            
            .btn-group .btn {
                border-radius: 30px !important;
                margin-bottom: 5px;
            }
            
            /* Breadcrumb */
            .breadcrumb {
                justify-content: center;
                background: transparent;
                padding: 0;
                margin-top: 10px;
            }
            
            /* Content header */
            .content-header h1 {
                text-align: center;
                font-size: 1.5rem;
                margin-bottom: 10px;
            }
            
            .content-header .col-sm-6:last-child {
                text-align: center !important;
            }
            
            /* Alertes */
            .alert {
                padding: 12px 15px;
                margin-bottom: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .content-header h1 {
                font-size: 1.3rem;
            }
            
            .card-title {
                font-size: 1rem;
            }
            
            .small-box h3 {
                font-size: 1.5rem;
            }
            
            .small-box p {
                font-size: 0.9rem;
            }
            
            .breadcrumb-item {
                font-size: 0.8rem;
            }
            
            .mobile-nav-item {
                font-size: 0.65rem;
            }
            
            .mobile-nav-item i {
                font-size: 1.2rem;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-bottom-nav {
                display: none !important;
            }
        }
        
        /* Tablettes */
        @media (min-width: 769px) and (max-width: 991px) {
            .main-sidebar {
                width: 200px !important;
            }
            
            .content-wrapper, .main-footer {
                margin-left: 200px !important;
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="wrapper">
        <!-- Navbar -->
        @include('partials.navbar')

        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            @yield('breadcrumb')
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Messages d'alerte -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Erreur de validation :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        @include('partials.footer')
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="mobile-bottom-nav">
        <div class="mobile-bottom-nav-items">
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.stocks.index') }}" class="mobile-nav-item {{ request()->routeIs('admin.stocks*') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Stocks</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="mobile-nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <i class="fas fa-cubes"></i>
                <span>Produits</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="mobile-nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Commandes</span>
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="mobile-nav-item {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Profil</span>
            </a>
            <a href="{{ route('admin.finance.index') }}" class="mobile-nav-item {{ request()->routeIs('admin.finance*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Finance</span>
            </a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    <!-- PWA Scripts -->
    {!! PwaKit::scripts() !!}
    
    <!-- Script mobile -->
    <script>
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
            
            // Auto-hide alerts
            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(function(alert) {
                    if (alert && alert.parentNode) {
                        $(alert).alert('close');
                    }
                });
            }, 5000);
            
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

    @stack('scripts')
</body>
</html>