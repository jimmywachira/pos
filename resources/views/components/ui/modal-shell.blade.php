@props([
    'title' => null,
    'description' => null,
    'maxWidth' => 'max-w-lg',
])

<div {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center bg-slate-900/45 p-4 backdrop-blur-sm']) }}>
    <div class="w-full {{ $maxWidth }} rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-700 dark:bg-slate-900">
        @if ($title || $description)
            <div class="mb-4">
                @if ($title)
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $title }}</h3>
                @endif
                @if ($description)
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $description }}</p>
                @endif
            </div>
        @endif

        {{ $slot }}
    </div>
</div>
