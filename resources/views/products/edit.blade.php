@extends('layouts.app')

@section('title', 'Modifier le produit')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-edit mr-2"></i> Modifier le produit : {{ $product->name }}
            </h1>
        </div>

        <!-- Formulaire -->
        <div class="px-6 py-4">
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-gray-700 dark:text-gray-200 font-medium mb-2">
                            Nom du produit <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $product->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                    </div>

                    <!-- Fournisseur -->
                    <div>
                        <label for="supplier_id" class="block text-gray-700 dark:text-gray-200 font-medium mb-2">
                            Fournisseur <span class="text-red-500">*</span>
                        </label>
                        <select name="supplier_id" 
                                id="supplier_id" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200"
                                required>
                            <option value="">Sélectionnez un fournisseur</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Prix -->
                    <div>
                        <label for="price" class="block text-gray-700 dark:text-gray-200 font-medium mb-2">
                            Prix (€) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="price" 
                               id="price" 
                               value="{{ old('price', $product->price) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                    </div>

                    <!-- Quantité -->
                    <div>
                        <label for="quantity" class="block text-gray-700 dark:text-gray-200 font-medium mb-2">
                            Quantité en stock <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="quantity" 
                               id="quantity" 
                               value="{{ old('quantity', $product->quantity) }}"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200"
                               required>
                    </div>

                    <!-- Seuil d'alerte -->
                    <div>
                        <label for="stock_threshold" class="block text-gray-700 dark:text-gray-200 font-medium mb-2">
                            Seuil d'alerte
                        </label>
                        <input type="number" 
                               name="stock_threshold" 
                               id="stock_threshold" 
                               value="{{ old('stock_threshold', $product->stock_threshold) }}"
                               min="1"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200"
                               placeholder="5 (par défaut)">
                        <p class="text-xs text-gray-500 mt-1">Stock minimum avant alerte</p>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-gray-700 dark:text-gray-200 font-medium mb-2">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('products.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection