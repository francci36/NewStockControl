@extends('layouts.app')

@section('title', 'Ajouter des produits')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-green-100 dark:bg-green-900 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-green-700 dark:text-green-400 flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Ajouter des produits
            </h1>
        </div>

        <div class="px-6 py-4">
            <!-- Messages d'erreur -->
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulaire d'ajout de produits -->
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Sélection du fournisseur -->
                <div class="mb-6">
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Fournisseur <span class="text-red-500">*</span>
                    </label>
                    <select name="supplier_id" 
                            id="supplier_id" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-300 dark:focus:ring-green-500 dark:bg-gray-700 dark:text-gray-200 @error('supplier_id') border-red-500 @enderror"
                            required>
                        <option value="">Sélectionner un fournisseur</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Conteneur pour les produits -->
                <div id="products-container">
                    <!-- Premier produit par défaut -->
                    <div class="product-item border p-4 mb-4 rounded-lg bg-gray-50 dark:bg-gray-700" data-index="0">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold">Produit #1</h3>
                            <button type="button" class="remove-product text-red-600 hover:text-red-800" style="display: none;">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nom du produit -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nom du produit <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="products[0][name]" 
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-300 dark:bg-gray-800"
                                       required>
                            </div>
                            
                            <!-- Prix -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Prix (€) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="products[0][price]" 
                                       step="0.01"
                                       min="0"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-300 dark:bg-gray-800"
                                       required>
                            </div>
                            
                            <!-- Sélection de la catégorie avec option de création -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <div class="flex space-x-2">
                                    <select name="products[0][category_id]" 
                                            id="category_select_0"
                                            class="category-select w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-300 dark:bg-gray-800"
                                            required
                                            onchange="toggleNewCategory(0)">
                                        <option value="">Sélectionner une catégorie</option>
                                        <option value="new">➕ Créer une nouvelle catégorie</option>
                                        @forelse($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Aucune catégorie disponible</option>
                                        @endforelse
                                    </select>
                                </div>
                                
                                <!-- Champ pour nouvelle catégorie (caché par défaut) -->
                                <div id="new_category_container_0" class="mt-2 hidden">
                                    <div class="flex space-x-2">
                                        <input type="text" 
                                               id="new_category_name_0"
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-300 dark:bg-gray-800"
                                               placeholder="Nom de la nouvelle catégorie">
                                        <button type="button" 
                                                onclick="addNewCategory(0)"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg whitespace-nowrap">
                                            <i class="fas fa-plus"></i> Ajouter
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Le slug sera généré automatiquement</p>
                                </div>
                            </div>
                            
                            <!-- Seuil de stock -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Seuil d'alerte stock
                                </label>
                                <input type="number" 
                                       name="products[0][stock_threshold]" 
                                       min="1"
                                       value="5"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-300 dark:bg-gray-800">
                            </div>
                            
                            <!-- Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Image du produit
                                </label>
                                <input type="file" 
                                       name="products[0][image]" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-300 dark:bg-gray-800">
                                <p class="text-xs text-gray-500 mt-1">Formats: JPEG, PNG, JPG, GIF (max 2MB)</p>
                            </div>
                            
                            <!-- Description courte -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Description courte
                                </label>
                                <input type="text" 
                                       name="products[0][short_description]" 
                                       maxlength="255"
                                       placeholder="Brève description"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-300 dark:bg-gray-800">
                            </div>
                            
                            <!-- Description complète -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Description détaillée
                                </label>
                                <textarea name="products[0][description]" 
                                          rows="3"
                                          placeholder="Description complète du produit"
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-300 dark:bg-gray-800"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-between items-center mt-6">
                    <button type="button" id="add-product" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-plus mr-2"></i> Ajouter un autre produit
                    </button>
                    
                    <div class="space-x-3">
                        <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                            <i class="fas fa-save mr-2"></i> Enregistrer les produits
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script pour gérer l'ajout dynamique de produits et la création de catégories -->
<script>
// Fonction pour afficher/masquer le champ de nouvelle catégorie
function toggleNewCategory(index) {
    const select = document.getElementById(`category_select_${index}`);
    const container = document.getElementById(`new_category_container_${index}`);
    
    if (select.value === 'new') {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
}

// Fonction pour ajouter une nouvelle catégorie via AJAX
function addNewCategory(index) {
    const categoryName = document.getElementById(`new_category_name_${index}`).value.trim();
    
    if (!categoryName) {
        alert('Veuillez saisir un nom de catégorie');
        return;
    }
    
    // Générer le slug à partir du nom
    const slug = categoryName.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-');
    
    // Appel AJAX pour créer la catégorie
    fetch('{{ route("admin.categories.quick-create") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            name: categoryName,
            slug: slug,
            description: 'Catégorie créée rapidement',
            is_active: true
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ajouter la nouvelle catégorie au select
            const select = document.getElementById(`category_select_${index}`);
            const option = document.createElement('option');
            option.value = data.category.id;
            option.textContent = data.category.name;
            option.selected = true;
            select.appendChild(option);
            
            // Réinitialiser et cacher le champ de nouvelle catégorie
            document.getElementById(`new_category_name_${index}`).value = '';
            document.getElementById(`new_category_container_${index}`).classList.add('hidden');
            
            // Afficher un message de succès
            alert('Catégorie créée avec succès !');
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la création de la catégorie');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    let productCount = 1;
    const container = document.getElementById('products-container');
    
    document.getElementById('add-product').addEventListener('click', function() {
        const template = document.querySelector('.product-item').cloneNode(true);
        const index = productCount;
        
        // Mettre à jour les indices
        template.setAttribute('data-index', index);
        template.querySelector('h3').textContent = 'Produit #' + (index + 1);
        
        // Mettre à jour les IDs et names
        template.querySelectorAll('input, textarea, select').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
            }
            
            // Mettre à jour les IDs pour les selects de catégorie
            if (input.id && input.id.includes('category_select_')) {
                input.id = `category_select_${index}`;
                input.setAttribute('onchange', `toggleNewCategory(${index})`);
            }
            
            // Réinitialiser les valeurs
            if (input.type !== 'file' && input.tagName !== 'SELECT') {
                input.value = '';
            }
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            }
        });
        
        // Mettre à jour le conteneur de nouvelle catégorie
        const newCatContainer = template.querySelector('[id^="new_category_container_"]');
        if (newCatContainer) {
            newCatContainer.id = `new_category_container_${index}`;
            newCatContainer.classList.add('hidden');
            
            const nameInput = newCatContainer.querySelector('[id^="new_category_name_"]');
            if (nameInput) {
                nameInput.id = `new_category_name_${index}`;
                nameInput.value = '';
            }
            
            const addButton = newCatContainer.querySelector('button[onclick^="addNewCategory"]');
            if (addButton) {
                addButton.setAttribute('onclick', `addNewCategory(${index})`);
            }
        }
        
        // Afficher le bouton supprimer
        const removeBtn = template.querySelector('.remove-product');
        removeBtn.style.display = 'inline-flex';
        removeBtn.addEventListener('click', function() {
            template.remove();
        });
        
        container.appendChild(template);
        productCount++;
    });
    
    // Gestion de la suppression pour le premier produit
    document.querySelectorAll('.remove-product').forEach(btn => {
        btn.addEventListener('click', function() {
            if (document.querySelectorAll('.product-item').length > 1) {
                this.closest('.product-item').remove();
            }
        });
    });
});
</script>

<style>
.hidden {
    display: none;
}
</style>
@endsection