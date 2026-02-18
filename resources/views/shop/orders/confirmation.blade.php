{{-- resources/views/shop/orders/confirmation.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Commande confirmée - Ma Boutique')

@section('content')
<div class="container">
    <div class="text-center mb-5">
        <div class="display-1 text-success mb-3">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="display-4">Merci pour votre commande !</h1>
        <p class="lead">Votre commande #{{ $order->id }} a bien été enregistrée.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> Détails de la commande</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Numéro de commande:</strong><br>
                            #{{ $order->id }}
                        </div>
                        <div class="col-sm-6">
                            <strong>Date:</strong><br>
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <strong>Statut:</strong><br>
                            <span class="badge bg-warning">{{ $order->status }}</span>
                        </div>
                        <div class="col-sm-6">
                            <strong>Total:</strong><br>
                            <span class="text-primary fw-bold">{{ number_format($order->total_amount, 2) }} €</span>
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3">Produits commandés :</h6>
                    <table class="table table-sm">
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
                                <td>{{ $product->name }}</td>
                                <td class="text-center">{{ $product->pivot->quantity }}</td>
                                <td class="text-end">{{ number_format($product->pivot->price, 2) }} €</td>
                                <td class="text-end">{{ number_format($product->pivot->price * $product->pivot->quantity, 2) }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total TTC :</th>
                                <th class="text-end">{{ number_format($order->total_amount, 2) }} €</th>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="alert alert-info mt-4">
                        <i class="fas fa-envelope me-2"></i>
                        Un email de confirmation a été envoyé à <strong>{{ auth()->user()->email }}</strong>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('shop.products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i> Continuer mes achats
                </a>
                
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-tachometer-alt me-2"></i> Administration
                    </a>
                @else
                    <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-history me-2"></i> Voir mes commandes
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection