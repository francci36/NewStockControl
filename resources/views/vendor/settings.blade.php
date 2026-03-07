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

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Erreurs :</strong>
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
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $vendor->logo) }}" 
                                             alt="{{ $vendor->translated_shop_name ?? $vendor->shop_name }}" 
                                             class="img-thumbnail" 
                                             style="max-width: 150px; max-height: 150px; border: 2px solid var(--accent-gold);">
                                    </div>
                                @else
                                    <div class="mb-3 p-4 bg-light rounded text-center" style="border: 2px dashed var(--accent-gold);">
                                        <i class="fas fa-store fa-3x" style="color: var(--accent-gold); opacity: 0.5;"></i>
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
                                    <small class="text-muted">{{ __('messages.image_help') }}</small>
                                    @error('logo')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Infos principales -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="shop_name" class="form-label fw-bold">{{ __('messages.vendor_shop_name') }} *</label>
                                        <input type="text" class="form-control @error('shop_name') is-invalid @enderror" 
                                               id="shop_name" name="shop_name" 
                                               value="{{ old('shop_name', $vendor->shop_name) }}" required>
                                        <small class="text-muted">{{ __('messages.shop_name_help') }}</small>
                                        @error('shop_name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label fw-bold">{{ __('messages.vendor_phone') }} *</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" 
                                               value="{{ old('phone', $vendor->phone) }}" required>
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
                                                  id="description" name="description" rows="4">{{ old('description', $vendor->description) }}</textarea>
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
                                <label for="address" class="form-label fw-bold">{{ __('messages.full_address') }} *</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                       id="address" name="address" 
                                       value="{{ old('address', $vendor->address) }}" required>
                                @error('address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="city" class="form-label fw-bold">{{ __('messages.city') }}</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" 
                                       value="{{ old('city', $vendor->city) }}">
                                @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="postal_code" class="form-label fw-bold">{{ __('messages.postal_code') }}</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" 
                                       value="{{ old('postal_code', $vendor->postal_code) }}">
                                @error('postal_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label fw-bold">{{ __('messages.country') }}</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" 
                                       value="{{ old('country', $vendor->country) }}">
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
                                       value="{{ old('tax_id', $vendor->tax_id) }}">
                                <small class="text-muted">{{ __('messages.tax_id_help') }}</small>
                                @error('tax_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bank_account" class="form-label fw-bold">{{ __('messages.vendor_bank_account') }} *</label>
                                <input type="text" class="form-control @error('bank_account') is-invalid @enderror" 
                                       id="bank_account" name="bank_account" 
                                       value="{{ old('bank_account', $vendor->bank_account) }}" required>
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
                                            <div class="col-md-6">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-4">
                            <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary mb-2 mb-sm-0 w-100 w-sm-auto">
                                <i class="fas fa-times mr-2"></i> {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-gold w-100 w-sm-auto">
                                <i class="fas fa-save mr-2"></i> {{ __('messages.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
}

/* Mode sombre */
.dark .card {
    background: #2d2d44;
}
.dark .card-header {
    background: #3a3a5a;
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
.dark .form-control.bg-white {
    background: #2d2d44 !important;
    color: #e0e0e0;
}

/* Responsive */
@media (max-width: 768px) {
    .col-md-3.text-center.text-md-left {
        text-align: center !important;
    }
}
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
            let fileName = this.files.length ? this.files[0].name : '{{ __("messages.choose_file") }}';
            fileLabel.innerHTML = '<i class="fas fa-upload mr-2"></i> ' + fileName;
        });
    }
});
</script>
@endpush
@endsection