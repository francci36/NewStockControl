{{-- resources/views/vendor/pending.blade.php --}}
@extends('layouts.app')

@section('title', 'Demande en cours')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-5x text-warning"></i>
                    </div>
                    
                    <h2 class="mb-3">Demande en cours de traitement</h2>
                    
                    <p class="lead mb-4">
                        Bonjour <strong>{{ auth()->user()->name }}</strong>,<br>
                        Votre demande pour ouvrir la boutique <strong>"{{ $vendor->shop_name }}"</strong> a bien été reçue.
                    </p>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Notre équipe examine actuellement votre demande. Vous recevrez un email sous 48h.
                    </div>

                    <div class="mt-4">
                        <h5 class="mb-3">Récapitulatif de votre demande</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Nom de la boutique</th>
                                <td>{{ $vendor->shop_name }}</td>
                            </tr>
                            <tr>
                                <th>Statut</th>
                                <td>
                                    <span class="badge bg-warning">En attente</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Date de demande</th>
                                <td>{{ $vendor->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('shop.home') }}" class="btn btn-primary">
                            <i class="fas fa-home mr-2"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection