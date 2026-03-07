@extends('shop.layouts.shop')

@section('title', __('messages.my_orders') . ' - ' . __('messages.shop_name'))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>
            <i class="fas fa-history text-primary me-2"></i>
            {{ __('messages.my_orders') }}
        </h1>
        <a href="{{ route('shop.products.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-shopping-bag me-2"></i> {{ __('messages.continue_shopping') }}
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-4">
                <i class="fas fa-box-open"></i>
            </div>
            <h2 class="mb-3">{{ __('messages.no_orders') }}</h2>
            <p class="text-muted mb-4">{{ __('messages.no_orders_message') }}</p>
            <a href="{{ route('shop.products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-store me-2"></i> {{ __('messages.view_products') }}
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
                            {{ __('messages.order') }} #{{ $order->id }}
                        </span>
                        <span class="badge bg-{{ $order->status_color ?? 'secondary' }} fs-6">
                            <i class="fas {{ $order->status_icon ?? 'fa-clock' }} me-1"></i>
                            {{ $order->status_label ?? $order->status }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <small class="text-muted">{{ __('messages.order_date') }}</small>
                                <p class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">{{ __('messages.total') }}</small>
                                <p class="fw-bold text-primary">{{ number_format($order->total_amount, 2) }} €</p>
                            </div>
                        </div>

                        <!-- Barre de progression (si commande non annulée) -->
                        @if($order->status != 'annulé')
                        @php
                            $progressSteps = [__('messages.status_pending'), __('messages.status_processing'), __('messages.status_shipped'), __('messages.status_delivered')];
                            $currentStep = array_search($order->status, $progressSteps);
                            $progress = ($currentStep !== false) ? (($currentStep + 1) / count($progressSteps)) * 100 : 0;
                        @endphp
                        <div class="mb-3">
                            <small class="text-muted">{{ __('messages.progress') }}</small>
                            <div class="progress mt-1" style="height: 8px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                     role="progressbar" 
                                     style="width: {{ $progress }}%;" 
                                     aria-valuenow="{{ $progress }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted mt-1 d-block text-end">{{ round($progress) }}%</small>
                        </div>
                        @endif

                        <div class="mb-3">
                            <small class="text-muted">{{ __('messages.ordered_products') }}</small>
                            <div class="mt-2">
                                @foreach($order->products->take(3) as $product)
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span>{{ $product->translated_name }}</span>
                                        <span class="badge bg-secondary">x{{ $product->pivot->quantity }}</span>
                                    </div>
                                @endforeach
                                @if($order->products->count() > 3)
                                    <small class="text-muted">
                                        {{ __('messages.and_x_more', ['count' => $order->products->count() - 3]) }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">{{ __('messages.shipping_address') }}</small>
                            <p class="mb-0">{{ $order->shipping_address }}</p>
                            <p class="mb-0">{{ $order->shipping_zip }} {{ $order->shipping_city }}</p>
                            <p>{{ $order->shipping_country }}</p>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('client.orders.show', $order) }}" 
                               class="btn btn-outline-primary flex-grow-1">
                                <i class="fas fa-eye me-2"></i> {{ __('messages.details') }}
                            </a>
                            <a href="{{ route('client.orders.tracking', $order) }}" 
                               class="btn btn-outline-success">
                                <i class="fas fa-truck me-2"></i> {{ __('messages.track') }}
                            </a>
                        </div>

                        <!-- Statut du paiement -->
                        @if($order->payment)
                        <div class="mt-2 text-end">
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i> {{ __('messages.payment_completed') }}
                            </small>
                        </div>
                        @endif
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

@push('styles')
<style>
/* Animation pour les cartes */
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

/* Style pour la barre de progression */
.progress {
    border-radius: 10px;
    background-color: #e9ecef;
}

.dark .progress {
    background-color: #4a4a6a;
}

/* Badge pour le statut */
.badge {
    padding: 0.5em 0.8em;
    font-weight: 500;
}

/* Animation subtile pour les icônes */
.fa-truck, .fa-eye {
    transition: transform 0.2s;
}

.btn:hover .fa-truck {
    transform: translateX(3px);
}

.btn:hover .fa-eye {
    transform: scale(1.1);
}

/* Mode sombre */
.dark .card {
    background-color: #2d2d44;
    border-color: #4a4a6a;
}

.dark .card-header {
    background-color: #1e1e2f !important;
    border-bottom-color: #4a4a6a;
}

.dark .card-header .fw-bold {
    color: #e0e0e0;
}

.dark .text-muted {
    color: #a0a0c0 !important;
}
</style>
@endpush