<!-- resources/views/layouts/navigation.blade.php -->

<nav class="bg-dark text-light py-4">
    <div class="container flex justify-between items-center">
        <div class="nav-links flex items-center">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('home') }}">Accueil</a>
            <a href="{{ route('stocks.index') }}">Stocks</a>
            <a href="{{ route('commandes.index') }}">Commandes</a>
            <a href="{{ route('fournisseurs.index') }}">Fournisseurs</a>
            <a href="{{ route('rapports.index') }}">Rapports</a>
        </div>
        <div class="user-info flex items-center">
            @auth
                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-full w-10 h-10 mr-3">
                <span>{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="ml-3">
                    @csrf
                    <button type="submit" class="btn btn-link text-light">Déconnexion</button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="btn btn-link text-light">Connexion</a>
                <a href="{{ route('register') }}" class="btn btn-link text-light">Inscription</a>
            @endguest
        </div>
    </div>
</nav>
