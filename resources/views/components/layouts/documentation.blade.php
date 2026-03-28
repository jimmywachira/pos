<x-layouts.marketing-shell
    title="Documentation - DemoPOS"
    pageTitle="Documentation"
    pageSubtitle="Everything you need to integrate with DemoPOS and get the most out of the platform."
>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <article class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Getting Started</h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">A step-by-step guide to setting up your account and hardware.</p>
            <a href="{{ route('guides') }}" class="mt-4 inline-flex text-sm font-semibold text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Read the guide →</a>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">API Reference</h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Technical endpoint and integration reference.</p>
            <a href="{{ route('api-status') }}" class="mt-4 inline-flex text-sm font-semibold text-blue-600 transition hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Explore API status →</a>
        </article>
    </div>
</x-layouts.marketing-shell>
