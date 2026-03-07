<div class="table-responsive">
    <table class="table table-hover table-striped mb-0">
        <thead class="thead-dark">
            <tr>
                <th class="d-none d-md-table-cell">Image</th>
                <th>Produit</th>
                <th class="d-none d-md-table-cell">Catégorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th class="d-none d-md-table-cell">Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <!-- Image : visible seulement sur desktop -->
                <td class="d-none d-md-table-cell">
                    @if($product->image)
                        @php
                            $imagePath = str_contains($product->image, 'products/') 
                                ? $product->image 
                                : 'products/' . $product->image;
                        @endphp
                        <img src="{{ asset('storage/' . $imagePath) }}" 
                             alt="{{ $product->admin_name }}" 
                             class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px;">
                            <i class="fas fa-box text-white"></i>
                        </div>
                    @endif
                </td>
                
                <!-- Produit (toujours visible) -->
                <td>
                    <strong>{{ $product->admin_name }}</strong>
                    @if($product->sku)
                        <br><small class="text-muted">SKU: <span class="text-primary">{{ $product->sku }}</span></small>
                    @endif
                    
                    <!-- Informations fournisseur/vendeur sur mobile -->
                    <span class="d-md-none d-block small text-muted mt-1">
                        @if($product->supplier)
                            <i class="fas fa-building mr-1"></i> {{ $product->supplier->name }}
                        @endif
                        @if($product->vendor)
                            <br><i class="fas fa-store mr-1"></i> {{ $product->vendor->shop_name }}
                        @endif
                    </span>
                    
                    <!-- Info catégorie sur mobile -->
                    <span class="d-md-none d-block small text-muted">
                        @if($product->category)
                            <i class="fas fa-tag mr-1"></i> {{ $product->category->admin_name ?? $product->category->name }}
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </span>
                </td>
                
                <!-- Catégorie (desktop only) -->
                <td class="d-none d-md-table-cell">
                    @if($product->category)
                        {{ $product->category->admin_name ?? $product->category->name }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                
                <!-- Prix -->
                <td class="font-weight-bold text-primary">
                    {{ number_format($product->price, 2) }} €
                </td>
                
                <!-- Stock -->
                <td>
                    @if($product->quantity > 10)
                        <span class="badge bg-success px-2 py-1">
                            <i class="fas fa-check-circle mr-1 d-none d-sm-inline"></i> 
                            <span>{{ $product->quantity }}</span>
                        </span>
                    @elseif($product->quantity > 0)
                        <span class="badge bg-warning px-2 py-1">
                            <i class="fas fa-exclamation-triangle mr-1 d-none d-sm-inline"></i> 
                            <span>{{ $product->quantity }}</span>
                            @if($product->isLowStock())
                                <span class="d-none d-sm-inline"> (bas)</span>
                            @endif
                        </span>
                    @else
                        <span class="badge bg-danger px-2 py-1">
                            <i class="fas fa-times-circle mr-1 d-none d-sm-inline"></i> 
                            <span>Rupture</span>
                        </span>
                    @endif
                    
                    @if($product->stock_threshold && $product->quantity <= $product->stock_threshold && $product->quantity > 0)
                        <br><small class="text-danger d-none d-md-block">Seuil: {{ $product->stock_threshold }}</small>
                    @endif
                </td>
                
                <!-- Statut (desktop only) -->
                <td class="d-none d-md-table-cell">
                    @if($product->is_active)
                        <span class="badge bg-success px-2 py-1">
                            <i class="fas fa-check-circle mr-1"></i> Actif
                        </span>
                    @else
                        <span class="badge bg-secondary px-2 py-1">
                            <i class="fas fa-times-circle mr-1"></i> Inactif
                        </span>
                    @endif
                    
                    <!-- Fournisseur/Vendeur en desktop -->
                    <br><small class="text-muted">
                        @if($product->supplier)
                            <i class="fas fa-building mr-1"></i>{{ $product->supplier->name }}
                        @endif
                        @if($product->vendor)
                            @if($product->supplier) <br> @endif
                            <i class="fas fa-store mr-1"></i>{{ $product->vendor->shop_name }}
                        @endif
                    </small>
                </td>
                
                <!-- Actions -->
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.products.edit', $product) }}" 
                           class="btn btn-warning" 
                           title="Modifier {{ $product->admin_name }}"
                           data-toggle="tooltip">
                            <i class="fas fa-edit"></i>
                            <span class="d-none d-md-inline ml-1">Modifier</span>
                        </a>
                        <button type="button" 
                                class="btn btn-danger" 
                                onclick="confirmDelete({{ $product->id }}, '{{ $product->admin_name }}')"
                                title="Supprimer {{ $product->admin_name }}"
                                data-toggle="tooltip">
                            <i class="fas fa-trash"></i>
                            <span class="d-none d-md-inline ml-1">Supprimer</span>
                        </button>
                    </div>
                    <form id="delete-form-{{ $product->id }}" 
                          action="{{ route('admin.products.destroy', $product) }}" 
                          method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <p class="text-muted mb-3">
                        @if(request('search'))
                            Aucun produit trouvé pour "<strong>{{ request('search') }}</strong>"
                        @elseif(request('supplier') || request('category') || request('status') || request('stock'))
                            Aucun produit ne correspond aux filtres sélectionnés
                        @else
                            Aucun produit trouvé
                        @endif
                    </p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i> Ajouter un produit
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
function confirmDelete(productId, productName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le produit "${productName}" ?`)) {
        document.getElementById('delete-form-' + productId).submit();
    }
}

// Activer les tooltips Bootstrap si vous utilisez Bootstrap
if (typeof $ !== 'undefined') {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
}
</script>
@endpush