<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Josefin Sans">

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
<body x-data="{ isDark: document.documentElement.classList.contains('dark'), toggleTheme() { this.isDark = !this.isDark; document.documentElement.classList.toggle('dark', this.isDark); localStorage.setItem('theme', this.isDark ? 'dark' : 'light'); } }" class="text-gray-900 antialiased" style="font-family: 'Josefin Sans';">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 dark:bg-slate-950">
        <div class="w-full max-w-5xl px-4 flex justify-end">
            <button @click="toggleTheme" type="button" class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white/80 px-3 py-2 text-xs text-gray-700 shadow-sm hover:bg-gray-100 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800" :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'" :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'">
                <ion-icon x-show="!isDark" name="moon-outline"></ion-icon>
                <ion-icon x-show="isDark" name="sunny-outline" style="display: none;"></ion-icon>
                <span x-text="isDark ? 'Light mode' : 'Dark mode'"></span>
            </button>
        </div>
        <div class="mb-6">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500 dark:text-slate-300" />
            </a>
        </div>

        <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-xl dark:bg-slate-900 dark:border dark:border-slate-700">
            {{ $slot }}
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
