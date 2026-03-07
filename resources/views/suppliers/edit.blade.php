@extends('layouts.app')

@section('title', 'Modifier le fournisseur - ' . ($supplier->admin_name ?? $supplier->name))

@section('content')
<div class="container-fluid py-6">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-warning text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-edit mr-2"></i>
                Modifier le fournisseur : <strong>{{ $supplier->admin_name ?? $supplier->name }}</strong>
            </h5>
            <span class="badge bg-light text-dark">
                <i class="fas fa-calendar mr-1"></i> Inscrit le {{ $supplier->created_at->format('d/m/Y') }}
            </span>
        </div>

        <!-- Formulaire -->
        <div class="card-body">
            <form method="POST" action="{{ route('admin.suppliers.update', $supplier->id) }}">
                @csrf
                @method('PATCH') {{-- Changé de PUT à PATCH pour correspondre à vos routes --}}

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
                                   value="{{ old('name', $supplier->name) }}" 
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $supplier->email) }}">
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
                                   value="{{ old('telephone', $supplier->telephone) }}" 
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
                                   value="{{ old('website', $supplier->website) }}" 
                                   placeholder="https://...">
                            @error('website')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse -->
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
                                   value="{{ old('address', $supplier->address) }}">
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
                                           value="{{ old('city', $supplier->city) }}">
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
                                           value="{{ old('postal_code', $supplier->postal_code) }}">
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
                                   value="{{ old('country', $supplier->country) }}">
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
                                   value="{{ old('tax_id', $supplier->tax_id) }}">
                            @error('tax_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="payment_terms">Conditions de paiement</label>
                            <select class="form-control @error('payment_terms') is-invalid @enderror" 
                                    id="payment_terms" 
                                    name="payment_terms">
                                <option value="immediate" {{ old('payment_terms', $supplier->payment_terms) == 'immediate' ? 'selected' : '' }}>Paiement immédiat</option>
                                <option value="15_days" {{ old('payment_terms', $supplier->payment_terms) == '15_days' ? 'selected' : '' }}>15 jours</option>
                                <option value="30_days" {{ old('payment_terms', $supplier->payment_terms) == '30_days' ? 'selected' : '' }}>30 jours</option>
                                <option value="45_days" {{ old('payment_terms', $supplier->payment_terms) == '45_days' ? 'selected' : '' }}>45 jours</option>
                                <option value="60_days" {{ old('payment_terms', $supplier->payment_terms) == '60_days' ? 'selected' : '' }}>60 jours</option>
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
                                       {{ old('is_preferred', $supplier->is_preferred) ? 'checked' : '' }}>
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
                                       {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}>
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
                                      rows="3">{{ old('notes', $supplier->notes) }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Statistiques rapides -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $supplier->products_count ?? 0 }}</h3>
                                <p>Produits</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $supplier->orders_count ?? 0 }}</h3>
                                <p>Commandes</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ number_format($supplier->total_purchases ?? 0, 2) }} €</h3>
                                <p>Total achats</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-euro-sign"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $supplier->created_at->format('d/m/Y') }}</h3>
                                <p>Date d'ajout</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                        </a>
                        <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="btn btn-info">
                            <i class="fas fa-eye mr-2"></i>Voir détails
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-2"></i>
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
.small-box {
    border-radius: 0.25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 20px;
    position: relative;
    color: #fff;
    padding: 15px;
}
.small-box > .inner {
    padding: 5px;
}
.small-box h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 5px;
}
.small-box p {
    font-size: 0.9rem;
    margin-bottom: 0;
}
.small-box .icon {
    color: rgba(0,0,0,.15);
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 50px;
}
</style>
@endsection