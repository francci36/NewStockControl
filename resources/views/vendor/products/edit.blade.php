{{-- resources/views/vendor/products/edit.blade.php --}}
@extends('vendor.layouts.app')

@section('title', 'Modifier le produit')

@section('page-title', 'Modifier le produit')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0">
        <!-- En-tête responsive -->
        <div class="card-header bg-gradient-warning text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
            <h5 class="mb-0">
                <i class="fas fa-edit mr-2"></i> Modifier le produit : <strong>{{ $product->translated_name ?? 'Produit' }}</strong>
            </h5>
            <div>
                <span class="badge bg-light text-dark mr-2">SKU: {{ $product->sku ?? 'Non défini' }}</span>
                <a href="{{ route('vendor.products.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i> <span class="d-none d-sm-inline">Retour</span>
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
                    <strong>Erreurs de validation :</strong>
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
                        <!-- Nom du produit (français) -->
                        <div class="form-group mb-3">
                            <label for="name_fr" class="form-label fw-bold">
                                Nom du produit (français) <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name_fr" 
                                   id="name_fr" 
                                   class="form-control form-control-lg @error('name_fr') is-invalid @enderror" 
                                   value="{{ old('name_fr', is_array($product->name) ? ($product->name['fr'] ?? '') : $product->name) }}"
                                   required>
                            <small class="text-muted">Le nom en français sera utilisé pour l'administration</small>
                            @error('name_fr')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Nom du produit (anglais) -->
                        <div class="form-group mb-3">
                            <label for="name_en" class="form-label fw-bold">
                                Nom du produit (anglais)
                            </label>
                            <input type="text" 
                                   name="name_en" 
                                   id="name_en" 
                                   class="form-control @error('name_en') is-invalid @enderror" 
                                   value="{{ old('name_en', is_array($product->name) ? ($product->name['en'] ?? '') : '') }}">
                            <small class="text-muted">Nom du produit en anglais pour les clients internationaux</small>
                            @error('name_en')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description courte (français) -->
                        <div class="form-group mb-3">
                            <label for="short_description_fr" class="form-label fw-bold">Description courte (français)</label>
                            <input type="text" 
                                   name="short_description_fr" 
                                   id="short_description_fr" 
                                   class="form-control @error('short_description_fr') is-invalid @enderror" 
                                   value="{{ old('short_description_fr', is_array($product->short_description) ? ($product->short_description['fr'] ?? '') : $product->short_description) }}"
                                   maxlength="500"
                                   placeholder="Brève description en français (max 500 caractères)">
                            @error('short_description_fr')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description courte (anglais) -->
                        <div class="form-group mb-3">
                            <label for="short_description_en" class="form-label fw-bold">Description courte (anglais)</label>
                            <input type="text" 
                                   name="short_description_en" 
                                   id="short_description_en" 
                                   class="form-control @error('short_description_en') is-invalid @enderror" 
                                   value="{{ old('short_description_en', is_array($product->short_description) ? ($product->short_description['en'] ?? '') : '') }}"
                                   maxlength="500"
                                   placeholder="Brief description in English (max 500 characters)">
                            @error('short_description_en')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description détaillée (français) -->
                        <div class="form-group mb-3">
                            <label for="description_fr" class="form-label fw-bold">Description détaillée (français)</label>
                            <textarea name="description_fr" 
                                      id="description_fr" 
                                      rows="6" 
                                      class="form-control @error('description_fr') is-invalid @enderror"
                                      placeholder="Description détaillée en français...">{{ old('description_fr', is_array($product->description) ? ($product->description['fr'] ?? '') : $product->description) }}</textarea>
                            @error('description_fr')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description détaillée (anglais) -->
                        <div class="form-group mb-3">
                            <label for="description_en" class="form-label fw-bold">Description détaillée (anglais)</label>
                            <textarea name="description_en" 
                                      id="description_en" 
                                      rows="6" 
                                      class="form-control @error('description_en') is-invalid @enderror"
                                      placeholder="Detailed description in English...">{{ old('description_en', is_array($product->description) ? ($product->description['en'] ?? '') : '') }}</textarea>
                            @error('description_en')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Colonne latérale (droite) -->
                    <div class="col-lg-4">
                        <!-- Catégorie -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Catégorie <span class="text-danger">*</span></h6>
                            </div>
                            <div class="card-body">
                                <select name="category_id" 
                                        id="category_id" 
                                        class="form-control @error('category_id') is-invalid @enderror"
                                        required>
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->translated_name ?? $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Prix et Stock -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Prix et Stock</h6>
                            </div>
                            <div class="card-body">
                                <!-- Prix -->
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label fw-bold">
                                        Prix (€) <span class="text-danger">*</span>
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
                                        Quantité en stock <span class="text-danger">*</span>
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
                                    <label for="stock_threshold" class="form-label fw-bold">Seuil d'alerte</label>
                                    <input type="number" 
                                           name="stock_threshold" 
                                           id="stock_threshold" 
                                           min="1"
                                           class="form-control @error('stock_threshold') is-invalid @enderror" 
                                           value="{{ old('stock_threshold', $product->stock_threshold ?? 5) }}">
                                    @error('stock_threshold')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Stock minimum avant alerte</small>
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
                                            <i class="fas fa-check-circle text-success mr-1"></i> Produit actif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Image du produit</h6>
                            </div>
                            <div class="card-body">
                                @if($product->image)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->translated_name ?? 'Produit' }}" 
                                             class="img-fluid rounded border" 
                                             style="max-height: 150px;">
                                        <p class="text-muted small mt-1">Image actuelle</p>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="image" class="form-label fw-bold">Nouvelle image</label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               name="image" 
                                               id="image" 
                                               class="custom-file-input @error('image') is-invalid @enderror"
                                               accept="image/jpeg,image/png,image/jpg,image/gif">
                                        <label class="custom-file-label" for="image">
                                            <i class="fas fa-upload mr-2"></i> Choisir un fichier
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Formats: JPG, PNG, GIF. Max: 2Mo. Laissez vide pour conserver l'image actuelle.
                                    </small>
                                    @error('image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Aperçu de la nouvelle image -->
                                <div id="imagePreview" class="text-center mt-3" style="display: none;">
                                    <img src="#" alt="Aperçu" class="img-fluid rounded border" style="max-height: 150px;">
                                    <p class="text-muted small mt-1">Nouvelle image</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Boutons d'action responsives -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary w-100 w-sm-auto">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning w-100 w-sm-auto">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
    content: "Parcourir";
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
}

/* Classes utilitaires */
.w-sm-auto { width: auto; }
@media (min-width: 576px) {
    .w-sm-auto { width: auto; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = imagePreview ? imagePreview.querySelector('img') : null;

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
                let fileName = this.files.length ? this.files[0].name : 'Choisir un fichier';
                fileLabel.innerHTML = '<i class="fas fa-upload mr-2"></i> ' + fileName;
            });
        }
    }
});
</script>
@endpush
@endsection