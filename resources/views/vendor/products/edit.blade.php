{{-- resources/views/vendor/products/edit.blade.php --}}
@extends('vendor.layouts.app')

@section('title', __('messages.vendor_edit_product'))

@section('page-title', __('messages.vendor_edit_product'))

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0">
        <!-- En-tête responsive -->
        <div class="card-header bg-gradient-warning text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
            <div>
                <i class="fas fa-edit mr-2"></i> {{ __('messages.vendor_edit_product') }}: <strong>{{ $product->translated_name }}</strong>
            </div>
            <div>
                <span class="badge bg-light text-dark mr-2">{{ __('messages.sku') }}: {{ $product->sku ?? __('messages.not_defined') }}</span>
                <a href="{{ route('vendor.products.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i> <span class="d-none d-sm-inline">{{ __('messages.back') }}</span>
                    <span class="d-sm-none">←</span>
                </a>
            </div>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>{{ __('messages.validation_errors') }} :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('vendor.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Colonne principale (gauche) -->
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <!-- Nom du produit -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label fw-bold">
                                {{ __('messages.vendor_product_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $product->name) }}"
                                   required>
                            <small class="text-muted">{{ __('messages.product_name_help') }}</small>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description courte -->
                        <div class="form-group mb-3">
                            <label for="short_description" class="form-label fw-bold">{{ __('messages.short_description') }}</label>
                            <input type="text" 
                                   name="short_description" 
                                   id="short_description" 
                                   class="form-control @error('short_description') is-invalid @enderror" 
                                   value="{{ old('short_description', $product->short_description) }}"
                                   maxlength="500"
                                   placeholder="{{ __('messages.short_description_placeholder') }}">
                            <small class="text-muted">{{ __('messages.max_characters', ['count' => 500]) }}</small>
                            @error('short_description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description complète -->
                        <div class="form-group mb-3">
                            <label for="description" class="form-label fw-bold">{{ __('messages.full_description') }}</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="6" 
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="{{ __('messages.description_placeholder') }}">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Colonne latérale (droite) -->
                    <div class="col-lg-4">
                        <!-- Catégorie principale et sous-catégorie -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">{{ __('messages.category') }} <span class="text-danger">*</span></h6>
                            </div>
                            <div class="card-body">
                                @php
                                    // Récupérer la catégorie actuelle du produit
                                    $currentCategory = $categories->find($product->category_id);
                                    $currentMainCategoryId = $currentCategory ? $currentCategory->parent_id : null;
                                @endphp

                                <!-- Catégorie principale -->
                                <div class="form-group mb-3">
                                    <label for="main_category" class="form-label fw-bold small">{{ __('messages.main_category') }}</label>
                                    <select name="main_category" 
                                            id="main_category" 
                                            class="form-control" 
                                            required>
                                        <option value="">{{ __('messages.select_main_category') }}</option>
                                        @foreach($mainCategories as $category)
                                            <option value="{{ $category->id }}" 
                                                data-id="{{ $category->id }}"
                                                {{ $currentMainCategoryId == $category->id ? 'selected' : '' }}>
                                                {{ $category->translated_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sous-catégorie -->
                                <div class="form-group mb-2" id="subcategory_group" style="{{ $currentMainCategoryId ? 'display:block;' : 'display:none;' }}">
                                    <label for="category_id" class="form-label fw-bold small">{{ __('messages.subcategory') }}</label>
                                    <select name="category_id" 
                                            id="category_id" 
                                            class="form-control @error('category_id') is-invalid @enderror"
                                            required>
                                        <option value="">{{ __('messages.select_subcategory') }}</option>
                                        @if($currentMainCategoryId && isset($subCategories[$currentMainCategoryId]) && count($subCategories[$currentMainCategoryId]) > 0)
                                            @foreach($subCategories[$currentMainCategoryId] as $subcategory)
                                                <option value="{{ $subcategory->id }}" 
                                                    {{ $product->category_id == $subcategory->id ? 'selected' : '' }}>
                                                    {{ $subcategory->translated_name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>{{ __('messages.no_subcategories') }}</option>
                                        @endif
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Option nouvelle sous-catégorie -->
                                <div class="mt-2">
                                    <small>
                                        <a href="{{ route('vendor.categories.create') }}" target="_blank" class="text-primary">
                                            <i class="fas fa-plus-circle mr-1"></i> {{ __('messages.create_subcategory') }}
                                        </a>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Prix et Stock -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">{{ __('messages.pricing_stock') }}</h6>
                            </div>
                            <div class="card-body">
                                <!-- Prix -->
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label fw-bold">
                                        {{ __('messages.vendor_product_price') }} (€) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">€</span>
                                        </div>
                                        <input type="number" 
                                               name="price" 
                                               id="price" 
                                               step="0.01" 
                                               min="0"
                                               class="form-control @error('price') is-invalid @enderror" 
                                               value="{{ old('price', $product->price) }}"
                                               required>
                                    </div>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Quantité -->
                                <div class="form-group mb-3">
                                    <label for="quantity" class="form-label fw-bold">
                                        {{ __('messages.vendor_product_stock') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           name="quantity" 
                                           id="quantity" 
                                           min="0"
                                           class="form-control @error('quantity') is-invalid @enderror" 
                                           value="{{ old('quantity', $product->quantity) }}"
                                           required>
                                    @error('quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Seuil d'alerte -->
                                <div class="form-group mb-3">
                                    <label for="stock_threshold" class="form-label fw-bold">{{ __('messages.stock_threshold') }}</label>
                                    <input type="number" 
                                           name="stock_threshold" 
                                           id="stock_threshold" 
                                           min="1"
                                           class="form-control @error('stock_threshold') is-invalid @enderror" 
                                           value="{{ old('stock_threshold', $product->stock_threshold ?? 5) }}">
                                    @error('stock_threshold')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted d-block mt-1">{{ __('messages.stock_threshold_help') }}</small>
                                </div>

                                <!-- Statut actif -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               name="is_active" 
                                               id="is_active" 
                                               value="1"
                                               class="custom-control-input"
                                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            <i class="fas fa-check-circle text-success mr-1"></i> {{ __('messages.active_product') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">{{ __('messages.product_image') }}</h6>
                            </div>
                            <div class="card-body">
                                @if($product->image)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->translated_name }}" 
                                             class="img-fluid rounded border" 
                                             style="max-height: 150px;">
                                        <p class="text-muted small mt-1">{{ __('messages.current_image') }}</p>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="image" class="form-label fw-bold">{{ __('messages.new_image') }}</label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               name="image" 
                                               id="image" 
                                               class="custom-file-input @error('image') is-invalid @enderror"
                                               accept="image/jpeg,image/png,image/jpg,image/gif">
                                        <label class="custom-file-label" for="image">
                                            <i class="fas fa-upload mr-2"></i> {{ __('messages.choose_file') }}
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('messages.image_help_edit') }}
                                    </small>
                                    @error('image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Aperçu de la nouvelle image -->
                                <div id="imagePreview" class="text-center mt-3" style="display: none;">
                                    <img src="#" alt="{{ __('messages.preview_image') }}" class="img-fluid rounded border" style="max-height: 150px;">
                                    <p class="text-muted small mt-1">{{ __('messages.new_image_preview') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Boutons d'action responsives -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary w-100 w-sm-auto">
                        <i class="fas fa-times mr-2"></i> {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-warning w-100 w-sm-auto">
                        <i class="fas fa-save mr-2"></i> {{ __('messages.update_product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- Script pour la gestion dynamique des catégories -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainCategorySelect = document.getElementById('main_category');
    const subcategoryGroup = document.getElementById('subcategory_group');
    const subcategorySelect = document.getElementById('category_id');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = imagePreview ? imagePreview.querySelector('img') : null;

    // Chargement des sous-catégories
    if (mainCategorySelect) {
        console.log('✅ Sélecteur de catégorie principale trouvé');
        
        mainCategorySelect.addEventListener('change', function() {
            const mainCategoryId = this.value;
            console.log('🔄 Catégorie sélectionnée:', mainCategoryId);
            
            if (mainCategoryId) {
                // Afficher le groupe
                subcategoryGroup.style.display = 'block';
                
                // Message de chargement
                subcategorySelect.innerHTML = '<option value="">{{ __("messages.loading") }}</option>';
                subcategorySelect.disabled = true;
                
                // Construire l'URL
                const url = `/api/categories/${mainCategoryId}/subcategories`;
                console.log('📡 Appel API:', url);
                
                // Charger les sous-catégories
                fetch(url)
                    .then(response => {
                        console.log('📥 Réponse reçue, status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('📦 Données reçues:', data);
                        
                        // Vider et remplir le select
                        subcategorySelect.innerHTML = '<option value="">{{ __("messages.select_subcategory") }}</option>';
                        
                        if (data.length === 0) {
                            console.log('⚠️ Aucune sous-catégorie trouvée');
                            subcategorySelect.innerHTML += '<option value="" disabled>{{ __("messages.no_subcategories") }}</option>';
                        } else {
                            data.forEach(subcat => {
                                const option = document.createElement('option');
                                option.value = subcat.id;
                                option.textContent = subcat.name;
                                subcategorySelect.appendChild(option);
                            });
                        }
                        
                        subcategorySelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('❌ Erreur:', error);
                        subcategorySelect.innerHTML = '<option value="">{{ __("messages.select_subcategory") }}</option>';
                        subcategorySelect.innerHTML += '<option value="" disabled class="text-danger">{{ __("messages.loading_error") }}</option>';
                        subcategorySelect.disabled = false;
                    });
            } else {
                console.log('👁️ Aucune catégorie sélectionnée, cache le groupe');
                subcategoryGroup.style.display = 'none';
                subcategorySelect.value = '';
                subcategorySelect.disabled = true;
            }
        });
    } else {
        console.error('❌ Sélecteur de catégorie principale non trouvé');
    }

    // Aperçu de l'image
    if (imageInput && imagePreview && previewImg) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.style.display = 'none';
                previewImg.src = '#';
            }
        });
    }

    // Mise à jour du label du fichier
    if (imageInput) {
        const fileLabel = document.querySelector('.custom-file-label');
        if (fileLabel) {
            imageInput.addEventListener('change', function() {
                let fileName = this.files.length ? this.files[0].name : '{{ __("messages.choose_file") }}';
                fileLabel.innerHTML = '<i class="fas fa-upload mr-2"></i> ' + fileName;
            });
        }
    }
});
</script>
@endpush