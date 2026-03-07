@extends('layouts.app')

@section('title', 'Nouvelle transaction')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0">
        <!-- Header -->
        <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <h5 class="mb-0">
                    <i class="fas fa-coins mr-2"></i> Nouvelle transaction
                </h5>
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i> <span class="d-none d-sm-inline">Retour</span>
                    <span class="d-sm-none">Retour</span>
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Erreur de validation :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.transactions.store') }}" method="POST" id="transactionForm">
                @csrf
                
                <!-- Type de transaction -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-tag mr-2"></i> Type de transaction</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="typeEntry" value="entry" checked>
                                    <label class="form-check-label w-100" for="typeEntry">
                                        <span class="badge bg-success p-2 d-flex align-items-center justify-content-center justify-content-md-start">
                                            <i class="fas fa-arrow-down mr-2"></i> 
                                            <span class="d-none d-sm-inline">Entrée (achat/approvisionnement)</span>
                                            <span class="d-sm-none">Entrée</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="typeExit" value="exit">
                                    <label class="form-check-label w-100" for="typeExit">
                                        <span class="badge bg-danger p-2 d-flex align-items-center justify-content-center justify-content-md-start">
                                            <i class="fas fa-arrow-up mr-2"></i> 
                                            <span class="d-none d-sm-inline">Sortie (vente/utilisation)</span>
                                            <span class="d-sm-none">Sortie</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Produits avec bouton d'ajout -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h6 class="mb-0"><i class="fas fa-boxes mr-2"></i> Produits</h6>
                        <button type="button" id="addRowBtn" class="btn btn-light btn-sm">
                            <i class="fas fa-plus mr-2"></i> Ajouter un produit
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="products-table-responsive">
                            <table id="productsTable" class="products-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="product-col">Produit</th>
                                        <th class="stock-col">Stock</th>
                                        <th class="qty-col">Qté</th>
                                        <th class="price-col">P.U.</th>
                                        <th class="total-col">Total</th>
                                        <th class="action-col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="productsBody">
                                    <!-- Les lignes seront ajoutées dynamiquement ici -->
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light font-weight-bold">
                                        <td colspan="5" class="text-right">TOTAL GÉNÉRAL :</td>
                                        <td class="text-success" id="grandTotal">0.00 €</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Message si aucun produit -->
                        <div id="noProductsMessage" class="text-center py-5">
                            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                            <p class="text-muted mb-3">Aucun produit ajouté</p>
                            <button type="button" id="addFirstProductBtn" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i> Ajouter un produit
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Résumé de la transaction -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-pie mr-2"></i> Résumé</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="summary-box bg-info h-100">
                                    <div class="inner">
                                        <h3 id="productCount">0</h3>
                                        <p>Produits</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="summary-box bg-success h-100">
                                    <div class="inner">
                                        <h3 id="totalQuantity">0</h3>
                                        <p>Quantité</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-cubes"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="summary-box bg-warning h-100">
                                    <div class="inner">
                                        <h3 id="totalValue">0.00 €</h3>
                                        <p>Total</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-euro-sign"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary mb-2 mb-sm-0">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> Enregistrer la transaction
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

/* ===== STYLES POUR LES BOXES DE RÉSUMÉ ===== */
.summary-box {
    border-radius: 0.5rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
    color: #fff;
    padding: 12px;
    transition: transform 0.3s;
    height: 100%;
}
.summary-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.summary-box .inner {
    padding: 5px 5px 5px 0;
}
.summary-box h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 3px;
    line-height: 1.2;
    word-break: break-word;
}
.summary-box p {
    font-size: 0.8rem;
    margin-bottom: 0;
    opacity: 0.9;
}
.summary-box .icon {
    color: rgba(255,255,255,0.15);
    position: absolute;
    right: 8px;
    top: 8px;
    font-size: 40px;
}

/* ===== STYLES POUR LE TABLEAU DES PRODUITS ===== */
.products-table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.products-table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
}

