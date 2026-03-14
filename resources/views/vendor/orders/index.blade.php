@extends('vendor.layouts.app')

@section('title', __('messages.vendor_orders'))
@section('page-title', __('messages.vendor_orders'))

@section('content')


<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart mr-2"></i> {{ __('messages.vendor_orders') }}
            </h5>
            <span class="badge bg-light text-dark">{{ $orders->total() }} {{ __('messages.orders_count') }}</span>
        </div>
        
        <div class="card-body">
            <!-- Filtres -->
            <div class="row mb-3">
                <div class="col-12 col-md-6">
                    <form method="GET" action="{{ route('vendor.orders.index') }}" class="form-inline w-100">
                        <div class="input-group w-100">
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="all">{{ __('messages.all_statuses') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('messages.processing') }}</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>{{ __('messages.shipped') }}</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('messages.delivered') }}</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                            </select>
                            @if(request('status') && request('status') != 'all')
                            <div class="input-group-append">
                                <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-secondary" title="{{ __('messages.reset') }}">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <p class="text-muted">{{ __('messages.vendor_no_orders') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th class="d-none d-md-table-cell">{{ __('messages.order_id') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.customer') }}</th>
                                <th class="d-none d-sm-table-cell">{{ __('messages.products') }}</th>
                                <th>{{ __('messages.total') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="d-none d-md-table-cell font-weight-bold">#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div>
                                        <strong>{{ $order->user->name ?? __('messages.unknown_customer') }}</strong>
                                        <span class="d-block d-md-none small text-muted mt-1">
                                            {{ $order->user->email ?? '' }}
                                        </span>
                                        <!-- Infos supplémentaires sur mobile -->
                                        @php
                                            $vendorItems = $order->items->filter(function($item) use ($vendor) {
                                                return $item->vendor_id == $vendor->id;
                                            });
                                            $productCount = $vendorItems->count();
                                            $unitCount = $vendorItems->sum('quantity');
                                        @endphp
                                        <span class="d-block d-md-none small text-muted mt-1">
                                            <span class="badge bg-info">{{ $productCount }} {{ __('messages.products') }}</span>
                                            <span class="badge bg-secondary">{{ $unitCount }} {{ __('messages.units') }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    @php
                                        $vendorItems = $order->items->filter(function($item) use ($vendor) {
                                            return $item->vendor_id == $vendor->id;
                                        });
                                        $productCount = $vendorItems->count();
                                        $unitCount = $vendorItems->sum('quantity');
                                    @endphp
                                    <span class="badge bg-info">{{ $productCount }} {{ __('messages.products') }}</span>
                                    <span class="badge bg-secondary">{{ $unitCount }} {{ __('messages.units') }}</span>
                                </td>
                                <td>
                                    @php
                                        $vendorTotal = $vendorItems->sum(function($item) {
                                            return $item->quantity * $item->price;
                                        });
                                    @endphp
                                    <strong class="text-success">{{ number_format($vendorTotal, 2) }} €</strong>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger',
                                        ];
                                        $statusLabels = [
                                            'pending' => __('messages.pending'),
                                            'processing' => __('messages.processing'),
                                            'shipped' => __('messages.shipped'),
                                            'delivered' => __('messages.delivered'),
                                            'cancelled' => __('messages.cancelled'),
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} status-badge">
                                        <i class="fas 
                                            @if($order->status == 'pending') fa-clock
                                            @elseif($order->status == 'processing') fa-cog
                                            @elseif($order->status == 'shipped') fa-truck
                                            @elseif($order->status == 'delivered') fa-check-circle
                                            @elseif($order->status == 'cancelled') fa-times-circle
                                            @endif mr-1 d-none d-sm-inline"></i>
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('vendor.orders.show', $order->id) }}" 
                                           class="btn btn-info" title="{{ __('messages.view_details') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->status == 'pending')
                                        <button type="button" class="btn btn-success" 
                                                onclick="updateStatus({{ $order->id }}, 'processing')"
                                                title="{{ __('messages.mark_processing') }}">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->appends(request()->except('page'))->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* ===== STYLES POUR LE TABLEAU ===== */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    min-width: 600px;
    border-collapse: collapse;
}

.table thead th {
    background: #f8f9fa;
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

/* Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    font-size: 0.85rem;
    white-space: nowrap;
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
.dark .bg-light {
    background: #35354a !important;
}
.dark .badge.bg-info {
    background: #0f3460 !important;
}
.dark .badge.bg-secondary {
    background: #4a4a6a !important;
}

/* ===== STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    .table thead th {
        padding: 10px 5px;
        font-size: 0.85rem;
    }
    
    .table tbody td {
        padding: 10px 5px;
        font-size: 0.85rem;
    }
    
    .status-badge {
        padding: 5px 8px;
        font-size: 0.8rem;
    }
    
    .btn-group .btn {
        padding: 5px 8px;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 4px 6px;
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
    
    .status-badge {
        padding: 4px 6px;
        font-size: 0.75rem;
    }
    
    .btn-group .btn {
        padding: 4px 6px;
    }
    
    .btn-group .btn i {
        margin: 0;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 3px 5px;
    }
}

/* Classes utilitaires */
.gap-3 { gap: 1rem; }
</style>

<script>
function updateStatus(orderId, status) {
    if (confirm('{{ __("messages.confirm_status_change") }}')) {
        fetch(`/vendor/orders/${orderId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('{{ __("messages.status_change_error") }}');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('{{ __("messages.error_occurred") }}');
        });
    }
}
</script>
@endsection