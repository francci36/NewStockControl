@extends('layouts.app')

@section('title', 'Modifier la commande #' . $order->id)

@section('content')
<div class="container-fluid py-6">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-warning text-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier la commande #{{ $order->id }}
                </h5>
                <small class="text-white-50">Créée le {{ $order->created_at->format('d/m/Y à H:i') }}</small>
            </div>
            <div>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-light btn-sm mr-2">
                    <i class="fas fa-eye mr-2"></i> Voir
                </a>
                <a href="{{ route('admin.orders.index', ['type' => 'supplier']) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="card-body">
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Colonne gauche -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informations générales</h6>
                            </div>
                            <div class="card-body">
                                <!-- Fournisseur -->
                                <div class="form-group mb-3">
                                    <label for="supplier_id" class="form-label">Fournisseur <span class="text-danger">*</span></label>
                                    <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                                        <option value="">Sélectionnez un fournisseur</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $order->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->admin_name ?? $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Date -->
                                <div class="form-group mb-3">
                                    <label for="date" class="form-label">Date de commande <span class="text-danger">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" 
                                           value="{{ old('date', $order->date->format('Y-m-d')) }}" required>
                                    @error('date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Statut (lecture seule car modification via updateStatus) -->
                                <div class="form-group mb-3">
                                    <label class="form-label">Statut actuel</label>
                                    <div>
                                        @php
                                            $statusLabels = [
                                                'pending' => 'En attente',
                                                'paid' => 'Payée',
                                                'processing' => 'En traitement',
                                                'shipped' => 'Expédiée',
                                                'delivered' => 'Livrée',
                                                'arrived' => 'Arrivée',
                                                'cancelled' => 'Annulée',
                                                'refunded' => 'Remboursée',
                                            ];
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'processing' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'arrived' => 'success',
                                                'cancelled' => 'danger',
                                                'refunded' => 'secondary',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} px-3 py-2">
                                            <i class="fas 
                                                @if($order->status == 'pending') fa-clock
                                                @elseif($order->status == 'processing') fa-cog
                                                @elseif($order->status == 'shipped') fa-truck
                                                @elseif($order->status == 'delivered') fa-check-circle
                                                @elseif($order->status == 'arrived') fa-check-circle
                                                @elseif($order->status == 'cancelled') fa-times-circle
                                                @elseif($order->status == 'paid') fa-credit-card
                                                @else fa-question-circle
                                                @endif mr-2"></i>
                                            {{ $statusLabels[$order->status] ?? $order->status }}
                                        </span>
                                        <small class="text-muted ml-2">(Modifiable via "Mettre à jour le statut" dans la page de détail)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Informations</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning mb-3">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Attention :</strong> La modification d'une commande en attente est possible. 
                                    Les produits et quantités peuvent être ajustés.
                                </div>
                                
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Type :</strong> 
                                    @if($order->type == 'client')
                                        Commande client
                                    @else
                                        Commande fournisseur
                                    @endif
                                    <br>
                                    <strong>Total actuel :</strong> <span class="text-success font-weight-bold">{{ number_format($order->total_amount, 2) }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section des produits -->
                <div class="card mb-3">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-boxes mr-2"></i>Produits commandés</h6>
                        <button type="button" id="add-item" class="btn btn-light btn-sm">
                            <i class="fas fa-plus mr-2"></i> Ajouter un produit
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="items">
                            @forelse($order->items as $index => $item)
                            <div class="row mb-3 item" data-index="{{ $index }}">
                                <div class="col-md-5">
                                    <label class="form-label">Produit</label>
                                    <select name="items[{{ $index }}][product_id]" class="form-control product-select" required>
                                        <option value="">Choisissez un produit</option>
                                        @foreach($products->where('supplier_id', $order->supplier_id) as $product)
                                            <option value="{{ $product->id }}" 
                                                    {{ $item->product_id == $product->id ? 'selected' : '' }}
                                                    data-price="{{ $product->price }}"
                                                    data-stock="{{ $product->quantity }}">
                                                {{ $product->admin_name ?? $product->name }} ({{ number_format($product->price, 2) }} €) - Stock: {{ $product->quantity }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity-input" 
                                           min="1" value="{{ $item->quantity }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Prix unitaire</label>
                                    <div class="input-group">
                                        <input type="number" name="items[{{ $index }}][price]" class="form-control product-price" 
                                               step="0.01" min="0" value="{{ $item->price }}" required>
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item" 
                                            {{ $loop->count == 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @empty
                            <div class="row mb-3 item" data-index="0">
                                <div class="col-md-5">
                                    <label class="form-label">Produit</label>
                                    <select name="items[0][product_id]" class="form-control product-select" required>
                                        <option value="">Choisissez un produit</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" name="items[0][quantity]" class="form-control quantity-input" 
                                           min="1" value="1" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Prix unitaire</label>
                                    <div class="input-group">
                                        <input type="number" name="items[0][price]" class="form-control product-price" 
                                               step="0.01" min="0" required>
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforelse
                        </div>

                        <!-- Total -->
                        <div class="row mt-4">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-2">Total commande</h6>
                                        <div class="d-flex justify-content-between">
                                            <span>Sous-total:</span>
                                            <span id="subtotal">{{ number_format($order->total_amount, 2) }} €</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between font-weight-bold">
                                            <span>Total TTC:</span>
                                            <span id="total" class="text-success h5">{{ number_format($order->total_amount, 2) }} €</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemsContainer = document.getElementById('items');
    const supplierSelect = document.getElementById('supplier_id');
    let itemCount = {{ count($order->items) }};

    // Fonction de calcul du total
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item').forEach(item => {
            const price = item.querySelector('.product-price').value;
            const quantity = item.querySelector('.quantity-input').value;
            if (price && quantity) {
                total += parseFloat(price) * parseInt(quantity);
            }
        });
        document.getElementById('subtotal').textContent = total.toFixed(2) + ' €';
        document.getElementById('total').textContent = total.toFixed(2) + ' €';
    }

    // Charger les produits quand le fournisseur change
    supplierSelect.addEventListener('change', function() {
        const supplierId = this.value;
        
        fetch(`/admin/api/products/${supplierId}`)
            .then(response => response.json())
            .then(products => {
                document.querySelectorAll('.product-select').forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = '<option value="">Choisissez un produit</option>';
                    
                    products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = `${product.admin_name || product.name} (${product.price.toFixed(2)} €) - Stock: ${product.quantity}`;
                        option.dataset.price = product.price;
                        option.dataset.stock = product.quantity;
                        
                        if (currentValue == product.id) {
                            option.selected = true;
                        }
                        
                        select.appendChild(option);
                    });
                    
                    // Déclencher l'événement change pour mettre à jour les prix
                    const event = new Event('change');
                    select.dispatchEvent(event);
                });
            });
    });

    // Écouteur pour les changements de produit
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const price = selectedOption?.dataset?.price || 0;
            const stock = selectedOption?.dataset?.stock || 0;
            
            const item = e.target.closest('.item');
            const priceInput = item.querySelector('.product-price');
            const quantityInput = item.querySelector('.quantity-input');
            
            if (priceInput) {
                priceInput.value = price;
            }
            
            if (quantityInput) {
                quantityInput.max = stock;
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = stock;
                }
            }
            
            calculateTotal();
        }
        
        if (e.target.classList.contains('quantity-input') || e.target.classList.contains('product-price')) {
            calculateTotal();
        }
    });

    // Écouteur pour les inputs
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input') || e.target.classList.contains('product-price')) {
            calculateTotal();
        }
    });

    // Ajouter un item
    document.getElementById('add-item').addEventListener('click', function() {
        const supplierId = supplierSelect.value;
        
        if (!supplierId) {
            alert('Veuillez d\'abord sélectionner un fournisseur.');
            return;
        }
        
        fetch(`/admin/api/products/${supplierId}`)
            .then(response => response.json())
            .then(products => {
                const newItem = document.createElement('div');
                newItem.className = 'row mb-3 item';
                newItem.dataset.index = itemCount;
                
                let options = '<option value="">Choisissez un produit</option>';
                products.forEach(product => {
                    options += `<option value="${product.id}" data-price="${product.price}" data-stock="${product.quantity}">
                        ${product.admin_name || product.name} (${product.price.toFixed(2)} €) - Stock: ${product.quantity}
                    </option>`;
                });
                
                newItem.innerHTML = `
                    <div class="col-md-5">
                        <label class="form-label">Produit</label>
                        <select name="items[${itemCount}][product_id]" class="form-control product-select" required>
                            ${options}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantité</label>
                        <input type="number" name="items[${itemCount}][quantity]" class="form-control quantity-input" 
                               min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Prix unitaire</label>
                        <div class="input-group">
                            <input type="number" name="items[${itemCount}][price]" class="form-control product-price" 
                                   step="0.01" min="0" required>
                            <span class="input-group-text">€</span>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                
                itemsContainer.appendChild(newItem);
                itemCount++;
                
                // Activer tous les boutons de suppression si nécessaire
                updateRemoveButtons();
            });
    });

    // Supprimer un item
    itemsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const item = e.target.closest('.item');
            if (item && document.querySelectorAll('.item').length > 1) {
                item.remove();
                calculateTotal();
                updateRemoveButtons();
            } else {
                alert('La commande doit contenir au moins un produit.');
            }
        }
    });

    // Mettre à jour l'état des boutons de suppression
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-item');
            if (removeBtn) {
                removeBtn.disabled = items.length === 1;
            }
        });
    }

    // Initialiser les max des champs de quantité
    document.querySelectorAll('.product-select').forEach(select => {
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.dataset) {
            const item = select.closest('.item');
            const quantityInput = item.querySelector('.quantity-input');
            if (quantityInput && selectedOption.dataset.stock) {
                quantityInput.max = selectedOption.dataset.stock;
            }
        }
    });

    // Calculer le total initial
    calculateTotal();
    updateRemoveButtons();
});
</script>
@endsection