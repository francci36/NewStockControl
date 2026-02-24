@extends('vendor.layouts.vendor')

@section('title', 'Ajouter un produit')

@section('page-title', 'Ajouter un produit')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Nouveau produit</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <!-- Nom du produit -->
                    <div class="form-group mb-3">
                        <label for="name">Nom du produit <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description courte -->
                    <div class="form-group mb-3">
                        <label for="short_description">Description courte</label>
                        <input type="text" 
                               name="short_description" 
                               id="short_description" 
                               class="form-control @error('short_description') is-invalid @enderror" 
                               value="{{ old('short_description') }}"
                               maxlength="500">
                        @error('short_description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description complète -->
                    <div class="form-group mb-3">
                        <label for="description">Description complète</label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="5" 
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Catégorie avec option "Ajouter nouvelle" -->
                    <div class="form-group mb-3">
                        <label for="category_id">Catégorie <span class="text-danger">*</span></label>
                        
                        <!-- Sélecteur de catégorie existante -->
                        <div class="input-group mb-2">
                            <select name="category_id" 
                                    id="category_id" 
                                    class="form-control @error('category_id') is-invalid @enderror" 
                                    required>
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                                <option value="new">➕ Ajouter une nouvelle catégorie...</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="refreshCategories">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Champ pour nouvelle catégorie (caché par défaut) -->
                        <div id="newCategoryField" style="display: none;">
                            <div class="input-group">
                                <input type="text" 
                                       name="new_category" 
                                       id="new_category" 
                                       class="form-control @error('new_category') is-invalid @enderror" 
                                       placeholder="Nom de la nouvelle catégorie">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="cancelNewCategory">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">La catégorie sera créée automatiquement</small>
                        </div>
                        
                        @error('category_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        @error('new_category')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Prix -->
                    <div class="form-group mb-3">
                        <label for="price">Prix (€) <span class="text-danger">*</span></label>
                        <input type="number" 
                               name="price" 
                               id="price" 
                               step="0.01" 
                               min="0"
                               class="form-control @error('price') is-invalid @enderror" 
                               value="{{ old('price') }}"
                               required>
                        @error('price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Quantité en stock -->
                    <div class="form-group mb-3">
                        <label for="quantity">Quantité en stock <span class="text-danger">*</span></label>
                        <input type="number" 
                               name="quantity" 
                               id="quantity" 
                               min="0"
                               class="form-control @error('quantity') is-invalid @enderror" 
                               value="{{ old('quantity', 0) }}"
                               required>
                        @error('quantity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Seuil d'alerte -->
                    <div class="form-group mb-3">
                        <label for="stock_threshold">Seuil d'alerte</label>
                        <input type="number" 
                               name="stock_threshold" 
                               id="stock_threshold" 
                               min="1"
                               class="form-control @error('stock_threshold') is-invalid @enderror" 
                               value="{{ old('stock_threshold', 5) }}">
                        @error('stock_threshold')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">Stock minimum avant alerte</small>
                    </div>

                    <!-- Image -->
                    <div class="form-group mb-3">
                        <label for="image">Image du produit</label>
                        <input type="file" 
                               name="image" 
                               id="image" 
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/*">
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="text-muted">Formats: JPG, PNG, GIF. Max: 2Mo</small>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-between">
                <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Enregistrer le produit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const newCategoryField = document.getElementById('newCategoryField');
    const newCategoryInput = document.getElementById('new_category');
    const cancelBtn = document.getElementById('cancelNewCategory');
    const refreshBtn = document.getElementById('refreshCategories');

    // Afficher le champ nouvelle catégorie quand on sélectionne "Ajouter"
    categorySelect.addEventListener('change', function() {
        if (this.value === 'new') {
            newCategoryField.style.display = 'block';
            newCategoryInput.required = true;
            categorySelect.required = false;
        } else {
            newCategoryField.style.display = 'none';
            newCategoryInput.required = false;
            categorySelect.required = true;
        }
    });

    // Annuler l'ajout d'une nouvelle catégorie
    cancelBtn.addEventListener('click', function() {
        newCategoryField.style.display = 'none';
        newCategoryInput.value = '';
        newCategoryInput.required = false;
        categorySelect.value = '';
        categorySelect.required = true;
    });

    // Rafraîchir la liste des catégories (optionnel)
    refreshBtn.addEventListener('click', function() {
        fetch('/api/categories')
            .then(response => response.json())
            .then(categories => {
                // Garder la valeur actuelle
                const currentValue = categorySelect.value;
                
                // Vider et re-remplir le select
                categorySelect.innerHTML = '<option value="">Sélectionnez une catégorie</option>';
                categories.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat.id;
                    option.textContent = cat.name;
                    categorySelect.appendChild(option);
                });
                
                // Ajouter l'option "Nouvelle catégorie"
                const newOption = document.createElement('option');
                newOption.value = 'new';
                newOption.textContent = '➕ Ajouter une nouvelle catégorie...';
                categorySelect.appendChild(newOption);
                
                // Restaurer la valeur si elle existe encore
                if (currentValue && currentValue !== 'new') {
                    const exists = Array.from(categorySelect.options).some(opt => opt.value === currentValue);
                    if (exists) {
                        categorySelect.value = currentValue;
                    }
                }
            })
            .catch(error => console.error('Erreur:', error));
    });

    // Si ancienne valeur 'new' après erreur de validation
    @if(old('category_id') === 'new')
        newCategoryField.style.display = 'block';
        newCategoryInput.required = true;
        categorySelect.required = false;
        newCategoryInput.value = "{{ old('new_category') }}";
    @endif
});
</script>
@endpush