{{-- resources/views/client/orders/index.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Mes commandes - Ma Boutique')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-history text-primary me-2"></i>
            Mes commandes
        </h1>
        <a href="{{ route('shop.products.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-shopping-bag me-2"></i> Continuer mes achats
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-4">
                <i class="fas fa-box-open"></i>
            </div>
            <h2 class="mb-3">Aucune commande pour le moment</h2>
            <p class="text-muted mb-4">Découvrez nos produits et passez votre première commande !</p>
            <a href="{{ route('shop.products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-store me-2"></i> Voir la boutique
            </a>
        </div>
    @else
        <div class="row">
            @foreach($orders as $order)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <span class="fw-bold">
                            <i class="fas fa-receipt me-2 text-primary"></i>
                            Commande #{{ $order->id }}
                        </span>
                        <span class="badge {{ $order->status === 'en attente' ? 'bg-warning' : 'bg-success' }} fs-6">
                            {{ $order->status }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <small class="text-muted">Date de commande</small>
                                <p class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">Total</small>
                                <p class="fw-bold text-primary">{{ number_format($order->total_amount, 2) }} €</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Produits commandés</small>
                            <div class="mt-2">
                                @foreach($order->products->take(3) as $product)
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>{{ $product->name }}</span>
                                        <span class="badge bg-secondary">x{{ $product->pivot->quantity }}</span>
                                    </div>
                                @endforeach
                                @if($order->products->count() > 3)
                                    <small class="text-muted">
                                        et {{ $order->products->count() - 3 }} autre(s) produit(s)
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Adresse de livraison</small>
                            <p class="mb-0">{{ $order->shipping_address }}</p>
                            <p class="mb-0">{{ $order->shipping_zip }} {{ $order->shipping_city }}</p>
                            <p>{{ $order->shipping_country }}</p>
                        </div>

                        <div class="d-grid">
                            <a href="{{ route('client.orders.show', $order) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i> Voir les détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection