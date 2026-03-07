@extends('vendor.layouts.app')

@section('title', 'Modifier la catégorie')
@section('page-title', 'Modifier la catégorie')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('vendor.categories.index') }}">Catégories</a></li>
    <li class="breadcrumb-item active">Modifier</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-warning">
            <h3 class="card-title">
                <i class="fas fa-edit mr-2"></i>
                Modifier : <strong>{{ is_array($category->name) ? ($category->name['fr'] ?? '') : $category->name }}</strong>
            </h3>
        </div>
        
        <form action="{{ route('vendor.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Erreurs de validation :</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_fr" class="form-label fw-bold">
                                Nom (Français) <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name_fr" 
                                   id="name_fr" 
                                   class="form-control @error('name_fr') is-invalid @enderror" 
                                   value="{{ old('name_fr', is_array($category->name) ? ($category->name['fr'] ?? '') : $category->name) }}"
                                   required>
                            @error('name_fr')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_en" class="form-label fw-bold">
                                Nom (Anglais)
                            </label>
                            <input type="text" 
                                   name="name_en" 
                                   id="name_en" 
                                   class="form-control @error('name_en') is-invalid @enderror" 
                                   value="{{ old('name_en', is_array($category->name) ? ($category->name['en'] ?? '') : '') }}">
                            @error('name_en')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="slug" class="form-label fw-bold">Slug (URL)</label>
                            <input type="text" 
                                   name="slug" 
                                   id="slug" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   value="{{ old('slug', $category->slug) }}"
                                   readonly
                                   style="background-color: #f5f5f5;">
                            @error('slug')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Le slug ne peut pas être modifié après création</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3" 
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active" 
                               class="custom-control-input"
                               value="1"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">
                            <i class="fas fa-check-circle text-success mr-1"></i>
                            Catégorie active
                        </label>
                    </div>
                </div>

                @if($category->products_count > 0)
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>{{ $category->products_count }} produit(s)</strong> sont associés à cette catégorie.
                </div>
                @endif
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('vendor.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('name_fr').addEventListener('keyup', function() {
    // Prévisualisation du slug (mais ne le modifie pas car il est en lecture seule)
    let slugPreview = document.createElement('small');
    slugPreview.className = 'text-muted d-block mt-1';
    slugPreview.innerHTML = 'Slug généré: ' + this.value.toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '');
    
    let existingPreview = document.getElementById('slug-preview');
    if (existingPreview) existingPreview.remove();
    
    slugPreview.id = 'slug-preview';
    document.getElementById('slug').parentNode.appendChild(slugPreview);
});
</script>

<style>
.bg-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
    color: white;
}
.form-label {
    font-weight: 600;
    color: #495057;
}
</style>
@endsection