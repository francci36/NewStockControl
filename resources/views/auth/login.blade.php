@extends('layouts.auth')

@section('title', __('auth.login_title') . ' - ' . config('app.name'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center py-4">
                <h4 class="mb-0">
                    <i class="fas fa-lock me-2"></i>
                    {{ __('auth.login') }}
                </h4>
            </div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

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
                               autofocus
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
                               autocomplete="current-password"
                               placeholder="{{ __('auth.password_placeholder') }}">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('auth.remember_me') }}
                            </label>
                        </div>
                    </div>

                    <!-- Bouton de connexion -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>{{ __('auth.login_button') }}
                        </button>
                    </div>

                    <!-- Lien d'inscription -->
                    <div class="text-center mt-4">
                        <p class="mb-0">{{ __('auth.no_account') }}</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary mt-2">
                            <i class="fas fa-user-plus me-2"></i>{{ __('auth.register_button') }}
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