{{-- resources/views/layouts/auth.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Connexion - Ma Boutique')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Navigation simplifiée -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('shop.home') }}">
                <i class="fas fa-paw"></i> Ma Boutique Animalière
            </a>
            <div class="navbar-nav ms-auto">
                <a href="{{ route('shop.home') }}" class="nav-link text-white">
                    <i class="fas fa-home"></i> Retour à la boutique
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
                &copy; {{ date('Y') }} Ma Boutique Animalière - 
                <a href="{{ route('shop.home') }}" class="text-primary">Retour à la boutique</a>
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>