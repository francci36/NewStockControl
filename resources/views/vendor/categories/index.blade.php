@extends('vendor.layouts.app')

@section('title', __('messages.vendor_subcategories'))
@section('page-title', __('messages.vendor_subcategories'))

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ __('messages.subcategories_list') }}</h3>
            <div class="card-tools">
                <a href="{{ route('vendor.categories.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> {{ __('messages.new_subcategory') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>{{ __('messages.main_category') }}</th>
                            <th>{{ __('messages.subcategory') }}</th>
                            <th>{{ __('messages.slug') }}</th>
                            <th>{{ __('messages.status') }}</th>
                            <th>{{ __('messages.products') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->parent->translated_name ?? '-' }}</td>
                            <td><strong>{{ $category->translated_name }}</strong></td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>
                                @if($category->is_active)
                                    <span class="badge bg-success">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $category->products_count ?? 0 }}</span>
                            </td>
                            <td>
                                <a href="{{ route('vendor.categories.edit', $category) }}" class="btn btn-sm btn-warning" title="{{ __('messages.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('vendor.categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.confirm_delete_category') }}')" title="{{ __('messages.delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p>{{ __('messages.no_subcategories') }}</p>
                                <a href="{{ route('vendor.categories.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> {{ __('messages.new_subcategory') }}
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection