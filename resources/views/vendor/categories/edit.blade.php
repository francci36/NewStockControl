@extends('vendor.layouts.app')

@section('title', 'Modifier la sous-catégorie')
@section('page-title', 'Modifier la sous-catégorie')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-warning text-white">
            <h3 class="card-title">
                <i class="fas fa-edit mr-2"></i>
                Modifier : {{ $category->translated_name }}
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

                <!-- Catégorie principale -->
                <div class="form-group mb-3">
                    <label for="main_category" class="form-label fw-bold">
                        Catégorie principale <span class="text-danger">*</span>
                    </label>
                    <select name="main_category" id="main_category" class="form-control" required>
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($mainCategories as $cat)
                            <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->translated_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nom de la sous-catégorie -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label fw-bold">
                        Nom de la sous-catégorie <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $category->name) }}"
                           required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <hr>

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
@endsection