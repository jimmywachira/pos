<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DemoPOS') }}</title>

    <!-- Preconnect to external resources for performance optimization -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Delius+Unicase:wght@400;700&display=swap" rel="stylesheet">

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

<body class="relative min-h-screen w-full bg-white text-slate-900 antialiased dark:bg-[#0a0d0c] dark:text-slate-100" font-family: 'Delius Unicase', cursive;>

    <!-- Subtle corner accent — restrained, single hue, no competing gradients -->
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-24 -right-24 h-72 w-72 bg-emerald-500/[0.06] blur-3xl dark:bg-emerald-400/[0.05]"></div>
    </div>

    <main class="relative z-10 flex min-h-screen items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-5xl">
            <div class="grid grid-cols-1 items-stretch gap-6 lg:grid-cols-2 lg:gap-8">

                <!-- Feature highlight -->
                <div class="order-2 flex flex-col justify-center border border-slate-200 bg-white p-8 dark:border-emerald-500/10 dark:bg-[#0f1413] lg:order-1">
                    <div class="mb-6 flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center border border-emerald-500/30 bg-emerald-500/10">
                            <ion-icon name="rocket-outline" class="text-2xl text-emerald-600 dark:text-emerald-400"></ion-icon>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="mb-2 text-lg font-semibold leading-tight text-slate-900 dark:text-white">
                                Streamline Your Business
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-400">
                                Eliminate complexity with our intelligent POS system. Real-time insights, seamless operations.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 font-mono text-xs">
                        <span class="inline-flex items-center gap-1.5 border border-emerald-500/30 bg-emerald-500/10 px-3 py-1.5 font-medium text-emerald-700 dark:text-emerald-300">
                            <ion-icon name="analytics-outline"></ion-icon>
                            Analytics
                        </span>
                        <span class="inline-flex items-center gap-1.5 border border-slate-200 bg-slate-50 px-3 py-1.5 font-medium text-slate-600 dark:border-emerald-500/10 dark:bg-white/[0.03] dark:text-slate-400">
                            <ion-icon name="cloud-outline"></ion-icon>
                            Cloud-Based
                        </span>
                        <span class="inline-flex items-center gap-1.5 border border-slate-200 bg-slate-50 px-3 py-1.5 font-medium text-slate-600 dark:border-emerald-500/10 dark:bg-white/[0.03] dark:text-slate-400">
                            <ion-icon name="shield-checkmark-outline"></ion-icon>
                            Secure
                        </span>
                    </div>
                </div>

                <!-- Auth section -->
                <div class="order-1 lg:order-2">
                    <div class="mb-8 text-center lg:text-left">
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center border border-emerald-500/30 bg-emerald-500/10">
                            <ion-icon name="storefront-outline" class="text-2xl text-emerald-600 dark:text-emerald-400"></ion-icon>
                        </div>
                        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">
                            {{ config('app.name', 'DemoPOS') }}
                        </h1>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            Modern point-of-sale solution
                        </p>
                    </div>

                    <div class="border border-slate-200 bg-white p-8 dark:border-emerald-500/10 dark:bg-[#0f1413] sm:p-10">
                        {{ $slot }}
                    </div>

                    <div class="mt-6 text-center font-mono text-xs text-slate-500 dark:text-slate-500 lg:text-left">
                        <p>{{ date('Y') }} &copy; {{ config('app.name', 'DemoPOS') }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>