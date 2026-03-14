@extends('layouts.auth')

@section('title', __('messages.auth.forgot_password_title') . ' - ' . config('app.name'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-warning text-dark text-center py-4">
                <h4 class="mb-0">
                    <i class="fas fa-key me-2"></i>
                    {{ __('messages.auth.forgot_password') }}
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

                <p class="text-muted mb-4">
                    <i class="fas fa-info-circle me-2 text-warning"></i>
                    {{ __('messages.auth.forgot_password_instructions') }}
                </p>

                <form method="POST" action="{{ route('password.email') }}">
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

                    <!-- Bouton d'envoi -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning btn-lg text-dark fw-bold">
                            <i class="fas fa-paper-plane me-2"></i>{{ __('messages.auth.send_reset_link') }}
                        </button>
                    </div>

                    <!-- Liens de retour -->
                    <div class="text-center mt-4">
                        <p class="mb-2">{{ __('messages.auth.remember_password') }}</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-sign-in-alt me-1"></i>{{ __('messages.auth.login_button') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-user-plus me-1"></i>{{ __('messages.auth.register_button') }}
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