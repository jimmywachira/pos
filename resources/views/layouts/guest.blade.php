<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DemoPOS') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tilt+Neon&display=swap" rel="stylesheet">

    <!-- Scripts -->
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
<body class="w-full min-h-screen bg-gray-100/50 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:36px_36px] text-gray-900 antialiased dark:bg-slate-950 dark:bg-[radial-gradient(#1f2937_1px,transparent_1px)] dark:text-slate-100">
    <div class="mx-auto flex min-h-screen w-full max-w-7xl flex-col items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-lg px-2 py-1 text-slate-900 dark:text-slate-100">
                <ion-icon name="grid-outline" class="text-xl text-blue-600 dark:text-blue-400"></ion-icon>
                <span class="text-base font-bold tracking-wide">DemoPOS</span>
            </a>
        </div>

        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white/95 px-6 py-8 shadow-xl dark:border-slate-700 dark:bg-slate-900/90">
            {{ $slot }}
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
