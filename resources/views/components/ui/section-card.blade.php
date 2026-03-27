@props([
    'title' => null,
    'subtitle' => null,
    'bodyClass' => 'p-6',
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white/95 shadow-lg dark:border-slate-700 dark:bg-slate-900/90']) }}>
    @if ($title || $subtitle)
        <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-700">
            @if ($title)
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $title }}</h3>
            @endif
            @if ($subtitle)
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    <div class="{{ $bodyClass }}">
        {{ $slot }}
    </div>
</div>
