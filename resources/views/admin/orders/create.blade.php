@extends('layouts.app')

@section('title', 'Créer une commande fournisseur')

@section('content')
<div class="container-fluid py-6">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle mr-2"></i>
                Créer une commande fournisseur
            </h5>
        </div>

        <!-- Formulaire -->
        <div class="card-body">
            <form action="{{ route('admin.orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

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
                                            <option value="{{ $supplier->id }}" {{ ($supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
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
                                           value="{{ old('date', date('Y-m-d')) }}" required>
                                    @error('date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite (statistiques/infos) -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Informations</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Sélectionnez un fournisseur pour voir les produits disponibles.
                                </div>
                                <div id="supplier-info" class="d-none">
                                    <hr>
                                    <h6 class="text-primary">Détails du fournisseur</h6>
                                    <div id="supplier-details"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section des produits -->
                <div class="card mb-3">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-boxes mr-2"></i>Produits commandés</h6>
                        <button type="button" id="add-item" class="btn btn-light btn-sm" disabled>
                            <i class="fas fa-plus mr-2"></i> Ajouter un produit
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="items">
                            <!-- Première ligne de produit (template) -->
                            <div class="row mb-3 item">
                                <div class="col-md-5">
                                    <label class="form-label">Produit</label>
                                    <select name="items[0][product_id]" class="form-control product-select" required disabled>
                                        <option value="">Sélectionnez d'abord un fournisseur</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" name="items[0][quantity]" class="form-control quantity-input" 
                                           min="1" value="1" required disabled>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Prix unitaire</label>
                                    <div class="input-group">
                                        <input type="number" name="items[0][price]" class="form-control product-price" 
                                               step="0.01" min="0" placeholder="Auto" readonly disabled>
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
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
                                            <span id="subtotal">0.00 €</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between font-weight-bold">
                                            <span>Total TTC:</span>
                                            <span id="total" class="text-success h5">0.00 €</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.orders.index', ['type' => 'supplier']) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    <button type="submit" id="submit-btn" class="btn btn-primary" disabled>
                        <i class="fas fa-save mr-2"></i> Créer la commande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const supplierSelect = document.getElementById('supplier_id');
    const addItemBtn = document.getElementById('add-item');
    const submitBtn = document.getElementById('submit-btn');
    const itemsContainer = document.getElementById('items');
    
    let itemCount = 1;
    let productsData = [];

    // Fonction utilitaire pour extraire le nom du produit (gère les objets JSON)
    function getProductName(product) {
        if (!product) return 'Produit inconnu';
        
        // Si le produit a un admin_name (accesseur)
        if (product.admin_name) {
            return product.admin_name;
        }
        
        // Si le nom est un objet JSON (traductions)
        if (product.name && typeof product.name === 'object') {
            return product.name.fr || Object.values(product.name)[0] || 'Produit';
        }
        
        // Si le nom est une chaîne
        if (product.name && typeof product.name === 'string') {
            return product.name;
        }
        
        return 'Produit';
    }

    // Fonction pour charger les produits
    function loadProducts(supplierId) {
        console.log('Chargement des produits pour fournisseur:', supplierId);
        
        if (!supplierId) {
            resetProductSelects();
            return;
        }
        
        setProductSelectsLoading(true);
        
        const url = `/admin/api/products/${supplierId}`;
        console.log('URL appelée:', url);
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        })
        .then(response => {
            console.log('Statut réponse:', response.status);
            
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error(`Route non trouvée (404). Vérifiez: ${url}`);
                } else if (response.status === 500) {
                    throw new Error(`Erreur serveur (500). Vérifiez les logs Laravel.`);
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
            }
            return response.json();
        })
        .then(products => {
            console.log('Produits reçus:', products);
            productsData = products;
            
            if (!products || products.length === 0) {
                setProductSelectsEmpty('Aucun produit disponible pour ce fournisseur');
                toggleFields(false);
            } else {
                updateProductSelects(products);
                toggleFields(true);
                showSupplierInfo();
            }
        })
        .catch(error => {
            console.error('Erreur détaillée:', error);
            setProductSelectsEmpty(`Erreur: ${error.message}`);
            toggleFields(false);
        });
    }

    function resetProductSelects() {
        document.querySelectorAll('.product-select').forEach(select => {
            select.innerHTML = '<option value="">Sélectionnez d\'abord un fournisseur</option>';
            select.disabled = true;
        });
        
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.disabled = true;
        });
        
        document.querySelectorAll('.product-price').forEach(input => {
            input.value = '';
            input.disabled = true;
        });
        
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.disabled = true;
        });
        
        addItemBtn.disabled = true;
        submitBtn.disabled = true;
        
        document.getElementById('supplier-info').classList.add('d-none');
        calculateTotal();
    }

    function setProductSelectsLoading(loading) {
        document.querySelectorAll('.product-select').forEach(select => {
            select.innerHTML = '<option value="">Chargement...</option>';
            select.disabled = loading;
        });
        
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.disabled = loading;
        });
        
        document.querySelectorAll('.product-price').forEach(input => {
            input.disabled = loading;
        });
        
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.disabled = loading;
        });
        
        addItemBtn.disabled = loading;
        submitBtn.disabled = loading;
    }

    function setProductSelectsEmpty(message) {
        document.querySelectorAll('.product-select').forEach(select => {
            select.innerHTML = `<option value="">${message}</option>`;
            select.disabled = false;
        });
        
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.disabled = true;
        });
        
        document.querySelectorAll('.product-price').forEach(input => {
            input.disabled = true;
        });
        
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.disabled = true;
        });
        
        addItemBtn.disabled = true;
        submitBtn.disabled = true;
    }

    function updateProductSelects(products) {
        document.querySelectorAll('.product-select').forEach(select => {
            select.innerHTML = '<option value="">Choisissez un produit</option>';
            
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                
                // Utiliser la fonction utilitaire pour obtenir le nom
                const productName = getProductName(product);
                const price = parseFloat(product.price || 0).toFixed(2);
                const stock = product.quantity || 0;
                
                option.textContent = `${productName} (${price} €) - Stock: ${stock}`;
                option.dataset.price = product.price;
                option.dataset.name = productName;
                option.dataset.stock = stock;
                
                select.appendChild(option);
            });
            
            select.disabled = false;
        });
        
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.disabled = false;
            input.value = 1;
        });
        
        document.querySelectorAll('.product-price').forEach(input => {
            input.disabled = false;
            input.value = '';
        });
        
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.disabled = false;
        });
        
        resetPrices();
    }

    function resetPrices() {
        document.querySelectorAll('.product-price').forEach(input => {
            input.value = '';
        });
        calculateTotal();
    }

    function toggleFields(enable) {
        document.querySelectorAll('.product-select').forEach(select => {
            select.disabled = !enable;
        });
        
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.disabled = !enable;
        });
        
        document.querySelectorAll('.product-price').forEach(input => {
            input.disabled = !enable;
        });
        
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.disabled = !enable;
        });
        
        addItemBtn.disabled = !enable;
        submitBtn.disabled = !enable;
    }

    function showSupplierInfo() {
        const supplierName = supplierSelect.options[supplierSelect.selectedIndex].text;
        const supplierInfo = document.getElementById('supplier-info');
        const details = document.getElementById('supplier-details');
        
        details.innerHTML = `
            <p><strong>Nom:</strong> ${supplierName}</p>
            <p><strong>Produits disponibles:</strong> ${productsData.length}</p>
        `;
        
        supplierInfo.classList.remove('d-none');
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item').forEach(item => {
            const select = item.querySelector('.product-select');
            const quantity = item.querySelector('.quantity-input');
            const priceInput = item.querySelector('.product-price');
            
            if (select && select.value && quantity && quantity.value) {
                const selectedOption = select.options[select.selectedIndex];
                const price = parseFloat(selectedOption?.dataset?.price || 0);
                const qty = parseInt(quantity.value) || 0;
                
                // Mettre à jour le prix affiché
                if (priceInput && price > 0) {
                    priceInput.value = price.toFixed(2);
                }
                
                total += price * qty;
            }
        });
        
        document.getElementById('subtotal').textContent = total.toFixed(2) + ' €';
        document.getElementById('total').textContent = total.toFixed(2) + ' €';
    }

    // Écouteur global pour les changements
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const price = selectedOption?.dataset?.price || 0;
            const stock = selectedOption?.dataset?.stock || 0;
            
            const item = e.target.closest('.item');
            const priceInput = item.querySelector('.product-price');
            const quantityInput = item.querySelector('.quantity-input');
            
            if (priceInput) {
                priceInput.value = parseFloat(price).toFixed(2);
            }
            
            if (quantityInput) {
                quantityInput.max = stock;
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.value = stock;
                }
            }
            
            calculateTotal();
        }
        
        if (e.target.classList.contains('quantity-input')) {
            calculateTotal();
        }
    });

    // Écouteur pour les inputs (pour le calcul en temps réel)
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            calculateTotal();
        }
    });

    // Événement changement fournisseur
    supplierSelect.addEventListener('change', function() {
        loadProducts(this.value);
    });

    // Ajouter un item
    addItemBtn.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.className = 'row mb-3 item';
        newItem.innerHTML = `
            <div class="col-md-5">
                <label class="form-label">Produit</label>
                <select name="items[${itemCount}][product_id]" class="form-control product-select" required>
                    <option value="">Choisissez un produit</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Quantité</label>
                <input type="number" name="items[${itemCount}][quantity]" class="form-control quantity-input" min="1" value="1" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Prix unitaire</label>
                <div class="input-group">
                    <input type="number" name="items[${itemCount}][price]" class="form-control product-price" 
                           step="0.01" min="0" placeholder="Auto" readonly>
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
        
        // Remplir le select avec les produits
        const newSelect = newItem.querySelector('.product-select');
        productsData.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            
            // Utiliser la fonction utilitaire pour obtenir le nom
            const productName = getProductName(product);
            const price = parseFloat(product.price || 0).toFixed(2);
            const stock = product.quantity || 0;
            
            option.textContent = `${productName} (${price} €) - Stock: ${stock}`;
            option.dataset.price = product.price;
            option.dataset.name = productName;
            option.dataset.stock = stock;
            
            newSelect.appendChild(option);
        });
        
        // Si un produit est déjà sélectionné dans le premier champ, on peut pré-sélectionner le même
        const firstSelect = document.querySelector('.product-select');
        if (firstSelect && firstSelect.value) {
            newSelect.value = firstSelect.value;
            // Déclencher l'événement change pour mettre à jour le prix
            const event = new Event('change');
            newSelect.dispatchEvent(event);
        }
        
        itemCount++;
    });

    // Gestion de la suppression d'item (délégation d'événement)
    itemsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const item = e.target.closest('.item');
            if (item) {
                item.remove();
                calculateTotal();
            }
        }
    });

    // Si un fournisseur est pré-sélectionné
    @if(isset($supplier_id) && $supplier_id)
        setTimeout(() => {
            supplierSelect.value = '{{ $supplier_id }}';
            loadProducts('{{ $supplier_id }}');
        }, 100);
    @endif
});
</script>
@endsection