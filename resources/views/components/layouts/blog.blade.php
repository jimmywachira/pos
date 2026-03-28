<x-layouts.marketing-shell
    title="Blog - DemoPOS"
    pageTitle="The DemoPOS Blog"
    pageSubtitle="Insights, tips, and stories from the world of retail and e-commerce."
>
    <div class="mx-auto grid max-w-5xl gap-8 sm:grid-cols-2 lg:grid-cols-3">
        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white/90 shadow-sm dark:border-slate-700 dark:bg-slate-900/80">
            <img class="h-44 w-full object-cover" src="https://images.unsplash.com/photo-1496128858413-b36217c2ce36?auto=format&fit=crop&w=1679&q=80" alt="Blog image">
            <div class="p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">Article</p>
                <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">Boost Your Sales: 5 Tips for Small Businesses</h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Learn how to increase your revenue and grow your customer base with proven strategies.</p>
                <div class="mt-4 flex items-center gap-3">
                    <img class="h-9 w-9 rounded-full" src="https://i.pravatar.cc/150?img=68" alt="Author avatar">
                    <div>
                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100">Jane Doe</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ date('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </article>
    </div>
</x-layouts.marketing-shell>
