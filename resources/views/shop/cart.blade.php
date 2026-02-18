{{-- resources/views/shop/cart.blade.php --}}
@extends('shop.layouts.shop')

@section('title', 'Mon Panier - Ma Boutique')

@section('content')
<div class="container">
    <h1 class="mb-4">Mon Panier</h1>

    @if($cartItems->isEmpty())
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart fa-4x mb-3"></i>
            <h3>Votre panier est vide</h3>
            <p>Découvrez nos produits et commencez vos achats !</p>
            <a href="{{ route('shop.products.index') }}" class="btn btn-primary btn-lg mt-3">
                <i class="fas fa-box me-2"></i> Voir les produits
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                @foreach($cartItems as $item)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-fluid rounded">
                                @else
                                    <div class="bg-light text-center py-3 rounded">
                                        <i class="fas fa-box fa-2x text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h5 class="card-title">{{ $item->product->name }}</h5>
                                <p class="text-muted small">{{ Str::limit($item->product->description, 50) }}</p>
                            </div>
                            <div class="col-md-2">
                                <strong class="text-primary">{{ number_format($item->product->price, 2) }} €</strong>
                            </div>
                            <div class="col-md-2">
                                <form action="{{ route('shop.cart.update', $item) }}" method="POST" class="d-flex">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" 
                                           name="quantity" 
                                           value="{{ $item->quantity }}" 
                                           min="1" 
                                           max="{{ $item->product->quantity }}"
                                           class="form-control form-control-sm"
                                           style="width: 80px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-2 text-end">
                                <strong>{{ number_format($item->total, 2) }} €</strong>
                                <form action="{{ route('shop.cart.remove', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger ms-2" onclick="return confirm('Retirer cet article du panier ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="text-end mt-3">
                    <form action="{{ route('shop.cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Vider le panier ?')">
                            <i class="fas fa-trash-alt me-2"></i> Vider le panier
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Récapitulatif</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td>Sous-total:</td>
                                <td class="text-end">{{ number_format($total, 2) }} €</td>
                            </tr>
                            <tr>
                                <td>Livraison:</td>
                                <td class="text-end">{{ number_format(0, 2) }} €</td>
                            </tr>
                            <tr class="fw-bold">
                                <td>Total:</td>
                                <td class="text-end text-primary">{{ number_format($total, 2) }} €</td>
                            </tr>
                        </table>

                        @auth
                            <a href="{{ route('shop.checkout.index') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-credit-card me-2"></i> Passer la commande
                            </a>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Vous devez être connecté pour passer commande.
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus me-2"></i> Créer un compte
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection