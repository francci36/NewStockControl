@extends('layouts.auth')

@section('title', __('messages.auth_reset_password_title') . ' - ' . config('app.name'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center py-4">
                <h4 class="mb-0">
                    <i class="fas fa-lock me-2"></i>
                    {{ __('messages.auth_reset_password') }}
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

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">
                            <i class="fas fa-envelope me-2"></i>{{ __('messages.auth_email') }}
                        </label>
                        <input id="email" 
                               type="email" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ $email ?? old('email') }}" 
                               required 
                               autocomplete="email" 
                               autofocus
                               readonly
                               placeholder="{{ __('messages.auth_email_placeholder') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Nouveau mot de passe -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">
                            <i class="fas fa-key me-2"></i>{{ __('messages.auth_new_password') }}
                        </label>
                        <input id="password" 
                               type="password" 
                               class="form-control form-control-lg @error('password') is-invalid @enderror" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="{{ __('messages.auth_new_password_placeholder') }}">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>{{ __('messages.auth_password_requirements') }}
                        </small>
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="mb-4">
                        <label for="password-confirm" class="form-label fw-bold">
                            <i class="fas fa-key me-2"></i>{{ __('messages.auth_confirm_password') }}
                        </label>
                        <input id="password-confirm" 
                               type="password" 
                               class="form-control form-control-lg" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="{{ __('messages.auth_confirm_password_placeholder') }}">
                    </div>

                    <!-- Bouton de réinitialisation -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sync-alt me-2"></i>{{ __('messages.auth_reset_password_button') }}
                        </button>
                    </div>

                    <!-- Lien de retour -->
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('messages.auth_back_to_login') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lien retour boutique -->
        <div class="text-center mt-4">
            <a href="{{ route('shop.home') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-2"></i>{{ __('messages.auth_back_to_shop') }}
            </a>
        </div>
    </div>
</div>
@endsection