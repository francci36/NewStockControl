{{-- resources/views/admin/vendors/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des vendeurs')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-store mr-2"></i> Demandes vendeurs</h5>
        </div>
        <div class="card-body">
            
            <!-- Filtres -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <form method="GET" action="{{ route('admin.vendors.index') }}" class="form-inline">
                        <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                            <option value="all">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendus</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetés</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Tableau -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Boutique</th>
                            <th>Vendeur</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Commission</th>
                            <th>Statut</th>
                            <th>Date demande</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendors as $vendor)
                        <tr>
                            <td>#{{ $vendor->id }}</td>
                            <td>
                                <strong>{{ $vendor->shop_name }}</strong>
                            </td>
                            <td>{{ $vendor->user->name }}</td>
                            <td>{{ $vendor->user->email }}</td>
                            <td>{{ $vendor->phone }}</td>
                            <td>{{ $vendor->commission_rate }}%</td>
                            <td>
                                @switch($vendor->status)
                                    @case('pending')
                                        <span class="badge bg-warning">En attente</span>
                                        @break
                                    @case('active')
                                        <span class="badge bg-success">Actif</span>
                                        @break
                                    @case('suspended')
                                        <span class="badge bg-danger">Suspendu</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-secondary">Rejeté</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $vendor->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.vendors.show', $vendor) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $vendors->links() }}
            </div>
        </div>
    </div>
</div>
@endsection