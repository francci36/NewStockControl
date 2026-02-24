{{-- resources/views/vendor/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Devenir vendeur')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-store mr-2"></i> Devenir vendeur sur notre marketplace</h4>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Remplissez ce formulaire pour ouvrir votre boutique. Nous examinerons votre demande sous 48h.
                    </div>

                    <form action="{{ route('vendor.register.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="shop_name">Nom de votre boutique <span class="text-danger">*</span></label>
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
                            <label for="description">Description de votre boutique</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="address">Adresse complète <span class="text-danger">*</span></label>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone">Téléphone <span class="text-danger">*</span></label>
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
                                    <label for="business_number">N° SIRET / RC <span class="text-danger">*</span></label>
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
                            <label for="bank_account">IBAN <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="bank_account" 
                                   id="bank_account" 
                                   class="form-control @error('bank_account') is-invalid @enderror" 
                                   value="{{ old('bank_account') }}"
                                   required>
                            <small class="text-muted">Pour recevoir vos paiements</small>
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
                                    J'accepte les <a href="#" target="_blank">conditions générales de vente</a> pour les vendeurs
                                </label>
                                @error('terms')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane mr-2"></i> Envoyer ma demande
                            </button>
                            <a href="{{ route('shop.home') }}" class="btn btn-link">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Avantages -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5><i class="fas fa-gift text-primary mr-2"></i> Pourquoi devenir vendeur ?</h5>
                    <div class="row mt-3">
                        <div class="col-md-4 text-center">
                            <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                            <h6>Visibilité</h6>
                            <p class="small">Des milliers de clients chaque jour</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-percent fa-3x text-info mb-3"></i>
                            <h6>Commission attractive</h6>
                            <p class="small">Seulement 10% sur vos ventes</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-tachometer-alt fa-3x text-warning mb-3"></i>
                            <h6>Dashboard dédié</h6>
                            <p class="small">Gérez vos produits et commandes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection