@extends('layouts.auth')

@section('title', __('messages.auth.login_title') . ' - ' . config('app.name'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center py-4">
                <h4 class="mb-0">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    {{ __('messages.auth.login') }}
                </h4>
            </div>
            <div class="card-body p-5">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">
                            <i class="fas fa-envelope me-2"></i>{{ __('messages.auth.email') }}
                        </label>
                        <input id="email" 
                               type="email" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email" 
                               autofocus
                               placeholder="{{ __('messages.auth.email_placeholder') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">
                            <i class="fas fa-key me-2"></i>{{ __('messages.auth.password') }}
                        </label>
                        <input id="password" 
                               type="password" 
                               class="form-control form-control-lg @error('password') is-invalid @enderror" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="{{ __('messages.auth.password_placeholder') }}">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        
                        <!-- Lien mot de passe oublié -->
                        <div class="text-end mt-2">
                            <a href="{{ route('password.request') }}" class="text-decoration-none small">
                                <i class="fas fa-question-circle me-1"></i>{{ __('messages.auth.forgot_password') }}
                            </a>
                        </div>
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('messages.auth.remember_me') }}
                            </label>
                        </div>
                    </div>

                    <!-- Bouton de connexion -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.auth.login_button') }}
                        </button>
                    </div>

                    <!-- Lien d'inscription -->
                    <div class="text-center mt-4">
                        <p class="mb-0">{{ __('messages.auth.no_account') }}</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-success mt-2">
                            <i class="fas fa-user-plus me-2"></i>{{ __('messages.auth.register_button') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lien retour boutique -->
        <div class="text-center mt-4">
            <a href="{{ route('shop.home') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>{{ __('messages.auth.back_to_shop') }}
            </a>
        </div>
    </div>
</div>
@endsection