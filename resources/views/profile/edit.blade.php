{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Mon Profil')

@section('page-title', 'Mon Profil')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Mon Profil</li>
    </ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Card Profil -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @php
                            $profilePhoto = null;
                            if (Auth::user()->profile_photo_url) {
                                $photoPath = 'storage/' . Auth::user()->profile_photo_url;
                                if (file_exists(public_path($photoPath))) {
                                    $profilePhoto = asset($photoPath);
                                }
                            }
                        @endphp
                        
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ $profilePhoto ?? asset('images/default-profile.png') }}"
                             alt="{{ Auth::user()->name }}"
                             style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #adb5bd; padding: 3px;"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-profile.png') }}';">
                    </div>

                    <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

                    <p class="text-muted text-center">
                        @if(Auth::user()->isAdmin())
                            <span class="badge bg-danger"><i class="fas fa-crown mr-1"></i> Administrateur</span>
                        @else
                            <span class="badge bg-info"><i class="fas fa-user mr-1"></i> Utilisateur</span>
                        @endif
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <i class="fas fa-envelope mr-2 text-primary"></i> <b>Email</b>
                            <span class="float-right">{{ Auth::user()->email }}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-calendar mr-2 text-success"></i> <b>Membre depuis</b>
                            <span class="float-right">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-image mr-2 text-info"></i> <b>Photo</b>
                            <span class="float-right">
                                @if(Auth::user()->profile_photo_url)
                                    <span class="badge bg-success">Présente</span>
                                @else
                                    <span class="badge bg-secondary">Absente</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Tabs -->
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#information" data-toggle="tab">
                                <i class="fas fa-user mr-2"></i> Informations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#password" data-toggle="tab">
                                <i class="fas fa-lock mr-2"></i> Mot de passe
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#delete" data-toggle="tab">
                                <i class="fas fa-trash mr-2 text-danger"></i> Supprimer
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Tab Informations -->
                        <div class="active tab-pane" id="information">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="form-group">
                                    <label for="name">Nom</label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', Auth::user()->name) }}">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', Auth::user()->email) }}">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="profile_photo">Photo de profil</label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               name="profile_photo" 
                                               class="custom-file-input @error('profile_photo') is-invalid @enderror" 
                                               id="profile_photo"
                                               accept="image/*">
                                        <label class="custom-file-label" for="profile_photo">Choisir un fichier</label>
                                    </div>
                                    @error('profile_photo')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Formats acceptés : JPG, JPEG, PNG, GIF. Taille max : 2 Mo.
                                    </small>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i> Mettre à jour
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab Mot de passe -->
                        <div class="tab-pane" id="password">
                            @if(session('password_success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    {{ session('password_success') }}
                                </div>
                            @endif

                            @if(session('password_error'))
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    {{ session('password_error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.profile.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="current_password">Mot de passe actuel <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" id="current_password" 
                                           class="form-control @error('current_password') is-invalid @enderror" required>
                                    @error('current_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="new_password">Nouveau mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="new_password" id="new_password" 
                                           class="form-control @error('new_password') is-invalid @enderror" required>
                                    @error('new_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Minimum 8 caractères.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="new_password_confirmation">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                           class="form-control" required>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key mr-2"></i> Changer le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab Supprimer -->
                        <div class="tab-pane" id="delete">
                            <div class="alert alert-danger">
                                <h5><i class="icon fas fa-ban"></i> Attention !</h5>
                                <p>La suppression de votre compte est irréversible. Toutes vos données seront définitivement effacées.</p>
                            </div>

                            <form method="POST" action="{{ route('admin.profile.destroy') }}" 
                                  onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
                                @csrf
                                @method('DELETE')

                                <div class="form-group">
                                    <label for="delete_password">Confirmez votre mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="delete_password" 
                                           class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash mr-2"></i> Supprimer mon compte
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-user-img {
        transition: transform 0.3s ease;
    }
    
    .profile-user-img:hover {
        transform: scale(1.05);
    }
    
    .custom-file-label::after {
        content: "Parcourir";
    }
    
    .dark .list-group-item {
        background-color: #2d2d44;
        border-color: #4a4a6a;
        color: #e0e0e0;
    }
    
    .dark .card {
        background-color: #252537;
        border-color: #3a3a5a;
    }
    
    .dark .card-header {
        background-color: #2d2d44;
        border-bottom-color: #4a4a6a;
    }
    
    .dark .form-control {
        background-color: #2d2d44;
        border-color: #4a4a6a;
        color: #e0e0e0;
    }
    
    .dark .form-control:focus {
        background-color: #35354a;
        border-color: #6a6a9a;
        color: #ffffff;
    }
    
    .dark .custom-file-label {
        background-color: #2d2d44;
        border-color: #4a4a6a;
        color: #e0e0e0;
    }
    
    .dark .custom-file-label::after {
        background-color: #3a3a5a;
        color: #e0e0e0;
        border-left-color: #4a4a6a;
    }
    
    .dark .nav-pills .nav-link.active {
        background-color: #3a3a5a;
    }
    
    .dark .nav-link {
        color: #e0e0e0;
    }
    
    .dark .nav-link:hover {
        background-color: #2d2d44;
    }
    
    .tab-pane {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
    // Mise à jour du label du fichier
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : 'Choisir un fichier';
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    // Gestionnaire pour les onglets
    var hash = window.location.hash;
    if (hash) {
        $('.nav-pills a[href="' + hash + '"]').tab('show');
    }
    
    $('.nav-pills a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });
</script>
@endpush