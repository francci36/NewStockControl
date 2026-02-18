{{-- resources/views/client/orders/show.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Détails de la commande - Ma Boutique')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('client.orders.index') }}">Mes commandes</a></li>
            <li class="breadcrumb-item active">Commande #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        Détails de la commande #{{ $order->id }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <strong>Date de commande</strong>
                            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>Statut</strong>
                            <p>
                                <span class="badge {{ $order->status === 'en attente' ? 'bg-warning' : 'bg-success' }} fs-6">
                                    {{ $order->status }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <h6 class="mb-3">Produits commandés</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Prix unitaire</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->products as $product)
                                <tr>
                                    <td>
                                        {{ $product->name }}
                                        <small class="d-block text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    </td>
                                    <td class="text-center">{{ $product->pivot->quantity }}</td>
                                    <td class="text-end">{{ number_format($product->pivot->price, 2) }} €</td>
                                    <td class="text-end">{{ number_format($product->pivot->price * $product->pivot->quantity, 2) }} €</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Sous-total :</th>
                                    <th class="text-end">{{ number_format($order->total_amount, 2) }} €</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Livraison :</th>
                                    <th class="text-end">Gratuite</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Total TTC :</th>
                                    <th class="text-end text-primary fs-5">{{ number_format($order->total_amount, 2) }} €</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Adresse de livraison -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-truck me-2"></i>
                        Adresse de livraison
                    </h5>
                </div>
                <div class="card-body">
                    <p class="fw-bold mb-1">{{ $order->shipping_name }}</p>
                    <p class="mb-1">{{ $order->shipping_address }}</p>
                    <p class="mb-1">{{ $order->shipping_zip }} {{ $order->shipping_city }}</p>
                    <p class="mb-1">{{ $order->shipping_country }}</p>
                    <p class="mb-0">
                        <i class="fas fa-phone me-2 text-muted"></i>
                        {{ $order->shipping_phone }}
                    </p>
                </div>
            </div>

            <!-- Paiement -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Paiement
                    </h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Mode de paiement :</strong><br>
                        @switch($order->payment_method)
                            @case('card')
                                <i class="fab fa-cc-visa me-2"></i> Carte bancaire
                                @break
                            @case('cash')
                                <i class="fas fa-money-bill me-2"></i> Paiement à la livraison
                                @break
                            @case('bank_transfer')
                                <i class="fas fa-university me-2"></i> Virement bancaire
                                @break
                        @endswitch
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-grid gap-2">
                <a href="{{ route('shop.products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i> Nouvelle commande
                </a>
                <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-history me-2"></i> Retour à l'historique
                </a>
            </div>
        </div>
    </div>
</div>
@endsection