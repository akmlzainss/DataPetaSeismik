<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin BBSPGL')</title>
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    @stack('styles')
</head>
<body>
    @include('layouts.sidebar')

    <main class="main-content">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>