.products-table thead th {
    background: #f8f9fa;
    padding: 12px 8px;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.products-table tbody td {
    padding: 12px 8px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.products-table tfoot td {
    padding: 12px 8px;
    border-top: 2px solid #dee2e6;
}

/* Largeurs des colonnes - toutes visibles */
.product-col { min-width: 280px; }
.stock-col { min-width: 80px; text-align: center; }
.qty-col { min-width: 80px; }
.price-col { min-width: 100px; }
.total-col { min-width: 100px; }
.action-col { min-width: 80px; }

/* ===== STYLES POUR LES LIGNES DE PRODUITS ===== */
.product-row td {
    vertical-align: middle;
}

.current-stock {
    font-weight: 600;
    color: #17a2b8;
}

.quantity-input.is-invalid {
    border-color: #dc3545;
}

.remove-row {
    white-space: nowrap;
}

/* ===== MODE SOMBRE ===== */
.dark .card {
    background: #2d2d44 !important;
    border-color: #4a4a6a !important;
}
.dark .card-header {
    background: #3a3a5a !important;
}
.dark .products-table {
    color: #e0e0e0;
}
.dark .products-table thead th {
    background: #3a3a5a !important;
    color: #e0e0e0;
    border-bottom-color: #4a4a6a;
}
.dark .products-table tbody td {
    border-bottom-color: #4a4a6a;
}
.dark .products-table tfoot td {
    background: #3a3a5a;
    color: #e0e0e0;
}
.dark .form-control, .dark .form-select {
    background: #2d2d44;
    border-color: #4a4a6a;
    color: #e0e0e0;
}
.dark .form-control:focus, .dark .form-select:focus {
    background: #35354a;
    border-color: #6a6a9a;
    color: #ffffff;
}
.dark .bg-light {
    background: #35354a !important;
}
.dark .summary-box {
    color: #fff;
}
.dark .current-stock {
    color: #5bc0de;
}

/* ===== STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    .summary-box h3 {
        font-size: 1.2rem;
    }
    .summary-box .icon {
        font-size: 35px;
    }
    
    .products-table tbody td {
        padding: 10px 5px;
    }
    
    .product-col { min-width: 220px; }
    .stock-col { min-width: 70px; }
    .qty-col { min-width: 70px; }
    .price-col { min-width: 90px; }
    .total-col { min-width: 90px; }
    .action-col { min-width: 70px; }
    
    .btn-sm {
        padding: 5px 8px;
    }
    
    .remove-row span {
        display: none;
    }
    
    .products-table {
        min-width: 800px;
    }
}

@media (max-width: 576px) {
    .summary-box h3 {
        font-size: 1rem;
    }
    .summary-box p {
        font-size: 0.7rem;
    }
    .summary-box .icon {
        font-size: 30px;
        right: 5px;
        top: 5px;
    }
    
    .products-table tbody td {
        padding: 8px 3px;
    }
    
    .form-control, .form-select {
        font-size: 0.85rem;
        padding: 6px 8px;
    }
    
    .product-col { min-width: 200px; }
    .stock-col { min-width: 60px; }
    .qty-col { min-width: 60px; }
    .price-col { min-width: 80px; }
    .total-col { min-width: 80px; }
    .action-col { min-width: 60px; }
    
    .products-table {
        min-width: 750px;
    }
    
    .btn .d-md-inline {
        display: none;
    }
}

/* Classes utilitaires */
.h-100 { height: 100%; }
.g-3 { gap: 1rem; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 0;
    const productsBody = document.getElementById('productsBody');
    const noProductsMessage = document.getElementById('noProductsMessage');
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const submitBtn = document.getElementById('submitBtn');
    
    // Données des produits passées depuis Laravel
    const productsData = @json($products);
    
    // Fonction pour obtenir le nom formaté du produit
    function getFormattedProductName(product) {
        if (!product) return 'Produit inconnu';
        
        // Utiliser admin_name s'il existe
        if (product.admin_name) {
            return product.admin_name;
        }
        
        // Si name est un objet JSON (traductions)
        if (product.name && typeof product.name === 'object') {
            return product.name.fr || Object.values(product.name)[0] || 'Produit';
        }
        
        // Si name est une chaîne
        if (product.name && typeof product.name === 'string') {
            return product.name;
        }
        
        return 'Produit';
    }
    
    // Fonction pour créer une nouvelle ligne
    function createProductRow() {
        const tr = document.createElement('tr');
        tr.className = 'product-row';
        
        let optionsHtml = '<option value="">Sélectionnez un produit</option>';
        productsData.forEach(product => {
            const productName = getFormattedProductName(product);
            const price = parseFloat(product.price || 0).toFixed(2);
            optionsHtml += `<option value="${product.id}" data-price="${product.price}" data-stock="${product.quantity}">${productName} - ${price} €</option>`;
        });
        
        tr.innerHTML = `
            <td class="product-col">
                <select name="products[${rowIndex}][product_id]" class="form-select product-select" required>
                    ${optionsHtml}
                </select>
            </td>
            <td class="stock-col text-center current-stock align-middle font-weight-bold">
                0
            </td>
            <td class="qty-col">
                <input type="number" name="products[${rowIndex}][quantity]" class="form-control quantity-input" required min="1">
            </td>
            <td class="price-col">
                <input type="number" step="0.01" name="products[${rowIndex}][price]" class="form-control price-input bg-light" readonly>
            </td>
            <td class="total-col">
                <input type="text" class="form-control total-input bg-light font-weight-bold" readonly>
            </td>
            <td class="action-col text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        productsBody.appendChild(tr);
        rowIndex++;
        
        // Cacher le message "aucun produit"
        noProductsMessage.style.display = 'none';
        
        // Attacher les événements à la nouvelle ligne
        attachRowEvents(tr);
        
        // Mettre à jour le résumé
        updateSummary();
        
        return tr;
    }
    
    // Fonction pour attacher les événements à une ligne
    function attachRowEvents(row) {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const removeBtn = row.querySelector('.remove-row');
        
        // Événement changement de produit
        select.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            const stock = selectedOption.getAttribute('data-stock') || 0;
            
            row.querySelector('.price-input').value = price;
            row.querySelector('.current-stock').textContent = stock;
            
            this.classList.remove('is-invalid');
            calculateRowTotal(row);
            validateRowQuantity(row);
            updateSummary();
        });
        
        // Événement changement de quantité
        quantityInput.addEventListener('input', function() {
            calculateRowTotal(row);
            validateRowQuantity(row);
            updateSummary();
            
            if (this.value > 0) {
                this.classList.remove('is-invalid');
            }
        });
        
        // Événement suppression
        removeBtn.addEventListener('click', function() {
            const rows = document.querySelectorAll('.product-row');
            if (rows.length > 1) {
                row.remove();
                updateSummary();
                
                // Afficher le message si plus aucun produit
                if (document.querySelectorAll('.product-row').length === 0) {
                    noProductsMessage.style.display = 'block';
                }
            } else {
                alert('Vous devez garder au moins un produit.');
            }
        });
    }
    
    // Fonction pour calculer le total d'une ligne
    function calculateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = quantity * price;
        row.querySelector('.total-input').value = total.toFixed(2);
        return total;
    }
    
    // Fonction pour valider la quantité d'une ligne
    function validateRowQuantity(row) {
        const type = document.querySelector('input[name="type"]:checked').value;
        if (type === 'exit') {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const currentStock = parseFloat(row.querySelector('.current-stock').textContent) || 0;
            
            if (quantity > currentStock) {
                row.querySelector('.quantity-input').classList.add('is-invalid');
                return false;
            } else {
                row.querySelector('.quantity-input').classList.remove('is-invalid');
                return true;
            }
        }
        return true;
    }
    
    // Fonction pour mettre à jour le résumé
    function updateSummary() {
        const rows = document.querySelectorAll('.product-row');
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
        document.getElementById('grandTotal').textContent = totalValue.toFixed(2) + ' €';
    }
    
    // Fonction pour valider tout le formulaire
    function validateForm() {
        const rows = document.querySelectorAll('.product-row');
        let isValid = true;
        
        if (rows.length === 0) {
            alert('Vous devez ajouter au moins un produit.');
            return false;
        }
        
        rows.forEach(row => {
            if (!validateRowQuantity(row)) {
                isValid = false;
            }
            
            const productSelect = row.querySelector('.product-select');
            if (!productSelect.value) {
                productSelect.classList.add('is-invalid');
                isValid = false;
            } else {
                productSelect.classList.remove('is-invalid');
            }
            
            const quantity = row.querySelector('.quantity-input').value;
            if (!quantity || quantity <= 0) {
                row.querySelector('.quantity-input').classList.add('is-invalid');
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    // Ajouter la première ligne automatiquement
    createProductRow();
    
    // Boutons d'ajout
    document.getElementById('addRowBtn').addEventListener('click', createProductRow);
    document.getElementById('addFirstProductBtn').addEventListener('click', createProductRow);
    
    // Événement changement de type
    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.product-row').forEach(row => {
                validateRowQuantity(row);
            });
        });
    });
    
    // Validation avant soumission
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs dans le formulaire.');
        } else {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Enregistrement...';
        }
    });
});
</script>
@endsection