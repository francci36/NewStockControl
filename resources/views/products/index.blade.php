@extends('layouts.app')

@section('title', 'Gestion des produits')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-box mr-2"></i> Gestion des produits
            </h1>
            <a href="{{ route('admin.products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Ajouter un produit
            </a>
        </div>

        <div class="px-6 py-4">
            <!-- Messages d'alerte -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg flex items-center" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg flex items-center" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Statistiques rapides -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Total produits</span>
                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">{{ $products->total() }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Prix moyen</span>
                    <p class="text-2xl font-bold text-green-700 dark:text-green-400">
                        {{ number_format($products->avg('price') ?? 0, 2, ',', ' ') }} €
                    </p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Fournisseurs</span>
                    <p class="text-2xl font-bold text-purple-700 dark:text-purple-400">
                        {{ $products->groupBy('supplier_id')->count() }}
                    </p>
                </div>
            </div>

            <!-- Filtres et recherche -->
            <div class="mb-6 flex flex-wrap gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Rechercher un produit..." 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">
                </div>
                <div>
                    <select id="supplierFilter" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">Tous les fournisseurs</option>
                        @foreach($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button id="filterBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                        <i class="fas fa-search mr-2"></i> Filtrer
                    </button>
                </div>
            </div>

            <!-- Tableau des produits -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm shadow-lg rounded-lg">
                    <thead class="bg-blue-50 dark:bg-blue-900">
                        <tr>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Image</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Nom</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Catégorie</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Prix</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Stock</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Statut</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-600">
                            <td class="border px-4 py-2">
                                @if($product->image)
                                    @php
                                        // Vérifier le type de chemin d'image
                                        $imagePath = $product->image;
                                        // Si le chemin contient déjà 'products/', on l'utilise tel quel
                                        if (str_contains($imagePath, 'products/')) {
                                            $imageUrl = asset('storage/' . $imagePath);
                                        } else {
                                            // Sinon, on suppose que c'est juste le nom du fichier
                                            $imageUrl = asset('storage/products/' . $imagePath);
                                        }
                                    @endphp
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-12 h-12 object-cover rounded"
                                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}'; this.classList.add('opacity-50');">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="border px-4 py-2 font-medium">{{ $product->name }}</td>
                            <td class="border px-4 py-2">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="border px-4 py-2 font-semibold text-blue-600 dark:text-blue-400">{{ number_format($product->price, 2) }} €</td>
                            <td class="border px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($product->quantity > 10) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($product->quantity > 0) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @endif">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td class="border px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($product->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200
                                    @endif">
                                    {{ $product->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="border px-4 py-2">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs flex items-center">
                                        <i class="fas fa-edit mr-1"></i> Modifier
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs flex items-center">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-gray-400 text-5xl mb-3"></i>
                                    <p class="text-gray-500 dark:text-gray-400 text-lg mb-3">Aucun produit trouvé</p>
                                    <a href="{{ route('admin.products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                        <i class="fas fa-plus mr-2"></i> Ajouter un produit
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="mt-6 flex justify-between items-center">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    Affichage de {{ $products->firstItem() }} à {{ $products->lastItem() }} sur {{ $products->total() }} produits
                </div>
                <div class="pagination-wrapper">
                    {{ $products->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Script pour les filtres -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const supplierFilter = document.getElementById('supplierFilter');
    const filterBtn = document.getElementById('filterBtn');
    
    filterBtn.addEventListener('click', function() {
        const search = searchInput.value;
        const supplier = supplierFilter.value;
        
        // Construire l'URL avec les paramètres de filtre
        let url = new URL(window.location.href);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (supplier) url.searchParams.set('supplier', supplier);
        else url.searchParams.delete('supplier');
        
        url.searchParams.set('page', 1); // Revenir à la première page
        
        window.location.href = url.toString();
    });
    
    // Appliquer les filtres avec Entrée dans le champ de recherche
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            filterBtn.click();
        }
    });
});
</script>

<style>
/* Styles pour la pagination si nécessaire */
.pagination-wrapper svg {
    width: 16px;
    height: 16px;
}
</style>
@endsection