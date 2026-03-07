{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Mon Profil')

@section('page-title', 'Mon Profil')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right bg-transparent">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-gold">Dashboard</a></li>
        <li class="breadcrumb-item active text-white-50">Mon Profil</li>
    </ol>
@endsection

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="row">
        <!-- Colonne gauche - Photo de profil -->
        <div class="col-lg-4 col-md-5 mb-4 mb-md-0">
            <div class="card card-gradient h-100">
                <div class="card-body box-profile">
                    <div class="text-center mb-4">
                        @php
                            $profilePhoto = null;
                            if (Auth::user()->profile_photo_url) {
                                $photoPath = 'storage/' . Auth::user()->profile_photo_url;
                                if (file_exists(public_path($photoPath))) {
                                    $profilePhoto = asset($photoPath);
                                }
                            }
                        @endphp
                        
                        <div class="profile-image-container d-inline-block">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ $profilePhoto ?? asset('images/default-profile.png') }}"
                                 alt="{{ Auth::user()->name }}"
                                 style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #f7d44a; padding: 3px; background: #1a1a2e; max-width: 100%;"
                                 onerror="this.onerror=null; this.src='{{ asset('images/default-profile.png') }}';">
                            @if($profilePhoto)
                                <div class="profile-image-badge">
                                    <i class="fas fa-check-circle text-gold"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <h3 class="profile-username text-center text-white font-weight-bold">{{ Auth::user()->name }}</h3>

                    <p class="text-center">
                        @if(Auth::user()->isAdmin())
                            <span class="badge bg-gradient-danger px-3 py-2">
                                <i class="fas fa-crown mr-1 text-gold"></i> Administrateur
                            </span>
                        @else
                            <span class="badge bg-gradient-info px-3 py-2">
                                <i class="fas fa-user mr-1"></i> Utilisateur
                            </span>
                        @endif
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item bg-transparent border-gold-20 d-flex flex-wrap">
                            <span class="w-100 w-sm-auto">
                                <i class="fas fa-envelope mr-2 text-gold"></i> 
                                <b class="text-white">Email</b>
                            </span>
                            <span class="ml-auto text-white-50 text-truncate">{{ Auth::user()->email }}</span>
                        </li>
                        <li class="list-group-item bg-transparent border-gold-20 d-flex flex-wrap">
                            <span class="w-100 w-sm-auto">
                                <i class="fas fa-calendar mr-2 text-gold"></i> 
                                <b class="text-white">Membre depuis</b>
                            </span>
                            <span class="ml-auto text-white-50">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                        </li>
                        <li class="list-group-item bg-transparent border-gold-20 d-flex flex-wrap">
                            <span class="w-100 w-sm-auto">
                                <i class="fas fa-image mr-2 text-gold"></i> 
                                <b class="text-white">Photo</b>
                            </span>
                            <span class="ml-auto">
                                @if(Auth::user()->profile_photo_url)
                                    <span class="badge bg-gradient-success px-2">Présente</span>
                                @else
                                    <span class="badge bg-gradient-secondary px-2">Absente</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Tabs -->
        <div class="col-lg-8 col-md-7">
            <div class="card card-gradient">
                <div class="card-header p-0 border-bottom-gold">
                    <ul class="nav nav-pills flex-column flex-sm-row w-100" id="profileTabs" role="tablist">
                        <li class="nav-item flex-sm-fill text-center">
                            <a class="nav-link text-white-50 hover-gold {{ !request()->has('tab') || request()->tab == 'information' ? 'active-gold' : '' }}" 
                               href="#information" data-toggle="tab" role="tab">
                                <i class="fas fa-user mr-2"></i> 
                                <span class="d-none d-sm-inline">Informations</span>
                            </a>
                        </li>
                        <li class="nav-item flex-sm-fill text-center">
                            <a class="nav-link text-white-50 hover-gold {{ request()->tab == 'password' ? 'active-gold' : '' }}" 
                               href="#password" data-toggle="tab" role="tab">
                                <i class="fas fa-lock mr-2"></i> 
                                <span class="d-none d-sm-inline">Mot de passe</span>
                            </a>
                        </li>
                        <li class="nav-item flex-sm-fill text-center">
                            <a class="nav-link text-white-50 hover-gold {{ request()->tab == 'delete' ? 'active-gold' : '' }}" 
                               href="#delete" data-toggle="tab" role="tab">
                                <i class="fas fa-trash mr-2 text-gold"></i> 
                                <span class="d-none d-sm-inline">Supprimer</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Tab Informations -->
                        <div class="tab-pane fade {{ !request()->has('tab') || request()->tab == 'information' ? 'show active' : '' }}" 
                             id="information" role="tabpanel">
                            
                            @if(session('success'))
                                <div class="alert alert-gradient-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-gradient-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="form-group">
                                    <label for="name" class="text-gold">Nom</label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control bg-transparent border-gold-20 text-white @error('name') is-invalid @enderror" 
                                           value="{{ old('name', Auth::user()->name) }}">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email" class="text-gold">Email</label>
                                    <input type="email" name="email" id="email" 
                                           class="form-control bg-transparent border-gold-20 text-white @error('email') is-invalid @enderror" 
                                           value="{{ old('email', Auth::user()->email) }}">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="profile_photo" class="text-gold">Photo de profil</label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               name="profile_photo" 
                                               class="custom-file-input @error('profile_photo') is-invalid @enderror" 
                                               id="profile_photo"
                                               accept="image/*">
                                        <label class="custom-file-label bg-transparent border-gold-20 text-white-50" for="profile_photo">
                                            <i class="fas fa-upload mr-2"></i> Choisir un fichier
                                        </label>
                                    </div>
                                    @error('profile_photo')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-white-50">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Formats acceptés : JPG, JPEG, PNG, GIF. Taille max : 2 Mo.
                                    </small>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-gold btn-block-mobile">
                                        <i class="fas fa-save mr-2"></i> Mettre à jour
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab Mot de passe -->
                        <div class="tab-pane fade {{ request()->tab == 'password' ? 'show active' : '' }}" 
                             id="password" role="tabpanel">
                            
                            @if(session('password_success'))
                                <div class="alert alert-gradient-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ session('password_success') }}
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('password_error'))
                                <div class="alert alert-gradient-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ session('password_error') }}
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.profile.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="current_password" class="text-gold">Mot de passe actuel <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" id="current_password" 
                                           class="form-control bg-transparent border-gold-20 text-white @error('current_password') is-invalid @enderror" required>
                                    @error('current_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="new_password" class="text-gold">Nouveau mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="new_password" id="new_password" 
                                           class="form-control bg-transparent border-gold-20 text-white @error('new_password') is-invalid @enderror" required>
                                    @error('new_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-white-50">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Minimum 8 caractères.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="new_password_confirmation" class="text-gold">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                           class="form-control bg-transparent border-gold-20 text-white" required>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-gold btn-block-mobile">
                                        <i class="fas fa-key mr-2"></i> Changer le mot de passe
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab Supprimer -->
                        <div class="tab-pane fade {{ request()->tab == 'delete' ? 'show active' : '' }}" 
                             id="delete" role="tabpanel">
                            
                            <div class="alert alert-gradient-danger">
                                <h5 class="text-white"><i class="icon fas fa-ban mr-2 text-gold"></i> Attention !</h5>
                                <p class="text-white-50 mb-0">La suppression de votre compte est irréversible. Toutes vos données seront définitivement effacées.</p>
                            </div>

                            <form method="POST" action="{{ route('admin.profile.destroy') }}" 
                                  onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
                                @csrf
                                @method('DELETE')

                                <div class="form-group">
                                    <label for="delete_password" class="text-gold">Confirmez votre mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="delete_password" 
                                           class="form-control bg-transparent border-gold-20 text-white @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-gradient-danger btn-block-mobile">
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
    /* ===== STYLES EXISTANTS (conservés) ===== */
    .bg-gradient-danger {
        background: linear-gradient(135deg, #cb356b 0%, #bd3f32 100%) !important;
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%) !important;
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
    }
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #757F9A 0%, #D7DDE8 100%) !important;
    }
    
    .alert-gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        border-bottom: 3px solid #f7d44a;
    }
    .alert-gradient-danger {
        background: linear-gradient(135deg, #cb356b 0%, #bd3f32 100%);
        color: white;
        border: none;
        border-bottom: 3px solid #f7d44a;
    }
    
    .card-gradient {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border: none;
        border-bottom: 3px solid #f7d44a;
        color: white;
    }
    
    .border-gold-20 {
        border-color: rgba(247, 212, 74, 0.2) !important;
    }
    .border-bottom-gold {
        border-bottom: 1px solid #f7d44a !important;
    }
    
    .text-gold {
        color: #f7d44a !important;
    }
    .btn-gold {
        background-color: #f7d44a;
        color: #1a1a2e;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-gold:hover {
        background-color: #e5c03a;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
    }
    .btn-gradient-danger {
        background: linear-gradient(135deg, #cb356b 0%, #bd3f32 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-gradient-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(203, 53, 107, 0.3);
    }
    
    .profile-image-container {
        position: relative;
        display: inline-block;
    }
    .profile-image-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #1a1a2e;
        border-radius: 50%;
        padding: 5px;
        border: 2px solid #f7d44a;
    }
    .profile-user-img {
        transition: transform 0.3s ease;
    }
    .profile-user-img:hover {
        transform: scale(1.05);
    }
    
    .nav-pills .nav-link {
        color: rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
        border-radius: 0;
        padding: 12px 15px;
    }
    .nav-pills .nav-link:hover {
        color: #f7d44a;
        background-color: rgba(247, 212, 74, 0.1);
    }
    .nav-pills .nav-link.active-gold {
        color: #f7d44a;
        background-color: rgba(247, 212, 74, 0.15);
        border-bottom: 2px solid #f7d44a;
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
    }
    .list-group-item:first-child {
        border-top: none;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .custom-file-label {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .custom-file-label::after {
        content: "Parcourir";
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-left: 1px solid #f7d44a;
    }
    .custom-file-input:focus ~ .custom-file-label {
        border-color: #f7d44a;
        box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25);
    }
    
    .form-control:focus {
        border-color: #f7d44a !important;
        box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25) !important;
    }
    
    .text-white-50 {
        color: rgba(255, 255, 255, 0.5) !important;
    }
    
    .tab-pane {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .breadcrumb {
        background: transparent;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(247, 212, 74, 0.3);
    }
    
    /* ===== NOUVEAUX STYLES RESPONSIVES ===== */
    @media (max-width: 768px) {
        /* Profile image */
        .profile-user-img {
            width: 120px !important;
            height: 120px !important;
        }
        
        /* Tabs en colonne sur mobile */
        .nav-pills {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        
        .nav-pills .nav-item {
            width: 100%;
            border-bottom: 1px solid rgba(247, 212, 74, 0.2);
        }
        
        .nav-pills .nav-item:last-child {
            border-bottom: none;
        }
        
        .nav-pills .nav-link {
            text-align: left;
            padding: 12px 15px;
        }
        
        .nav-pills .nav-link.active-gold {
            border-bottom: none;
            border-left: 4px solid #f7d44a;
        }
        
        .nav-pills .nav-link i {
            width: 25px;
            text-align: center;
        }
        
        /* List group items */
        .list-group-item {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .list-group-item span.ml-auto {
            margin-left: 0 !important;
            margin-top: 5px;
            width: 100%;
        }
        
        /* Buttons full width */
        .btn-block-mobile {
            width: 100%;
        }
        
        /* Cards padding */
        .card-body {
            padding: 1rem;
        }
        
        /* Badges */
        .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
        }
        
        /* Form labels */
        .form-group label {
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 576px) {
        /* Profile image plus petite */
        .profile-user-img {
            width: 100px !important;
            height: 100px !important;
        }
        
        /* Titre */
        .profile-username {
            font-size: 1.2rem;
        }
        
        /* Alert padding */
        .alert {
            padding: 0.75rem;
        }
        
        /* Form inputs */
        .form-control {
            font-size: 0.9rem;
        }
    }
    
    /* Pour les petits desktop et tablettes */
    @media (min-width: 769px) and (max-width: 991px) {
        .profile-user-img {
            width: 130px !important;
            height: 130px !important;
        }
        
        .list-group-item {
            font-size: 0.9rem;
        }
    }
    
    /* Classe utilitaire pour les écrans sm */
    .w-sm-auto {
        width: auto;
    }
    
    @media (max-width: 576px) {
        .w-sm-auto {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Mise à jour du label du fichier
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.querySelector('.custom-file-input');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : 'Choisir un fichier';
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerHTML = '<i class="fas fa-upload mr-2"></i> ' + fileName;
            });
        }
    });

    // Gestionnaire pour les onglets avec hash
    var hash = window.location.hash;
    if (hash) {
        $('.nav-pills a[href="' + hash + '"]').tab('show');
    }
    
    $('.nav-pills a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

    // Ajouter le paramètre tab à l'URL sans recharger
    $('.nav-pills a').on('click', function(e) {
        e.preventDefault();
        var tabId = $(this).attr('href').substring(1);
        var url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.history.pushState({}, '', url);
        $(this).tab('show');
    });
</script>
@endpush