@extends('layouts.app')

@section('title', 'Modifier le produit')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-yellow-100 dark:bg-yellow-900 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-yellow-700 dark:text-yellow-400 flex items-center">
                <i class="fas fa-edit mr-2"></i> Modifier le produit : {{ $product->name }}
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

            <!-- Formulaire de modification -->
            <form action="{{ route('admin.products.update', $product) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom du produit -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nom du produit <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $product->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-300 dark:focus:ring-yellow-500 dark:bg-gray-700 dark:text-gray-200 @error('name') border-red-500 @enderror"
                               required>
                    </div>

                    <!-- Prix -->
                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Prix (€) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="price" 
                               id="price" 
                               value="{{ old('price', $product->price) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-300 dark:focus:ring-yellow-500 dark:bg-gray-700 dark:text-gray-200 @error('price') border-red-500 @enderror"
                               required>
                    </div>

                    <!-- Quantité -->
                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Quantité en stock <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="quantity" 
                               id="quantity" 
                               value="{{ old('quantity', $product->quantity) }}"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-300 dark:focus:ring-yellow-500 dark:bg-gray-700 dark:text-gray-200 @error('quantity') border-red-500 @enderror"
                               required>
                    </div>

                    <!-- Seuil de stock -->
                    <div class="mb-4">
                        <label for="stock_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Seuil d'alerte stock
                        </label>
                        <input type="number" 
                               name="stock_threshold" 
                               id="stock_threshold" 
                               value="{{ old('stock_threshold', $product->stock_threshold) }}"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-300 dark:focus:ring-yellow-500 dark:bg-gray-700 dark:text-gray-200">
                    </div>

                    <!-- Catégorie -->
                    <div class="mb-4">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catégorie
                        </label>
                        <select name="category_id" 
                                id="category_id" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-300 dark:focus:ring-yellow-500 dark:bg-gray-700 dark:text-gray-200">
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fournisseur -->
                    <div class="mb-4">
                        <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fournisseur
                        </label>
                        <select name="supplier_id" 
                                id="supplier_id" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-300 dark:focus:ring-yellow-500 dark:bg-gray-700 dark:text-gray-200">
                            <option value="">Sélectionner un fournisseur</option>
                            @foreach($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->id }}" 
                                    {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Statut actif -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Statut
                        </label>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                Produit actif
                            </label>
                        </div>
                    </div>

                    <!-- Image actuelle -->
                    <div class="mb-4 col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Image actuelle
                        </label>
                        @if($product->image)
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="h-20 w-20 object-cover rounded border">
                                <span class="text-sm text-gray-500">{{ $product->image }}</span>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Aucune image</p>
                        @endif
                    </div>

                    <!-- Nouvelle image -->
                    <div class="mb-4 col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nouvelle image (optionnel)
                        </label>
                        <input type="file" 
                               name="image" 
                               id="image" 
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-300 dark:focus:ring-yellow-500 dark:bg-gray-700 dark:text-gray-200">
                        <p class="text-xs text-gray-500 mt-1">Laissez vide pour conserver l'image actuelle</p>
                    </div>

                    <!-- Description courte -->
                    <div class="mb-4 col-span-2">
                        <label for="short_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description courte
                        </label>
                        <input type="text" 
                               name="short_description" 
                               id="short_description" 
                               value="{{ old('short_description', $product->short_description) }}"
                               maxlength="255"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-300 dark:focus:ring-yellow-500 dark:bg-gray-700 dark:text-gray-200">
                    </div>

                    <!-- Description complète -->
                    <div class="mb-4 col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description détaillée
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="5"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-300 dark:focus:ring-yellow-500 dark:bg-gray-700 dark:text-gray-200">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('admin.products.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" 
                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection