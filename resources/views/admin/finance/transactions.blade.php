@extends('layouts.app')

@section('title', 'Transactions financières')

@section('page-title', 'Transactions financières')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.finance.index') }}">Finance</a></li>
    <li class="breadcrumb-item active">Transactions</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filtres -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter mr-2"></i>
                Filtres
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-3 col-sm-6 col-12 mb-2">
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option value="">Tous</option>
                            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Revenus</option>
                            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Dépenses</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12 mb-2">
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="status" class="form-control">
                            <option value="">Tous</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-12 mb-2">
                    <div class="form-group">
                        <label>Du</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-12 mb-2">
                    <div class="form-group">
                        <label>Au</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="col-md-2 col-12 mb-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-search"></i> <span class="d-none d-sm-inline">Filtrer</span>
                            </button>
                            <a href="{{ route('admin.finance.transactions') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> <span class="d-none d-sm-inline">Reset</span>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Résumé rapide - CORRIGÉ : toutes les pastilles ont la même classe -->
    <div class="row mb-4">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $transactions->total() }}</h3>
                    <p>Total transactions</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($transactions->where('type', 'income')->sum('total') ?? 0, 2) }} €</h3>
                    <p>Total revenus</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($transactions->where('type', 'expense')->sum('total') ?? 0, 2) }} €</h3>
                    <p>Total dépenses</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $commissionsPending ?? 0 }}</h3>
                    <p>Commissions en attente</p>
                    @if(($commissionsPendingAmount ?? 0) > 0)
                        <small class="text-white d-block">{{ number_format($commissionsPendingAmount, 2) }} €</small>
                    @endif
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('admin.finance.commissions', ['status' => 'pending']) }}" class="small-box-footer">
                    Voir les commissions <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mb-3">
        <div class="col-12 text-right">
            <a href="{{ route('admin.finance.export') }}?{{ http_build_query(request()->all()) }}" 
               class="btn btn-success btn-sm">
                <i class="fas fa-download mr-1"></i> Exporter CSV
            </a>
        </div>
    </div>

    <!-- Liste des transactions -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-2"></i>
                Liste des transactions
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="d-none d-md-table-cell">ID</th>
                            <th>Date</th>
                            <th>Référence</th>
                            <th class="d-none d-lg-table-cell">Description</th>
                            <th>Type</th>
                            <th>Catégorie</th>
                            <th class="text-right">Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ $transaction->id }}</td>
                            <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            <td><code>{{ $transaction->reference }}</code></td>
                            <td class="d-none d-lg-table-cell">{{ Str::limit($transaction->description, 50) }}</td>
                            <td>
                                @if($transaction->type == 'income')
                                    <span class="badge bg-success">Revenu</span>
                                @else
                                    <span class="badge bg-danger">Dépense</span>
                                @endif
                                <!-- Info supplémentaire sur mobile -->
                                <span class="d-block d-md-none small text-muted mt-1">
                                    {{ $transaction->description ? Str::limit($transaction->description, 30) : '' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $transaction->category }}</span>
                            </td>
                            <td class="text-right font-weight-bold {{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->type == 'income' ? '+' : '-' }} {{ number_format($transaction->total, 2) }} €
                                <!-- Montant sur mobile (déjà visible) -->
                            </td>
                            <td>
                                @if($transaction->status == 'completed')
                                    <span class="badge bg-success">Complété</span>
                                @elseif($transaction->status == 'pending')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($transaction->status == 'cancelled')
                                    <span class="badge bg-danger">Annulé</span>
                                @elseif($transaction->status == 'failed')
                                    <span class="badge bg-danger">Échoué</span>
                                @else
                                    <span class="badge bg-secondary">{{ $transaction->status }}</span>
                                @endif
                                <!-- Date complète sur mobile -->
                                <span class="d-block d-md-none small text-muted mt-1">
                                    {{ $transaction->transaction_date->format('H:i') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-exchange-alt fa-4x text-muted mb-3"></i>
                                <p class="text-muted">Aucune transaction trouvée</p>
                                <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i> Nouvelle transaction
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($transactions->hasPages())
        <div class="card-footer">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    Affichage de {{ $transactions->firstItem() ?? 0 }} à {{ $transactions->lastItem() ?? 0 }} 
                    sur {{ $transactions->total() }} transactions
                </div>
                <div>
                    {{ $transactions->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .small-box {
        border-radius: 10px;
        margin-bottom: 20px;
        transition: transform 0.3s;
        height: auto;
        min-height: 120px;
    }
    .small-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .small-box .inner {
        padding: 15px;
    }
    .small-box h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 5px;
    }
    .small-box p {
        font-size: 1rem;
        margin-bottom: 5px;
    }
    .small-box .icon {
        font-size: 60px;
        color: rgba(255,255,255,0.15);
        position: absolute;
        right: 10px;
        top: 10px;
    }
    .small-box small {
        font-size: 0.85rem;
        opacity: 0.9;
    }
    .small-box-footer {
        display: block;
        padding: 8px 0;
        text-align: center;
        background: rgba(0,0,0,0.1);
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        border-radius: 0 0 10px 10px;
        margin-top: 10px;
        transition: background 0.3s;
    }
    .small-box-footer:hover {
        background: rgba(0,0,0,0.15);
        color: #fff;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .small-box {
            margin-bottom: 15px;
            min-height: 100px;
        }
        .small-box h3 {
            font-size: 1.5rem;
        }
        .small-box .icon {
            font-size: 45px;
        }
        .small-box .inner {
            padding: 12px;
        }
        .table td, .table th {
            padding: 8px 5px;
            font-size: 0.85rem;
        }
        .btn-sm {
            padding: 4px 8px;
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .small-box h3 {
            font-size: 1.3rem;
        }
        .small-box .inner {
            padding: 10px;
        }
        .small-box .icon {
            font-size: 35px;
        }
        .table {
            font-size: 0.8rem;
        }
        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.4rem;
        }
        .small-box-footer {
            font-size: 0.8rem;
            padding: 6px 0;
        }
    }
    
    /* Gap utility */
    .gap-2 {
        gap: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts après 5 secondes
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            if (alert && alert.parentNode) {
                $(alert).alert('close');
            }
        });
    }, 5000);
});
</script>
@endpush