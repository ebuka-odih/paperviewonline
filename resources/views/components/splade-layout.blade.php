<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts and Styles -->
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @spladeHead
</head>
<body class="font-sans antialiased">
    <div id="app">
        <x-splade-flash />
        {{ $slot }}
    </div>
    
    @spladeScripts
</body>
</html> 