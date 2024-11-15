<!-- resources/views/stocks/index.blade.php -->
@extends('layouts.app')

@section('title', 'Stocks')

@section('content')
    <div class="container">
        <h1>Liste des stocks</h1>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                    <tr>
                        <td>{{ $stock->produit }}</td>
                        <td>{{ $stock->quantite }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
