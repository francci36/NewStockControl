@extends('layouts.app')

@section('title', 'Gestion des commissions')

@section('page-title', 'Gestion des commissions')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Accueil</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.finance.index') }}">Finance</a></li>
    <li class="breadcrumb-item active">Commissions</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Stats -->
    <div class="row">
        <div class="col-md-6 col-12 mb-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totalPending, 2) }} €</h3>
                    <p>Commissions en attente</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mb-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalPaid, 2) }} €</h3>
                    <p>Commissions payées</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

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
                <div class="col-md-4 col-12 mb-2">
                    <div class="form-group">
                        <label>Vendeur</label>
                        <select name="vendor_id" class="form-control">
                            <option value="">Tous</option>
                            @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->admin_shop_name ?? $vendor->shop_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="status" class="form-control">
                            <option value="">Tous</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payées</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-12 mb-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                    </div>
                </div>
                <div class="col-md-2 col-12 mb-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a href="{{ route('admin.finance.commissions') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des commissions -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-2"></i>
                Liste des commissions
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-success btn-sm" id="paySelectedBtn" style="display:none;">
                    <i class="fas fa-check mr-1"></i> Payer sélection
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="30"><input type="checkbox" id="selectAll"></th>
                            <th>Date</th>
                            <th>Vendeur</th>
                            <th>Commande</th>
                            <th class="d-none d-md-table-cell">Produit</th>
                            <th>Taux</th>
                            <th class="text-right">Montant</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commissions as $commission)
                        <tr>
                            <td>
                                @if($commission->status == 'pending')
                                <input type="checkbox" class="commission-check" 
                                       data-id="{{ $commission->id }}"
                                       data-vendor="{{ $commission->vendor_id }}">
                                @endif
                            </td>
                            <td>{{ $commission->created_at->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $commission->vendor->admin_shop_name ?? $commission->vendor->shop_name }}</strong>
                                <!-- Infos sur mobile -->
                                <span class="d-block d-md-none small text-muted mt-1">
                                    Produit: {{ $commission->orderItem && $commission->orderItem->product ? 
                                        Str::limit($commission->orderItem->product->admin_name ?? $commission->orderItem->product->name, 20) : 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $commission->order_id) }}" class="text-decoration-none">
                                    #{{ $commission->order_id }}
                                </a>
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($commission->orderItem && $commission->orderItem->product)
                                    {{ Str::limit($commission->orderItem->product->admin_name ?? $commission->orderItem->product->name, 30) }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ number_format($commission->rate, 2) }}%</td>
                            <td class="text-right font-weight-bold">{{ number_format($commission->amount, 2) }} €</td>
                            <td>
                                @if($commission->status == 'pending')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($commission->status == 'paid')
                                    <span class="badge bg-success">Payée</span>
                                    <small class="d-block d-md-none text-muted">
                                        {{ $commission->paid_at ? $commission->paid_at->format('d/m/Y') : '' }}
                                    </small>
                                @else
                                    <span class="badge bg-secondary">{{ $commission->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($commission->status == 'pending')
                                <button type="button" 
                                        class="btn btn-success btn-sm" 
                                        onclick="paySingleCommission({{ $commission->id }}, {{ $commission->vendor_id }})"
                                        title="Payer">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-percent fa-4x text-muted mb-3"></i>
                                <p class="text-muted">Aucune commission trouvée</p>
                                <p class="text-muted small">
                                    Les commissions apparaîtront automatiquement quand des produits marketplace seront vendus.
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($commissions->hasPages())
        <div class="card-footer">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-muted mb-2 mb-md-0">
                    Affichage de {{ $commissions->firstItem() ?? 0 }} à {{ $commissions->lastItem() ?? 0 }} sur {{ $commissions->total() }}
                </div>
                <div>
                    {{ $commissions->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de paiement -->
<div class="modal fade" id="payModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle mr-2"></i>
                    Payer les commissions
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.finance.commissions.pay') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p id="payModalMessage">Voulez-vous payer les commissions sélectionnées ?</p>
                    <input type="hidden" name="vendor_id" id="payVendorId">
                    <input type="hidden" name="commission_ids" id="payCommissionIds">
                    
                    <div class="form-group">
                        <label>Méthode de paiement</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="bank_transfer">Virement bancaire</option>
                            <option value="paypal">PayPal</option>
                            <option value="check">Chèque</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Notes (optionnel)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Ajoutez des notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Confirmer le paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .small-box {
        border-radius: 10px;
        margin-bottom: 0;
    }
    .small-box .inner {
        padding: 15px;
    }
    .small-box h3 {
        font-size: 2rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .small-box h3 {
            font-size: 1.6rem;
        }
        .small-box .icon {
            font-size: 50px;
        }
        .table td, .table th {
            padding: 8px 5px;
            font-size: 0.85rem;
        }
        .btn-sm {
            padding: 4px 6px;
        }
    }
    
    @media (max-width: 576px) {
        .small-box h3 {
            font-size: 1.4rem;
        }
        .badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
let selectedCommissions = [];

document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.commission-check');
    const paySelectedBtn = document.getElementById('paySelectedBtn');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                if (cb && !cb.disabled) {
                    cb.checked = this.checked;
                }
            });
            updateSelectedCommissions();
        });
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedCommissions);
    });
    
    function updateSelectedCommissions() {
        selectedCommissions = [];
        const vendorIds = new Set();
        
        document.querySelectorAll('.commission-check:checked').forEach(cb => {
            selectedCommissions.push(parseInt(cb.dataset.id));
            vendorIds.add(parseInt(cb.dataset.vendor));
        });
        
        if (selectedCommissions.length > 0) {
            paySelectedBtn.style.display = 'inline-block';
        } else {
            paySelectedBtn.style.display = 'none';
        }
    }
    
    paySelectedBtn.addEventListener('click', function() {
        if (selectedCommissions.length === 0) return;
        
        // Vérifier que toutes les commissions sont du même vendeur
        const vendorIds = new Set();
        document.querySelectorAll('.commission-check:checked').forEach(cb => {
            vendorIds.add(parseInt(cb.dataset.vendor));
        });
        
        if (vendorIds.size > 1) {
            alert('Vous ne pouvez payer que les commissions d\'un seul vendeur à la fois.');
            return;
        }
        
        const vendorId = Array.from(vendorIds)[0];
        const vendor = vendors.find(v => v.id === vendorId);
        const vendorName = vendor ? (vendor.admin_shop_name || vendor.shop_name) : 'ce vendeur';
        
        document.getElementById('payVendorId').value = vendorId;
        document.getElementById('payCommissionIds').value = JSON.stringify(selectedCommissions);
        document.getElementById('payModalMessage').textContent = 
            `Payer ${selectedCommissions.length} commission(s) pour ${vendorName} ?`;
        
        $('#payModal').modal('show');
    });
});

function paySingleCommission(commissionId, vendorId) {
    const vendor = vendors.find(v => v.id === vendorId);
    const vendorName = vendor ? (vendor.admin_shop_name || vendor.shop_name) : 'ce vendeur';
    
    document.getElementById('payVendorId').value = vendorId;
    document.getElementById('payCommissionIds').value = JSON.stringify([commissionId]);
    document.getElementById('payModalMessage').textContent = `Payer cette commission pour ${vendorName} ?`;
    $('#payModal').modal('show');
}

// Données des vendeurs pour le modal
const vendors = @json($vendors);
</script>
@endpush