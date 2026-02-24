{{-- resources/views/shop/checkout.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Finaliser votre commande - Ma Boutique Animalière')

@section('content')
<div class="container">
    <h1 class="mb-4">Finaliser votre commande</h1>

    @php
        $shipping = $total >= 50 ? 0 : 5.99;
        $finalTotal = $total + $shipping;
    @endphp

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
                            <div class="col-md-4 mb-3">
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
                            <div class="col-md-4 mb-3">
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
                            <div class="col-md-4 mb-3">
                                <label for="shipping_country" class="form-label">Pays <span class="text-danger">*</span></label>
                                <select class="form-control @error('shipping_country') is-invalid @enderror" 
                                        id="shipping_country" 
                                        name="shipping_country" 
                                        required>
                                    <option value="">Sélectionnez un pays</option>
                                    <option value="FR" {{ old('shipping_country', 'FR') == 'FR' ? 'selected' : '' }}>France</option>
                                    <option value="BE" {{ old('shipping_country') == 'BE' ? 'selected' : '' }}>Belgique</option>
                                    <option value="CH" {{ old('shipping_country') == 'CH' ? 'selected' : '' }}>Suisse</option>
                                    <option value="LU" {{ old('shipping_country') == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                                    <option value="CA" {{ old('shipping_country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                    <option value="US" {{ old('shipping_country') == 'US' ? 'selected' : '' }}>États-Unis</option>
                                    <option value="GB" {{ old('shipping_country') == 'GB' ? 'selected' : '' }}>Royaume-Uni</option>
                                    <option value="DE" {{ old('shipping_country') == 'DE' ? 'selected' : '' }}>Allemagne</option>
                                    <option value="ES" {{ old('shipping_country') == 'ES' ? 'selected' : '' }}>Espagne</option>
                                    <option value="IT" {{ old('shipping_country') == 'IT' ? 'selected' : '' }}>Italie</option>
                                    <option value="PT" {{ old('shipping_country') == 'PT' ? 'selected' : '' }}>Portugal</option>
                                    <option value="NL" {{ old('shipping_country') == 'NL' ? 'selected' : '' }}>Pays-Bas</option>
                                    <option value="DK" {{ old('shipping_country') == 'DK' ? 'selected' : '' }}>Danemark</option>
                                    <option value="SE" {{ old('shipping_country') == 'SE' ? 'selected' : '' }}>Suède</option>
                                    <option value="NO" {{ old('shipping_country') == 'NO' ? 'selected' : '' }}>Norvège</option>
                                    <option value="FI" {{ old('shipping_country') == 'FI' ? 'selected' : '' }}>Finlande</option>
                                </select>
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
                                <i class="fab fa-cc-visa me-2"></i> Carte bancaire (Stripe)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash">
                            <label class="form-check-label" for="payment_cash">
                                <i class="fas fa-money-bill me-2"></i> Paiement à la livraison
                            </label>
                        </div>
                    </div>

                    <!-- Section de paiement Stripe -->
                    <div id="stripe-payment-section" style="display: none;">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-lock me-2"></i> Paiement sécurisé par Stripe
                        </div>
                        
                        <!-- Stripe Elements va s'insérer ici -->
                        <div id="stripe-card-element" class="form-control mb-3" style="padding: 12px;">
                            <!-- Un élément de carte Stripe sera monté ici -->
                        </div>
                        <div id="stripe-card-errors" class="text-danger mb-3" role="alert"></div>
                        
                        <!-- Champs cachés pour les données Stripe -->
                        <input type="hidden" name="stripe_payment_method_id" id="stripe_payment_method_id">
                        <input type="hidden" name="stripe_payment_intent_id" id="stripe_payment_intent_id">
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
                        @if($shipping > 0)
                            <span>{{ number_format($shipping, 2) }} €</span>
                        @else
                            <span class="text-success">Gratuite</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between mb-3 fw-bold fs-5">
                        <span>Total TTC:</span>
                        <span class="text-primary" id="totalAmount">{{ number_format($finalTotal, 2) }} €</span>
                    </div>

                    @if($total < 50)
                    <div class="alert alert-info py-2 mb-3">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Plus que {{ number_format(50 - $total, 2) }} € pour la livraison gratuite !
                        </small>
                    </div>
                    @endif

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            J'accepte les <a href="#" target="_blank">conditions générales de vente</a>
                        </label>
                    </div>

                    <button type="submit" form="checkoutForm" class="btn btn-success btn-lg w-100" id="submitBtn">
                        <i class="fas fa-check-circle me-2"></i> Confirmer la commande ({{ number_format($finalTotal, 2) }} €)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour Stripe -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialiser Stripe
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    
    // Créer l'élément de carte
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#aab7c4'
                }
            }
        }
    });

    // Éléments DOM
    const stripeSection = document.getElementById('stripe-payment-section');
    const cardErrors = document.getElementById('stripe-card-errors');
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const checkoutForm = document.getElementById('checkoutForm');
    const submitBtn = document.getElementById('submitBtn');
    const finalTotal = parseFloat('{{ $finalTotal }}');

    // Afficher/masquer la section Stripe selon le mode de paiement
    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const cardInfo = document.getElementById('cardInfo');
            if (this.value === 'card') {
                stripeSection.style.display = 'block';
                cardElement.mount('#stripe-card-element');
                cardInfo.style.display = 'none';
            } else {
                stripeSection.style.display = 'none';
                cardElement.unmount();
                cardInfo.style.display = 'block';
            }
        });
    });

    // Activer Stripe par défaut
    if (document.getElementById('payment_card').checked) {
        stripeSection.style.display = 'block';
        cardElement.mount('#stripe-card-element');
    }

    // Gérer les erreurs de carte en temps réel
    cardElement.on('change', function(event) {
        if (event.error) {
            cardErrors.textContent = event.error.message;
        } else {
            cardErrors.textContent = '';
        }
    });

    // Gérer la soumission du formulaire
    checkoutForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (paymentMethod === 'card') {
            // Désactiver le bouton
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement du paiement...';
            
            try {
                // Créer le PaymentIntent côté serveur avec le montant total (incluant livraison)
                const response = await fetch('{{ route("shop.checkout.create-payment-intent") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        amount: Math.round(finalTotal * 100) // Convertir en centimes
                    })
                });
                
                const data = await response.json();
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                // Confirmer le paiement avec Stripe
                const { error, paymentIntent } = await stripe.confirmCardPayment(data.clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: document.getElementById('shipping_name').value,
                            email: '{{ auth()->user()->email }}',
                            phone: document.getElementById('shipping_phone').value,
                            address: {
                                line1: document.getElementById('shipping_address').value,
                                city: document.getElementById('shipping_city').value,
                                postal_code: document.getElementById('shipping_zip').value,
                                country: document.getElementById('shipping_country').value
                            }
                        }
                    }
                });
                
                if (error) {
                    throw new Error(error.message);
                }
                
                if (paymentIntent.status === 'succeeded') {
                    // Ajouter les IDs Stripe au formulaire
                    document.getElementById('stripe_payment_intent_id').value = paymentIntent.id;
                    document.getElementById('stripe_payment_method_id').value = paymentIntent.payment_method;
                    
                    // Soumettre le formulaire normalement
                    checkoutForm.submit();
                }
                
            } catch (error) {
                cardErrors.textContent = error.message;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Confirmer la commande ({{ number_format($finalTotal, 2) }} €)';
            }
        } else {
            // Pour les autres modes de paiement, soumettre directement
            checkoutForm.submit();
        }
    });
</script>
@endsection