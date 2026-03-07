@extends('vendor.layouts.app')

@section('title', __('messages.vendor_products'))

@section('page-title', __('messages.vendor_products'))

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0">
        <!-- En-tête responsive -->
        <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
            <h5 class="mb-0">
                <i class="fas fa-box mr-2"></i> {{ __('messages.vendor_products') }}
            </h5>
            <a href="{{ route('vendor.products.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus mr-2"></i> <span class="d-none d-sm-inline">{{ __('messages.vendor_add_product') }}</span>
                <span class="d-sm-none">{{ __('Ajouter') }}</span>
            </a>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="card-body p-0 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="image-col">{{ __('Image') }}</th>
                            <th class="product-col">{{ __('messages.vendor_product_name') }}</th>
                            <th class="category-col d-none d-md-table-cell">{{ __('messages.category') }}</th>
                            <th class="price-col">{{ __('messages.vendor_product_price') }}</th>
                            <th class="stock-col">{{ __('messages.vendor_product_stock') }}</th>
                            <th class="status-col d-none d-sm-table-cell">{{ __('messages.vendor_product_status') }}</th>
                            <th class="action-col" colspan="2">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <!-- Image -->
                            <td class="image-col">
                                @if($product->image)
                                    @php
                                        $imageName = basename($product->image);
                                    @endphp
                                    <img src="{{ asset('storage/products/' . $imageName) }}" 
                                         alt="{{ $product->translated_name ?? $product->name }}" 
                                         class="product-image"
                                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';">
                                @else
                                    <div class="product-image-placeholder">
                                        <i class="fas fa-box text-secondary"></i>
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Nom du produit (traduit) -->
                            <td class="product-col">
                                <div class="product-info">
                                    <strong>{{ Str::limit($product->translated_name ?? $product->name, 40) }}</strong>
                                    @if($product->sku)
                                        <br><small class="text-muted">SKU: {{ $product->sku }}</small>
                                    @endif
                                    <!-- Infos catégorie sur mobile -->
                                    <span class="d-block d-md-none small text-muted mt-1">
                                        @if($product->category)
                                            @if($product->category->parent)
                                                {{ $product->category->parent->translated_name }} → 
                                            @endif
                                            {{ $product->category->translated_name }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </td>
                            
                            <!-- Catégorie (desktop only) avec hiérarchie -->
                            <td class="category-col d-none d-md-table-cell">
                                @if($product->category)
                                    @if($product->category->parent)
                                        <span class="text-muted">{{ $product->category->parent->translated_name }} →</span>
                                    @endif
                                    <strong>{{ $product->category->translated_name }}</strong>
                                @else
                                    N/A
                                @endif
                            </td>
                            
                            <!-- Prix -->
                            <td class="price-col">
                                <span class="product-price">{{ number_format($product->price, 2) }} €</span>
                            </td>
                            
                            <!-- Stock -->
                            <td class="stock-col">
                                @php
                                    $stockClass = $product->quantity > 0 ? 
                                        ($product->quantity <= ($product->stock_threshold ?? 5) ? 'bg-warning' : 'bg-success') : 
                                        'bg-danger';
                                @endphp
                                <span class="badge {{ $stockClass }} stock-badge">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            
                            <!-- Statut -->
                            <td class="status-col d-none d-sm-table-cell">
                                @if($product->is_active)
                                    <span class="badge bg-success status-badge">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge bg-secondary status-badge">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            
                            <!-- Actions - Modifier -->
                            <td class="action-col">
                                <a href="{{ route('vendor.products.edit', $product) }}" 
                                   class="btn btn-sm btn-warning" 
                                   title="{{ __('messages.vendor_product_edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                            
                            <!-- Actions - Supprimer -->
                            <td class="action-col">
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->translated_name ?? $product->name) }}')"
                                        title="{{ __('messages.vendor_product_delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $product->id }}" 
                                      action="{{ route('vendor.products.destroy', $product) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                <p class="text-muted">{{ __('messages.vendor_no_products') }}</p>
                                <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i> {{ __('messages.vendor_add_product') }}
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
        @endif
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
    min-width: 800px;
    border-collapse: collapse;
}

.table thead th {
    padding: 12px 8px;
    font-weight: 600;
    color: white;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
}

.table tbody td {
    padding: 12px 8px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
    text-align: center;
}

.table tbody td.product-col {
    text-align: left;
}

/* Largeurs des colonnes */
.image-col { min-width: 70px; }
.product-col { min-width: 200px; text-align: left; }
.category-col { min-width: 150px; }
.price-col { min-width: 100px; }
.stock-col { min-width: 80px; }
.status-col { min-width: 80px; }
.action-col { min-width: 50px; }

/* ===== STYLES POUR LES IMAGES ===== */
.product-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #dee2e6;
}

.product-image-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dee2e6;
    margin: 0 auto;
}

/* ===== STYLES POUR LE TEXTE ===== */
.product-info {
    text-align: left;
}

.product-price {
    font-weight: 600;
    color: #28a745;
}

/* ===== STYLES POUR LES BADGES ===== */
.badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.6rem;
    font-weight: 500;
}

.badge.bg-success {
    background-color: #28a745 !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #212529;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}

.badge.bg-secondary {
    background-color: #6c757d !important;
}

/* ===== STYLES POUR LES BOUTONS ===== */
.btn-sm {
    padding: 5px 10px;
    margin: 2px;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
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

.dark .product-price {
    color: #4cd964;
}

.dark .product-image-placeholder {
    background: #35354a;
    border-color: #4a4a6a;
}

.dark .product-image-placeholder i {
    color: #a0a0c0;
}

.dark .badge.bg-success {
    background-color: #2c8c3c !important;
}

.dark .badge.bg-warning {
    background-color: #f7d44a !important;
    color: #1a1a2e;
}

.dark .badge.bg-danger {
    background-color: #e34234 !important;
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
    
    .image-col { min-width: 50px; }
    .product-col { min-width: 150px; }
    .category-col { display: none; }
    .price-col { min-width: 80px; }
    .stock-col { min-width: 60px; }
    .status-col { display: none; }
    .action-col { min-width: 40px; }
    
    .product-image {
        width: 40px;
        height: 40px;
    }
    
    .product-image-placeholder {
        width: 40px;
        height: 40px;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.5rem;
    }
    
    .btn-sm {
        padding: 4px 8px;
    }
}

@media (max-width: 576px) {
    .table thead th {
        padding: 8px 3px;
        font-size: 0.75rem;
    }
    
    .table tbody td {
        padding: 8px 3px;
        font-size: 0.75rem;
    }
    
    .image-col { min-width: 40px; }
    .product-col { min-width: 120px; }
    .price-col { min-width: 70px; }
    .stock-col { min-width: 50px; }
    
    .product-image {
        width: 35px;
        height: 35px;
    }
    
    .product-image-placeholder {
        width: 35px;
        height: 35px;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.4rem;
    }
    
    .btn-sm {
        padding: 3px 6px;
        font-size: 0.7rem;
    }
    
    .btn-sm i {
        margin: 0;
    }
}
</style>

<script>
function confirmDelete(productId, productName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le produit "${productName}" ? Cette action est irréversible.`)) {
        document.getElementById('delete-form-' + productId).submit();
    }
}
</script>
@endsection