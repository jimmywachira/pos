@props([
    'title' => null,
    'pageTitle' => null,
    'pageSubtitle' => null,
])

<x-layouts.app :title="$title">
    <section class="py-10 sm:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white/90 shadow-xl backdrop-blur dark:border-slate-700 dark:bg-slate-900/85">
                <div class="p-6 sm:p-10 lg:p-12">
                    @if($pageTitle)
                        <header class="mx-auto mb-10 max-w-3xl text-center">
                            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl dark:text-slate-100">{{ $pageTitle }}</h1>
                            @if($pageSubtitle)
                                <p class="mt-4 text-base text-slate-600 sm:text-lg dark:text-slate-300">{{ $pageSubtitle }}</p>
                            @endif
                        </header>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
