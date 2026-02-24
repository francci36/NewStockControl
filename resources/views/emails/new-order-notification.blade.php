<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle commande</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .client-info {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Nouvelle commande !</h1>
        </div>
        
        <div class="content">
            <p>Bonjour Admin,</p>
            <p>Une nouvelle commande vient d'être passée sur la boutique.</p>
            
            <div class="client-info">
                <h3>Informations client</h3>
                <p><strong>Nom :</strong> {{ $client->name ?? 'N/A' }}</p>
                <p><strong>Email :</strong> {{ $client->email ?? 'N/A' }}</p>
                <p><strong>Téléphone :</strong> {{ $order->shipping_phone }}</p>
                
                <h4>Commande #{{ $order->id }}</h4>
                <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Montant total :</strong> {{ number_format($total, 2) }} €</p>
                <p><strong>Mode de paiement :</strong> 
                    @switch($order->payment_method)
                        @case('card')
                            Carte bancaire
                            @break
                        @case('cash')
                            Paiement à la livraison
                            @break
                        @default
                            {{ $order->payment_method }}
                    @endswitch
                </p>
            </div>
            
            <p>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="button">
                    Voir la commande dans l'admin
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Ma Boutique - Notification automatique</p>
        </div>
    </div>
</body>
</html>