@extends('layouts.app')

@section('title', 'Ajouter un fournisseur')

@section('content')
<div class="container-fluid py-6">
    <div class="card shadow-lg">
        <div class="card-header bg-gradient-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle mr-2"></i>
                Ajouter un fournisseur
            </h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.suppliers.store') }}">
                @csrf

                <div class="row">
                    <!-- Informations principales -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle mr-2"></i>Informations générales
                        </h6>
                        
                        <div class="form-group mb-3">
                            <label for="name">Nom du fournisseur <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="telephone">Téléphone <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('telephone') is-invalid @enderror" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="{{ old('telephone') }}" 
                                   required>
                            @error('telephone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="website">Site web</label>
                            <input type="url" 
                                   class="form-control @error('website') is-invalid @enderror" 
                                   id="website" 
                                   name="website" 
                                   value="{{ old('website') }}" 
                                   placeholder="https://...">
                            @error('website')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse complète -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-map-marker-alt mr-2"></i>Adresse
                        </h6>
                        
                        <div class="form-group mb-3">
                            <label for="address">Adresse</label>
                            <input type="text" 
                                   class="form-control @error('address') is-invalid @enderror" 
                                   id="address" 
                                   name="address" 
                                   value="{{ old('address') }}" 
                                   placeholder="123 rue du Commerce">
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="city">Ville</label>
                                    <input type="text" 
                                           class="form-control @error('city') is-invalid @enderror" 
                                           id="city" 
                                           name="city" 
                                           value="{{ old('city') }}" 
                                           placeholder="Paris">
                                    @error('city')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="postal_code">Code postal</label>
                                    <input type="text" 
                                           class="form-control @error('postal_code') is-invalid @enderror" 
                                           id="postal_code" 
                                           name="postal_code" 
                                           value="{{ old('postal_code') }}" 
                                           placeholder="75001">
                                    @error('postal_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="country">Pays</label>
                            <input type="text" 
                                   class="form-control @error('country') is-invalid @enderror" 
                                   id="country" 
                                   name="country" 
                                   value="{{ old('country') }}" 
                                   placeholder="France">
                            @error('country')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Informations professionnelles -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-building mr-2"></i>Informations professionnelles
                        </h6>
                        
                        <div class="form-group mb-3">
                            <label for="tax_id">N° SIRET / TVA</label>
                            <input type="text" 
                                   class="form-control @error('tax_id') is-invalid @enderror" 
                                   id="tax_id" 
                                   name="tax_id" 
                                   value="{{ old('tax_id') }}" 
                                   placeholder="123 456 789 00012">
                            @error('tax_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="payment_terms">Conditions de paiement</label>
                            <select class="form-control @error('payment_terms') is-invalid @enderror" 
                                    id="payment_terms" 
                                    name="payment_terms">
                                <option value="">Sélectionner...</option>
                                <option value="immediate" {{ old('payment_terms') == 'immediate' ? 'selected' : '' }}>Paiement immédiat</option>
                                <option value="15_days" {{ old('payment_terms') == '15_days' ? 'selected' : '' }}>15 jours</option>
                                <option value="30_days" {{ old('payment_terms') == '30_days' ? 'selected' : '' }}>30 jours</option>
                                <option value="45_days" {{ old('payment_terms') == '45_days' ? 'selected' : '' }}>45 jours</option>
                                <option value="60_days" {{ old('payment_terms') == '60_days' ? 'selected' : '' }}>60 jours</option>
                            </select>
                            @error('payment_terms')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-tags mr-2"></i>Options
                        </h6>
                        
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_preferred" 
                                       name="is_preferred" 
                                       value="1"
                                       {{ old('is_preferred') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_preferred">
                                    <i class="fas fa-star text-warning mr-1"></i> 
                                    Fournisseur préféré
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    <i class="fas fa-check-circle text-success mr-1"></i> 
                                    Fournisseur actif
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer le fournisseur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection