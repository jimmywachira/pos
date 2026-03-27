<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Delicious Handrawn">
<head>
    @include('partials.head')
    <script>
        (() => {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const shouldUseDark = savedTheme ? savedTheme === 'dark' : prefersDark;
            document.documentElement.classList.toggle('dark', shouldUseDark);
        })();
    </script>
</head>
<body class="min-h-screen bg-slate-50 antialiased dark:bg-slate-950" style="font-family: 'Delicious Handrawn', cursive;">
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-gradient-to-br from-slate-50 via-white to-slate-100 p-6 md:p-10 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
        <div class="flex w-full max-w-md flex-col gap-6">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white shadow-sm dark:bg-slate-800">
                    <x-app-logo-icon class="size-9 fill-current text-slate-900 dark:text-slate-100" />
                </span>

                <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <div class="flex flex-col gap-6">
                <div class="rounded-2xl border border-slate-200 bg-white/95 text-slate-800 shadow-xl dark:border-slate-700 dark:bg-slate-900/90 dark:text-slate-100">
                    <div class="px-10 py-8">{{ $slot }}</div>
                </div>
            </div>
        </div>
    </div>
    @fluxScripts
</body>
</html>
