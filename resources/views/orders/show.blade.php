@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-info-circle mr-2"></i> Détails de la commande #{{ $order->id }}
            </h1>
        </div>

        <!-- Détails de la commande -->
        <div class="px-6 py-4 space-y-6">
            <!-- Informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Fournisseur ou Client selon le type -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">
                        @if($order->type == 'client')
                            Client
                        @else
                            Fournisseur
                        @endif
                    </label>
                    <p class="text-gray-800 dark:text-gray-200">
                        @if($order->type == 'client')
                            {{ $order->client->name ?? 'Client inconnu' }}
                        @else
                            {{ $order->supplier->name ?? 'Fournisseur inconnu' }}
                        @endif
                    </p>
                </div>
                
                <div>
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">Date de la commande</label>
                    <p class="text-gray-800 dark:text-gray-200">{{ $order->date->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">Statut actuel</label>
                    <p class="text-gray-800 dark:text-gray-200">
                        <span class="px-2 py-1 text-sm rounded-full 
                            @if($order->status == 'en attente') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                            @elseif($order->status == 'en traitement') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                            @elseif($order->status == 'expédiée') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100
                            @elseif($order->status == 'livrée') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                            @elseif($order->status == 'arrivé') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                            @elseif($order->status == 'annulé') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
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
                    </p>
                </div>
                
                <div>
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">
                        @if($order->type == 'client')
                            Acheteur
                        @else
                            Utilisateur
                        @endif
                    </label>
                    <p class="text-gray-800 dark:text-gray-200">{{ $order->user->name ?? 'Inconnu' }}</p>
                </div>

                <!-- Montant total -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">Montant total</label>
                    <p class="text-gray-800 dark:text-gray-200 font-semibold text-green-600">{{ number_format($order->total_amount, 2) }} €</p>
                </div>

                <!-- Méthode de paiement -->
                <div>
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">Mode de paiement</label>
                    <p class="text-gray-800 dark:text-gray-200">
                        @if($order->payment)
                            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> Payé</span><br>
                        @endif
                        @switch($order->payment_method)
                            @case('card')
                                <i class="fas fa-credit-card mr-1"></i> Carte bancaire
                                @break
                            @case('cash')
                                <i class="fas fa-money-bill mr-1"></i> Paiement à la livraison
                                @break
                            @case('bank_transfer')
                                <i class="fas fa-university mr-1"></i> Virement bancaire
                                @break
                            @default
                                {{ $order->payment_method }}
                        @endswitch
                    </p>
                </div>
            </div>

            <!-- Informations de livraison pour les commandes clients -->
            @if($order->type == 'client' && $order->shipping_name)
            <div class="mt-6">
                <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-400 mb-4 flex items-center">
                    <i class="fas fa-truck mr-2"></i> Informations de livraison
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium">Nom du destinataire</label>
                        <p class="text-gray-800 dark:text-gray-200">{{ $order->shipping_name }}</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium">Téléphone</label>
                        <p class="text-gray-800 dark:text-gray-200">{{ $order->shipping_phone }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 dark:text-gray-300 font-medium">Adresse complète</label>
                        <p class="text-gray-800 dark:text-gray-200">{{ $order->shipping_address }}</p>
                        <p class="text-gray-800 dark:text-gray-200">{{ $order->shipping_zip }} {{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Barre de progression pour les commandes clients -->
            @if($order->type == 'client' && $order->status != 'annulé' && $order->status != 'arrivé')
            @php
                $progressSteps = ['en attente', 'en traitement', 'expédiée', 'livrée'];
                $currentStep = array_search($order->status, $progressSteps);
                $progress = ($currentStep !== false) ? (($currentStep + 1) / count($progressSteps)) * 100 : 0;
            @endphp
            <div class="mt-6">
                <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-400 mb-4 flex items-center">
                    <i class="fas fa-chart-line mr-2"></i> Progression
                </h3>
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                             role="progressbar" 
                             style="width: {{ $progress }}%;" 
                             aria-valuenow="{{ $progress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ round($progress) }}%
                        </div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mt-2">
                        <span class="{{ $order->status == 'en attente' ? 'font-bold text-blue-600' : '' }}">✓ En attente</span>
                        <span class="{{ $order->status == 'en traitement' ? 'font-bold text-blue-600' : '' }}">⚙️ En traitement</span>
                        <span class="{{ $order->status == 'expédiée' ? 'font-bold text-blue-600' : '' }}">🚚 Expédiée</span>
                        <span class="{{ $order->status == 'livrée' ? 'font-bold text-blue-600' : '' }}">✅ Livrée</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Liste des produits commandés -->
            <div class="mt-6">
                <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-400 mb-4 flex items-center">
                    <i class="fas fa-boxes mr-2"></i> Produits commandés
                </h3>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-200 dark:border-gray-600 text-sm">
                        <thead class="bg-blue-50 dark:bg-blue-900">
                            <tr>
                                <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Produit</th>
                                <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Quantité</th>
                                <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Prix unitaire</th>
                                <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach ($order->products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                                    <div class="flex items-center">
                                        @if($product->image)
                                            @php
                                                $imagePath = str_contains($product->image, 'products/') 
                                                    ? $product->image 
                                                    : 'products/' . $product->image;
                                            @endphp
                                            <img src="{{ asset('storage/' . $imagePath) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="w-8 h-8 object-cover rounded mr-2"
                                                 onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';">
                                        @endif
                                        <div>
                                            <div class="font-medium">{{ $product->name }}</div>
                                            @if($product->category)
                                                <div class="text-xs text-gray-500">{{ $product->category->name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200 text-center">{{ $product->pivot->quantity }}</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ number_format($product->price, 2) }} €</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200 font-medium">{{ number_format($product->price * $product->pivot->quantity, 2) }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                                <td colspan="3" class="border px-4 py-2 text-right text-gray-700 dark:text-gray-200">Total TTC :</td>
                                <td class="border px-4 py-2 text-left text-green-600 dark:text-green-400">{{ number_format($order->total_amount, 2) }} €</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Historique des statuts -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-400 mb-4 flex items-center">
                    <i class="fas fa-history mr-2"></i> Historique de la commande
                </h3>
                
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    @forelse($order->statusHistory as $history)
                    <div class="flex items-start mb-4 last:mb-0">
                        <div class="flex-shrink-0 mr-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full 
                                @if($history->status == 'en attente') bg-yellow-500
                                @elseif($history->status == 'en traitement') bg-blue-500
                                @elseif($history->status == 'expédiée') bg-purple-500
                                @elseif($history->status == 'livrée') bg-green-500
                                @elseif($history->status == 'arrivé') bg-green-500
                                @elseif($history->status == 'annulé') bg-red-500
                                @else bg-gray-500
                                @endif text-white">
                                <i class="fas 
                                    @if($history->status == 'en attente') fa-clock
                                    @elseif($history->status == 'en traitement') fa-cog
                                    @elseif($history->status == 'expédiée') fa-truck
                                    @elseif($history->status == 'livrée') fa-check-circle
                                    @elseif($history->status == 'arrivé') fa-check-circle
                                    @elseif($history->status == 'annulé') fa-times-circle
                                    @else fa-question-circle
                                    @endif"></i>
                            </span>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ ucfirst($history->status) }}
                                    </p>
                                    @if($history->comment)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                        <i class="fas fa-comment mr-1"></i> {{ $history->comment }}
                                    </p>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $history->formatted_date ?? $history->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            @if($history->user)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Par: {{ $history->user->name }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Aucun historique disponible pour cette commande.
                    </p>
                    @endforelse
                </div>
            </div>

            <!-- Actions sur la commande -->
            <div class="flex flex-wrap gap-4 mt-8">
                <!-- Bouton de changement de statut pour les commandes clients -->
                @if($order->type == 'client' && $order->status != 'livrée' && $order->status != 'annulé')
                <div>
                    <button onclick="openStatusModal()" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i> Mettre à jour le statut
                    </button>
                </div>
                @endif

                <!-- Bouton de changement de statut pour les commandes fournisseurs -->
                @if($order->type == 'supplier' && $order->status == 'en attente')
                <div>
                    <button onclick="openSupplierStatusModal()" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg flex items-center">
                        <i class="fas fa-truck mr-2"></i> Marquer comme reçu
                    </button>
                </div>
                @endif

                <!-- Bouton de modification (seulement pour commandes fournisseurs en attente) -->
                @if($order->type == 'supplier' && $order->status == 'en attente')
                <div>
                    <a href="{{ route('admin.orders.edit', $order->id) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg flex items-center">
                        <i class="fas fa-edit mr-2"></i> Modifier la commande
                    </a>
                </div>
                @endif

                <!-- Bouton de suppression (seulement pour commandes fournisseurs en attente) -->
                @if($order->type == 'supplier' && $order->status == 'en attente')
                <div>
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande fournisseur ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg flex items-center">
                            <i class="fas fa-trash mr-2"></i> Supprimer
                        </button>
                    </form>
                </div>
                @endif

                <!-- Bouton de retour -->
                <div class="ml-auto">
                    <a href="{{ route('admin.orders.index', ['type' => $order->type]) }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Retour aux commandes {{ $order->type == 'client' ? 'clients' : 'fournisseurs' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de mise à jour du statut (Commandes clients) -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-sync-alt mr-2 text-blue-500"></i>
                Mettre à jour le statut
            </h3>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Nouveau statut
                    </label>
                    <select name="status" class="form-select w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200">
                        <option value="en traitement" {{ $order->status == 'en traitement' ? 'selected' : '' }}>En traitement</option>
                        <option value="expédiée" {{ $order->status == 'expédiée' ? 'selected' : '' }}>Expédiée</option>
                        <option value="livrée" {{ $order->status == 'livrée' ? 'selected' : '' }}>Livrée</option>
                        <option value="annulé" {{ $order->status == 'annulé' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Commentaire (optionnel)
                    </label>
                    <textarea name="comment" rows="3" 
                              class="form-textarea w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"
                              placeholder="Ajoutez un commentaire sur ce changement..."></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeStatusModal()" 
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

<!-- Modal de réception (Commandes fournisseurs) -->
<div id="supplierStatusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                <i class="fas fa-truck mr-2 text-green-500"></i>
                Confirmer la réception
            </h3>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="arrivé">
                
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Commentaire (optionnel)
                    </label>
                    <textarea name="comment" rows="3" 
                              class="form-textarea w-full border rounded-lg p-2 dark:bg-gray-700 dark:text-gray-200"
                              placeholder="Ajoutez un commentaire..."></textarea>
                </div>

                <div class="bg-yellow-100 dark:bg-yellow-900 border-l-4 border-yellow-500 text-yellow-700 dark:text-yellow-100 p-4 mb-4">
                    <p class="text-sm">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Cette action augmentera le stock des produits concernés.
                    </p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeSupplierStatusModal()" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Confirmer la réception
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal() {
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function openSupplierStatusModal() {
    document.getElementById('supplierStatusModal').classList.remove('hidden');
}

function closeSupplierStatusModal() {
    document.getElementById('supplierStatusModal').classList.add('hidden');
}

// Fermer les modals en cliquant dehors
window.onclick = function(event) {
    const clientModal = document.getElementById('statusModal');
    const supplierModal = document.getElementById('supplierStatusModal');
    
    if (event.target == clientModal) {
        closeStatusModal();
    }
    if (event.target == supplierModal) {
        closeSupplierStatusModal();
    }
}
</script>

<style>
/* Animation pour les modals */
#statusModal, #supplierStatusModal {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Style pour la timeline */
.flex.items-start {
    transition: transform 0.2s;
}

.flex.items-start:hover {
    transform: translateX(5px);
}

/* Style pour la barre de progression */
.progress {
    background-color: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.dark .progress {
    background-color: #4a4a6a;
}

.progress-bar {
    transition: width 0.6s ease;
}
</style>
@endsection