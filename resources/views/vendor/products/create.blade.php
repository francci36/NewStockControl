@extends('vendor.layouts.app')

@section('title', __('messages.vendor_add_product'))

@section('page-title', __('messages.vendor_add_product'))

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0">
        <!-- En-tête responsive -->
        <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle mr-2"></i> {{ __('messages.vendor_add_product') }}
            </h5>
            <a href="{{ route('vendor.products.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left mr-2"></i> <span class="d-none d-sm-inline">{{ __('messages.back') }}</span>
                <span class="d-sm-none">←</span>
            </a>
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
                    <strong>{{ __('messages.errors') }} :</strong>
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

            <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                @csrf

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
                                   value="{{ old('name') }}"
                                   placeholder="Ex: Croquettes pour chiens Premium"
                                   required>
                            <small class="text-muted">Ce nom sera traduit automatiquement selon la langue du visiteur</small>
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
                                   value="{{ old('short_description') }}"
                                   maxlength="500"
                                   placeholder="Brève description (max 500 caractères)">
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
                                      placeholder="Description détaillée du produit...">{{ old('description') }}</textarea>
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
                                <!-- Catégorie principale -->
                                <div class="form-group mb-3">
                                    <label for="main_category" class="form-label fw-bold small">Catégorie principale</label>
                                    <select name="main_category" 
                                            id="main_category" 
                                            class="form-control" 
                                            required>
                                        <option value="">{{ __('messages.select_category') }}</option>
                                        @foreach($mainCategories as $category)
                                            <option value="{{ $category->id }}" data-id="{{ $category->id }}">
                                                {{ $category->translated_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sous-catégorie (chargée dynamiquement) -->
                                <div class="form-group mb-2" id="subcategory_group" style="display: none;">
                                    <label for="category_id" class="form-label fw-bold small">Sous-catégorie</label>
                                    <select name="category_id" 
                                            id="category_id" 
                                            class="form-control @error('category_id') is-invalid @enderror"
                                            required>
                                        <option value="">Choisissez d'abord une catégorie principale</option>
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Option nouvelle sous-catégorie (si le vendeur veut en créer une) -->
                                <div class="mt-2">
                                    <small>
                                        <a href="{{ route('vendor.categories.create') }}" target="_blank" class="text-primary">
                                            <i class="fas fa-plus-circle mr-1"></i> Créer une nouvelle sous-catégorie
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
                                               value="{{ old('price') }}"
                                               placeholder="0.00"
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
                                           value="{{ old('quantity', 0) }}"
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
                                           value="{{ old('stock_threshold', 5) }}">
                                    @error('stock_threshold')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted d-block mt-1">{{ __('messages.stock_threshold_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">{{ __('messages.product_image') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="image" class="form-label fw-bold">{{ __('messages.choose_image') }}</label>
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
                                    @error('image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('messages.image_help') }}
                                    </small>
                                </div>
                                
                                <!-- Aperçu de l'image -->
                                <div id="imagePreview" class="text-center mt-3" style="display: none;">
                                    <img src="#" alt="Aperçu" class="img-fluid rounded border" style="max-height: 150px;">
                                    <p class="text-muted small mt-1">{{ __('messages.image_preview') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Boutons d'action responsives -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary w-100 w-sm-auto">
                        <i class="fas fa-arrow-left mr-2"></i> {{ __('messages.back') }}
                    </a>
                    <button type="submit" class="btn btn-primary w-100 w-sm-auto">
                        <i class="fas fa-save mr-2"></i> {{ __('messages.vendor_save_product') }}
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

/* ===== STYLES POUR LES CARTES ===== */
.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
    overflow: hidden;
}

.card-header.bg-light {
    background: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6;
    padding: 12px 15px;
}

/* ===== STYLES POUR LE FORMULAIRE ===== */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
}

.form-control-lg {
    border-radius: 0.5rem;
}

.input-group-text {
    background: #f8f9fa;
    border: 1px solid #ced4da;
}

/* ===== STYLES POUR LE CUSTOM FILE ===== */
.custom-file-label {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    border-radius: 0.5rem;
}
.custom-file-label::after {
    content: "{{ __('messages.browse') }}";
    background: #667eea;
    color: white;
    border-left: 1px solid #5a6fd8;
    border-radius: 0 0.5rem 0.5rem 0;
}
.custom-file-input:focus ~ .custom-file-label {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
}

/* ===== MODE SOMBRE ===== */
.dark .card {
    background: #2d2d44;
    border-color: #4a4a6a;
}
.dark .card-header.bg-light {
    background: #3a3a5a !important;
    border-bottom-color: #4a4a6a;
}
.dark .card-header h6 {
    color: #e0e0e0;
}
.dark .form-label {
    color: #c0c0e0;
}
.dark .form-control {
    background: #2d2d44;
    border-color: #4a4a6a;
    color: #e0e0e0;
}
.dark .form-control:focus {
    background: #35354a;
    border-color: #6a6a9a;
    color: #ffffff;
}
.dark .input-group-text {
    background: #35354a;
    border-color: #4a4a6a;
    color: #c0c0e0;
}
.dark .custom-file-label {
    background: #2d2d44;
    border-color: #4a4a6a;
    color: #c0c0e0;
}
.dark .custom-file-label::after {
    background: #5a6fd8;
    color: white;
    border-left-color: #4a4a6a;
}
.dark .text-muted {
    color: #a0a0c0 !important;
}

/* ===== STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .form-control-lg {
        font-size: 1rem;
    }
    
    .btn {
        padding: 0.6rem 1rem;
    }
    
    .custom-file-label {
        font-size: 0.9rem;
    }
    
    .card-header h5 {
        font-size: 1.1rem;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 0.8rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .w-sm-auto {
        width: 100%;
    }
    
    .d-flex.gap-3 {
        flex-direction: column;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group-append {
        width: 100%;
        margin-top: 5px;
    }
    
    .input-group-append .btn {
        width: 100%;
        border-radius: 0.25rem !important;
    }
}

/* Classes utilitaires */
.w-sm-auto { width: auto; }
@media (min-width: 576px) {
    .w-sm-auto { width: auto; }
}
</style>
@endsection

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
        mainCategorySelect.addEventListener('change', function() {
            const mainCategoryId = this.value;
            
            if (mainCategoryId) {
                // Réinitialiser et cacher le select des sous-catégories pendant le chargement
                subcategorySelect.innerHTML = '<option value="">Chargement...</option>';
                subcategorySelect.disabled = true;
                subcategoryGroup.style.display = 'block';
                
                // Charger les sous-catégories via AJAX
                fetch(`/api/categories/${mainCategoryId}/subcategories`)
                    .then(response => response.json())
                    .then(data => {
                        subcategorySelect.innerHTML = '<option value="">Choisissez une sous-catégorie</option>';
                        
                        if (data.length === 0) {
                            subcategorySelect.innerHTML += '<option value="" disabled>Aucune sous-catégorie disponible</option>';
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
                        console.error('Erreur:', error);
                        subcategorySelect.innerHTML = '<option value="">Erreur de chargement</option>';
                        subcategorySelect.disabled = false;
                    });
            } else {
                subcategoryGroup.style.display = 'none';
                subcategorySelect.value = '';
            }
        });
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