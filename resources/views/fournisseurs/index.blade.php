<!-- resources/views/fournisseurs/index.blade.php -->

@extends('layouts.app')

@section('title', 'Liste des fournisseurs')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Liste des fournisseurs</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                    <tr>
                        <td><a href="{{ route('commandes.create', ['supplier_id' => $supplier->id]) }}">{{ $supplier->name }}</a></td>
                        <td>{{ $supplier->contact }}</td>
                        <td>
                            <form action="{{ route('fournisseurs.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary">Ajouter un fournisseur</a>
    </div>
</div>
@endsection
