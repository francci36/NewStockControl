@extends('vendor.layouts.app')

@section('title', __('messages.vendor_register'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-store mr-2"></i> {{ __('messages.vendor_register') }}</h4>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ __('messages.vendor_register_info') }}
                    </div>

                    <form action="{{ route('vendor.register.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="shop_name">{{ __('messages.vendor_shop_name') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="shop_name" 
                                   id="shop_name" 
                                   class="form-control @error('shop_name') is-invalid @enderror" 
                                   value="{{ old('shop_name') }}"
                                   required>
                            @error('shop_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">{{ __('messages.vendor_shop_description') }}</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="address">{{ __('messages.vendor_address') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="address" 
                                   id="address" 
                                   class="form-control @error('address') is-invalid @enderror" 
                                   value="{{ old('address') }}"
                                   required>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- NOUVEAU: Champs d'adresse complète -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="city">{{ __('messages.city') }}</label>
                                    <input type="text" 
                                           name="city" 
                                           id="city" 
                                           class="form-control @error('city') is-invalid @enderror" 
                                           value="{{ old('city') }}"
                                           placeholder="Ex: Paris">
                                    @error('city')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="postal_code">{{ __('messages.postal_code') }}</label>
                                    <input type="text" 
                                           name="postal_code" 
                                           id="postal_code" 
                                           class="form-control @error('postal_code') is-invalid @enderror" 
                                           value="{{ old('postal_code') }}"
                                           placeholder="Ex: 75001">
                                    @error('postal_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="country">{{ __('messages.country') }}</label>
                                    <input type="text" 
                                           name="country" 
                                           id="country" 
                                           class="form-control @error('country') is-invalid @enderror" 
                                           value="{{ old('country') }}"
                                           placeholder="Ex: France">
                                    @error('country')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- NOUVEAU: Site web -->
                        <div class="form-group mb-3">
                            <label for="website">{{ __('messages.website') }}</label>
                            <input type="url" 
                                   name="website" 
                                   id="website" 
                                   class="form-control @error('website') is-invalid @enderror" 
                                   value="{{ old('website') }}"
                                   placeholder="https://www.maboutique.com">
                            <small class="text-muted">Lien vers votre site web (optionnel)</small>
                            @error('website')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone">{{ __('messages.vendor_phone') }} <span class="text-danger">*</span></label>
                                    <input type="tel" 
                                           name="phone" 
                                           id="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone') }}"
                                           required>
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="business_number">{{ __('messages.vendor_tax_id') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="business_number" 
                                           id="business_number" 
                                           class="form-control @error('business_number') is-invalid @enderror" 
                                           value="{{ old('business_number') }}"
                                           required>
                                    @error('business_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="bank_account">{{ __('messages.vendor_bank_account') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="bank_account" 
                                   id="bank_account" 
                                   class="form-control @error('bank_account') is-invalid @enderror" 
                                   value="{{ old('bank_account') }}"
                                   required>
                            <small class="text-muted">{{ __('messages.bank_account_help') }}</small>
                            @error('bank_account')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       name="terms" 
                                       id="terms" 
                                       class="custom-control-input @error('terms') is-invalid @enderror" 
                                       required>
                                <label class="custom-control-label" for="terms">
                                    {!! __('messages.accept_terms', ['link' => '<a href="#" target="_blank">'.__('messages.terms_link').'</a>']) !!}
                                </label>
                                @error('terms')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane mr-2"></i> {{ __('messages.submit_request') }}
                            </button>
                            <a href="{{ route('shop.home') }}" class="btn btn-link">{{ __('messages.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Avantages -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5><i class="fas fa-gift text-primary mr-2"></i> {{ __('messages.vendor_benefits_title') }}</h5>
                    <div class="row mt-3">
                        <div class="col-md-4 text-center">
                            <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                            <h6>{{ __('messages.vendor_benefit_visibility') }}</h6>
                            <p class="small">{{ __('messages.vendor_benefit_visibility_desc') }}</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-percent fa-3x text-info mb-3"></i>
                            <h6>{{ __('messages.vendor_benefit_commission') }}</h6>
                            <p class="small">{{ __('messages.vendor_benefit_commission_desc') }}</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-tachometer-alt fa-3x text-warning mb-3"></i>
                            <h6>{{ __('messages.vendor_benefit_dashboard') }}</h6>
                            <p class="small">{{ __('messages.vendor_benefit_dashboard_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection