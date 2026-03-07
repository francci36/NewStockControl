@extends('layouts.app')

@section('title', 'Ajouter des produits')

@section('content')
<div class="container-fluid py-6">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Ajouter des produits
                </h5>
                <small class="text-white-50">Créez un ou plusieurs produits en une seule fois</small>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>

        <div class="card-body">
            <!-- Messages d'erreur -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Erreurs de validation :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Formulaire d'ajout de produits -->
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                @csrf
                
                <!-- Sélection du fournisseur -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-truck mr-2"></i>Fournisseur</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier_id" class="form-label">Fournisseur <span class="text-danger">*</span></label>
                                    <select name="supplier_id" 
                                            id="supplier_id" 
                                            class="form-control @error('supplier_id') is-invalid @enderror"
                                            required>
                                        <option value="">Sélectionner un fournisseur</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Tous les produits seront associés à ce fournisseur.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conteneur pour les produits -->
                <div id="products-container">
                    <!-- Premier produit par défaut -->
                    <div class="card mb-4 product-item" data-index="0">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-box mr-2"></i>
                                Produit <span class="product-number">#1</span>
                            </h6>
                            <button type="button" class="btn btn-danger btn-sm remove-product" style="display: none;">
                                <i class="fas fa-trash mr-1"></i> Supprimer
                            </button>
                        </div>
                        
                        <div class="card-body">
                            <div class="row">
                                <!-- Colonne gauche -->
                                <div class="col-md-6">
                                    <!-- Nom du produit -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Nom du produit <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="products[0][name]" 
                                               class="form-control"
                                               value="{{ old('products.0.name') }}"
                                               required>
                                    </div>
                                    
                                    <!-- Prix -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Prix (€) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   name="products[0][price]" 
                                                   step="0.01"
                                                   min="0"
                                                   class="form-control"
                                                   value="{{ old('products.0.price') }}"
                                                   required>
                                            <span class="input-group-text">€</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Seuil de stock -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Seuil d'alerte stock</label>
                                        <input type="number" 
                                               name="products[0][stock_threshold]" 
                                               min="1"
                                               value="{{ old('products.0.stock_threshold', 5) }}"
                                               class="form-control">
                                        <small class="text-muted">Stock minimum avant alerte</small>
                                    </div>
                                </div>
                                
                                <!-- Colonne droite -->
                                <div class="col-md-6">
                                    <!-- Catégorie -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                        <select name="products[0][category_id]" 
                                                id="category_select_0"
                                                class="form-control category-select"
                                                required>
                                            <option value="">Sélectionner une catégorie</option>
                                            @forelse($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('products.0.category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @empty
                                                <option value="" disabled>Aucune catégorie disponible</option>
                                            @endforelse
                                        </select>
                                        
                                        <!-- Bouton pour ajouter une catégorie rapidement -->
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="quickAddCategory(0)">
                                            <i class="fas fa-plus mr-1"></i> Ajouter une nouvelle catégorie
                                        </button>
                                    </div>
                                    
                                    <!-- Image -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Image du produit</label>
                                        <input type="file" 
                                               name="products[0][image]" 
                                               accept="image/jpeg,image/png,image/jpg,image/gif"
                                               class="form-control">
                                        <small class="text-muted">Formats: JPEG, PNG, JPG, GIF (max 2MB)</small>
                                    </div>
                                </div>
                                
                                <!-- Ligne complète pour les descriptions -->
                                <div class="col-md-12">
                                    <!-- Description courte -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Description courte</label>
                                        <input type="text" 
                                               name="products[0][short_description]" 
                                               maxlength="255"
                                               class="form-control"
                                               value="{{ old('products.0.short_description') }}"
                                               placeholder="Brève description (max 255 caractères)">
                                    </div>
                                    
                                    <!-- Description complète -->
                                    <div class="form-group mb-3">
                                        <label class="form-label">Description détaillée</label>
                                        <textarea name="products[0][description]" 
                                                  rows="4"
                                                  class="form-control"
                                                  placeholder="Description complète du produit...">{{ old('products.0.description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="button" id="add-product" class="btn btn-info">
                        <i class="fas fa-plus mr-2"></i> Ajouter un autre produit
                    </button>
                    
                    <div>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-2"></i> Enregistrer les produits
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script pour gérer l'ajout dynamique de produits et les catégories -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let productCount = 1;
    const container = document.getElementById('products-container');
    
    // Fonction pour ajouter une nouvelle catégorie
    window.quickAddCategory = function(index) {
        const categoryName = prompt('Nom de la nouvelle catégorie:');
        if (!categoryName) return;
        
        // Désactiver le bouton pendant l'appel
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création...';
        button.disabled = true;
        
        fetch('/admin/categories/quick-create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: categoryName, is_active: true })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Ajouter la catégorie à tous les selects
                document.querySelectorAll('.category-select').forEach(select => {
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.textContent = data.category.name;
                    
                    // Ajouter l'option et la sélectionner
                    select.appendChild(option);
                });
                
                // Sélectionner la nouvelle catégorie dans le select concerné
                const currentSelect = document.getElementById(`category_select_${index}`);
                if (currentSelect) {
                    currentSelect.value = data.category.id;
                }
                
                alert('Catégorie créée avec succès !');
            } else {
                alert('Erreur: ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la création de la catégorie');
        })
        .finally(() => {
            // Restaurer le bouton
            button.innerHTML = originalText;
            button.disabled = false;
        });
    };
    
    // Ajouter une nouvelle ligne de produit
    document.getElementById('add-product').addEventListener('click', function() {
        const template = container.querySelector('.product-item').cloneNode(true);
        
        // Mettre à jour les indices dans les noms des champs
        template.innerHTML = template.innerHTML.replace(/products\[0\]/g, `products[${productCount}]`);
        
        // Mettre à jour les IDs des selects et les appels de fonction
        const categorySelect = template.querySelector('.category-select');
        if (categorySelect) {
            categorySelect.id = `category_select_${productCount}`;
            categorySelect.value = '';
        }
        
        // Mettre à jour le bouton d'ajout de catégorie
        const addButton = template.querySelector('button[onclick^="quickAddCategory"]');
        if (addButton) {
            addButton.setAttribute('onclick', `quickAddCategory(${productCount})`);
        }
        
        // Mettre à jour le numéro du produit
        const productNumber = template.querySelector('.product-number');
        if (productNumber) {
            productNumber.textContent = `#${productCount + 1}`;
        }
        
        // Réinitialiser les valeurs des champs
        template.querySelectorAll('input:not([type="button"]):not([type="file"]), textarea, select').forEach(input => {
            if (input.type === 'file') {
                input.value = '';
            } else if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });
        
        // Afficher le bouton supprimer
        const removeBtn = template.querySelector('.remove-product');
        if (removeBtn) {
            removeBtn.style.display = 'inline-block';
            removeBtn.onclick = function() {
                if (document.querySelectorAll('.product-item').length > 1) {
                    template.remove();
                    updateProductNumbers();
                } else {
                    alert('Vous devez garder au moins un produit');
                }
            };
        }
        
        container.appendChild(template);
        productCount++;
    });
    
    // Fonction pour mettre à jour les numéros de produits
    function updateProductNumbers() {
        const rows = document.querySelectorAll('.product-item');
        rows.forEach((row, idx) => {
            const productNumber = row.querySelector('.product-number');
            if (productNumber) {
                productNumber.textContent = '#' + (idx + 1);
            }
        });
    }
    
    // Gestionnaire pour le bouton supprimer du premier produit
    const firstRemoveBtn = document.querySelector('.remove-product');
    if (firstRemoveBtn) {
        firstRemoveBtn.addEventListener('click', function() {
            if (document.querySelectorAll('.product-item').length > 1) {
                this.closest('.product-item').remove();
                updateProductNumbers();
            } else {
                alert('Vous devez garder au moins un produit');
            }
        });
    }
});
</script>

<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
}
.text-white-50 {
    color: rgba(255, 255, 255, 0.5);
}
.card-header {
    font-weight: 600;
}
.form-label {
    font-weight: 500;
}
.product-item {
    transition: all 0.3s ease;
}
.product-item:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
@media (max-width: 768px) {
    .bg-gradient-success {
        flex-direction: column;
        text-align: center;
    }
    .bg-gradient-success .btn {
        margin-top: 10px;
        width: 100%;
    }
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
    .d-flex.justify-content-between .btn,
    .d-flex.justify-content-between a {
        width: 100%;
        margin: 5px 0 !important;
    }
    .product-item .row > div {
        margin-bottom: 15px;
    }
    .input-group {
        flex-wrap: wrap;
    }
    .input-group .btn {
        width: 100%;
        margin-top: 5px;
        border-radius: 0.25rem !important;
    }
}
</style>
@endsection