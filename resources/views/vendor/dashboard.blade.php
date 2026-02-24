{{-- resources/views/vendor/dashboard.blade.php --}}
@extends('vendor.layouts.vendor')

@section('title', 'Tableau de bord')

@section('page-title', 'Tableau de bord')

@section('content')
<div class="row">
    <!-- Stats cards -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_products'] }}</h3>
                <p>Produits</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <a href="{{ route('vendor.products.index') }}" class="small-box-footer">
                Voir détails <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['total_orders'] }}</h3>
                <p>Commandes</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="{{ route('vendor.orders.index') }}" class="small-box-footer">
                Voir détails <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($stats['revenue_month'], 2) }} €</h3>
                <p>Chiffre d'affaires (mois)</p>
            </div>
            <div class="icon">
                <i class="fas fa-euro-sign"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['pending_orders'] }}</h3>
                <p>Commandes en attente</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
</div>

<!-- Derniers produits -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Derniers produits ajoutés</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentProducts as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 2) }} €</td>
                    <td>
                        <span class="badge {{ $product->quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $product->quantity }}
                        </span>
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('vendor.products.edit', $product) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun produit</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary mt-2">
            <i class="fas fa-plus"></i> Ajouter un produit
        </a>
    </div>
</div>
@endsection