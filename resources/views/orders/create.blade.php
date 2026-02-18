@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-shopping-cart mr-2"></i> Créer une commande
            </h1>
        </div>

        <!-- Formulaire -->
        <div class="px-6 py-4">
            <form action="{{ route('admin.orders.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Champ pour utilisateur (caché) -->
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <!-- Champ pour fournisseur -->
                <div>
                    <label for="supplier_id" class="block text-gray-700 dark:text-gray-200 font-medium">Fournisseur</label>
                    <select name="supplier_id" id="supplier_id" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" required>
                        <option value="">Sélectionnez un fournisseur</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $supplier_id == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Champ pour la date -->
                <div>
                    <label for="date" class="block text-gray-700 dark:text-gray-200 font-medium">Date</label>
                    <input type="date" name="date" id="date" class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" required value="{{ date('Y-m-d') }}">
                </div>

                <!-- Section des éléments de commande -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-medium mb-2">Produits commandés</label>
                    <div id="items" class="space-y-4">
                        <div class="flex items-center space-x-4 item">
                            <select name="items[0][product_id]" class="form-select mt-1 flex-1 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 product-select" required disabled>
                                <option value="">Sélectionnez d'abord un fournisseur</option>
                            </select>
                            <input type="number" name="items[0][quantity]" class="form-input mt-1 flex-1 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" placeholder="Quantité" required min="1" value="1" disabled>
                        </div>
                    </div>
                    <button type="button" id="add-item" class="mt-4 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2 px-4 rounded-lg" disabled>
                        <i class="fas fa-plus mr-2"></i> Ajouter un produit
                    </button>
                </div>

                <!-- Bouton de soumission -->
                <div class="flex justify-end">
                    <button type="submit" id="submit-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg" disabled>
                        <i class="fas fa-save mr-2"></i> Créer la commande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const supplierSelect = document.getElementById('supplier_id');
    const addItemBtn = document.getElementById('add-item');
    const submitBtn = document.getElementById('submit-btn');
    const itemsContainer = document.getElementById('items');
    
    let itemCount = 1;
    
    // Fonction pour charger les produits
    function loadProducts(supplierId) {
        console.log('Chargement des produits pour fournisseur:', supplierId);
        
        if (!supplierId) {
            resetProductSelects();
            return;
        }
        
        // Afficher le chargement
        setProductSelectsLoading(true);
        
        // URL absolue pour éviter les problèmes
         const url = `{{ route('admin.api.products.by-supplier', '') }}/${supplierId}`;
            console.log('URL appelée:', url);
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            console.log('Réponse reçue:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(products => {
            console.log('Produits reçus:', products);
            
            if (!products || products.length === 0) {
                setProductSelectsEmpty('Aucun produit disponible pour ce fournisseur');
                toggleFields(false);
            } else {
                updateProductSelects(products);
                toggleFields(true);
            }
        })
        .catch(error => {
            console.error('Erreur détaillée:', error);
            setProductSelectsEmpty('Erreur de chargement des produits');
            toggleFields(false);
        });
    }
    
    function resetProductSelects() {
        document.querySelectorAll('.product-select').forEach(select => {
            select.innerHTML = '<option value="">Sélectionnez d\'abord un fournisseur</option>';
            select.disabled = true;
        });
        
        document.querySelectorAll('input[name^="items"][type="number"]').forEach(input => {
            input.disabled = true;
        });
        
        addItemBtn.disabled = true;
        submitBtn.disabled = true;
    }
    
    function setProductSelectsLoading(loading) {
        document.querySelectorAll('.product-select').forEach(select => {
            select.innerHTML = '<option value="">Chargement...</option>';
            select.disabled = loading;
        });
        
        document.querySelectorAll('input[name^="items"][type="number"]').forEach(input => {
            input.disabled = loading;
        });
        
        addItemBtn.disabled = loading;
        submitBtn.disabled = loading;
    }
    
    function setProductSelectsEmpty(message) {
        document.querySelectorAll('.product-select').forEach(select => {
            select.innerHTML = `<option value="">${message}</option>`;
            select.disabled = false;
        });
        
        document.querySelectorAll('input[name^="items"][type="number"]').forEach(input => {
            input.disabled = true;
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
                option.textContent = product.name;
                select.appendChild(option);
            });
            select.disabled = false;
        });
        
        document.querySelectorAll('input[name^="items"][type="number"]').forEach(input => {
            input.disabled = false;
        });
    }
    
    function toggleFields(enable) {
        document.querySelectorAll('.product-select').forEach(select => {
            select.disabled = !enable;
        });
        
        document.querySelectorAll('input[name^="items"][type="number"]').forEach(input => {
            input.disabled = !enable;
        });
        
        addItemBtn.disabled = !enable;
        submitBtn.disabled = !enable;
    }
    
    // Événement changement fournisseur
    supplierSelect.addEventListener('change', function() {
        console.log('Fournisseur changé:', this.value);
        loadProducts(this.value);
    });
    
    // Ajouter un item
    addItemBtn.addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.classList.add('flex', 'items-center', 'space-x-4', 'item');
        newItem.innerHTML = `
            <select name="items[${itemCount}][product_id]" class="form-select mt-1 flex-1 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 product-select" required>
                <option value="">Choisissez un produit</option>
            </select>
            <input type="number" name="items[${itemCount}][quantity]" class="form-input mt-1 flex-1 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" placeholder="Quantité" required min="1" value="1">
        `;
        itemsContainer.appendChild(newItem);
        
        // Si un fournisseur est sélectionné, charger ses produits
        if (supplierSelect.value) {
            const url = `/api/products/${supplierSelect.value}`;
            fetch(url)
                .then(response => response.json())
                .then(products => {
                    const newSelect = newItem.querySelector('select');
                    newSelect.innerHTML = '<option value="">Choisissez un produit</option>';
                    products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = product.name;
                        newSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erreur chargement produits:', error);
                });
        }
        
        itemCount++;
    });
    
    // Si un fournisseur est pré-sélectionné via l'URL
    @if(isset($supplier_id) && $supplier_id)
        console.log('Fournisseur pré-sélectionné:', {{ $supplier_id }});
        setTimeout(() => {
            supplierSelect.value = {{ $supplier_id }};
            loadProducts({{ $supplier_id }});
        }, 100);
    @endif
});
</script>
@endsection