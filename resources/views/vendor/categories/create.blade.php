@extends('vendor.layouts.app')

@section('title', __('messages.create_subcategory'))
@section('page-title', __('messages.new_subcategory'))

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">
                <i class="fas fa-plus-circle mr-2"></i>
                {{ __('messages.create_subcategory') }}
            </h3>
        </div>
        
        <form action="{{ route('vendor.categories.store') }}" method="POST">
            @csrf
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>{{ __('messages.validation_errors') }} :</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Catégorie principale -->
                <div class="form-group mb-3">
                    <label for="main_category" class="form-label fw-bold">
                        {{ __('messages.main_category') }} <span class="text-danger">*</span>
                    </label>
                    <select name="main_category" id="main_category" class="form-control" required>
                        <option value="">{{ __('messages.select_main_category') }}</option>
                        @foreach($mainCategories as $category)
                            <option value="{{ $category->id }}" {{ old('main_category') == $category->id ? 'selected' : '' }}>
                                {{ $category->translated_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nom de la sous-catégorie -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label fw-bold">
                        {{ __('messages.subcategory_name') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}"
                           required
                           placeholder="{{ __('messages.subcategory_name_placeholder') }}">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('messages.category_name_help') }}
                    </small>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('vendor.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i> {{ __('messages.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> {{ __('messages.create_subcategory_button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection