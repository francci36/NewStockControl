@extends('layouts.app')

@section('title', 'Détails de la demande vendeur')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-store mr-2"></i> 
                        Détails de la demande - {{ $vendor->shop_name }}
                    </h5>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">Informations de la boutique</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Nom de la boutique</th>
                                            <td>{{ $vendor->shop_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Slug</th>
                                            <td>{{ $vendor->slug }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $vendor->description ?? 'Aucune' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Commission</th>
                                            <td>{{ $vendor->commission_rate }}%</td>
                                        </tr>
                                        <tr>
                                            <th>Statut</th>
                                            <td>
                                                @if($vendor->status == 'pending')
                                                    <span class="badge bg-warning">En attente</span>
                                                @elseif($vendor->status == 'active')
                                                    <span class="badge bg-success">Actif</span>
                                                @elseif($vendor->status == 'rejected')
                                                    <span class="badge bg-danger">Rejeté</span>
                                                @elseif($vendor->status == 'suspended')
                                                    <span class="badge bg-secondary">Suspendu</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Date de demande</th>
                                            <td>{{ $vendor->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">Informations du vendeur</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Nom</th>
                                            <td>{{ $vendor->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $vendor->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Téléphone</th>
                                            <td>{{ $vendor->phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Adresse</th>
                                            <td>{{ $vendor->address }}</td>
                                        </tr>
                                        <tr>
                                            <th>N° SIRET</th>
                                            <td>{{ $vendor->business_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>IBAN</th>
                                            <td>{{ $vendor->bank_account }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($vendor->status == 'pending')
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning">
                                    <h6 class="mb-0">Actions</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.vendors.approve', $vendor) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Approuver cette demande ?')">
                                            <i class="fas fa-check-circle mr-2"></i> Approuver
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                        <i class="fas fa-times-circle mr-2"></i> Rejeter
                                    </button>
                                    
                                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-2"></i> Retour
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.vendors.reject', $vendor) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Rejeter la demande</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Motif du rejet <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" 
                                  class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection