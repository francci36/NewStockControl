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
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">Statut</label>
                    <p class="text-gray-800 dark:text-gray-200">
                        <span class="px-2 py-1 text-sm rounded-full {{ $order->status === 'en attente' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100' : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' }}">
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
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-medium">Mode de paiement</label>
                        <p class="text-gray-800 dark:text-gray-200">
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
                                    <div class="font-medium">{{ $product->name }}</div>
                                    @if($product->supplier)
                                    <div class="text-xs text-gray-500">Fournisseur: {{ $product->supplier->name }}</div>
                                    @endif
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

            <!-- Actions sur la commande -->
            <div class="flex flex-wrap gap-4 mt-8">
                <!-- Bouton de changement de statut (visible seulement si commande en attente) -->
                @if($order->status == 'en attente')
                <div>
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="arrivé">
                        
                        @if($order->type == 'client')
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg flex items-center"
                                onclick="return confirm('Confirmer la livraison de cette commande client ?\n\nLe stock des produits sera DIMINUÉ.')">
                            <i class="fas fa-truck mr-2"></i> Marquer comme livré
                        </button>
                        @else
                        <button type="submit" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg flex items-center"
                                onclick="return confirm('Confirmer la réception de cette commande fournisseur ?\n\nLe stock des produits sera AUGMENTÉ.')">
                            <i class="fas fa-check-circle mr-2"></i> Marquer comme reçu
                        </button>
                        @endif
                    </form>
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
@endsection