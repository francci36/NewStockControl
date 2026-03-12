@extends('shop.layouts.shop')

@section('title', __('messages.checkout_title'))

@section('content')
<div class="container py-4">
    <div class="section-header mb-4">
        <h1 class="section-title">{{ __('messages.checkout') }}</h1>
    </div>

    @php
        $shipping = $total >= 50 ? 0 : 5.99;
        $finalTotal = $total + $shipping;
    @endphp

    <div class="row">
        <div class="col-lg-7">
            <!-- Informations de livraison -->
            <div class="card border-0 shadow-lg mb-4 checkout-card">
                <div class="card-header bg-gradient-dark text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-truck me-2 text-gold"></i> {{ __('messages.shipping_address') }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('shop.checkout.process') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="shipping_name" class="form-label fw-bold">{{ __('messages.full_name') }} <span class="text-gold">*</span></label>
                            <input type="text" 
                                   class="form-control border-gold @error('shipping_name') is-invalid @enderror" 
                                   id="shipping_name" 
                                   name="shipping_name" 
                                   value="{{ old('shipping_name', auth()->user()->name) }}" 
                                   required>
                            @error('shipping_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label fw-bold">{{ __('messages.full_address') }} <span class="text-gold">*</span></label>
                            <textarea class="form-control border-gold @error('shipping_address') is-invalid @enderror" 
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
                                <label for="shipping_city" class="form-label fw-bold">{{ __('messages.city') }} <span class="text-gold">*</span></label>
                                <input type="text" 
                                       class="form-control border-gold @error('shipping_city') is-invalid @enderror" 
                                       id="shipping_city" 
                                       name="shipping_city" 
                                       value="{{ old('shipping_city') }}" 
                                       required>
                                @error('shipping_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shipping_zip" class="form-label fw-bold">{{ __('messages.postal_code') }} <span class="text-gold">*</span></label>
                                <input type="text" 
                                       class="form-control border-gold @error('shipping_zip') is-invalid @enderror" 
                                       id="shipping_zip" 
                                       name="shipping_zip" 
                                       value="{{ old('shipping_zip') }}" 
                                       required>
                                @error('shipping_zip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shipping_country" class="form-label fw-bold">{{ __('messages.country') }} <span class="text-gold">*</span></label>
                                <select class="form-select border-gold @error('shipping_country') is-invalid @enderror" 
                                        id="shipping_country" 
                                        name="shipping_country" 
                                        required>
                                    <option value="">{{ __('messages.select_country') }}</option>
                                    <option value="FR" {{ old('shipping_country', 'FR') == 'FR' ? 'selected' : '' }}>{{ __('countries.fr') }}</option>
                                    <option value="BE" {{ old('shipping_country') == 'BE' ? 'selected' : '' }}>{{ __('countries.be') }}</option>
                                    <option value="CH" {{ old('shipping_country') == 'CH' ? 'selected' : '' }}>{{ __('countries.ch') }}</option>
                                    <option value="LU" {{ old('shipping_country') == 'LU' ? 'selected' : '' }}>{{ __('countries.lu') }}</option>
                                    <option value="CA" {{ old('shipping_country') == 'CA' ? 'selected' : '' }}>{{ __('countries.ca') }}</option>
                                    <option value="US" {{ old('shipping_country') == 'US' ? 'selected' : '' }}>{{ __('countries.us') }}</option>
                                    <option value="GB" {{ old('shipping_country') == 'GB' ? 'selected' : '' }}>{{ __('countries.gb') }}</option>
                                    <option value="DE" {{ old('shipping_country') == 'DE' ? 'selected' : '' }}>{{ __('countries.de') }}</option>
                                    <option value="ES" {{ old('shipping_country') == 'ES' ? 'selected' : '' }}>{{ __('countries.es') }}</option>
                                    <option value="IT" {{ old('shipping_country') == 'IT' ? 'selected' : '' }}>{{ __('countries.it') }}</option>
                                    <option value="PT" {{ old('shipping_country') == 'PT' ? 'selected' : '' }}>{{ __('countries.pt') }}</option>
                                    <option value="NL" {{ old('shipping_country') == 'NL' ? 'selected' : '' }}>{{ __('countries.nl') }}</option>
                                    <option value="DK" {{ old('shipping_country') == 'DK' ? 'selected' : '' }}>{{ __('countries.dk') }}</option>
                                    <option value="SE" {{ old('shipping_country') == 'SE' ? 'selected' : '' }}>{{ __('countries.se') }}</option>
                                    <option value="NO" {{ old('shipping_country') == 'NO' ? 'selected' : '' }}>{{ __('countries.no') }}</option>
                                    <option value="FI" {{ old('shipping_country') == 'FI' ? 'selected' : '' }}>{{ __('countries.fi') }}</option>
                                    <option value="BR" {{ old('shipping_country') == 'BR' ? 'selected' : '' }}>{{ __('countries.br') }}</option>
                                </select>
                                @error('shipping_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_phone" class="form-label fw-bold">{{ __('messages.phone') }} <span class="text-gold">*</span></label>
                            <input type="tel" 
                                   class="form-control border-gold @error('shipping_phone') is-invalid @enderror" 
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

            <!-- Mode de paiement -->
            <div class="card border-0 shadow-lg mb-4 checkout-card">
                <div class="card-header bg-gradient-dark text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2 text-gold"></i> {{ __('messages.payment_method') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" checked>
                            <label class="form-check-label fw-bold" for="payment_card">
                                <span class="badge bg-gold text-dark me-2">
                                    <i class="fab fa-cc-visa"></i> <i class="fab fa-cc-mastercard"></i>
                                </span>
                                {{ __('messages.credit_card') }}
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash">
                            <label class="form-check-label fw-bold" for="payment_cash">
                                <span class="badge bg-light-gold text-dark me-2">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                                {{ __('messages.cash_on_delivery') }}
                            </label>
                        </div>
                    </div>

                    <!-- Section de paiement Stripe -->
                    <div id="stripe-payment-section" style="display: block;">
                        <div class="alert alert-gold mb-3">
                            <i class="fas fa-lock me-2 text-gold"></i> {{ __('messages.secure_stripe') }}
                        </div>
                        
                        <!-- Conteneur pour l'élément de carte Stripe -->
                        <div id="card-element" class="form-control border-gold mb-3" style="padding: 12px; min-height: 50px;">
                            <!-- Stripe Elements va s'insérer ici -->
                        </div>
                        <div id="card-errors" class="text-danger mb-3" role="alert"></div>
                        
                        <!-- Champs cachés pour les données Stripe -->
                        <input type="hidden" name="stripe_payment_method_id" id="stripe_payment_method_id">
                        <input type="hidden" name="stripe_payment_intent_id" id="stripe_payment_intent_id">
                    </div>
                </div>
            </div>
        </div>

        <!-- Résumé de la commande -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg summary-card sticky-top" style="top: 20px;">
                <div class="card-header bg-gradient-dark text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2 text-gold"></i> {{ __('messages.order_summary') }}</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Liste des produits -->
                    <div class="products-list mb-3">
                        @foreach($cartItems as $item)
                        @php
                            // Nettoyer le nom du produit
                            $productName = $item->product->translated_name;
                            if (is_string($productName) && str_starts_with($productName, '{') && str_ends_with($productName, '}')) {
                                $decoded = json_decode($productName, true);
                                if (is_array($decoded)) {
                                    $productName = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $productName;
                                }
                            }
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-gold-20">
                            <div>
                                <span class="fw-bold">{{ $productName }}</span>
                                <small class="text-muted d-block">
                                    <i class="fas fa-times me-1 text-gold" style="font-size: 0.7rem;"></i> {{ $item->quantity }}
                                </small>
                            </div>
                            <span class="text-gold fw-bold">{{ number_format($item->product->price * $item->quantity, 2) }} €</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Détails des prix -->
                    <div class="price-details">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('messages.subtotal') }}:</span>
                            <span class="fw-bold">{{ number_format($total, 2) }} €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ __('messages.shipping') }}:</span>
                            @if($shipping > 0)
                                <span class="fw-bold">{{ number_format($shipping, 2) }} €</span>
                            @else
                                <span class="text-success fw-bold">{{ __('messages.free') }}</span>
                            @endif
                        </div>
                        
                        <hr class="border-gold-20 my-3">
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold fs-5">{{ __('messages.total_ttc') }}:</span>
                            <span class="text-gold fw-bold fs-4" id="totalAmount">{{ number_format($finalTotal, 2) }} €</span>
                        </div>
                    </div>

                    @if($total < 50)
                    <div class="alert alert-gold py-2 mb-3">
                        <small class="d-flex align-items-center">
                            <i class="fas fa-truck text-gold me-2"></i>
                            {{ __('messages.free_shipping_remaining', ['amount' => number_format(50 - $total, 2)]) }}
                        </small>
                    </div>
                    @endif

                    <!-- Conditions -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label small" for="terms">
                            {!! __('messages.accept_terms', ['link' => '<a href="#" class="text-gold text-decoration-none">'.__('messages.terms_link').'</a>']) !!}
                        </label>
                    </div>

                    <!-- Bouton de confirmation -->
                    <button type="submit" form="checkoutForm" class="btn btn-gold btn-lg w-100" id="submitBtn">
                        <i class="fas fa-check-circle me-2"></i> {{ __('messages.confirm_order') }} ({{ number_format($finalTotal, 2) }} €)
                    </button>

                    <p class="text-center text-muted small mt-3 mb-0">
                        <i class="fas fa-lock me-1 text-gold"></i> {{ __('messages.secure_payment') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FERMETURE DU FORMULAIRE -->
</form>

<!-- Scripts pour Stripe -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Initialiser Stripe avec la clé depuis la configuration
            const stripe = Stripe('{{ config('services.stripe.key') }}');
            const elements = stripe.elements();
            
            // Créer l'élément de carte avec style personnalisé
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    }
                }
            });

            // Éléments DOM
            const stripeSection = document.getElementById('stripe-payment-section');
            const cardErrors = document.getElementById('card-errors');
            const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
            const checkoutForm = document.getElementById('checkoutForm');
            const submitBtn = document.getElementById('submitBtn');
            const finalTotal = parseFloat('{{ $finalTotal }}');

            let isCardMounted = false;

            // Fonction pour monter la carte
            function mountCard() {
                if (!isCardMounted) {
                    cardElement.mount('#card-element');
                    isCardMounted = true;
                }
            }

            // Fonction pour démonter la carte
            function unmountCard() {
                if (isCardMounted) {
                    cardElement.unmount();
                    isCardMounted = false;
                }
            }

            // Monter la carte par défaut (carte sélectionnée)
            if (document.getElementById('payment_card').checked) {
                mountCard();
            }

            // Gérer le changement de mode de paiement
            paymentMethodRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'card') {
                        stripeSection.style.display = 'block';
                        setTimeout(() => mountCard(), 50);
                    } else {
                        stripeSection.style.display = 'none';
                        unmountCard();
                    }
                });
            });

            // Gérer les erreurs de carte
            cardElement.on('change', function(event) {
                if (event.error) {
                    cardErrors.textContent = event.error.message;
                } else {
                    cardErrors.textContent = '';
                }
            });

            // Gérer la soumission du formulaire
            checkoutForm.addEventListener('submit', async function(e) {
                const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                
                if (paymentMethod === 'card') {
                    e.preventDefault();
                    
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> {{ __('messages.processing_payment') }}';
                    
                    try {
                        const response = await fetch('{{ route("shop.checkout.create-payment-intent") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                amount: Math.round(finalTotal * 100)
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.error) throw new Error(data.error);
                        
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
                        
                        if (error) throw new Error(error.message);
                        
                        if (paymentIntent.status === 'succeeded') {
                            document.getElementById('stripe_payment_intent_id').value = paymentIntent.id;
                            document.getElementById('stripe_payment_method_id').value = paymentIntent.payment_method;
                            checkoutForm.submit();
                        }
                        
                    } catch (error) {
                        cardErrors.textContent = error.message;
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> {{ __('messages.confirm_order') }} ({{ number_format($finalTotal, 2) }} €)';
                    }
                }
            });

        } catch (error) {
            console.error('Erreur Stripe:', error);
        }
    });
