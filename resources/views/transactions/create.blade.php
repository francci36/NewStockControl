@extends('layouts.app')

@section('title', 'Nouvelle transaction')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <!-- Header -->
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-coins mr-2"></i> Nouvelle transaction
    </h2>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Erreur de validation :</strong>
            <ul class="mt-2">
                @foreach($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-4" id="transactionForm">
        @csrf
        
        <!-- Type de transaction -->
        <div class="mb-6">
            <label for="type" class="block text-gray-800 dark:text-gray-200 font-medium mb-2">Type de transaction :</label>
            <div class="flex space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="type" value="entry" class="form-radio text-green-500" checked>
                    <span class="ml-2 text-gray-700 dark:text-gray-300">
                        <i class="fas fa-arrow-down text-green-500 mr-1"></i>
                        Entrée (achat/approvisionnement)
                    </span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="type" value="exit" class="form-radio text-red-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">
                        <i class="fas fa-arrow-up text-red-500 mr-1"></i>
                        Sortie (vente/utilisation)
                    </span>
                </label>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table id="transactionTable" class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Produit</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Stock actuel</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Quantité</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Prix Unitaire</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Total</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="item-row">
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                            <select name="product_id[]" class="product-select w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" required>
                                <option value="">Sélectionnez un produit</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-price="{{ $product->price }}"
                                            data-stock="{{ $product->quantity }}">
                                        {{ $product->name }} ({{ number_format($product->price, 2, ',', ' ') }} €) - Stock: {{ $product->quantity }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center current-stock font-bold">
                            0
                        </td>
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                            <input type="number" name="quantity[]" class="quantity-input w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" required min="1">
                        </td>
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                            <input type="number" step="0.01" name="price[]" class="price-input w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" readonly>
                        </td>
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                            <input type="text" class="total-input w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg font-bold" readonly>
                        </td>
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                            <button type="button" class="remove-row bg-red-500 hover:bg-red-700 text-white p-2 rounded-lg">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add Row Button -->
        <button type="button" id="addRow" class="mt-4 bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i> Ajouter une ligne
        </button>

        <!-- Résumé de la transaction -->
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Résumé</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Nombre de produits</span>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="productCount">1</p>
                </div>
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Quantité totale</span>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="totalQuantity">0</p>
                </div>
                <div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Valeur totale</span>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400" id="totalValue">0.00 €</p>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end mt-6">
            <a href="{{ route('admin.transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg shadow-md mr-2">
                <i class="fas fa-times mr-2"></i> Annuler
            </a>
            <button type="submit" id="submitBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md">
                <i class="fas fa-save mr-2"></i> Enregistrer les transactions
            </button>
        </div>
    </form>
</div>

<!-- JavaScript amélioré -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('transactionTable');
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const submitBtn = document.getElementById('submitBtn');
    
    // Fonction pour calculer le total d'une ligne
    function calculateTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = quantity * price;
        row.querySelector('.total-input').value = total.toFixed(2);
        return total;
    }
    
    // Fonction pour mettre à jour le stock affiché
    function updateCurrentStock(select, row) {
        const selectedOption = select.options[select.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock') || 0;
        row.querySelector('.current-stock').textContent = stock;
    }
    
    // Fonction pour mettre à jour le résumé
    function updateSummary() {
        const rows = document.querySelectorAll('.item-row');
        let productCount = rows.length;
        let totalQuantity = 0;
        let totalValue = 0;
        
        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            totalQuantity += qty;
            totalValue += qty * price;
        });
        
        document.getElementById('productCount').textContent = productCount;
        document.getElementById('totalQuantity').textContent = totalQuantity;
        document.getElementById('totalValue').textContent = totalValue.toFixed(2) + ' €';
    }
    
    // Fonction pour vérifier si la quantité est valide pour une sortie
    function validateQuantity(row) {
        const type = document.querySelector('input[name="type"]:checked').value;
        if (type === 'exit') {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const currentStock = parseFloat(row.querySelector('.current-stock').textContent) || 0;
            
            if (quantity > currentStock) {
                row.querySelector('.quantity-input').classList.add('border-red-500');
                return false;
            } else {
                row.querySelector('.quantity-input').classList.remove('border-red-500');
                return true;
            }
        }
        return true;
    }
    
    // Fonction pour valider tout le formulaire
    function validateForm() {
        const rows = document.querySelectorAll('.item-row');
        let isValid = true;
        
        rows.forEach(row => {
            if (!validateQuantity(row)) {
                isValid = false;
            }
            
            const productSelect = row.querySelector('.product-select');
            if (!productSelect.value) {
                productSelect.classList.add('border-red-500');
                isValid = false;
            }
            
            const quantity = row.querySelector('.quantity-input').value;
            if (!quantity || quantity <= 0) {
                row.querySelector('.quantity-input').classList.add('border-red-500');
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    // Initialiser la première ligne
    const firstRow = document.querySelector('.item-row');
    const firstSelect = firstRow.querySelector('.product-select');
    if (firstSelect.value) {
        updateCurrentStock(firstSelect, firstRow);
    }
    
    // Événement changement de produit
    table.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            const row = e.target.closest('tr');
            const selectedOption = e.target.options[e.target.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            
            row.querySelector('.price-input').value = price;
            e.target.classList.remove('border-red-500');
            updateCurrentStock(e.target, row);
            calculateTotal(row);
            updateSummary();
        }
    });
    
    // Événement changement de quantité
    table.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const row = e.target.closest('tr');
            calculateTotal(row);
            validateQuantity(row);
            updateSummary();
            
            if (e.target.value > 0) {
                e.target.classList.remove('border-red-500');
            }
        }
    });
    
    // Événement changement de type
    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.item-row').forEach(row => {
                validateQuantity(row);
            });
        });
    });
    
    // Ajouter une ligne
    document.getElementById('addRow').addEventListener('click', function() {
        const tbody = table.querySelector('tbody');
        const firstRow = document.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        // Réinitialiser les valeurs
        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
            input.classList.remove('border-red-500');
        });
        newRow.querySelector('select').selectedIndex = 0;
        newRow.querySelector('.current-stock').textContent = '0';
        newRow.querySelector('.total-input').value = '';
        
        tbody.appendChild(newRow);
        updateSummary();
    });
    
    // Supprimer une ligne
    table.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                updateSummary();
            } else {
                alert('Vous devez garder au moins une ligne');
            }
        }
    });
    
    // Validation avant soumission
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs dans le formulaire.');
        } else {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement...';
        }
    });
    
    // Initialiser le résumé
    updateSummary();
});
</script>
@endsection