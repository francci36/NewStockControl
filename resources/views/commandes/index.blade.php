<!-- resources/views/commandes/index.blade.php -->

@extends('layouts.app')

@section('title', 'Liste des commandes')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Liste des commandes</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fournisseur</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($commandes as $commande)
                    <tr>
                        <td>{{ $commande->supplier->name }}</td>
                        <td>{{ $commande->product }}</td>
                        <td>{{ $commande->quantity }}</td>
                        <td>{{ $commande->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
