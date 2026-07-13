<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'DemoPOS') }}</title>
    <meta name="description" content="POS system">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Delius+Unicase:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        (() => {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const shouldUseDark = savedTheme ? savedTheme === 'dark' : prefersDark;
            document.documentElement.classList.toggle('dark', shouldUseDark);
        })();
    </script>
</head>

<body class="flex h-full min-h-screen flex-col bg-white font-medium text-slate-900 antialiased dark:bg-[#0a0d0c] dark:text-slate-100" font-family: 'Delius Unicase', cursive;>

    <x-header />

    <div class="relative flex w-full flex-1 min-h-0 lg:pl-60">
        <main class="mx-auto flex h-full min-h-0 w-full flex-1 flex-col">
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