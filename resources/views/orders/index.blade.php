{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Liste des commandes')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-list-alt mr-2"></i> Gestion des commandes
            </h1>
        </div>

        <!-- Tabs pour filtrer -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="nav nav-tabs flex space-x-4">
                <li class="nav-item">
                    <a class="px-4 py-2 rounded-lg {{ !request('type') || request('type') == 'supplier' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} transition duration-200" 
                       href="{{ route('admin.orders.index', ['type' => 'supplier']) }}">
                        <i class="fas fa-truck mr-2"></i> Commandes fournisseurs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="px-4 py-2 rounded-lg {{ request('type') == 'client' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} transition duration-200" 
                       href="{{ route('admin.orders.index', ['type' => 'client']) }}">
                        <i class="fas fa-user mr-2"></i> Commandes clients
                    </a>
                </li>
            </ul>
        </div>

        <!-- Statistiques rapides -->
        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                <span class="text-sm text-gray-600 dark:text-gray-300">Total commandes</span>
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">{{ $orders->total() }}</p>
            </div>
            <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                <span class="text-sm text-gray-600 dark:text-gray-300">Total montant</span>
                <p class="text-2xl font-bold text-green-700 dark:text-green-400">
                    {{ number_format($orders->sum('total_amount') ?? 0, 2) }} €
                </p>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                <span class="text-sm text-gray-600 dark:text-gray-300">En attente</span>
                <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-400">
                    {{ $orders->where('status', 'en attente')->count() }}
                </p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="px-6 py-4 flex gap-4">
            <input type="text" id="searchInput" placeholder="Rechercher par ID ou fournisseur..." 
                   class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200">
                <option value="">Tous les statuts</option>
                <option value="en attente">En attente</option>
                <option value="en traitement">En traitement</option>
                <option value="expédiée">Expédiée</option>
                <option value="livrée">Livrée</option>
                <option value="arrivé">Arrivé</option>
                <option value="annulé">Annulé</option>
            </select>
            <button id="filterBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-search mr-2"></i> Filtrer
            </button>
        </div>

        <!-- Tableau des commandes -->
        <div class="px-6 py-4">
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-200 dark:border-gray-600 text-sm">
                    <thead class="bg-blue-50 dark:bg-blue-900">
                        <tr>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">ID</th>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">
                                {{ request('type') == 'client' ? 'Client' : 'Fournisseur' }}
                            </th>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Date</th>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Total</th>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Statut</th>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">#{{ $order->id }}</td>
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                                @if($order->type == 'client')
                                    {{ $order->client->name ?? 'Client inconnu' }}
                                @else
                                    <div class="flex items-center">
                                        @if($order->supplier && $order->supplier->logo)
                                            <img src="{{ asset('storage/' . $order->supplier->logo) }}" 
                                                 alt="{{ $order->supplier->name }}" 
                                                 class="w-6 h-6 object-cover rounded-full mr-2">
                                        @endif
                                        {{ $order->supplier->name ?? 'Fournisseur inconnu' }}
                                    </div>
                                @endif
                            </td>
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ $order->date->format('d/m/Y') }}</td>
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200 font-semibold text-green-600">{{ number_format($order->total_amount, 2) }} €</td>
                            <td class="border px-4 py-2">
                                <span class="px-2 py-1 text-sm rounded-full inline-flex items-center
                                    @if($order->status == 'en attente') bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100
                                    @elseif($order->status == 'en traitement') bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-100
                                    @elseif($order->status == 'expédiée') bg-purple-100 dark:bg-purple-800 text-purple-800 dark:text-purple-100
                                    @elseif($order->status == 'livrée') bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100
                                    @elseif($order->status == 'arrivé') bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100
                                    @elseif($order->status == 'annulé') bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                    @endif">
                                    <i class="fas 
                                        @if($order->status == 'en attente') fa-clock
                                        @elseif($order->status == 'en traitement') fa-cog
                                        @elseif($order->status == 'expédiée') fa-truck
                                        @elseif($order->status == 'livrée') fa-check-circle
                                        @elseif($order->status == 'arrivé') fa-check-circle
                                        @elseif($order->status == 'annulé') fa-times-circle
                                        @else fa-question-circle
                                        @endif mr-1"></i>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="border px-4 py-2">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded-lg inline-flex items-center">
                                        <i class="fas fa-eye mr-1"></i> Voir
                                    </a>

                                    @if($order->type == 'supplier' && $order->status == 'en attente')
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="arrivé">
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded-lg inline-flex items-center"
                                                onclick="return confirm('Confirmer la réception de cette commande fournisseur ? Le stock sera augmenté.')">
                                            <i class="fas fa-check mr-1"></i> Réceptionner
                                        </button>
                                    </form>
                                    @endif

                                    @if($order->type == 'supplier' && $order->status == 'en attente')
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded-lg inline-flex items-center" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4">
            <div class="flex justify-end">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Script pour les filtres -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const filterBtn = document.getElementById('filterBtn');
    
    filterBtn.addEventListener('click', function() {
        const search = searchInput.value;
        const status = statusFilter.value;
        
        let url = new URL(window.location.href);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');
        
        url.searchParams.set('page', 1);
        
        window.location.href = url.toString();
    });
    
    // Appliquer les filtres avec Entrée
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            filterBtn.click();
        }
    });
});
</script>

<style>
/* Style pour la pagination */
.pagination svg {
    width: 16px;
    height: 16px;
}

/* Animation pour les lignes du tableau */
tr {
    transition: background-color 0.2s;
}

/* Mode sombre */
.dark .bg-blue-50 {
    background-color: #1e3a5f !important;
}
</style>
@endsection