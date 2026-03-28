<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-slate-100">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="min-h-[calc(100vh-12rem)] bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-ui.section-card bodyClass="p-6 sm:p-8">
                <div class="space-y-2">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Welcome back</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ __("You're logged in!") }}</p>
                </div>
            </x-ui.section-card>
        </div>
    </div>
</x-app-layout>
