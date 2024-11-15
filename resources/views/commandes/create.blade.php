<!-- resources/views/commandes/create.blade.php -->

@extends('layouts.app')

@section('title', 'Passer une commande')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Passer une commande</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('commandes.store') }}">
        @csrf
        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
        <div class="mb-3">
            <label for="product" class="form-label">Produit</label>
            <input type="text" class="form-control" id="product" name="product" value="{{ old('product') }}" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantité</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Statut</label>
            <input type="text" class="form-control" id="status" name="status" value="En cours" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Passer la commande</button>
    </form>
</div>
@endsection
