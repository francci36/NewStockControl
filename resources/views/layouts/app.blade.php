<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body>
    <div id="app" class="flex flex-col min-h-screen">
        @if (Route::has('login'))
            <header>
                @include('layouts.navigation')
            </header>
        @endif

        <main class="flex-grow">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>
    @vite('resources/js/app.js')
    @yield('scripts')
</body>
</html>
