@extends('shop.layouts.shop')

@section('title', __('messages.order_confirmation_title'))

@section('content')
<div class="container py-5">
    <!-- Icône de succès et message de remerciement -->
    <div class="text-center mb-5">
        <div class="success-animation mb-4">
            <div class="success-circle">
                <i class="fas fa-check-circle fa-5x text-gold"></i>
            </div>
        </div>
        <h1 class="display-4 fw-bold text-gradient">{{ __('messages.thank_you') }}</h1>
        <p class="lead text-muted">{{ __('messages.order_confirmed', ['id' => $order->id]) }}</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Carte des détails de la commande -->
            <div class="card border-0 shadow-lg confirmation-card mb-4">
                <div class="card-header bg-gradient-dark text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2 text-gold"></i> {{ __('messages.order_details') }}</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Informations générales -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="info-box p-3 bg-light-gold rounded-3">
                                <small class="text-muted d-block mb-1">{{ __('messages.order_number') }}</small>
                                <span class="fs-4 fw-bold text-gold">#{{ $order->id }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box p-3 bg-light-gold rounded-3">
                                <small class="text-muted d-block mb-1">{{ __('messages.order_date') }}</small>
                                <span class="fs-4 fw-bold text-gold">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Statut et total -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="info-box p-3 bg-light-gold rounded-3">
                                <small class="text-muted d-block mb-1">{{ __('messages.status') }}</small>
                                <span class="badge bg-gold text-dark fs-6 px-3 py-2">
                                    <i class="fas fa-clock me-1"></i> {{ __('messages.' . $order->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box p-3 bg-light-gold rounded-3">
                                <small class="text-muted d-block mb-1">{{ __('messages.total') }}</small>
                                <span class="fs-4 fw-bold text-gold">{{ number_format($order->total_amount, 2) }} €</span>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des produits avec traductions -->
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-box me-2 text-gold"></i> {{ __('messages.ordered_products') }}
                    </h6>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light-gold">
                                <tr>
                                    <th>{{ __('messages.product') }}</th>
                                    <th class="text-center">{{ __('messages.quantity') }}</th>
                                    <th class="text-end">{{ __('messages.unit_price') }}</th>
                                    <th class="text-end">{{ __('messages.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->products as $product)
                                @php
                                    // Nettoyer le nom du produit
                                    $productName = $product->translated_name;
                                    if (is_string($productName) && str_starts_with($productName, '{') && str_ends_with($productName, '}')) {
                                        $decoded = json_decode($productName, true);
                                        if (is_array($decoded)) {
                                            $productName = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $productName;
                                        }
                                    }
                                    
                                    // Nettoyer le nom de la catégorie
                                    $categoryName = null;
                                    if ($product->category) {
                                        $categoryName = $product->category->translated_name;
                                        if (is_string($categoryName) && str_starts_with($categoryName, '{') && str_ends_with($categoryName, '}')) {
                                            $decoded = json_decode($categoryName, true);
                                            if (is_array($decoded)) {
                                                $categoryName = $decoded[app()->getLocale()] ?? $decoded['fr'] ?? $categoryName;
                                            }
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                                @php
                                                    $imageName = basename($product->image);
                                                @endphp
                                                <img src="{{ asset('storage/products/' . $imageName) }}" 
                                                     alt="{{ $productName }}" 
                                                     class="rounded me-3"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light-gold rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-box text-gold"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <!-- Nom du produit traduit et nettoyé -->
                                                <span class="fw-bold">{{ $productName }}</span>
                                                
                                                <!-- Catégorie du produit traduite et nettoyée -->
                                                @if($categoryName)
                                                    <br><small class="text-muted">{{ $categoryName }}</small>
                                                @endif
                                                
                                                <!-- Fournisseur si disponible -->
                                                @if($product->supplier)
                                                    <br><small class="text-muted">
                                                        <i class="fas fa-truck me-1" style="color: var(--accent-gold);"></i>
                                                        {{ $product->supplier->name }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light-gold text-dark px-3 py-2">
                                            {{ $product->pivot->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-end">{{ number_format($product->pivot->price, 2) }} €</td>
                                    <td class="text-end fw-bold text-gold">
                                        {{ number_format($product->pivot->price * $product->pivot->quantity, 2) }} €
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light-gold">
                                <tr>
                                    <th colspan="3" class="text-end fs-5">{{ __('messages.total_ttc') }}:</th>
                                    <th class="text-end fs-4 text-gold">{{ number_format($order->total_amount, 2) }} €</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Message de confirmation email -->
                    <div class="alert alert-gold mt-4 d-flex align-items-center">
                        <div class="alert-icon me-3">
                            <i class="fas fa-envelope fa-2x text-gold"></i>
                        </div>
                        <div>
                            <strong>{{ __('messages.confirmation_email_sent', ['email' => auth()->user()->email]) }}</strong>
                            <p class="mb-0 small text-muted">{{ __('messages.email_info') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="text-center">
                <a href="{{ route('shop.products.index') }}" class="btn btn-gold btn-lg px-5 mb-2 mb-sm-0">
                    <i class="fas fa-shopping-bag me-2"></i> {{ __('messages.continue_shopping') }}
                </a>
                
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-gold btn-lg px-5 ms-sm-2">
                        <i class="fas fa-tachometer-alt me-2"></i> {{ __('messages.administration') }}
                    </a>
                @else
                    <a href="{{ route('client.orders.index') }}" class="btn btn-outline-gold btn-lg px-5 ms-sm-2">
                        <i class="fas fa-history me-2"></i> {{ __('messages.view_my_orders') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
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

    /* Animation de succès */
    .success-animation {
        animation: bounceIn 0.8s ease;
    }
    
    .success-circle {
        display: inline-block;
        animation: pulse 2s infinite;
    }
    
    @keyframes bounceIn {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        50% {
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Texte gradient */
    .text-gradient {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 50%, var(--primary-light) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Gradient dark */
    .bg-gradient-dark {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-medium) 50%, var(--primary-light) 100%) !important;
    }

    /* Alertes gold */
    .alert-gold {
        background-color: rgba(247, 212, 74, 0.1);
        border: 1px solid var(--accent-gold);
        border-radius: 10px;
        color: var(--primary-dark);
    }
    
    .dark .alert-gold {
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

    /* Texte gold */
    .text-gold {
        color: var(--accent-gold) !important;
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
    
    .btn-outline-gold {
        background-color: transparent;
        color: var(--accent-gold);
        border: 2px solid var(--accent-gold);
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .btn-outline-gold:hover {
        background-color: var(--accent-gold);
        color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
    }

    /* Carte de confirmation */
    .confirmation-card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .confirmation-card:hover {
        box-shadow: 0 15px 30px rgba(247, 212, 74, 0.15) !important;
    }

    /* Boîtes d'information */
    .info-box {
        transition: transform 0.3s ease;
    }
    
    .info-box:hover {
        transform: translateY(-3px);
    }

    /* Tableau */
    .table thead th {
        border-bottom: 2px solid var(--accent-gold);
        font-weight: 600;
    }
    
    .table tfoot th {
        border-top: 2px solid var(--accent-gold);
    }
    
    .table td, .table th {
        vertical-align: middle;
    }

    /* Mode sombre */
    .dark .table {
        color: var(--text-light);
    }
    
    .dark .table thead th {
        background-color: rgba(247, 212, 74, 0.15);
        color: var(--text-light);
    }
    
    .dark .table tfoot {
        background-color: rgba(247, 212, 74, 0.1);
    }
    
    .dark .confirmation-card {
        background-color: #2d2d44;
    }
    
    .dark .text-muted {
        color: #a0a0c0 !important;
    }
    
    .dark .alert-gold {
        background-color: rgba(247, 212, 74, 0.15);
    }
</style>
@endpush