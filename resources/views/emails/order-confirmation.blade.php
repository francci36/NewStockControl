<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
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
            background-color: #28a745;
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
        .order-details {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #e9ecef;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
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
            <h1>Merci pour votre commande !</h1>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $order->shipping_name }}</strong>,</p>
            <p>Nous vous confirmons la réception de votre commande n°<strong>#{{ $order->id }}</strong>.</p>
            
            <div class="order-details">
                <h3>Détails de la commande</h3>
                <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Statut :</strong> {{ ucfirst($order->status) }}</p>
                
                <h4>Produits commandés</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->pivot->quantity }}</td>
                            <td>{{ number_format($item->pivot->price, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="total">
                    Total : {{ number_format($total, 2) }} €
                </div>
            </div>
            
            <h4>Adresse de livraison</h4>
            <p>
                {{ $order->shipping_name }}<br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_zip }} {{ $order->shipping_city }}<br>
                {{ $order->shipping_country }}
            </p>
            
            <p>
                <a href="{{ route('client.orders.show', $order->id) }}" class="button">
                    Voir ma commande
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Ma Boutique. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>