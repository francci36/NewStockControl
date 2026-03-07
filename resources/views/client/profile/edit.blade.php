{{-- resources/views/client/profile/edit.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Mon profil - Ma Boutique')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Menu latéral -->
            <div class="card mb-4 shadow-sm">
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
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Modifier mon profil</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulaire d'édition du profil -->
                    <form action="{{ route('client.profile.update') }}" method="POST" class="mb-4">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nom complet</label>
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
                            <label for="email" class="form-label fw-bold">Email</label>
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
                    <h5 class="mb-3"><i class="fas fa-key me-2 text-warning"></i> Changer mon mot de passe</h5>
                    <form action="{{ route('client.profile.password') }}" method="POST" class="mb-4">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-bold">Mot de passe actuel</label>
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
                            <label for="new_password" class="form-label fw-bold">Nouveau mot de passe</label>
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
                            <label for="new_password_confirmation" class="form-label fw-bold">Confirmer le mot de passe</label>
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

                    <hr>

                    <!-- Section Suppression de compte (NOUVEAU) -->
                    <div class="mt-4">
                        <h5 class="mb-3 text-danger"><i class="fas fa-exclamation-triangle me-2"></i> Zone dangereuse</h5>
                        
                        <!-- Bouton pour ouvrir la modale -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash me-2"></i> Supprimer mon compte
                        </button>
                        
                        <small class="d-block text-muted mt-2">
                            <i class="fas fa-info-circle me-1"></i> 
                            La suppression de votre compte est irréversible. Toutes vos données personnelles seront effacées définitivement.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Supprimer votre compte
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Attention !</strong> Cette action est <u>irréversible</u>.
                </div>
                
                <p>Vous êtes sur le point de supprimer définitivement votre compte :</p>
                <ul class="mb-3">
                    <li><strong>{{ $user->name }}</strong> ({{ $user->email }})</li>
                </ul>
                
                <p class="text-danger fw-bold">Conséquences :</p>
                <ul>
                    <li>Toutes vos informations personnelles seront effacées</li>
                    <li>Vous ne pourrez plus accéder à votre historique de commandes</li>
                    <li>Cette action ne peut pas être annulée</li>
                </ul>
                
                <p>Veuillez confirmer votre mot de passe pour procéder :</p>
                
                <form action="{{ route('client.profile.destroy') }}" method="POST" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-3">
                        <label for="delete_password" class="form-label fw-bold">Mot de passe</label>
                        <input type="password" 
                               class="form-control @error('delete_password') is-invalid @enderror" 
                               id="delete_password" 
                               name="password" 
                               required
                               placeholder="Entrez votre mot de passe pour confirmer">
                        @error('delete_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Annuler
                </button>
                <button type="submit" form="deleteAccountForm" class="btn btn-danger" 
                        onclick="return confirm('Dernière confirmation : êtes-vous ABSOLUMENT sûr de vouloir supprimer votre compte ?')">
                    <i class="fas fa-trash me-2"></i> Supprimer définitivement
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles additionnels */
.card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
}
.card-header {
    border-bottom: none;
}
.list-group-item.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.list-group-item-action:hover {
    background-color: rgba(13, 110, 253, 0.1);
}
.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}
.btn-danger:hover {
    background-color: #bb2d3b;
    border-color: #b02a37;
}
.modal-header .btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}
</style>
@endsection