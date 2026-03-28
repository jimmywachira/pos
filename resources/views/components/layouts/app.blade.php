<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'DemoPOS') }}</title>
    <meta name="description" content="A modern POS and operations workspace">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tilt+Neon&display=swap" rel="stylesheet">

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

<body class="w-full min-h-screen flex flex-col font-semibold bg-gray-100/50 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:36px_36px] text-gray-900 antialiased dark:bg-slate-950 dark:bg-[radial-gradient(#1f2937_1px,transparent_1px)] dark:text-slate-100">
    <x-header />

    <div class="relative flex w-full flex-1">
        <main class="mx-auto flex h-full w-full flex-col pb-6 sm:pb-8 lg:pb-10">
            {{ $slot }}
        </main>
    </div>

    <x-layouts.footer />

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
