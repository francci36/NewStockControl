{{-- resources/views/client/profile/edit.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Mon profil - Ma Boutique')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <!-- Menu latéral -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Mon compte</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('client.orders.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-history me-2"></i> Mes commandes
                    </a>
                    <a href="{{ route('client.profile.edit') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user me-2"></i> Mon profil
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Modifier mon profil</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Formulaire d'édition du profil -->
                    <form action="{{ route('client.profile.update') }}" method="POST" class="mb-4">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Mettre à jour
                        </button>
                    </form>

                    <hr>

                    <!-- Formulaire de changement de mot de passe -->
                    <h5 class="mb-3">Changer mon mot de passe</h5>
                    <form action="{{ route('client.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password" 
                                   required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   required>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i> Changer le mot de passe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection