<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('auth.login_title') . ' - ' . config('app.name'))</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Navigation simplifiée -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('shop.home') }}">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="{{ config('app.name') }}" 
                     style="height: 35px; width: auto; margin-right: 10px;">
                <span style="color: #f7d44a; font-weight: 600;">Carioca & Coquelicot</span>
            </a>
            <div class="navbar-nav ms-auto">
                <a href="{{ route('shop.home') }}" class="nav-link text-white">
                    <i class="fas fa-home me-2" style="color: #f7d44a;"></i>{{ __('auth.back_to_shop') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer simple -->
    <footer class="bg-white py-3 mt-5 border-top">
        <div class="container text-center">
            <p class="text-muted mb-0">
                &copy; {{ date('Y') }} <strong>Carioca & Coquelicot</strong> - 
                <a href="{{ route('shop.home') }}" class="text-primary">{{ __('auth.back_to_shop') }}</a>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>