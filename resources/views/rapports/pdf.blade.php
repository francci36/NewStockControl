<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #3b82f6;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            margin-top: 0;
        }
        .date {
            font-size: 12px;
            color: #999;
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .kpi-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .kpi-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #6c757d;
            text-transform: uppercase;
        }
        .kpi-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background: #3b82f6;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        .table td {
            padding: 8px 10px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }
        .table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .section-title {
            color: #3b82f6;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
            margin: 20px 0 15px 0;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-processing { background: #d1ecf1; color: #0c5460; }
        .status-shipped { background: #d4edda; color: #155724; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $report->name }}</h1>
        <p>{{ $report->description }}</p>
        <p class="date">Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <h3>Chiffre d'affaires</h3>
            <div class="value">{{ number_format($chiffreAffaires, 2, ',', ' ') }} €</div>
        </div>
        <div class="kpi-card">
            <h3>Achats fournisseurs</h3>
            <div class="value">{{ number_format($totalAchats, 2, ',', ' ') }} €</div>
        </div>
        <div class="kpi-card">
            <h3>Stock critique</h3>
            <div class="value">{{ $stockCritique }}</div>
        </div>
        <div class="kpi-card">
            <h3>Panier moyen</h3>
            <div class="value">{{ number_format($panierMoyen, 2, ',', ' ') }} €</div>
        </div>
    </div>

    <!-- Commandes par statut -->
    <h2 class="section-title">Commandes par statut</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Statut</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusLabels = [
                    'pending' => 'En attente',
                    'processing' => 'En traitement',
                    'shipped' => 'Expédiée',
                    'delivered' => 'Livrée',
                    'arrived' => 'Arrivée',
                    'cancelled' => 'Annulée',
                    'paid' => 'Payée',
                    'refunded' => 'Remboursée',
                ];
            @endphp
            @foreach($commandesParStatut as $statut)
            <tr>
                <td>
                    <span class="status-badge status-{{ $statut->status }}">
                        {{ $statusLabels[$statut->status] ?? ucfirst($statut->status) }}
                    </span>
                </td>
                <td>{{ $statut->count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Top 10 produits en stock -->
    <h2 class="section-title">Top 10 produits en stock</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produitsEnStock as $stock)
            <tr>
                <td>{{ $stock->product->name ?? 'Produit inconnu' }}</td>
                <td>{{ $stock->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Fournisseurs -->
    <h2 class="section-title">Statistiques des fournisseurs</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Fournisseur</th>
                <th>Email</th>
                <th>Nb commandes</th>
                <th>Montant total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fournisseurs as $fournisseur)
            <tr>
                <td>{{ $fournisseur->name }}</td>
                <td>{{ $fournisseur->email }}</td>
                <td>{{ $fournisseur->orders_count ?? 0 }}</td>
                <td>{{ number_format($fournisseur->orders_sum_total_amount ?? 0, 2, ',', ' ') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Transactions du rapport -->
    @if($report->transactions->count() > 0)
    <h2 class="section-title">Transactions</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Prix</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report->transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                <td>{{ $transaction->product->name ?? 'Produit inconnu' }}</td>
                <td>
                    @if($transaction->type === 'exit')
                        <span style="color: #28a745;">Vente</span>
                    @else
                        <span style="color: #dc3545;">Achat</span>
                    @endif
                </td>
                <td>{{ $transaction->quantity }}</td>
                <td>{{ number_format($transaction->price, 2, ',', ' ') }} €</td>
                <td>{{ number_format($transaction->quantity * $transaction->price, 2, ',', ' ') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Stocks critiques -->
    @if($report->stocks->count() > 0)
    <h2 class="section-title">Stocks critiques</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Seuil</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report->stocks as $stock)
            <tr>
                <td>{{ $stock->product->name ?? 'Produit inconnu' }}</td>
                <td>{{ $stock->quantity }}</td>
                <td>≤ 10</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Rapport généré automatiquement - NewStockControl &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>