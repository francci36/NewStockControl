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
                <table class="table-auto w-full bg-gray-50 dark:bg-gray-700 border-collapse border border-gray-200 dark:border-gray-600 text-sm shadow-lg rounded-lg">
                    <thead class="bg-blue-50 dark:bg-blue-900">
                        <tr>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Produit</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Description</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Prix</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Stock</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Fournisseur</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                            <td class="border px-4 py-2">
                                <div class="font-medium text-gray-800 dark:text-gray-200">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $product->id }}</div>
                            </td>
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                                {{ Str::limit($product->description, 50) ?? 'Aucune description' }}
                            </td>
                            <td class="border px-4 py-2">
                                <span class="font-semibold text-green-600 dark:text-green-400">
                                    {{ number_format($product->price, 2, ',', ' ') }} €
                                </span>
                            </td>
                            <td class="border px-4 py-2">
                                @if($product->quantity <= 0)
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100">
                                        Rupture
                                    </span>
                                @elseif($product->quantity <= ($product->stock_threshold ?? 5))
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100">
                                        {{ $product->quantity }} (bas)
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">
                                        {{ $product->quantity }}
                                    </span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                <div class="flex items-center">
                                    <i class="fas fa-truck text-gray-400 mr-2"></i>
                                    <span class="text-gray-800 dark:text-gray-200">
                                        {{ optional($product->supplier)->name ?? 'Non assigné' }}
                                    </span>
                                </div>
                            </td>
                            <td class="border px-4 py-2">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded flex items-center">
                                        <i class="fas fa-edit mr-1"></i> Modifier
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded flex items-center">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.orders.create', ['supplier_id' => $product->supplier_id]) }}" 
                                       class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded flex items-center">
                                        <i class="fas fa-shopping-cart mr-1"></i> Commander
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="border px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-lg">Aucun produit disponible</p>
                                    <a href="{{ route('admin.products.create') }}" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
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
                <div>
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
@endsection