@extends('vendor.layouts.app')

@section('title', __('messages.vendor_pending'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-5x text-warning"></i>
                    </div>
                    
                    <h2 class="mb-3">{{ __('messages.vendor_pending') }}</h2>
                    
                    <p class="lead mb-4">
                        {{ __('messages.vendor_pending_greeting', ['name' => auth()->user()->name]) }}<br>
                        {{ __('messages.vendor_pending_message', ['shop' => $vendor->shop_name]) }}
                    </p>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        {{ __('messages.vendor_pending_info') }}
                    </div>

                    <div class="mt-4">
                        <h5 class="mb-3">{{ __('messages.vendor_summary') }}</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ __('messages.vendor_shop_name') }}</th>
                                <td>{{ $vendor->shop_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.status') }}</th>
                                <td>
                                    <span class="badge bg-warning">{{ __('messages.vendor_pending') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('messages.request_date') }}</th>
                                <td>{{ $vendor->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('shop.home') }}" class="btn btn-primary">
                            <i class="fas fa-home mr-2"></i> {{ __('messages.back_to_shop') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection