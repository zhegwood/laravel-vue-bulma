<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel-Vue-Bulma</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <global-error></global-error>
        @if (auth()->check())
        <auth-nav></auth-nav>
        @else
        <no-auth-nav></no-auth-nav>
        @endif
        <div class="section is-paddingless">
            <div class="container is-fluid">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
