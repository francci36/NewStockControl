<!-- resources/views/home.blade.php -->

@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="container mx-auto py-12">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold mb-4">Bienvenue sur notre site</h1>
        <p class="text-lg text-gray-700">Gérez vos stocks, commandes et fournisseurs facilement.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Connexion</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" class="form-input mt-1 block w-full" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Mot de passe</label>
                    <input type="password" class="form-input mt-1 block w-full" id="password" name="password" required>
                </div>

                <div class="mb-4 flex items-center">
                    <input type="checkbox" class="form-checkbox" id="remember" name="remember">
                    <label class="ml-2 text-gray-700" for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Connexion</button>

                @if (Route::has('password.request'))
                    <a class="block text-center text-blue-500 mt-4" href="{{ route('password.request') }}">
                        Mot de passe oublié ?
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Inscription</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Nom</label>
                    <input type="text" class="form-input mt-1 block w-full" id="name" name="name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" class="form-input mt-1 block w-full" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Mot de passe</label>
                    <input type="password" class="form-input mt-1 block w-full" id="password" name="password" required>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700">Confirmer le mot de passe</label>
                    <input type="password" class="form-input mt-1 block w-full" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Inscription</button>
            </form>
        </div>
    </div>
</div>
@endsection
