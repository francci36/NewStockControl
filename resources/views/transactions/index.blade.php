@extends('layouts.app')

@section('title', 'Gestion des transactions')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0">
        <!-- Header -->
        <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <h5 class="mb-0">
                    <i class="fas fa-history mr-2"></i> Historique des transactions
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.transactions.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus mr-2"></i> <span class="d-none d-sm-inline">Nouvelle transaction</span>
                        <span class="d-sm-none">Nouvelle</span>
                    </a>
                    <button onclick="exportTransactions()" class="btn btn-success btn-sm">
                        <i class="fas fa-download mr-2"></i> <span class="d-none d-sm-inline">Exporter</span>
                        <span class="d-sm-none">Export</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filtres et recherche -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-filter mr-2"></i> Filtres</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="filterPeriod" class="form-label">Période</label>
                            <select id="filterPeriod" class="form-control">
                                <option value="7" {{ request('period') == 7 ? 'selected' : '' }}>7 derniers jours</option>
                                <option value="30" {{ request('period') == 30 || !request('period') ? 'selected' : '' }}>30 derniers jours</option>
                                <option value="90" {{ request('period') == 90 ? 'selected' : '' }}>90 derniers jours</option>
                                <option value="365" {{ request('period') == 365 ? 'selected' : '' }}>Cette année</option>
                                <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Tout</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filterType" class="form-label">Type</label>
                            <select id="filterType" class="form-control">
                                <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>Tous les types</option>
                                <option value="entry" {{ request('type') == 'entry' ? 'selected' : '' }}>Entrées uniquement</option>
                                <option value="exit" {{ request('type') == 'exit' ? 'selected' : '' }}>Sorties uniquement</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="searchProduct" class="form-label">Recherche</label>
                            <input type="text" id="searchProduct" class="form-control" 
                                   placeholder="Nom du produit..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button onclick="applyFilters()" class="btn btn-primary w-100">
                                <i class="fas fa-search mr-2"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé des transactions -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="small-box bg-info h-100">
                        <div class="inner">
                            <h3 class="stats-number">{{ $transactions->total() }}</h3>
                            <p class="stats-label">Total transactions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-success h-100">
                        <div class="inner">
                            <h3 class="stats-number">{{ $transactions->where('type', 'entry')->count() }}</h3>
                            <p class="stats-label">Entrées</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-danger h-100">
                        <div class="inner">
                            <h3 class="stats-number">{{ $transactions->where('type', 'exit')->count() }}</h3>
                            <p class="stats-label">Sorties</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-warning h-100">
                        <div class="inner">
                            <h3 class="stats-number">{{ number_format($transactions->sum('total_value') ?? 0, 2) }} €</h3>
                            <p class="stats-label">Valeur totale</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des transactions -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="d-none d-md-table-cell">ID</th>
                            <th>Produit</th>
                            <th class="d-none d-sm-table-cell">Qté</th>
                            <th class="d-none d-lg-table-cell">Prix</th>
                            <th>Total</th>
                            <th class="d-none d-sm-table-cell">Type</th>
                            <th class="d-none d-lg-table-cell">Utilisateur</th>
                            <th class="d-none d-md-table-cell">Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td class="d-none d-md-table-cell">#{{ $transaction->id }}</td>
                            <td>
                                <div class="font-weight-bold">
                                    @if($transaction->product)
                                        {{ Str::limit($transaction->product->admin_name ?? $transaction->product->name, 30) }}
                                    @else
                                        <span class="text-muted">Produit supprimé</span>
                                    @endif
                                </div>
                                <!-- Infos supplémentaires sur mobile -->
                                <span class="d-block d-sm-none small text-muted mt-1">
                                    Qté: {{ $transaction->quantity }} | 
                                    @if($transaction->type === 'entry')
                                        <span class="text-success">Entrée</span>
                                    @else
                                        <span class="text-danger">Sortie</span>
                                    @endif
                                </span>
                                <!-- Date sur mobile -->
                                <span class="d-block d-md-none small text-muted">
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="d-none d-sm-table-cell">{{ $transaction->quantity }}</td>
                            <td class="d-none d-lg-table-cell">{{ number_format($transaction->price, 2) }} €</td>
                            <td class="font-weight-bold">{{ number_format($transaction->total_value, 2) }} €</td>
                            <td class="d-none d-sm-table-cell">
                                @if($transaction->type === 'entry')
                                    <span class="badge bg-success p-2">
                                        <i class="fas fa-arrow-down mr-1"></i> Entrée
                                    </span>
                                @else
                                    <span class="badge bg-danger p-2">
                                        <i class="fas fa-arrow-up mr-1"></i> Sortie
                                    </span>
                                @endif
                            </td>
                            <td class="d-none d-lg-table-cell">
                                {{ $transaction->user->name ?? 'Système' }}
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @if($transaction->order_id)
                                    <a href="{{ route('admin.orders.show', $transaction->order_id) }}" 
                                       class="btn btn-info" 
                                       title="Voir commande">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                    @endif
                                    <button onclick="showTransactionDetails({{ $transaction->id }})" 
                                            class="btn btn-success"
                                            title="Détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                <p class="text-muted mb-3">Aucune transaction trouvée</p>
                                <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i> Créer une transaction
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                <div class="text-muted mb-2 mb-md-0">
                    Affichage de {{ $transactions->firstItem() ?? 0 }} à {{ $transactions->lastItem() ?? 0 }} sur {{ $transactions->total() }} transactions
                </div>
                <div>
                    {{ $transactions->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de détails de transaction -->
<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle mr-2"></i> Détails de la transaction
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="transactionDetails" class="table-responsive">
                    <!-- Les détails seront chargés dynamiquement -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* ===== STYLES POUR LES SMALL BOX ===== */
.small-box {
    border-radius: 0.5rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 0;
    position: relative;
    overflow: hidden;
    color: #fff;
    padding: 15px;
    transition: transform 0.3s;
    height: 100%;
}
.small-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.small-box .inner {
    padding: 5px 5px 5px 0;
}
.small-box .stats-number {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 3px;
    line-height: 1.2;
    word-break: break-word;
}
.small-box .stats-label {
    font-size: 0.9rem;
    margin-bottom: 0;
    opacity: 0.9;
}
.small-box .icon {
    color: rgba(255,255,255,0.15);
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 60px;
}

/* ===== MODAL RESPONSIVE ===== */
.modal-dialog {
    margin: 1rem;
    max-width: 500px;
}
.modal-content {
    border-radius: 10px;
    overflow: hidden;
}
.modal-body {
    padding: 20px;
}
.modal-body .table {
    margin-bottom: 0;
}
.modal-body .table th {
    width: 40%;
    background-color: #f8f9fa;
}
.modal-body .table td {
    width: 60%;
    word-break: break-word;
}

/* ===== MODE SOMBRE ===== */
.dark .card {
    background: #2d2d44 !important;
    border-color: #4a4a6a !important;
}
.dark .card-header {
    background: #3a3a5a !important;
}
.dark .table {
    color: #e0e0e0;
}
.dark .table thead th {
    background: #3a3a5a !important;
    color: #e0e0e0;
}
.dark .table tbody td {
    border-color: #4a4a6a;
}
.dark .bg-light {
    background: #35354a !important;
}
.dark .text-muted {
    color: #a0a0c0 !important;
}
.dark .modal-content {
    background-color: #2d2d44;
    color: #e0e0e0;
}
.dark .modal-body .table th {
    background-color: #35354a;
    color: #e0e0e0;
}
.dark .modal-body .table td {
    color: #e0e0e0;
}

/* ===== STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    .small-box {
        padding: 10px;
    }
    .small-box .stats-number {
        font-size: 1.4rem;
    }
    .small-box .stats-label {
        font-size: 0.8rem;
    }
    .small-box .icon {
        font-size: 45px;
    }
    
    .table td, .table th {
        padding: 8px 5px;
        font-size: 0.85rem;
    }
    
    .btn-group-sm .btn {
        padding: 4px 6px;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
}

@media (max-width: 576px) {
    .small-box {
        padding: 8px;
    }
    .small-box .stats-number {
        font-size: 1.2rem;
    }
    .small-box .stats-label {
        font-size: 0.7rem;
    }
    .small-box .icon {
        font-size: 35px;
        right: 5px;
        top: 5px;
    }
    
    .table td, .table th {
        padding: 6px 3px;
        font-size: 0.8rem;
    }
    
    .btn-group .btn {
        padding: 4px 6px;
    }
}

/* Classes utilitaires */
.h-100 { height: 100%; }
.g-3 { gap: 1rem; }
</style>

<script>
function applyFilters() {
    const period = document.getElementById('filterPeriod').value;
    const type = document.getElementById('filterType').value;
    const search = document.getElementById('searchProduct').value;
    
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
    const search = document.getElementById('searchProduct').value;
    
    window.location.href = `/admin/transactions/export/csv?period=${period}&type=${type}&search=${search}`;
}

function showTransactionDetails(transactionId) {
    fetch(`/admin/transactions/${transactionId}`)
        .then(response => response.json())
        .then(data => {
            const typeIcon = data.type === 'entry' ? 'arrow-down' : 'arrow-up';
            const typeClass = data.type === 'entry' ? 'success' : 'danger';
            const typeLabel = data.type === 'entry' ? 'Entrée' : 'Sortie';
            
            const details = `
                <table class="table table-sm">
                    <tr><th>ID</th><td>#${data.id}</td></tr>
                    <tr><th>Produit</th><td>${data.product_name}</td></tr>
                    <tr><th>Quantité</th><td>${data.quantity}</td></tr>
                    <tr><th>Prix unitaire</th><td>${data.price} €</td></tr>
                    <tr><th>Total</th><td class="font-weight-bold">${data.total} €</td></tr>
                    <tr><th>Type</th><td><span class="badge bg-${typeClass}"><i class="fas fa-${typeIcon} mr-1"></i> ${typeLabel}</span></td></tr>
                    <tr><th>Utilisateur</th><td>${data.user_name}</td></tr>
                    <tr><th>Date</th><td>${data.date}</td></tr>
                    ${data.order_id ? `<tr><th>Commande associée</th><td><a href="/admin/orders/${data.order_id}" class="btn btn-sm btn-info">#${data.order_id}</a></td></tr>` : ''}
                    ${data.notes ? `<tr><th>Notes</th><td>${data.notes}</td></tr>` : ''}
                </table>
            `;
            document.getElementById('transactionDetails').innerHTML = details;
            $('#transactionModal').modal('show');
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Impossible de charger les détails de la transaction.');
        });
}

// Auto-refresh optionnel (toutes les 60 secondes)
let refreshInterval = setInterval(function() {
    if (document.visibilityState === 'visible') {
        // Recharger les données sans recharger la page
        // location.reload();
    }
}, 60000);

window.addEventListener('beforeunload', function() {
    clearInterval(refreshInterval);
});
</script>
@endsection