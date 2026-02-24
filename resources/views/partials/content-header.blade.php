{{-- resources/views/partials/content-header.blade.php --}}
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="col-sm-6">
                @yield('breadcrumb')
            </div>
        </div>
    </div>
</div>