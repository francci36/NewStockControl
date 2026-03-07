@extends('shop.layouts.shop')

@section('title', __('messages.order_details') . ' #' . $order->id . ' - ' . __('messages.shop_name'))

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('shop.home') }}">{{ __('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('client.orders.index') }}">{{ __('messages.my_orders') }}</a></li>
            <li class="breadcrumb-item active">{{ __('messages.order') }} #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        {{ __('messages.order_details') }} #{{ $order->id }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <strong>{{ __('messages.order_date') }}</strong>
                            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <strong>{{ __('messages.status') }}</strong>
                            <p>
                                <span class="badge bg-{{ $order->status_color }}">
                                    <i class="fas {{ $order->status_icon }} me-1"></i>
                                    {{ $order->status_label }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <h6 class="mb-3">{{ __('messages.ordered_products') }}</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.product') }}</th>
                                    <th class="text-center">{{ __('messages.quantity') }}</th>
                                    <th class="text-end">{{ __('messages.unit_price') }}</th>
                                    <th class="text-end">{{ __('messages.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                                @php
                                                    $imagePath = str_contains($product->image, 'products/') 
                                                        ? $product->image 
                                                        : 'products/' . $product->image;
                                                @endphp
                                                <img src="{{ asset('storage/' . $imagePath) }}" 
                                                     alt="{{ $product->translated_name }}" 
                                                     class="rounded me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';">
                                            @endif
                                            <div>
                                                {{ $product->translated_name }}
                                                @if($product->category)
                                                    <small class="d-block text-muted">{{ $product->category->translated_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">{{ $product->pivot->quantity }}</td>
                                    <td class="text-end align-middle">{{ number_format($product->pivot->price, 2) }} €</td>
                                    <td class="text-end align-middle">{{ number_format($product->pivot->price * $product->pivot->quantity, 2) }} €</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">{{ __('messages.subtotal') }} :</th>
                                    <th class="text-end">{{ number_format($order->total_amount, 2) }} €</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">{{ __('messages.shipping') }} :</th>
                                    <th class="text-end">
                                        @if($order->total_amount >= 50)
                                            <span class="text-success">{{ __('messages.free') }}</span>
                                        @else
                                            {{ number_format(5.99, 2) }} €
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">{{ __('messages.total_ttc') }} :</th>
                                    <th class="text-end text-primary fs-5">
                                        @php
                                            $shipping = $order->total_amount >= 50 ? 0 : 5.99;
                                            $finalTotal = $order->total_amount + $shipping;
                                        @endphp
                                        {{ number_format($finalTotal, 2) }} €
                                    </th>
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
                        {{ __('messages.shipping_address') }}
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
                        {{ __('messages.payment') }}
                    </h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>{{ __('messages.payment_method') }} :</strong><br>
                        @switch($order->payment_method)
                            @case('card')
                                <i class="fab fa-cc-visa me-2"></i> {{ __('messages.credit_card') }}
                                @break
                            @case('cash')
                                <i class="fas fa-money-bill me-2"></i> {{ __('messages.cash_on_delivery') }}
                                @break
                            @case('bank_transfer')
                                <i class="fas fa-university me-2"></i> {{ __('messages.bank_transfer') }}
                                @break
                            @default
                                <i class="fas fa-credit-card me-2"></i> {{ $order->payment_method }}
                        @endswitch
                    </p>
                    @if($order->payment && $order->payment->status === 'completed')
                        <p class="text-success">
                            <i class="fas fa-check-circle me-1"></i> {{ __('messages.payment_completed') }}
                        </p>
                    @else
                        <p class="text-warning">
                            <i class="fas fa-clock me-1"></i> {{ __('messages.payment_pending') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="d-grid gap-2">
                <a href="{{ route('shop.products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i> {{ __('messages.new_order') }}
                </a>
                <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-history me-2"></i> {{ __('messages.back_to_orders') }}
                </a>
                @if($order->canBeTracked())
                    <a href="{{ route('client.orders.tracking', $order) }}" class="btn btn-outline-success">
                        <i class="fas fa-truck me-2"></i> {{ __('messages.track') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection