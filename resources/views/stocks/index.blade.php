@extends('layouts.app')

@section('title', 'Gestion des stocks')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 mb-6 flex items-center">
        <i class="fas fa-boxes mr-2"></i> Niveaux de stock
    </h2>

    <!-- Résumé rapide -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
            <span class="text-sm text-gray-600 dark:text-gray-300">Total produits</span>
            <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">{{ $stocks->total() }}</p>
        </div>
        <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
            <span class="text-sm text-gray-600 dark:text-gray-300">Unités en stock</span>
            <p class="text-2xl font-bold text-green-700 dark:text-green-400">{{ $stocks->sum('quantity') }}</p>
        </div>
        <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
            <span class="text-sm text-gray-600 dark:text-gray-300">Stock faible</span>
            <p class="text-2xl font-bold text-red-700 dark:text-red-400">
                {{ $stocks->filter(function($stock) { 
                    return $stock->quantity <= $stock->product->stock_threshold; 
                })->count() }}
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full bg-gray-50 dark:bg-gray-700 border-collapse border border-gray-200 dark:border-gray-600 text-sm shadow-lg rounded-lg">
            <thead class="bg-blue-50 dark:bg-blue-900">
                <tr>
                    <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Produit</th>
                    <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Quantité en stock</th>
                    <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Seuil</th>
                    <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Statut</th>
                    <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Dernière mise à jour</th>
                    <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                @forelse($stocks as $stock)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                    <!-- Nom du produit -->
                    <td class="border px-4 py-2 text-gray-800 dark:text-gray-200 font-medium">
                        {{ $stock->product->name ?? 'Produit inconnu' }}
                        <span class="text-xs text-gray-500 block">
                            Fournisseur: {{ $stock->product->supplier->name ?? 'N/A' }}
                        </span>
                    </td>

                    <!-- Quantité en stock -->
                    <td class="border px-4 py-2">
                        <span class="font-semibold {{ $stock->quantity <= $stock->product->stock_threshold ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                            {{ $stock->quantity }}
                        </span>
                    </td>

                    <!-- Seuil de stock -->
                    <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                        {{ $stock->product->stock_threshold ?? 5 }}
                    </td>

                    <!-- Statut -->
                    <td class="border px-4 py-2">
                        @if($stock->quantity <= 0)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100">
                                Rupture
                            </span>
                        @elseif($stock->quantity <= $stock->product->stock_threshold)
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100">
                                Stock bas
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">
                                Normal
                            </span>
                        @endif
                    </td>

                    <!-- Dernière mise à jour -->
                    <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                        {{ $stock->updated_at ? $stock->updated_at->format('d/m/Y H:i') : 'Jamais' }}
                    </td>

                    <!-- Actions -->
                    <td class="border px-4 py-2">
                        <button onclick="openUpdateModal({{ $stock->product->id }}, '{{ $stock->product->name }}', {{ $stock->quantity }})" 
                                class="bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-1 px-2 rounded">
                            <i class="fas fa-edit"></i> Ajuster
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="border px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                        Aucun stock disponible.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-end">
        {{ $stocks->links() }}
    </div>
</div>

<!-- Modal d'ajustement de stock -->
<div id="updateStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Ajuster le stock
            </h3>
            <form id="updateStockForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Produit: <span id="modalProductName" class="font-normal"></span>
                    </label>
                    <input type="number" name="quantity" id="modalQuantity" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           min="0" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeUpdateModal()" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openUpdateModal(productId, productName, currentQuantity) {
    document.getElementById('updateStockModal').classList.remove('hidden');
    document.getElementById('modalProductName').textContent = productName;
    document.getElementById('modalQuantity').value = currentQuantity;
    document.getElementById('updateStockForm').action = `/stocks/${productId}`;
}

function closeUpdateModal() {
    document.getElementById('updateStockModal').classList.add('hidden');
}

// Fermer le modal en cliquant dehors
window.onclick = function(event) {
    const modal = document.getElementById('updateStockModal');
    if (event.target == modal) {
        closeUpdateModal();
    }
}
</script>
@endsection