<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6">Tableau de bord</h1>

    <div class="dashboard-section mb-6">
        <h2 class="text-2xl font-semibold mb-4">Niveaux de stock</h2>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Produit</th>
                    <th class="py-2 px-4 border-b">Quantité</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4 border-b">Produit A</td>
                    <td class="py-2 px-4 border-b">50</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b">Produit B</td>
                    <td class="py-2 px-4 border-b">20</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="dashboard-section mb-6">
        <h2 class="text-2xl font-semibold mb-4">Alertes de réapprovisionnement</h2>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Produit</th>
                    <th class="py-2 px-4 border-b">Quantité</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4 border-b">Produit C</td>
                    <td class="py-2 px-4 border-b">5</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b">Produit D</td>
                    <td class="py-2 px-4 border-b">2</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="dashboard-section mb-6">
        <h2 class="text-2xl font-semibold mb-4">Commandes récentes</h2>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Commande</th>
                    <th class="py-2 px-4 border-b">Date</th>
                    <th class="py-2 px-4 border-b">Statut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4 border-b">Commande #123</td>
                    <td class="py-2 px-4 border-b">2024-11-01</td>
                    <td class="py-2 px-4 border-b">Livrée</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b">Commande #124</td>
                    <td class="py-2 px-4 border-b">2024-11-02</td>
                    <td class="py-2 px-4 border-b">En cours</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="dashboard-section mb-6">
        <h2 class="text-2xl font-semibold mb-4">Graphique des stocks</h2>
        <canvas id="stockChart" class="w-full h-64"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('stockChart').getContext('2d');
        var stockChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Produit A', 'Produit B', 'Produit C', 'Produit D'],
                datasets: [{
                    label: 'Quantité',
                    data: [50, 20, 5, 2],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
