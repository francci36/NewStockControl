@extends('layouts.app')

@section('title', 'Modifier le produit')

@section('content')
<div class="container-fluid py-6">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-warning text-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier le produit : <strong>{{ $product->name }}</strong>
                </h5>
                <small class="text-white-50">ID: {{ $product->id }} | SKU: {{ $product->sku ?? 'Non défini' }}</small>
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

            <!-- Formulaire de modification -->
            <form action="{{ route('admin.products.update', $product) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Colonne gauche -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informations générales</h6>
                            </div>
                            <div class="card-body">
                                <!-- Nom du produit -->
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Nom du produit <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name', $product->name) }}"
                                           class="form-control @error('name') is-invalid @enderror"
                                           required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Prix -->
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label">Prix (€) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" 
                                               name="price" 
                                               id="price" 
                                               value="{{ old('price', $product->price) }}"
                                               step="0.01"
                                               min="0"
                                               class="form-control @error('price') is-invalid @enderror"
                                               required>
                                        <span class="input-group-text">€</span>
                                    </div>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Quantité -->
                                <div class="form-group mb-3">
                                    <label for="quantity" class="form-label">Quantité en stock <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="quantity" 
                                           id="quantity" 
                                           value="{{ old('quantity', $product->quantity) }}"
                                           min="0"
                                           class="form-control @error('quantity') is-invalid @enderror"
                                           required>
                                    @error('quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Seuil de stock -->
                                <div class="form-group mb-3">
                                    <label for="stock_threshold" class="form-label">Seuil d'alerte stock</label>
                                    <input type="number" 
                                           name="stock_threshold" 
                                           id="stock_threshold" 
                                           value="{{ old('stock_threshold', $product->stock_threshold ?? 5) }}"
                                           min="0"
                                           class="form-control @error('stock_threshold') is-invalid @enderror">
                                    <small class="text-muted">Stock minimum avant alerte</small>
                                    @error('stock_threshold')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-tags mr-2"></i>Classification</h6>
                            </div>
                            <div class="card-body">
                                <!-- Catégorie -->
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">Catégorie</label>
                                    <select name="category_id" 
                                            id="category_id" 
                                            class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories ?? [] as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Fournisseur -->
                                <div class="form-group mb-3">
                                    <label for="supplier_id" class="form-label">Fournisseur</label>
                                    <select name="supplier_id" 
                                            id="supplier_id" 
                                            class="form-control @error('supplier_id') is-invalid @enderror">
                                        <option value="">Sélectionner un fournisseur</option>
                                        @foreach($suppliers ?? [] as $supplier)
                                            <option value="{{ $supplier->id }}" 
                                                {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Statut actif -->
                                <div class="form-group mb-3">
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
                    </div>

                    <!-- Colonne droite -->
                    <div class="col-md-6">
                        <!-- Image actuelle -->
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-image mr-2"></i>Image du produit</h6>
                            </div>
                            <div class="card-body">
                                @if($product->image)
                                    <div class="text-center mb-3">
                                        @php
                                            $imagePath = str_contains($product->image, 'products/') 
                                                ? $product->image 
                                                : 'products/' . $product->image;
                                        @endphp
                                        <img src="{{ asset('storage/' . $imagePath) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-fluid rounded border" 
                                             style="max-height: 200px; object-fit: contain;"
                                             onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';">
                                        <p class="text-muted mt-2">
                                            <i class="fas fa-info-circle mr-1"></i> 
                                            Image actuelle: {{ basename($product->image) }}
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center mb-3 p-4 bg-light rounded">
                                        <i class="fas fa-image fa-4x text-muted mb-2"></i>
                                        <p class="text-muted">Aucune image disponible</p>
                                    </div>
                                @endif

                                <!-- Nouvelle image -->
                                <div class="form-group mb-3">
                                    <label for="image" class="form-label">Nouvelle image (optionnel)</label>
                                    <input type="file" 
                                           name="image" 
                                           id="image" 
                                           accept="image/*"
                                           class="form-control @error('image') is-invalid @enderror">
                                    <small class="text-muted">Laissez vide pour conserver l'image actuelle. Formats: JPG, PNG, GIF. Max: 2Mo</small>
                                    @error('image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Aperçu de la nouvelle image -->
                                <div id="imagePreview" class="text-center mt-3" style="display: none;">
                                    <img src="#" alt="Aperçu" class="img-fluid rounded border" style="max-height: 150px;">
                                    <p class="text-muted mt-1">Nouvelle image</p>
                                </div>
                            </div>
                        </div>

                        <!-- Descriptions -->
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-align-left mr-2"></i>Descriptions</h6>
                            </div>
                            <div class="card-body">
                                <!-- Description courte -->
                                <div class="form-group mb-3">
                                    <label for="short_description" class="form-label">Description courte</label>
                                    <input type="text" 
                                           name="short_description" 
                                           id="short_description" 
                                           value="{{ old('short_description', $product->short_description) }}"
                                           maxlength="255"
                                           class="form-control @error('short_description') is-invalid @enderror"
                                           placeholder="Brève description du produit">
                                    @error('short_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Description complète -->
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description détaillée</label>
                                    <textarea name="description" 
                                              id="description" 
                                              rows="6"
                                              class="form-control @error('description') is-invalid @enderror"
                                              placeholder="Description complète du produit...">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Date de création</small>
                                        <strong>{{ $product->created_at->format('d/m/Y H:i') }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Dernière modification</small>
                                        <strong>{{ $product->updated_at->format('d/m/Y H:i') }}</strong>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Les champs marqués d'un <span class="text-danger">*</span> sont obligatoires
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script pour l'aperçu de l'image -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = imagePreview.querySelector('img');

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
});
</script>

<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
@media (max-width: 768px) {
    .bg-gradient-warning {
        flex-direction: column;
        text-align: center;
    }
    .bg-gradient-warning .btn {
        margin-top: 10px;
        width: 100%;
    }
    .row .col-md-6 {
        margin-bottom: 15px;
    }
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
    .d-flex.justify-content-between .btn {
        width: 100%;
    }
}
</style>
@endsection