</script>
@endsection

@push('styles')
<style>
    /* Couleurs du thème */
    :root {
        --primary-dark: #1a1a2e;
        --primary-medium: #16213e;
        --primary-light: #0f3460;
        --accent-gold: #f7d44a;
        --text-light: #e0e0e0;
    }

    /* En-tête de section */
    .section-header {
        border-bottom: 2px solid var(--accent-gold);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    .section-title {
        color: var(--primary-dark);
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .dark .section-title {
        color: var(--text-light);
    }

    /* Gradient dark */
    .bg-gradient-dark {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 50%, var(--primary-light) 100%) !important;
    }

    /* Alertes gold */
    .alert-gold {
        background-color: rgba(247, 212, 74, 0.1);
        border: 1px solid var(--accent-gold);
        color: var(--primary-dark);
    }
    
    .dark .alert-gold {
        background-color: rgba(247, 212, 74, 0.15);
        color: var(--text-light);
    }

    /* Badges */
    .bg-light-gold {
        background-color: rgba(247, 212, 74, 0.15) !important;
        color: var(--primary-dark) !important;
    }
    
    .dark .bg-light-gold {
        color: var(--text-light) !important;
    }
    
    .bg-gold {
        background-color: var(--accent-gold) !important;
    }

    /* Bordures */
    .border-gold {
        border-color: var(--accent-gold) !important;
    }
    
    .border-gold:focus {
        box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25);
    }
    
    .border-gold-20 {
        border-color: rgba(247, 212, 74, 0.2) !important;
    }

    /* Bouton gold */
    .btn-gold {
        background-color: var(--accent-gold);
        color: var(--primary-dark);
        border: none;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .btn-gold:hover {
        background-color: #e5c03a;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
    }

    /* Texte gold */
    .text-gold {
        color: var(--accent-gold) !important;
    }

    /* Cartes checkout */
    .checkout-card, .summary-card {
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .checkout-card:hover, .summary-card:hover {
        box-shadow: 0 15px 30px rgba(247, 212, 74, 0.15) !important;
    }

    /* Formulaires */
    .form-control:focus, .form-select:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25);
    }

    /* Checkbox personnalisée */
    .form-check-input:checked {
        background-color: var(--accent-gold);
        border-color: var(--accent-gold);
    }
    
    .form-check-input:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 0.2rem rgba(247, 212, 74, 0.25);
    }

    /* Mode sombre */
    .dark .checkout-card,
    .dark .summary-card {
        background-color: #2d2d44;
    }
    
    .dark .form-control,
    .dark .form-select {
        background-color: #2d2d44;
        border-color: #4a4a6a;
        color: var(--text-light);
    }
    
    .dark .form-control:focus,
    .dark .form-select:focus {
        background-color: #35354a;
    }
    
    .dark .text-muted {
        color: #a0a0c0 !important;
    }
    
    .dark .border-gold-20 {
        border-color: rgba(247, 212, 74, 0.15) !important;
    }
</style>
@endpush