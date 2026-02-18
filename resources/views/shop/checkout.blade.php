{{-- resources/views/shop/checkout.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Finaliser la commande - Ma Boutique')

@section('content')
<div class="container">
    <h1 class="mb-4">Finaliser votre commande</h1>

    <div class="row">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Adresse de livraison</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('shop.checkout.process') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="shipping_name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('shipping_name') is-invalid @enderror" 
                                   id="shipping_name" 
                                   name="shipping_name" 
                                   value="{{ old('shipping_name', auth()->user()->name) }}" 
                                   required>
                            @error('shipping_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Adresse complète <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                      id="shipping_address" 
                                      name="shipping_address" 
                                      rows="3" 
                                      required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping_city" class="form-label">Ville <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('shipping_city') is-invalid @enderror" 
                                       id="shipping_city" 
                                       name="shipping_city" 
                                       value="{{ old('shipping_city') }}" 
                                       required>
                                @error('shipping_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="shipping_zip" class="form-label">Code postal <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('shipping_zip') is-invalid @enderror" 
                                       id="shipping_zip" 
                                       name="shipping_zip" 
                                       value="{{ old('shipping_zip') }}" 
                                       required>
                                @error('shipping_zip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="shipping_country" class="form-label">Pays <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('shipping_country') is-invalid @enderror" 
                                       id="shipping_country" 
                                       name="shipping_country" 
                                       value="{{ old('shipping_country', 'France') }}" 
                                       required>
                                @error('shipping_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="tel" 
                                   class="form-control @error('shipping_phone') is-invalid @enderror" 
                                   id="shipping_phone" 
                                   name="shipping_phone" 
                                   value="{{ old('shipping_phone') }}" 
                                   required>
                            @error('shipping_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i> Mode de paiement</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" checked>
                            <label class="form-check-label" for="payment_card">
                                <i class="fab fa-cc-visa me-2"></i> Carte bancaire
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash">
                            <label class="form-check-label" for="payment_cash">
                                <i class="fas fa-money-bill me-2"></i> Paiement à la livraison
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_bank" value="bank_transfer">
                            <label class="form-check-label" for="payment_bank">
                                <i class="fas fa-university me-2"></i> Virement bancaire
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-info" id="cardInfo" style="display: none;">
                        <i class="fas fa-info-circle me-2"></i>
                        Vous serez redirigé vers la plateforme de paiement sécurisé après validation.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> Récapitulatif de la commande</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="fw-bold">{{ $item->product->name }}</span>
                            <small class="text-muted d-block">Quantité: {{ $item->quantity }}</small>
                        </div>
                        <span class="text-primary">{{ number_format($item->product->price * $item->quantity, 2) }} €</span>
                    </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total:</span>
                        <span>{{ number_format($total, 2) }} €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Livraison:</span>
                        <span class="text-success">Gratuite</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 fw-bold fs-5">
                        <span>Total TTC:</span>
                        <span class="text-primary">{{ number_format($total, 2) }} €</span>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            J'accepte les <a href="#" target="_blank">conditions générales de vente</a>
                        </label>
                    </div>

                    <button type="submit" form="checkoutForm" class="btn btn-success btn-lg w-100" id="submitBtn">
                        <i class="fas fa-check-circle me-2"></i> Confirmer la commande
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Afficher/masquer l'info carte bancaire
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const cardInfo = document.getElementById('cardInfo');
            if (this.value === 'card') {
                cardInfo.style.display = 'block';
            } else {
                cardInfo.style.display = 'none';
            }
        });
    });

    // Activer l'info carte par défaut
    if (document.getElementById('payment_card').checked) {
        document.getElementById('cardInfo').style.display = 'block';
    }

    // Empêcher la soumission multiple
    document.getElementById('checkoutForm').addEventListener('submit', function() {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement en cours...';
    });
</script>
@endpush
@endsection