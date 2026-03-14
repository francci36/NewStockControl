@extends('vendor.layouts.app')

@section('title', __('messages.vendor_settings'))
@section('page-title', __('messages.vendor_settings'))

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb float-sm-right bg-transparent">
            <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}" class="text-gold">{{ __('messages.vendor_dashboard') }}</a></li>
            <li class="breadcrumb-item active text-white-50">{{ __('messages.vendor_settings') }}</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-store mr-2"></i> {{ __('messages.vendor_shop_info') }}
                    </h5>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>{{ __('messages.validation_errors') }} :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('vendor.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        @php
                            $vendor = Auth::user()->vendor;
                        @endphp

                        <!-- Logo -->
                        <div class="row mb-4">
                            <div class="col-md-3 text-center text-md-left">
                                <label class="form-label fw-bold">{{ __('messages.vendor_shop_logo') }}</label>
                                @if($vendor && $vendor->logo)
                                    <div class="mb-3 logo-container">
                                        <img src="{{ asset('storage/' . $vendor->logo) }}" 
                                             alt="{{ $vendor->translated_shop_name ?? $vendor->shop_name }}" 
                                             class="img-thumbnail logo-image" 
                                             style="border: 2px solid var(--accent-gold);">
                                        <button type="button" class="btn btn-sm btn-danger mt-2 remove-logo-btn" onclick="removeLogo()">
                                            <i class="fas fa-trash mr-1"></i> {{ __('messages.remove_logo') }}
                                        </button>
                                    </div>
                                @else
                                    <div class="mb-3 p-4 bg-light rounded text-center logo-placeholder" style="border: 2px dashed var(--accent-gold);">
                                        <i class="fas fa-store fa-3x" style="color: var(--accent-gold); opacity: 0.5;"></i>
                                        <p class="text-muted small mt-2">{{ __('messages.no_logo') }}</p>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" 
                                               id="logo" name="logo" accept="image/*">
                                        <label class="custom-file-label" for="logo">
                                            <i class="fas fa-upload mr-2"></i> {{ __('messages.choose_file') }}
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">{{ __('messages.image_help') }}</small>
                                    @error('logo')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Infos principales -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="shop_name" class="form-label fw-bold">{{ __('messages.vendor_shop_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('shop_name') is-invalid @enderror" 
                                               id="shop_name" name="shop_name" 
                                               value="{{ old('shop_name', $vendor->shop_name) }}" 
                                               placeholder="{{ __('messages.shop_name_placeholder') }}" required>
                                        <small class="text-muted">{{ __('messages.shop_name_help') }}</small>
                                        @error('shop_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label fw-bold">{{ __('messages.vendor_phone') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" 
                                               value="{{ old('phone', $vendor->phone) }}" 
                                               placeholder="{{ __('messages.phone_placeholder') }}" required>
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">{{ __('messages.email') }}</label>
                                        <input type="email" class="form-control bg-light" 
                                               value="{{ Auth::user()->email }}" readonly disabled>
                                        <small class="text-muted">{{ __('messages.email_readonly') }}</small>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="description" class="form-label fw-bold">{{ __('messages.vendor_shop_description') }}</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4" 
                                                  placeholder="{{ __('messages.description_placeholder') }}">{{ old('description', $vendor->description) }}</textarea>
                                        <small class="text-muted">{{ __('messages.description_help') }}</small>
                                        @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Adresse -->
                        <h5 class="mb-3"><i class="fas fa-map-marker-alt mr-2 text-gold"></i> {{ __('messages.vendor_address_info') }}</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label fw-bold">{{ __('messages.full_address') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                       id="address" name="address" 
                                       value="{{ old('address', $vendor->address) }}" 
                                       placeholder="{{ __('messages.address_placeholder') }}" required>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="city" class="form-label fw-bold">{{ __('messages.city') }}</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" 
                                       value="{{ old('city', $vendor->city) }}" 
                                       placeholder="{{ __('messages.city_placeholder') }}">
                                @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="postal_code" class="form-label fw-bold">{{ __('messages.postal_code') }}</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" 
                                       value="{{ old('postal_code', $vendor->postal_code) }}" 
                                       placeholder="{{ __('messages.postal_code_placeholder') }}">
                                @error('postal_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label fw-bold">{{ __('messages.country') }}</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" 
                                       value="{{ old('country', $vendor->country) }}" 
                                       placeholder="{{ __('messages.country_placeholder') }}">
                                @error('country')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Informations professionnelles -->
                        <h5 class="mb-3"><i class="fas fa-briefcase mr-2 text-gold"></i> {{ __('messages.vendor_business_info') }}</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="tax_id" class="form-label fw-bold">{{ __('messages.vendor_tax_id') }}</label>
                                <input type="text" class="form-control @error('tax_id') is-invalid @enderror" 
                                       id="tax_id" name="tax_id" 
                                       value="{{ old('tax_id', $vendor->tax_id) }}" 
                                       placeholder="{{ __('messages.tax_id_placeholder') }}">
                                <small class="text-muted">{{ __('messages.tax_id_help') }}</small>
                                @error('tax_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bank_account" class="form-label fw-bold">{{ __('messages.vendor_bank_account') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bank_account') is-invalid @enderror" 
                                       id="bank_account" name="bank_account" 
                                       value="{{ old('bank_account', $vendor->bank_account) }}" 
                                       placeholder="{{ __('messages.bank_account_placeholder') }}" required>
                                <small class="text-muted">{{ __('messages.bank_account_help') }}</small>
                                @error('bank_account')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Informations de commission (lecture seule) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <label class="form-label fw-bold">{{ __('messages.vendor_commission_rate') }}</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control bg-white" 
                                                           value="{{ $vendor->commission_rate }}%" readonly>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text bg-gold">
                                                            <i class="fas fa-percent"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ __('messages.commission_help') }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold">{{ __('messages.vendor_status') }}</label>
                                                <div>
                                                    @if($vendor->status == 'active')
                                                        <span class="badge bg-success p-2">
                                                            <i class="fas fa-check-circle mr-1"></i> {{ __('messages.active') }}
                                                        </span>
                                                    @elseif($vendor->status == 'pending')
                                                        <span class="badge bg-warning p-2">
                                                            <i class="fas fa-clock mr-1"></i> {{ __('messages.pending') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary p-2">{{ $vendor->status }}</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted d-block mt-2">{{ __('messages.status_help') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4 action-buttons">
                            <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary mb-2 mb-sm-0 w-100 w-sm-auto">
                                <i class="fas fa-times mr-2"></i> {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-gold w-100 w-sm-auto">
                                <i class="fas fa-save mr-2"></i> {{ __('messages.save_settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden input pour suppression logo -->
<form id="remove-logo-form" action="{{ route('vendor.settings.remove-logo') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.text-gold {
    color: #f7d44a !important;
}
.btn-gold {
    background: #f7d44a;
    color: #1a1a2e;
    border: none;
    transition: all 0.3s;
    font-weight: 600;
}
.btn-gold:hover {
    background: #e5c03a;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
}
.bg-gold {
    background-color: #f7d44a !important;
    color: #1a1a2e;
}
.custom-file-label::after {
    content: "{{ __('messages.browse') }}";
    background: #667eea;
    color: white;
    border-radius: 0 0.25rem 0.25rem 0;
}
.custom-file-label {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Logo styles */
.logo-image {
    max-width: 150px;
    max-height: 150px;
    transition: transform 0.3s;
}
.logo-image:hover {
    transform: scale(1.05);
}
.remove-logo-btn {
    font-size: 0.8rem;
    padding: 0.2rem 0.5rem;
}

/* Action buttons */
.action-buttons {
    gap: 1rem;
}

/* Mode sombre */
.dark .card {
    background: #2d2d44;
    border-color: #4a4a6a;
}
.dark .card-header {
    background: #3a3a5a;
    color: #e0e0e0;
}
.dark .bg-light {
    background: #35354a !important;
    color: #e0e0e0;
}
.dark .form-control {
    background: #2d2d44;
    border-color: #4a4a6a;
    color: #e0e0e0;
}
.dark .form-control:focus {
    background: #35354a;
    border-color: #6a6a9a;
    color: #ffffff;
}
.dark .form-control.bg-white {
    background: #2d2d44 !important;
    color: #e0e0e0;
}
.dark .form-control.bg-light {
    background: #35354a !important;
    color: #e0e0e0;
}
.dark .input-group-text {
    background: #3a3a5a;
    border-color: #4a4a6a;
    color: #e0e0e0;
}
.dark .bg-gold {
    background-color: #f7d44a !important;
    color: #1a1a2e;
}
.dark .bg-gold .fas {
    color: #1a1a2e;
}
.dark .custom-file-label {
    background: #2d2d44;
    border-color: #4a4a6a;
    color: #e0e0e0;
}
.dark .custom-file-label::after {
    background: #5a6fd8;
    color: white;
    border-left-color: #4a4a6a;
}

/* ===== STYLES RESPONSIVES AMÉLIORÉS ===== */

/* Tablettes */
@media (max-width: 768px) {
    .col-md-3.text-center.text-md-left {
        text-align: center !important;
        margin-bottom: 1.5rem;
    }
    
    .logo-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .logo-image {
        max-width: 120px;
        max-height: 120px;
    }
    
    .remove-logo-btn {
        width: auto;
        display: inline-block;
    }
    
    .form-label {
        font-size: 0.95rem;
    }
    
    h5 {
        font-size: 1.1rem;
    }
    
    .card-header h5 {
        font-size: 1rem;
    }
    
    .breadcrumb {
        justify-content: center;
        padding: 0.5rem 0;
    }
    
    .breadcrumb-item {
        font-size: 0.9rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .bg-light .row {
        flex-direction: column;
    }
    
    .bg-light .col-md-6 {
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .input-group {
        flex-wrap: wrap;
    }
    
    .input-group .form-control {
        border-radius: 0.25rem !important;
        margin-bottom: 0.5rem;
    }
    
    .input-group-append {
        width: 100%;
    }
    
    .input-group-append .input-group-text {
        width: 100%;
        border-radius: 0.25rem;
        justify-content: center;
    }
}

/* Mobiles */
@media (max-width: 576px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-control {
        font-size: 0.95rem;
        padding: 0.5rem 0.75rem;
    }
    
    .btn {
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
    }
    
    h5 {
        font-size: 1rem;
    }
    
    .card-header h5 {
        font-size: 0.95rem;
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
    
    .breadcrumb {
        flex-wrap: wrap;
        font-size: 0.85rem;
    }
    
    .text-muted.small {
        font-size: 0.75rem !important;
    }
    
    .logo-placeholder {
        padding: 1rem !important;
    }
    
    .logo-placeholder i {
        font-size: 2rem !important;
    }
    
    .logo-image {
        max-width: 100px;
        max-height: 100px;
    }
    
    .remove-logo-btn {
        font-size: 0.7rem;
        padding: 0.15rem 0.4rem;
    }
    
    .custom-file-label {
        font-size: 0.9rem;
        padding: 0.4rem 0.6rem;
    }
    
    .custom-file-label i {
        margin-right: 0.3rem;
    }
    
    .action-buttons {
        margin-top: 1rem !important;
    }
    
    .action-buttons .btn {
        font-size: 0.9rem;
        padding: 0.5rem 0.8rem;
    }
    
    .action-buttons .btn i {
        margin-right: 0.3rem;
    }
    
    /* Réorganisation des colonnes */
    .row > [class*="col-"] {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    /* Alerte messages */
    .alert {
        padding: 0.75rem;
        font-size: 0.9rem;
    }
    
    .alert ul {
        margin-top: 0.5rem;
        padding-left: 1.2rem;
    }
    
    .alert li {
        font-size: 0.85rem;
    }
}

/* Très petits écrans */
@media (max-width: 375px) {
    .btn {
        font-size: 0.85rem;
        padding: 0.5rem 0.7rem;
    }
    
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.2rem;
    }
    
    .form-control {
        font-size: 0.9rem;
        padding: 0.4rem 0.6rem;
    }
    
    .card-body {
        padding: 0.8rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.5rem;
    }
    
    .logo-image {
        max-width: 80px;
        max-height: 80px;
    }
    
    .breadcrumb-item {
        font-size: 0.8rem;
    }
}

/* Classes utilitaires */
.w-sm-auto { width: auto; }
@media (max-width: 576px) {
    .w-sm-auto { width: 100%; }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('logo');
    const fileLabel = document.querySelector('.custom-file-label');
    
    if (fileInput && fileLabel) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                let fileName = this.files[0].name;
                // Tronquer le nom si trop long
                if (fileName.length > 30) {
                    fileName = fileName.substring(0, 27) + '...';
                }
                fileLabel.innerHTML = '<i class="fas fa-upload mr-2"></i> ' + fileName;
            } else {
                fileLabel.innerHTML = '<i class="fas fa-upload mr-2"></i> {{ __("messages.choose_file") }}';
            }
        });
    }
});

function removeLogo() {
    if (confirm('{{ __("messages.confirm_remove_logo") }}')) {
        document.getElementById('remove-logo-form').submit();
    }
}
</script>
@endpush
@endsection