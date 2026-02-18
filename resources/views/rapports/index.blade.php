@extends('layouts.app')

@section('title', 'Rapports et Analyses')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg">
            <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-chart-bar mr-2"></i> Rapports et Analyses
            </h3>
        </div>

        <!-- Contenu -->
        <div class="px-6 py-4">
            <!-- Messages de notification -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Première ligne d'indicateurs -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Chiffre d'affaires mensuel -->
                <div class="bg-gradient-to-br from-green-400 to-green-600 text-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Chiffre d'affaires</p>
                            <p class="text-2xl font-bold">{{ number_format($chiffreAffaires, 0, ',', ' ') }} €</p>
                            <p class="text-xs opacity-75">Ventes du mois</p>
                        </div>
                        <i class="fas fa-euro-sign text-3xl opacity-50"></i>
                    </div>
                </div>

                <!-- Total achats fournisseurs -->
                <div class="bg-gradient-to-br from-orange-400 to-orange-600 text-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Achats fournisseurs</p>
                            <p class="text-2xl font-bold">{{ number_format($totalAchats, 0, ',', ' ') }} €</p>
                            <p class="text-xs opacity-75">Approvisionnements</p>
                        </div>
                        <i class="fas fa-truck text-3xl opacity-50"></i>
                    </div>
                </div>

                <!-- Bénéfice estimé -->
                <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Bénéfice estimé</p>
                            <p class="text-2xl font-bold">{{ number_format($benefice, 0, ',', ' ') }} €</p>
                            <p class="text-xs opacity-75">Marge brute</p>
                        </div>
                        <i class="fas fa-chart-line text-3xl opacity-50"></i>
                    </div>
                </div>

                <!-- Produits en stock critique -->
                <div class="bg-gradient-to-br from-red-400 to-red-600 text-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Stock critique</p>
                            <p class="text-2xl font-bold">{{ $stockCritique }}</p>
                            <p class="text-xs opacity-75">Produits à risque</p>
                        </div>
                        <i class="fas fa-exclamation-triangle text-3xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Deuxième ligne de KPIs clients -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Clients actifs -->
                <div class="bg-gradient-to-br from-purple-400 to-purple-600 text-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Clients actifs</p>
                            <p class="text-2xl font-bold">{{ $clientsActifs }}</p>
                            <p class="text-xs opacity-75">Acheteurs enregistrés</p>
                        </div>
                        <i class="fas fa-users text-3xl opacity-50"></i>
                    </div>
                </div>

                <!-- Commandes clients -->
                <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 text-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Commandes clients</p>
                            <p class="text-2xl font-bold">{{ $nombreCommandesClients }}</p>
                            <p class="text-xs opacity-75">Depuis le début</p>
                        </div>
                        <i class="fas fa-shopping-bag text-3xl opacity-50"></i>
                    </div>
                </div>

                <!-- Panier moyen -->
                <div class="bg-gradient-to-br from-pink-400 to-pink-600 text-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Panier moyen</p>
                            <p class="text-2xl font-bold">{{ number_format($panierMoyen, 2, ',', ' ') }} €</p>
                            <p class="text-xs opacity-75">Par commande client</p>
                        </div>
                        <i class="fas fa-shopping-cart text-3xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <!-- Section des graphiques -->
            <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Graphique des commandes par statut -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-blue-500"></i>
                        Commandes par statut
                    </h4>
                    <div class="chart-container" style="position: relative; height: 250px;">
                        <canvas id="commandesParStatutChart"></canvas>
                    </div>
                    <!-- Légende supplémentaire -->
                    <div class="mt-4 grid grid-cols-3 gap-2 text-center text-sm">
                        @foreach($commandesParStatut as $statut)
                        <div class="p-2 rounded-lg {{ $statut->status === 'en attente' ? 'bg-yellow-100 dark:bg-yellow-800' : ($statut->status === 'arrivé' ? 'bg-green-100 dark:bg-green-800' : 'bg-red-100 dark:bg-red-800') }}">
                            <span class="font-medium">{{ ucfirst($statut->status) }}</span>
                            <span class="block text-lg font-bold">{{ $statut->count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Graphique des produits en stock -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-green-500"></i>
                        Top 10 produits en stock
                    </h4>
                    <div class="chart-container" style="position: relative; height: 250px;">
                        <canvas id="produitsEnStockChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Section des fournisseurs -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <i class="fas fa-truck mr-2 text-purple-500"></i>
                    Statistiques des fournisseurs
                </h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                        <thead class="bg-blue-50 dark:bg-blue-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Fournisseur</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Téléphone</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Nb commandes</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Montant total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse ($fournisseurs as $fournisseur)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-200">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-gray-200">
                                    {{ $fournisseur->name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $fournisseur->email }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $fournisseur->telephone }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                        {{ $fournisseur->orders_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-green-600 dark:text-green-400">
                                    {{ number_format($fournisseur->orders_sum_total_amount ?? 0, 2, ',', ' ') }} €
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-truck text-4xl mb-2"></i>
                                    <p>Aucun fournisseur trouvé</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section des rapports générés -->
            <div class="mt-8">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <i class="fas fa-file-alt mr-2 text-orange-500"></i>
                    Rapports générés
                </h4>
                
                @if($reports->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($reports as $report)
                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-lg transition duration-200">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h5 class="font-semibold text-blue-600 dark:text-blue-400 text-lg">{{ $report->name }}</h5>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ $report->description }}</p>
                                    
                                    <div class="grid grid-cols-2 gap-4 text-xs">
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">Transactions:</span>
                                            <span class="ml-1 font-semibold">{{ $report->transactions->count() }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">Stocks critiques:</span>
                                            <span class="ml-1 font-semibold">{{ $report->stocks->count() }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">Créé le:</span>
                                            <span class="ml-1 font-semibold">{{ $report->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">Statut:</span>
                                            <span class="ml-1 px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs">
                                                Actif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button class="text-gray-400 hover:text-blue-500 transition-colors" onclick="exportReport({{ $report->id }})">
                                    <i class="fas fa-download text-lg"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $reports->links() }}
                    </div>
                @else
                    <div class="bg-gray-50 dark:bg-gray-700 p-8 rounded-lg text-center">
                        <i class="fas fa-file-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400">Aucun rapport généré pour le moment.</p>
                        <button onclick="generateReports()" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            <i class="fas fa-sync-alt mr-2"></i> Générer les rapports
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration du mode sombre
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#d1d5db' : '#374151';
    const gridColor = isDarkMode ? '#4b5563' : '#e5e7eb';
    
    // Graphique des commandes par statut
    const commandesParStatutElement = document.getElementById('commandesParStatutChart');
    if (commandesParStatutElement) {
        const commandesParStatutCtx = commandesParStatutElement.getContext('2d');
        
        // Données PHP
        const commandesLabels = @json($commandesParStatut->pluck('status'));
        const commandesData = @json($commandesParStatut->pluck('count'));
        
        // Traduire les statuts
        const translatedLabels = commandesLabels.map(label => {
            const translations = {
                'en attente': 'En attente',
                'arrivé': 'Arrivé',
                'annulé': 'Annulé',
                'pending': 'En attente',
                'completed': 'Terminé',
                'cancelled': 'Annulé'
            };
            return translations[label] || label;
        });
        
        // Couleurs adaptées au mode
        const backgroundColors = isDarkMode ? 
            ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'] : 
            ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'];
        
        new Chart(commandesParStatutCtx, {
            type: 'pie',
            data: {
                labels: translatedLabels,
                datasets: [{
                    data: commandesData,
                    backgroundColor: backgroundColors,
                    borderColor: isDarkMode ? '#1f2937' : '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                        titleColor: textColor,
                        bodyColor: textColor,
                        borderColor: gridColor,
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} commandes (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Graphique des produits en stock
    const produitsEnStockElement = document.getElementById('produitsEnStockChart');
    if (produitsEnStockElement) {
        const produitsEnStockCtx = produitsEnStockElement.getContext('2d');
        
        // Données PHP
        const produitsLabels = @json($produitsEnStock->pluck('product.name'));
        const produitsQuantities = @json($produitsEnStock->pluck('quantity'));
        
        // Générer des couleurs basées sur les quantités
        const barColors = produitsQuantities.map(qty => {
            if (qty < 5) return isDarkMode ? '#ef4444' : '#f87171';
            if (qty < 10) return isDarkMode ? '#f59e0b' : '#fbbf24';
            return isDarkMode ? '#10b981' : '#34d399';
        });
        
        new Chart(produitsEnStockCtx, {
            type: 'bar',
            data: {
                labels: produitsLabels,
                datasets: [{
                    label: 'Quantité en stock',
                    data: produitsQuantities,
                    backgroundColor: barColors,
                    borderColor: isDarkMode ? '#374151' : '#9ca3af',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                        titleColor: textColor,
                        bodyColor: textColor,
                        borderColor: gridColor,
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return `Quantité: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                            drawBorder: false
                        },
                        ticks: {
                            color: textColor,
                            stepSize: 5
                        },
                        title: {
                            display: true,
                            text: 'Quantité',
                            color: textColor
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }
});

// Fonction pour exporter un rapport
function exportReport(reportId) {
    fetch(`/rapports/${reportId}/export`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `rapport_${reportId}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        a.remove();
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'export du rapport');
    });
}

// Fonction pour générer les rapports
function generateReports() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Génération...';
    btn.disabled = true;
    
    fetch('/rapports/generate', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la génération des rapports');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Rafraîchir les données périodiquement (optionnel)
let refreshInterval = setInterval(function() {
    if (document.visibilityState === 'visible') {
        fetch('/rapports/refresh-data', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.changed) {
                location.reload();
            }
        })
        .catch(() => {});
    }
}, 60000); // Vérifier toutes les minutes

// Nettoyer l'intervalle quand on quitte la page
window.addEventListener('beforeunload', function() {
    clearInterval(refreshInterval);
});
</script>

<!-- Styles supplémentaires -->
<style>
.chart-container {
    position: relative;
    width: 100%;
}

/* Animation pour les cartes */
.bg-gradient-to-br {
    transition: transform 0.2s;
}

.bg-gradient-to-br:hover {
    transform: translateY(-2px);
}

/* Style pour la pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
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