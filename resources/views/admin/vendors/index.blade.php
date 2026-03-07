{{-- resources/views/admin/vendors/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des vendeurs')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <h5 class="mb-0">
                <i class="fas fa-store mr-2"></i> 
                Demandes vendeurs
            </h5>
            <div>
                <span class="badge bg-warning text-dark mr-2" id="pendingBadge">
                    <i class="fas fa-clock mr-1"></i> 
                    <span id="pendingCount">{{ $vendors->where('status', 'pending')->count() }}</span> en attente
                </span>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Statistiques rapides -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="small-box bg-info h-100">
                        <div class="inner">
                            <h3 class="stats-number">{{ $vendors->total() }}</h3>
                            <p class="stats-label">Total vendeurs</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-store"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-warning h-100">
                        <div class="inner">
                            <h3 class="stats-number" id="statPending">{{ $vendors->where('status', 'pending')->count() }}</h3>
                            <p class="stats-label">En attente</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-success h-100">
                        <div class="inner">
                            <h3 class="stats-number" id="statActive">{{ $vendors->where('status', 'active')->count() }}</h3>
                            <p class="stats-label">Actifs</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small-box bg-secondary h-100">
                        <div class="inner">
                            <h3 class="stats-number" id="statRejected">{{ $vendors->whereIn('status', ['rejected', 'suspended'])->count() }}</h3>
                            <p class="stats-label">Rejetés/Suspendus</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('admin.vendors.index') }}" class="row g-2">
                        <div class="col-12 col-md-5">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Rechercher un vendeur..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tous les statuts</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendus</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetés</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            @if(request('search') || (request('status') && request('status') != 'all'))
                            <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times mr-1"></i> Réinitialiser
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-md-right">
                    <button type="button" class="btn btn-outline-primary w-100 w-md-auto" onclick="exportTable()">
                        <i class="fas fa-download mr-1"></i> Exporter
                    </button>
                </div>
            </div>

            <!-- Tableau -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="vendorsTable">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="d-none d-md-table-cell">ID</th>
                            <th>Boutique</th>
                            <th>Vendeur</th>
                            <th class="d-none d-lg-table-cell">Email</th>
                            <th class="d-none d-lg-table-cell">Téléphone</th>
                            <th>Commission</th>
                            <th>Statut</th>
                            <th class="d-none d-md-table-cell">Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                        <tr>
                            <td class="d-none d-md-table-cell">#{{ $vendor->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($vendor->logo)
                                        <img src="{{ asset('storage/' . $vendor->logo) }}" 
                                             alt="{{ $vendor->admin_shop_name ?? $vendor->shop_name }}" 
                                             class="rounded-circle mr-2 d-none d-sm-block" 
                                             style="width: 30px; height: 30px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <strong>{{ $vendor->admin_shop_name ?? $vendor->shop_name }}</strong>
                                        <!-- Infos supplémentaires sur mobile -->
                                        <span class="d-block d-md-none small text-muted mt-1">
                                            {{ $vendor->user->email ?? '' }} | {{ $vendor->phone ?? '' }}
                                            <br>Créé le: {{ $vendor->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $vendor->user->name ?? 'N/A' }}</td>
                            <td class="d-none d-lg-table-cell">
                                <a href="mailto:{{ $vendor->user->email ?? '' }}" class="text-decoration-none">
                                    <i class="fas fa-envelope mr-1 text-primary"></i>{{ Str::limit($vendor->user->email ?? '', 25) }}
                                </a>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <a href="tel:{{ $vendor->phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone mr-1 text-success"></i>{{ $vendor->phone ?? 'Non renseigné' }}
                                </a>
                            </td>
                            <td><span class="badge bg-info">{{ $vendor->commission_rate ?? 10 }}%</span></td>
                            <td>
                                @if($vendor->status == 'pending')
                                    <span class="badge bg-warning text-dark p-2">
                                        <i class="fas fa-clock mr-1"></i> En attente
                                    </span>
                                @elseif($vendor->status == 'active')
                                    <span class="badge bg-success p-2">
                                        <i class="fas fa-check-circle mr-1"></i> Actif
                                    </span>
                                @elseif($vendor->status == 'suspended')
                                    <span class="badge bg-danger p-2">
                                        <i class="fas fa-ban mr-1"></i> Suspendu
                                    </span>
                                @elseif($vendor->status == 'rejected')
                                    <span class="badge bg-secondary p-2">
                                        <i class="fas fa-times-circle mr-1"></i> Rejeté
                                    </span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">{{ $vendor->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.vendors.show', $vendor) }}" 
                                       class="btn btn-info" 
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($vendor->status == 'pending')
                                        <button type="button" 
                                                class="btn btn-success" 
                                                onclick="approveVendor({{ $vendor->id }})"
                                                title="Approuver">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="rejectVendor({{ $vendor->id }})"
                                                title="Rejeter">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    @if($vendor->status == 'active')
                                        <button type="button" 
                                                class="btn btn-warning" 
                                                onclick="suspendVendor({{ $vendor->id }})"
                                                title="Suspendre">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-store fa-4x text-muted mb-3"></i>
                                <p class="text-muted mb-3">Aucun vendeur trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                <div class="text-muted mb-2 mb-md-0">
                    Affichage de {{ $vendors->firstItem() ?? 0 }} à {{ $vendors->lastItem() ?? 0 }} sur {{ $vendors->total() }} vendeurs
                </div>
                <div>
                    {{ $vendors->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour les actions -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle mr-2"></i> Approuver le vendeur
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir approuver ce vendeur ?</p>
                    <p class="text-muted small">Il pourra immédiatement accéder à son espace vendeur.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Approuver</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle mr-2"></i> Rejeter la demande
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Motif du rejet <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-ban mr-2"></i> Suspendre le vendeur
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="suspendForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PATCH">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reason">Motif de la suspension <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Suspendre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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
    margin: 0 0 5px;
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
    font-size: 50px;
}

/* ===== COULEURS BOOTSTRAP ===== */
.bg-info { background-color: #17a2b8 !important; }
.bg-warning { background-color: #ffc107 !important; }
.bg-success { background-color: #28a745 !important; }
.bg-secondary { background-color: #6c757d !important; }
.bg-primary { background-color: #007bff !important; }
.bg-danger { background-color: #dc3545 !important; }

/* ===== BADGES ===== */
.badge {
    font-size: 0.85rem;
    padding: 6px 10px;
}

/* ===== BOUTONS DANS TABLEAU ===== */
.btn-group .btn {
    margin: 0 2px;
}

/* ===== PAGINATION ===== */
.pagination {
    margin-bottom: 0;
}

/* ===== RESPONSIVE ===== */
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
        font-size: 40px;
    }
    .table td, .table th {
        padding: 8px 5px;
        font-size: 0.85rem;
    }
}

@media (max-width: 576px) {
    .small-box .stats-number {
        font-size: 1.2rem;
    }
    .small-box .stats-label {
        font-size: 0.7rem;
    }
    .small-box .icon {
        font-size: 35px;
    }
}

/* Classes utilitaires */
.h-100 { height: 100%; }
.g-3 { gap: 1rem; }
.w-md-auto { width: auto; }
@media (min-width: 768px) {
    .w-md-auto { width: auto; }
}
</style>
@endsection

@push('scripts')
<script>
// Mettre à jour les statistiques
document.addEventListener('DOMContentLoaded', function() {
    const vendors = @json($vendors->items());
    
    const pending = vendors.filter(v => v.status === 'pending').length;
    const active = vendors.filter(v => v.status === 'active').length;
    const rejected = vendors.filter(v => ['rejected', 'suspended'].includes(v.status)).length;
    
    document.getElementById('statPending').textContent = pending;
    document.getElementById('statActive').textContent = active;
    document.getElementById('statRejected').textContent = rejected;
    document.getElementById('pendingCount').textContent = pending;
});

// Fonctions d'action avec PATCH
function approveVendor(id) {
    const form = document.getElementById('approveForm');
    form.action = "{{ route('admin.vendors.approve', '') }}/" + id;
    $('#approveModal').modal('show');
}

function rejectVendor(id) {
    const form = document.getElementById('rejectForm');
    form.action = "{{ route('admin.vendors.reject', '') }}/" + id;
    $('#rejectModal').modal('show');
}

function suspendVendor(id) {
    const form = document.getElementById('suspendForm');
    form.action = "{{ route('admin.vendors.suspend', '') }}/" + id;
    $('#suspendModal').modal('show');
}

// Export table
function exportTable() {
    const table = document.getElementById('vendorsTable');
    const rows = table.querySelectorAll('tr');
    let csv = [];
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        cols.forEach(col => {
            let text = col.innerText.replace(/,/g, ';').trim();
            rowData.push(text);
        });
        csv.push(rowData.join(','));
    });
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'vendeurs.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>
@endpush