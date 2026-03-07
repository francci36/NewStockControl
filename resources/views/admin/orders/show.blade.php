@extends('layouts.app')

@section('title', 'Détails de la commande #' . $order->id)

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="card shadow-lg">
        <!-- En-tête -->
        <div class="card-header bg-gradient-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-info-circle mr-2"></i> Détails de la commande #{{ $order->id }}
                </h5>
                <small class="text-white-50 d-block d-md-inline mt-1 mt-md-0">
                    <i class="fas fa-calendar mr-1"></i> {{ $order->created_at->format('d/m/Y à H:i') }} | 
                    Type: {{ $order->type == 'client' ? 'Commande client' : 'Commande fournisseur' }}
                </small>
            </div>
            <div class="btn-group flex-wrap" role="group">
                @if($order->type == 'supplier' && $order->status == 'pending')
                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit mr-2"></i><span class="d-none d-sm-inline"> Modifier</span>
                </a>
                @endif
                <a href="{{ route('admin.orders.index', ['type' => $order->type]) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-2"></i><span class="d-none d-sm-inline"> Retour</span>
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Informations générales -->
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informations générales</h6>
                        </div>
                        <div class="card-body p-0 p-md-3">
                            <div class="info-grid">
                                <div class="info-row">
                                    <span class="info-label">Type :</span>
                                    <span class="info-value">
                                        @if($order->type == 'client')
                                            <span class="badge bg-primary">Commande client</span>
                                        @else
                                            <span class="badge bg-success">Commande fournisseur</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">{{ $order->type == 'client' ? 'Client' : 'Fournisseur' }} :</span>
                                    <span class="info-value">
                                        @if($order->type == 'client')
                                            <strong>{{ Str::limit($order->user->name ?? 'Client inconnu', 25) }}</strong>
                                            <span class="d-block small text-muted">{{ $order->user->email ?? '' }}</span>
                                        @else
                                            <div class="d-flex align-items-center flex-wrap">
                                                @if($order->supplier && $order->supplier->logo)
                                                    <img src="{{ asset('storage/' . $order->supplier->logo) }}" 
                                                         alt="{{ $order->supplier->admin_name ?? $order->supplier->name }}" 
                                                         class="rounded-circle mr-2 d-none d-sm-block" style="width: 30px; height: 30px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <strong>{{ Str::limit($order->supplier->admin_name ?? $order->supplier->name ?? 'Fournisseur inconnu', 25) }}</strong>
                                                    <span class="d-block small text-muted">{{ $order->supplier->email ?? '' }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Date commande :</span>
                                    <span class="info-value">{{ $order->date->format('d/m/Y') }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Utilisateur :</span>
                                    <span class="info-value">{{ $order->user->name ?? 'Inconnu' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION PAIEMENT ET STATUT CORRIGÉE -->
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-credit-card mr-2"></i>Paiement et statut</h6>
                        </div>
                        <div class="card-body p-0 p-md-3">
                            <div class="info-grid">
                                <!-- Statut -->
                                <div class="info-row">
                                    <span class="info-label">Statut :</span>
                                    <span class="info-value">
                                        @php
                                            $statusLabels = [
                                                'pending' => 'En attente',
                                                'paid' => 'Payée',
                                                'processing' => 'En traitement',
                                                'shipped' => 'Expédiée',
                                                'delivered' => 'Livrée',
                                                'arrived' => 'Arrivée',
                                                'cancelled' => 'Annulée',
                                                'refunded' => 'Remboursée',
                                            ];
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'processing' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'arrived' => 'success',
                                                'cancelled' => 'danger',
                                                'refunded' => 'secondary',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} status-badge">
                                            <i class="fas 
                                                @if($order->status == 'pending') fa-clock
                                                @elseif($order->status == 'processing') fa-cog
                                                @elseif($order->status == 'shipped') fa-truck
                                                @elseif($order->status == 'delivered') fa-check-circle
                                                @elseif($order->status == 'arrived') fa-check-circle
                                                @elseif($order->status == 'cancelled') fa-times-circle
                                                @elseif($order->status == 'paid') fa-credit-card
                                                @else fa-question-circle
                                                @endif mr-2"></i>
                                            {{ $statusLabels[$order->status] ?? $order->status }}
                                        </span>
                                    </span>
                                </div>

                                <!-- Montant total -->
                                <div class="info-row">
                                    <span class="info-label">Montant total :</span>
                                    <span class="info-value">
                                        <span class="total-amount-compact">{{ number_format($order->total_amount, 2) }} €</span>
                                    </span>
                                </div>

                                <!-- Mode de paiement -->
                                <div class="info-row">
                                    <span class="info-label">Mode de paiement :</span>
                                    <span class="info-value">
                                        @switch($order->payment_method)
                                            @case('card')
                                                <i class="fas fa-credit-card mr-1"></i> Carte bancaire
                                                @break
                                            @case('cash')
                                                <i class="fas fa-money-bill mr-1"></i> À la livraison
                                                @break
                                            @case('bank_transfer')
                                                <i class="fas fa-university mr-1"></i> Virement
                                                @break
                                            @default
                                                {{ $order->payment_method ?? 'Non spécifié' }}
                                        @endswitch
                                    </span>
                                </div>

                                <!-- Paiement effectué -->
                                @if($order->payment)
                                <div class="info-row">
                                    <span class="info-label">Paiement :</span>
                                    <span class="info-value">
                                        <span class="text-success payment-info">
                                            <i class="fas fa-check-circle mr-1"></i> 
                                            <span class="payment-text">Payé le {{ $order->payment->created_at->format('d/m/Y') }}</span>
                                        </span>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de livraison pour les commandes clients -->
            @if($order->type == 'client' && $order->shipping_name)
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-truck mr-2"></i>Informations de livraison</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Destinataire :</strong> {{ $order->shipping_name }}</p>
                            <p class="mb-1"><strong>Téléphone :</strong> {{ $order->shipping_phone }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Adresse :</strong></p>
                            <p class="mb-1">{{ $order->shipping_address }}<br>
                            {{ $order->shipping_zip }} {{ $order->shipping_city }}, {{ $order->shipping_country }}</p>
                        </div>
                    </div>
                    @if($order->tracking_number)
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Transporteur :</strong> {{ $order->carrier ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-1"><strong>Numéro de suivi :</strong> {{ $order->tracking_number }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- SECTION PRODUITS COMMANDÉS -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h6 class="mb-0"><i class="fas fa-boxes mr-2"></i>Produits commandés</h6>
                    <span class="badge bg-light text-dark">{{ $order->items->count() }} article(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="products-table-responsive">
                        <table class="products-table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="source-col">Source</th>
                                    <th>Fournisseur</th>
                                    <th class="contact-col">Contact</th>
                                    <th class="qty-col">Qté</th>
                                    <th class="price-col">P.U.</th>
                                    <th class="total-col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                @php
                                    $sourceType = 'boutique';
                                    $sourceName = 'Boutique';
                                    $sourceContact = '';
                                    $sourceEmail = '';
                                    
                                    if ($item->vendor_id) {
                                        $sourceType = 'marketplace';
                                        $vendor = $vendors[$item->vendor_id] ?? null;
                                        if ($vendor) {
                                            $sourceName = $vendor->admin_shop_name ?? $vendor->shop_name;
                                            $sourceContact = $vendor->phone ?? '';
                                            $sourceEmail = $vendor->email ?? '';
                                        } else {
                                            $sourceName = 'Vendeur ID: ' . $item->vendor_id;
                                        }
                                    } else {
                                        if ($item->product && $item->product->supplier) {
                                            $supplier = $item->product->supplier;
                                            $sourceName = $supplier->admin_name ?? $supplier->name;
                                            $sourceContact = $supplier->phone ?? $supplier->telephone ?? '';
                                            $sourceEmail = $supplier->email ?? '';
                                        } else {
                                            $sourceName = 'Fournisseur direct';
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="product-cell">
                                        <div class="product-info">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->admin_name ?? $item->product->name }}" 
                                                     class="product-image">
                                            @endif
                                            <div class="product-details">
                                                <strong>{{ Str::limit($item->product->admin_name ?? $item->product->name ?? 'Produit inconnu', 30) }}</strong>
                                                @if($item->product && $item->product->sku)
                                                    <span class="product-sku">SKU: {{ $item->product->sku }}</span>
                                                @endif
                                                <!-- Infos supplémentaires sur mobile -->
                                                <span class="mobile-source-info">
                                                    @if($sourceType == 'marketplace')
                                                        <span class="badge bg-info">Marketplace</span>
                                                    @else
                                                        <span class="badge bg-success">Boutique</span>
                                                    @endif
                                                    {{ Str::limit($sourceName, 15) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="source-col">
                                        @if($sourceType == 'marketplace')
                                            <span class="badge bg-info source-badge">
                                                <i class="fas fa-store mr-1"></i> Marketplace
                                            </span>
                                        @else
                                            <span class="badge bg-success source-badge">
                                                <i class="fas fa-building mr-1"></i> Boutique
                                            </span>
                                        @endif
                                    </td>
                                    <td class="supplier-cell">
                                        <strong>{{ Str::limit($sourceName, 20) }}</strong>
                                    </td>
                                    <td class="contact-col">
                                        @if($sourceEmail)
                                            <a href="mailto:{{ $sourceEmail }}" class="contact-link" title="{{ $sourceEmail }}">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        @endif
                                        @if($sourceContact)
                                            <a href="tel:{{ $sourceContact }}" class="contact-link ml-2" title="{{ $sourceContact }}">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="qty-col text-center">{{ $item->quantity }}</td>
                                    <td class="price-col text-nowrap">{{ number_format($item->price, 2) }} €</td>
                                    <td class="total-col font-weight-bold">
                                        <span class="total-compact">{{ number_format($item->quantity * $item->price, 2) }} €</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-right font-weight-bold">Total TTC :</td>
                                    <td class="font-weight-bold text-success">
                                        <span class="grand-total-compact">{{ number_format($order->total_amount, 2) }} €</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Historique des statuts -->
            @if($order->statusHistory && $order->statusHistory->count() > 0)
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-history mr-2"></i>Historique de la commande</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($order->statusHistory as $history)
                        <div class="timeline-item mb-3">
                            <div class="d-flex flex-column flex-sm-row align-items-start">
                                <div class="timeline-icon mr-3 mb-2 mb-sm-0">
                                    <span class="badge bg-{{ $statusColors[$history->status] ?? 'secondary' }} p-2">
                                        <i class="fas 
                                            @if($history->status == 'pending') fa-clock
                                            @elseif($history->status == 'processing') fa-cog
                                            @elseif($history->status == 'shipped') fa-truck
                                            @elseif($history->status == 'delivered') fa-check-circle
                                            @elseif($history->status == 'arrived') fa-check-circle
                                            @elseif($history->status == 'cancelled') fa-times-circle
                                            @elseif($history->status == 'paid') fa-credit-card
                                            @else fa-question-circle
                                            @endif"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-column flex-sm-row justify-content-between">
                                        <h6 class="mb-1">{{ $statusLabels[$history->status] ?? $history->status }}</h6>
                                        <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    @if($history->comment)
                                    <p class="mb-1">{{ $history->comment }}</p>
                                    @endif
                                    @if($history->user)
                                    <small class="text-muted">Par: {{ $history->user->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions sur la commande -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 mt-4">
                <div class="d-flex flex-wrap gap-2">
                    @if($order->type == 'client' && !in_array($order->status, ['delivered', 'cancelled']))
                    <button type="button" class="btn btn-primary" onclick="openCustomModal()">
                        <i class="fas fa-sync-alt mr-2"></i> <span class="d-none d-sm-inline">Mettre à jour le statut</span>
                        <span class="d-sm-none">Statut</span>
                    </button>
                    @endif

                    @if($order->type == 'supplier' && $order->status == 'pending')
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="arrived">
                        <button type="submit" class="btn btn-success" 
                                onclick="return confirm('Confirmer la réception de cette commande ? Le stock sera augmenté.')">
                            <i class="fas fa-check mr-2"></i> <span class="d-none d-sm-inline">Confirmer la réception</span>
                            <span class="d-sm-none">Réceptionner</span>
                        </button>
                    </form>
                    @endif
                </div>

                <a href="{{ route('admin.orders.index', ['type' => $order->type]) }}" class="btn btn-secondary btn-block-mobile">
                    <i class="fas fa-arrow-left mr-2"></i> <span class="d-none d-sm-inline">Retour à la liste</span>
                    <span class="d-sm-none">Retour</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MODALE PERSONNALISÉE -->
<div id="customStatusModal" class="custom-modal" style="display: none;">
    <div class="custom-modal-overlay" onclick="closeCustomModal()"></div>
    <div class="custom-modal-container">
        <div class="custom-modal-header bg-primary text-white">
            <h5 class="custom-modal-title">
                <i class="fas fa-sync-alt mr-2"></i> Mettre à jour le statut
            </h5>
            <button type="button" class="custom-modal-close" onclick="closeCustomModal()">×</button>
        </div>
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="custom-modal-body">
                <div class="form-group mb-3">
                    <label for="custom-status" class="form-label fw-bold">Nouveau statut</label>
                    <select name="status" id="custom-status" class="form-control">
                        @foreach($statusLabels as $value => $label)
                            @if($value != $order->status)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="custom-comment" class="form-label fw-bold">Commentaire (optionnel)</label>
                    <textarea name="comment" id="custom-comment" rows="3" class="form-control" 
                              placeholder="Ajoutez un commentaire sur ce changement..."></textarea>
                </div>
            </div>

            <div class="custom-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCustomModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.text-white-50 {
    color: rgba(255, 255, 255, 0.5);
}

/* ===== STYLES POUR LA GRILLE D'INFORMATIONS ===== */
.info-grid {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.info-row {
    display: flex;
    flex-wrap: wrap;
    padding: 12px 15px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    align-items: flex-start;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    width: 140px;
    color: #495057;
    flex-shrink: 0;
}

.info-value {
    flex: 1;
    min-width: 0;
    word-wrap: break-word;
    overflow-wrap: break-word;
    max-width: calc(100% - 140px);
}

/* ===== STYLES POUR LE MONTANT TOTAL (COMPACT) ===== */
.total-amount-compact {
    font-size: 1.2rem;
    font-weight: 700;
    color: #28a745;
    display: inline-block;
    max-width: 100%;
    word-break: break-word;
    white-space: normal;
    line-height: 1.3;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    font-size: 0.9rem;
    max-width: 100%;
    white-space: normal;
    text-align: left;
    word-break: break-word;
}

.payment-info {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
    max-width: 100%;
}

.payment-text {
    word-break: break-word;
}

/* ===== STYLES POUR LE TABLEAU DES PRODUITS ===== */
.products-table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.products-table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
}

.products-table thead th {
    background: #f8f9fa;
    padding: 12px 10px;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.products-table tbody td {
    padding: 12px 10px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.products-table tfoot td {
    padding: 15px 10px;
    background: #f8f9fa;
    border-top: 2px solid #dee2e6;
}

.product-cell {
    min-width: 250px;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-image {
    width: 45px;
    height: 45px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #dee2e6;
}

.product-details {
    display: flex;
    flex-direction: column;
}

.product-sku {
    font-size: 0.8rem;
    color: #6c757d;
}

.mobile-source-info {
    display: none;
    margin-top: 5px;
    font-size: 0.8rem;
}

.source-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    font-size: 0.85rem;
}

.contact-link {
    color: #6c757d;
    transition: color 0.3s;
}

.contact-link:hover {
    color: #007bff;
}

/* ===== STYLES POUR LES TOTAUX DANS LE TABLEAU ===== */
.total-compact {
    display: inline-block;
    max-width: 100%;
    white-space: normal;
    word-break: break-word;
    font-size: 0.9rem;
}

/* ===== STYLES POUR LE GRAND TOTAL ===== */
.grand-total-compact {
    font-size: 1.1rem;
    white-space: normal;
    word-break: break-word;
}

/* ===== STYLES POUR LA TIMELINE ===== */
.timeline-icon {
    width: 40px;
    text-align: center;
}
.timeline-item {
    border-left: 2px solid #dee2e6;
    padding-left: 20px;
    margin-left: 20px;
    position: relative;
}
.timeline-item:last-child {
    border-left: none;
}
.timeline-item .badge {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    left: -22px;
    top: 0;
}

/* ===== STYLES POUR LA MODALE ===== */
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 15px;
}

.custom-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(3px);
}

.custom-modal-container {
    position: relative;
    width: 500px;
    max-width: 100%;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    z-index: 10000;
    animation: modalSlideIn 0.3s ease-out;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.custom-modal-header {
    padding: 15px 20px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.custom-modal-title {
    margin: 0;
    font-size: 1.25rem;
}

.custom-modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 28px;
    cursor: pointer;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.custom-modal-close:hover {
    opacity: 1;
}

.custom-modal-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
}

.custom-modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.h-100 {
    height: 100%;
}

/* ===== MODE SOMBRE ===== */
.dark .custom-modal-container {
    background-color: #2d2d44;
    color: #e0e0e0;
}
.dark .timeline-item {
    border-left-color: #4a4a6a;
}
.dark .bg-light {
    background-color: #2d2d44 !important;
}
.dark .text-muted {
    color: #a0a0c0 !important;
}
.dark .info-label {
    color: #c0c0e0;
}
.dark .total-amount-compact,
.dark .grand-total-compact {
    color: #4cd964;
}
.dark .products-table thead th {
    background-color: #2d2d44;
    color: #e0e0e0;
    border-bottom-color: #4a4a6a;
}
.dark .products-table tbody td {
    border-bottom-color: #4a4a6a;
}
.dark .products-table tfoot td {
    background-color: #2d2d44;
    border-top-color: #4a4a6a;
}

/* ===== STYLES RESPONSIVES ===== */
@media (max-width: 768px) {
    .info-row {
        flex-direction: column;
        padding: 12px 15px;
    }
    
    .info-label {
        width: 100%;
        margin-bottom: 6px;
        font-size: 0.9rem;
    }
    
    .info-value {
        width: 100%;
        max-width: 100%;
        padding-left: 0;
    }
    
    .status-badge {
        width: 100%;
        justify-content: center;
        font-size: 0.9rem;
        white-space: normal;
        text-align: center;
    }
    
    .total-amount-compact {
        font-size: 1.1rem;
        display: block;
        text-align: left;
    }
    
    .payment-info {
        flex-wrap: wrap;
    }
    
    .product-image {
        width: 35px;
        height: 35px;
    }
    
    .source-col,
    .contact-col {
        display: none;
    }
    
    .mobile-source-info {
        display: block;
    }
    
    .products-table {
        min-width: 700px;
    }
    
    .custom-modal-footer {
        flex-direction: column-reverse;
    }
    
    .custom-modal-footer .btn {
        width: 100%;
        margin: 5px 0;
    }
    
    .btn-block-mobile {
        width: 100%;
    }
    
    .grand-total-compact {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .info-row {
        padding: 10px 12px;
    }
    
    .status-badge {
        font-size: 0.85rem;
        padding: 6px 10px;
    }
    
    .total-amount-compact {
        font-size: 1rem;
    }
    
    .product-info {
        gap: 8px;
    }
    
    .product-image {
        width: 30px;
        height: 30px;
    }
    
    .qty-col,
    .price-col {
        font-size: 0.85rem;
    }
    
    .total-compact {
        font-size: 0.85rem;
    }
    
    .grand-total-compact {
        font-size: 0.9rem;
    }
    
    .timeline-icon {
        width: 35px;
    }
    
    .timeline-item .badge {
        width: 35px;
        height: 35px;
        left: -19px;
    }
    
    .timeline-item {
        padding-left: 15px;
        margin-left: 15px;
    }
}

/* Classes utilitaires */
.h-100 { height: 100%; }
.gap-2 { gap: 0.5rem; }
.gap-3 { gap: 1rem; }
.text-nowrap { white-space: nowrap; }
</style>

<script>
function openCustomModal() {
    document.getElementById('customStatusModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeCustomModal() {
    document.getElementById('customStatusModal').style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCustomModal();
    }
});

// Fermer la modale en cliquant sur l'overlay
document.querySelector('.custom-modal-overlay')?.addEventListener('click', closeCustomModal);
</script>
@endsection