@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-blue-600 dark:text-blue-400 flex items-center">
            <i class="fas fa-history mr-2"></i> Historique des transactions
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.transactions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Nouvelle transaction
            </a>
            <button onclick="exportTransactions()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                <i class="fas fa-download mr-2"></i> Exporter
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filtres et recherche -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Période</label>
            <select id="filterPeriod" class="form-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <option value="7">7 derniers jours</option>
                <option value="30" selected>30 derniers jours</option>
                <option value="90">90 derniers jours</option>
                <option value="365">Cette année</option>
                <option value="all">Tout</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
            <select id="filterType" class="form-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <option value="all">Tous les types</option>
                <option value="entry">Entrées uniquement</option>
                <option value="exit">Sorties uniquement</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Produit</label>
            <input type="text" id="searchProduct" placeholder="Rechercher un produit..." 
                   class="form-input w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
        </div>
        <div class="flex items-end">
            <button onclick="applyFilters()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg w-full">
                <i class="fas fa-search mr-2"></i> Filtrer
            </button>
        </div>
    </div>

    <!-- Résumé des transactions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
            <span class="text-sm text-gray-600 dark:text-gray-300">Total transactions</span>
            <p class="text-2xl font-bold text-blue-700 dark:text-blue-400" id="totalTransactions">{{ $transactions->total() }}</p>
        </div>
        <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
            <span class="text-sm text-gray-600 dark:text-gray-300">Entrées</span>
            <p class="text-2xl font-bold text-green-700 dark:text-green-400" id="totalEntries">
                {{ $transactions->where('type', 'entry')->count() }}
            </p>
        </div>
        <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
            <span class="text-sm text-gray-600 dark:text-gray-300">Sorties</span>
            <p class="text-2xl font-bold text-red-700 dark:text-red-400" id="totalExits">
                {{ $transactions->where('type', 'exit')->count() }}
            </p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
            <span class="text-sm text-gray-600 dark:text-gray-300">Valeur totale</span>
            <p class="text-2xl font-bold text-purple-700 dark:text-purple-400" id="totalValue">
                {{ number_format($transactions->sum('quantity') * $transactions->avg('price'), 0, ',', ' ') }} €
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-50 dark:bg-gray-700 border rounded-lg shadow-lg">
            <thead class="bg-blue-100 dark:bg-blue-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Quantité</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Prix Unitaire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Valeur Totale</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200 font-mono">
                        #{{ $transaction->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-800 dark:text-gray-200">
                            {{ $transaction->product->name ?? 'Produit supprimé' }}
                        </div>
                        @if($transaction->product && $transaction->product->supplier)
                        <div class="text-xs text-gray-500">
                            Fournisseur: {{ $transaction->product->supplier->name }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200">
                        {{ $transaction->quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200">
                        {{ number_format($transaction->price, 2, ',', ' ') }} €
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-800 dark:text-gray-200">
                        {{ number_format($transaction->total_value, 2, ',', ' ') }} €
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-sm rounded-full {{ $transaction->type_color }}">
                            <i class="fas {{ $transaction->type === 'entry' ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-1"></i>
                            {{ $transaction->type_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <i class="fas fa-user-circle text-gray-400 mr-2"></i>
                            <span class="text-gray-800 dark:text-gray-200">
                                {{ $transaction->user->name ?? 'Système' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200">
                        <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($transaction->order_id)
                        <a href="{{ route('admin.orders.show', $transaction->order_id) }}" 
                           class="text-blue-500 hover:text-blue-700 mr-2" 
                           title="Voir la commande associée">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                        @endif
                        <button onclick="showTransactionDetails({{ $transaction->id }})" 
                                class="text-green-500 hover:text-green-700"
                                title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-box-open text-4xl mb-4"></i>
                        <p class="text-lg">Aucune transaction enregistrée</p>
                        <a href="{{ route('admin.transactions.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            <i class="fas fa-plus mr-2"></i> Créer une transaction
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-700 dark:text-gray-300">
            Affichage de {{ $transactions->firstItem() ?? 0 }} à {{ $transactions->lastItem() ?? 0 }} sur {{ $transactions->total() }} transactions
        </div>
        <div>
            {{ $transactions->links() }}
        </div>
    </div>
</div>

<!-- Modal de détails de transaction -->
<div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Détails de la transaction
            </h3>
            <div id="transactionDetails" class="space-y-2">
                <!-- Les détails seront chargés dynamiquement -->
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
function applyFilters() {
    const period = document.getElementById('filterPeriod').value;
    const type = document.getElementById('filterType').value;
    const search = document.getElementById('searchProduct').value;
    
    // Construire l'URL avec les filtres
    let url = new URL(window.location.href);
    url.searchParams.set('period', period);
    url.searchParams.set('type', type);
    url.searchParams.set('search', search);
    url.searchParams.set('page', 1);
    
    window.location.href = url.toString();
}

function exportTransactions() {
    const period = document.getElementById('filterPeriod').value;
    const type = document.getElementById('filterType').value;
    
    window.location.href = `/admin/transactions/export?period=${period}&type=${type}`;
}

function showTransactionDetails(transactionId) {
    fetch(`/admin/transactions/${transactionId}`)
        .then(response => response.json())
        .then(data => {
            const details = `
                <p><strong>ID:</strong> #${data.id}</p>
                <p><strong>Produit:</strong> ${data.product_name}</p>
                <p><strong>Quantité:</strong> ${data.quantity}</p>
                <p><strong>Prix unitaire:</strong> ${data.price} €</p>
                <p><strong>Total:</strong> ${data.total} €</p>
                <p><strong>Type:</strong> ${data.type === 'entry' ? 'Entrée' : 'Sortie'}</p>
                <p><strong>Utilisateur:</strong> ${data.user_name}</p>
                <p><strong>Date:</strong> ${data.date}</p>
                ${data.order_id ? `<p><strong>Commande associée:</strong> #${data.order_id}</p>` : ''}
            `;
            document.getElementById('transactionDetails').innerHTML = details;
            document.getElementById('transactionModal').classList.remove('hidden');
        });
}

function closeModal() {
    document.getElementById('transactionModal').classList.add('hidden');
}

// Fermer le modal en cliquant dehors
window.onclick = function(event) {
    const modal = document.getElementById('transactionModal');
    if (event.target == modal) {
        closeModal();
    }
}

// Auto-refresh optionnel (toutes les 60 secondes)
let refreshInterval = setInterval(function() {
    if (document.visibilityState === 'visible') {
        location.reload();
    }
}, 60000);

// Nettoyer l'intervalle quand on quitte la page
window.addEventListener('beforeunload', function() {
    clearInterval(refreshInterval);
});
</script>

<style>
/* Animation pour le modal */
#transactionModal {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Style pour les lignes du tableau */
tbody tr {
    transition: background-color 0.2s;
}

/* Style pour la pagination */
.pagination {
    display: flex;
    gap: 0.5rem;
}

.pagination .page-item .page-link {
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    background-color: #f3f4f6;
    color: #374151;
    transition: all 0.2s;
}

.dark .pagination .page-item .page-link {
    background-color: #374151;
    color: #d1d5db;
}

.pagination .page-item.active .page-link {
    background-color: #3b82f6;
    color: white;
}

.pagination .page-item .page-link:hover {
    background-color: #60a5fa;
    color: white;
}
</style>
@endsection