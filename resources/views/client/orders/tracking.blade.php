@extends('shop.layouts.shop')

@section('title', __('messages.order_tracking') . ' #' . $order->id)

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('client.orders.index') }}">{{ __('messages.my_orders') }}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.order_tracking') }} #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-truck me-2"></i> {{ __('messages.order_tracking') }} #{{ $order->id }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Statut actuel -->
                    <div class="text-center mb-4">
                        <span class="badge bg-{{ $order->status_color }} fs-6 p-3">
                            <i class="fas {{ $order->status_icon }} me-2"></i>
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <!-- Barre de progression -->
                    @php
                        $progressSteps = [
                            __('messages.status_pending'),
                            __('messages.status_processing'),
                            __('messages.status_shipped'),
                            __('messages.status_delivered')
                        ];
                        $currentStep = array_search($order->status_label, $progressSteps);
                        $progress = ($currentStep !== false) ? (($currentStep + 1) / count($progressSteps)) * 100 : 0;
                    @endphp

                    @if(!$order->isCancelled())
                    <div class="progress mb-4" style="height: 30px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                             role="progressbar" 
                             style="width: {{ $progress }}%;" 
                             aria-valuenow="{{ $progress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ round($progress) }}%
                        </div>
                    </div>
                    @endif

                    <!-- Timeline d'historique -->
                    <h6 class="mt-4 mb-3">{{ __('messages.order_history') }}</h6>
                    <div class="timeline">
                        @forelse($order->statusHistory ?? [] as $history)
                        <div class="timeline-item">
                            <div class="timeline-left">
                                <span class="timeline-icon bg-{{ $history->status_color ?? 'secondary' }}">
                                    <i class="fas {{ $history->status_icon ?? 'fa-clock' }}"></i>
                                </span>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <span class="badge bg-{{ $history->status_color ?? 'secondary' }} mb-2">
                                        {{ $history->status_label ?? $history->status }}
                                    </span>
                                    <small class="text-muted">{{ $history->formatted_date ?? $history->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                @if($history->comment)
                                <p class="mb-0">{{ $history->comment }}</p>
                                @endif
                                @if($history->user)
                                <small class="text-muted">{{ __('messages.by') }}: {{ $history->user->name }}</small>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-muted">{{ __('messages.no_history') }}</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Informations de la commande -->
                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <h6>{{ __('messages.order_details') }}</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>{{ __('messages.order_number') }}:</td>
                                    <td class="text-end">#{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('messages.order_date') }}:</td>
                                    <td class="text-end">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('messages.total') }}:</td>
                                    <td class="text-end text-primary">{{ number_format($order->total_amount, 2) }} €</td>
                                </tr>
                                <tr>
                                    <td>{{ __('messages.payment') }}:</td>
                                    <td class="text-end">
                                        @if($order->payment && $order->payment->status === 'completed')
                                            <span class="badge bg-success">{{ __('messages.paid') }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-6">
                            <h6>{{ __('messages.shipping_address') }}</h6>
                            <address>
                                {{ $order->shipping_name }}<br>
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_zip }} {{ $order->shipping_city }}<br>
                                {{ $order->shipping_country }}<br>
                                <i class="fas fa-phone"></i> {{ $order->shipping_phone }}
                            </address>
                        </div>
                    </div>

                    <!-- Informations de livraison si disponibles -->
                    @if($order->tracking_number)
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-box me-2"></i>
                        <strong>{{ __('messages.tracking_number') }}:</strong> {{ $order->tracking_number }}
                        @if($order->carrier)
                            <br><i class="fas fa-truck me-2"></i>
                            <strong>{{ __('messages.carrier') }}:</strong> {{ $order->carrier }}
                        @endif
                    </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('client.orders.show', $order) }}" class="btn btn-outline-primary">
                            <i class="fas fa-receipt me-2"></i> {{ __('messages.view_details') }}
                        </a>
                        <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> {{ __('messages.back_to_orders') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    display: flex;
    margin-bottom: 20px;
    position: relative;
}

.timeline-left {
    position: relative;
    width: 50px;
    margin-right: 20px;
    text-align: center;
}

.timeline-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    font-size: 16px;
    z-index: 1;
    position: relative;
}

.timeline-item:not(:last-child) .timeline-left::after {
    content: '';
    position: absolute;
    top: 40px;
    left: 19px;
    width: 2px;
    height: calc(100% + 20px);
    background: #e0e0e0;
}

.timeline-content {
    flex: 1;
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    position: relative;
}

.timeline-content::before {
    content: '';
    position: absolute;
    left: -10px;
    top: 15px;
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    border-right: 10px solid #f8f9fa;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

/* Mode sombre */
.dark .timeline-content {
    background: #2d2d44;
}

.dark .timeline-content::before {
    border-right-color: #2d2d44;
}

.dark .timeline-item:not(:last-child) .timeline-left::after {
    background: #4a4a6a;
}

/* Animation */
.timeline-item {
    animation: slideIn 0.3s ease-out forwards;
    opacity: 0;
    transform: translateX(-20px);
}

@keyframes slideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.timeline-item:nth-child(1) { animation-delay: 0.1s; }
.timeline-item:nth-child(2) { animation-delay: 0.2s; }
.timeline-item:nth-child(3) { animation-delay: 0.3s; }
.timeline-item:nth-child(4) { animation-delay: 0.4s; }
.timeline-item:nth-child(5) { animation-delay: 0.5s; }
</style>
@endsection