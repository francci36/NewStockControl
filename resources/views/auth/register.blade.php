@extends('layouts.auth')

@section('title', __('auth.register_title') . ' - ' . config('app.name'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-success text-white text-center py-4">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    {{ __('auth.register') }}
                </h4>
            </div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nom -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">
                            <i class="fas fa-user me-2"></i>{{ __('auth.name') }}
                        </label>
                        <input id="name" 
                               type="text" 
                               class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autocomplete="name" 
                               autofocus
                               placeholder="{{ __('auth.name_placeholder') }}">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">
                            <i class="fas fa-envelope me-2"></i>{{ __('auth.email') }}
                        </label>
                        <input id="email" 
                               type="email" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email"
                               placeholder="{{ __('auth.email_placeholder') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">
                            <i class="fas fa-key me-2"></i>{{ __('auth.password') }}
                        </label>
                        <input id="password" 
                               type="password" 
                               class="form-control form-control-lg @error('password') is-invalid @enderror" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="{{ __('auth.password_placeholder') }}">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="mb-4">
                        <label for="password-confirm" class="form-label fw-bold">
                            <i class="fas fa-key me-2"></i>{{ __('auth.confirm_password') }}
                        </label>
                        <input id="password-confirm" 
                               type="password" 
                               class="form-control form-control-lg" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="{{ __('auth.confirm_password_placeholder') }}">
                    </div>

                    <!-- Bouton d'inscription -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-user-plus me-2"></i>{{ __('auth.register_button') }}
                        </button>
                    </div>

                    <!-- Lien de connexion -->
                    <div class="text-center mt-4">
                        <p class="mb-0">{{ __('auth.have_account') }}</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary mt-2">
                            <i class="fas fa-sign-in-alt me-2"></i>{{ __('auth.login_button') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lien retour boutique -->
        <div class="text-center mt-4">
            <a href="{{ route('shop.home') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>{{ __('auth.back_to_shop') }}
            </a>
        </div>
    </div>
</div>
@endsection