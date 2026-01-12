<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'DemoPOS') }}</title>
    <meta name="description" content="A simple Livewire POS system">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Code&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Google Sans Code', monospace;
        }

    </style>
</head>

<body class="w-full min-h-screen flex flex-col bg-gray-100/50 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:36px_36px] text-gray-900 antialiased">

    <x-header />

    <div class="relative w-full flex-1">
        <main class="h-full md:pl-0 pl-20">
            {{ $slot }}
        </main>
    </div>

    <x-layouts.footer />

    <!-- Toast Notifications Placeholder -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 pointer-events-none"></div>

    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
