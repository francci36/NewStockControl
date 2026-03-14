@extends('vendor.layouts.app')

@section('title', __('messages.order_details') . ' #' . $order->id)
@section('page-title', __('messages.order') . ' #' . $order->id)

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-info text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-info-circle mr-2"></i> {{ __('messages.order') }} #{{ $order->id }}
                </h5>
                <small class="text-white-50 d-block d-md-inline mt-1 mt-md-0">
                    <i class="fas fa-calendar mr-1"></i> {{ __('messages.placed_on') }} {{ $order->created_at->format('d/m/Y à H:i') }}
                </small>
            </div>
            <div>
                <a href="{{ route('vendor.orders.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i> <span class="d-none d-sm-inline">{{ __('messages.back') }}</span>
                    <span class="d-sm-none">←</span>
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Message de confirmation si expédiée -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            @endif

            <!-- Badge de statut -->
            <div class="row mb-3">
                <div class="col-12">
                    @php
                        $statusClasses = [
                            'pending' => 'bg-warning',
                            'processing' => 'bg-info',
                            'shipped' => 'bg-primary',
                            'delivered' => 'bg-success',
                            'cancelled' => 'bg-danger',
                        ];
                        $statusIcons = [
                            'pending' => 'fa-clock',
                            'processing' => 'fa-cog',
                            'shipped' => 'fa-truck',
                            'delivered' => 'fa-check-circle',
                            'cancelled' => 'fa-times-circle',
                        ];
                        $statusLabels = [
                            'pending' => __('messages.pending'),
                            'processing' => __('messages.processing'),
                            'shipped' => __('messages.shipped'),
                            'delivered' => __('messages.delivered'),
                            'cancelled' => __('messages.cancelled'),
                        ];
                    @endphp
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
                        <span class="badge {{ $statusClasses[$order->status] ?? 'bg-secondary' }} status-badge-large">
                            <i class="fas {{ $statusIcons[$order->status] ?? 'fa-question' }} mr-2"></i>
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                        @if($order->status == 'shipped' && $order->shipped_at)
                            <span class="text-success">
                                <i class="fas fa-check-circle mr-1"></i> 
                                {{ __('messages.shipped_on') }} {{ $order->shipped_at->format('d/m/Y à H:i') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Informations client -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-user mr-2"></i> {{ __('messages.customer') }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="info-grid">
                                <div class="info-row">
                                    <span class="info-label">{{ __('messages.name') }} :</span>
                                    <span class="info-value">{{ $order->user->name ?? __('messages.unknown_customer') }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">{{ __('messages.email') }} :</span>
                                    <span class="info-value">
                                        <a href="mailto:{{ $order->user->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope mr-1 text-primary"></i>{{ $order->user->email ?? __('messages.unknown_customer') }}
                                        </a>
                                    </span>
                                </div>
                                @if($order->shipping_phone)
                                <div class="info-row">
                                    <span class="info-label">{{ __('messages.phone') }} :</span>
                                    <span class="info-value">
                                        <a href="tel:{{ $order->shipping_phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone mr-1 text-success"></i>{{ $order->shipping_phone }}
                                        </a>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informations livraison -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-truck mr-2"></i> {{ __('messages.shipping_address') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($order->shipping_address)
                                <address class="mb-0">
                                    {{ $order->shipping_address }}<br>
                                    @if($order->shipping_zip){{ $order->shipping_zip }} @endif
                                    {{ $order->shipping_city }}<br>
                                    {{ $order->shipping_country }}
                                </address>
                            @else
                                <p class="text-muted mb-0">{{ __('messages.address_not_provided') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de suivi (si expédiée) -->
            @if($order->status == 'shipped' && ($order->tracking_number || $order->carrier))
            <div class="card mt-4 bg-light">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-search-location mr-2"></i> {{ __('messages.tracking_info') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="info-row">
                                <span class="info-label">{{ __('messages.carrier') }} :</span>
                                <span class="info-value">{{ $order->carrier ?? __('messages.not_specified') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-row">
                                <span class="info-label">{{ __('messages.tracking_number') }} :</span>
                                <span class="info-value">{{ $order->tracking_number ?? __('messages.not_specified') }}</span>
                            </div>
                        </div>
                    </div>
                    @if($order->carrier && $order->tracking_number && $order->tracking_url)
                    <div class="mt-3">
                        <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-outline-primary w-100 w-sm-auto">
                            <i class="fas fa-external-link-alt mr-2"></i> {{ __('messages.track_package') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Produits de la commande -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-boxes mr-2"></i> {{ __('messages.ordered_products') }}</h6>
                </div>
                <div class="card-body p-0 p-md-3">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="product-col">{{ __('messages.product') }}</th>
                                    <th class="qty-col">{{ __('messages.quantity') }}</th>
                                    <th class="price-col">{{ __('messages.unit_price') }}</th>
                                    <th class="total-col">{{ __('messages.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="product-col">
                                        <div class="product-info">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->translated_name ?? __('messages.product') }}" 
                                                     class="product-image d-none d-sm-block">
                                            @endif
                                            <div>
                                                <strong>{{ Str::limit($item->product->translated_name ?? __('messages.unknown_product'), 40) }}</strong>
                                                <span class="d-block d-sm-none small text-muted mt-1">
                                                    {{ __('messages.price') }}: {{ number_format($item->price, 2) }} €
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="qty-col text-center">{{ $item->quantity }}</td>
                                    <td class="price-col d-none d-sm-table-cell">{{ number_format($item->price, 2) }} €</td>
                                    <td class="total-col font-weight-bold">{{ number_format($item->quantity * $item->price, 2) }} €</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-right font-weight-bold">{{ __('messages.vendor_total') }} :</td>
                                    <td class="font-weight-bold text-success">{{ number_format($vendorTotal, 2) }} €</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Actions pour le vendeur -->
            @if(in_array($order->status, ['pending', 'processing']))
            <div class="card mt-4">
                <div class="card-header bg-warning">
                    <h6 class="mb-0"><i class="fas fa-tasks mr-2"></i> {{ __('messages.manage_order') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('vendor.orders.update-shipping', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">{{ __('messages.update_status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>{{ __('messages.processing') }}</option>
                                        <option value="shipped">{{ __('messages.shipped') }}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="carrier">{{ __('messages.carrier') }}</label>
                                    <input type="text" name="carrier" id="carrier" class="form-control" 
                                           value="{{ $order->carrier }}" placeholder="{{ __('messages.carrier_placeholder') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking_number">{{ __('messages.tracking_number') }}</label>
                                    <input type="text" name="tracking_number" id="tracking_number" class="form-control" 
                                           value="{{ $order->tracking_number }}" placeholder="{{ __('messages.tracking_placeholder') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>{{ __('messages.important') }} :</strong> {{ __('messages.shipping_confirmation_warning') }}
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 w-md-auto mt-3" 
                                onclick="return confirm('{{ __('messages.confirm_shipping') }}')">
                            <i class="fas fa-truck mr-2"></i> {{ __('messages.confirm_shipping_button') }}
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Message informatif si déjà expédiée -->
            @if($order->status == 'shipped')
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-check-circle mr-2"></i> {{ __('messages.order_completed') }}</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ __('messages.order_shipped_message', ['date' => $order->shipped_at ? $order->shipped_at->format('d/m/Y à H:i') : __('messages.unknown_date')]) }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
}
.text-white-50 {
    color: rgba(255, 255, 255, 0.5);
}

/* ===== STYLES POUR LA GRILLE D'INFORMATIONS ===== */
.info-grid {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.info-row {
    display: flex;
    flex-wrap: wrap;
    padding: 8px 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    align-items: flex-start;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    width: 100px;
    color: #495057;
    flex-shrink: 0;
}

.info-value {
    flex: 1;
    min-width: 0;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* ===== STYLES POUR LE STATUT ===== */
.status-badge-large {
    display: inline-flex;
    align-items: center;
    padding: 12px 20px;
    font-size: 1.1rem;
    white-space: normal;
    text-align: center;
}

/* ===== STYLES POUR LE TABLEAU ===== */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    min-width: 500px;
    border-collapse: collapse;
}

.table thead th {
    padding: 12px 8px;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.table tbody td {
    padding: 12px 8px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.table tfoot td {
    padding: 12px 8px;
    border-top: 2px solid #dee2e6;
}

/* Largeurs des colonnes */
.product-col { min-width: 250px; }
.qty-col { min-width: 80px; text-align: center; }
.price-col { min-width: 100px; }
.total-col { min-width: 100px; }

/* ===== STYLES POUR LES PRODUITS ===== */
.product-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-image {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #dee2e6;
}

/* ===== MODE SOMBRE ===== */
.dark .card {
    background: #2d2d44;
    border-color: #4a4a6a;
}
.dark .card-header {
    background: #3a3a5a;
    color: #e0e0e0;
}
.dark .info-label {
    color: #c0c0e0;
}
.dark .info-value {
    color: #e0e0e0;
}
.dark .info-row {
    border-bottom-color: rgba(255,255,255,0.1);
}
.dark .table {
    color: #e0e0e0;
}
.dark .table thead th {
    background: #3a3a5a;
    color: #e0e0e0;
    border-bottom-color: #4a4a6a;
}
.dark .table tbody td {
    border-bottom-color: #4a4a6a;
}
.dark .table tfoot td {
    background: #3a3a5a;
    color: #e0e0e0;
}
.dark .bg-light {
    background: #35354a !important;
}

/* ===== STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    .info-row {
        flex-direction: column;
        padding: 8px 0;
    }
    
    .info-label {
        width: 100%;
        margin-bottom: 3px;
        font-size: 0.9rem;
    }
    
    .info-value {
        width: 100%;
        padding-left: 0;
    }
    
    .status-badge-large {
        padding: 8px 15px;
        font-size: 1rem;
    }
    
    .table thead th {
        padding: 10px 5px;
        font-size: 0.85rem;
    }
    
    .table tbody td {
        padding: 10px 5px;
        font-size: 0.85rem;
    }
    
    .product-col { min-width: 200px; }
    .price-col { min-width: 80px; }
    .total-col { min-width: 80px; }
    
    .product-image {
        width: 30px;
        height: 30px;
    }
}

@media (max-width: 576px) {
    .table thead th {
        padding: 8px 3px;
        font-size: 0.8rem;
    }
    
    .table tbody td {
        padding: 8px 3px;
        font-size: 0.8rem;
    }
    
    .product-col { min-width: 150px; }
    .qty-col { min-width: 60px; }
    
    .btn {
        width: 100%;
    }
    
    .w-md-auto {
        width: 100%;
    }
}

/* Classes utilitaires */
.h-100 { height: 100%; }
.g-3 { gap: 1rem; }
.w-md-auto { width: auto; }
@media (min-width: 768px) {
    .w-md-auto { width: auto; }
}
</style>
@endsection