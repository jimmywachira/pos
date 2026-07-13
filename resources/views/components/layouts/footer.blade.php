<footer class="mt-8 border-t border-slate-200 bg-white font-mono lg:pl-60 dark:border-emerald-500/10 dark:bg-[#0a0d0c]" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <a wire:navigate.hover href="{{ route('pos') }}" class="inline-flex items-center gap-2 px-2 py-1 text-slate-900 dark:text-slate-100">
                    <span class="relative flex h-2 w-2 shrink-0">
                        <span class="absolute inline-flex h-full w-full bg-emerald-500/60 dark:bg-emerald-400/60"></span>
                        <span class="relative inline-flex h-2 w-2 bg-emerald-500 dark:bg-emerald-400"></span>
                    </span>
                    <span class="text-[15px] font-semibold tracking-tight">
                        Demo<span class="text-emerald-600 dark:text-emerald-400">POS</span>
                    </span>
                </a>
                <p class="mt-3 max-w-sm text-sm leading-6 text-slate-600 dark:text-slate-400">
                    Smart checkout, inventory, and reporting in one place for retail teams that need speed and clarity.
                </p>
                <div class="mt-4 flex items-center gap-2">
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center border border-slate-300 text-slate-500 transition-colors hover:border-emerald-500/40 hover:text-emerald-700 dark:border-emerald-500/15 dark:text-slate-400 dark:hover:border-emerald-500/40 dark:hover:text-emerald-300" aria-label="Facebook">
                        <ion-icon name="logo-facebook" class="text-lg"></ion-icon>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center border border-slate-300 text-slate-500 transition-colors hover:border-emerald-500/40 hover:text-emerald-700 dark:border-emerald-500/15 dark:text-slate-400 dark:hover:border-emerald-500/40 dark:hover:text-emerald-300" aria-label="Instagram">
                        <ion-icon name="logo-instagram" class="text-lg"></ion-icon>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center border border-slate-300 text-slate-500 transition-colors hover:border-emerald-500/40 hover:text-emerald-700 dark:border-emerald-500/15 dark:text-slate-400 dark:hover:border-emerald-500/40 dark:hover:text-emerald-300" aria-label="X">
                        <ion-icon name="logo-twitter" class="text-lg"></ion-icon>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center border border-slate-300 text-slate-500 transition-colors hover:border-emerald-500/40 hover:text-emerald-700 dark:border-emerald-500/15 dark:text-slate-400 dark:hover:border-emerald-500/40 dark:hover:text-emerald-300" aria-label="GitHub">
                        <ion-icon name="logo-github" class="text-lg"></ion-icon>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-8 lg:grid-cols-4">
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Product</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a wire:navigate.hover href="{{ route('pos') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Point of Sale</a></li>
                        <li><a wire:navigate.hover href="{{ route('inventory.products') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Inventory</a></li>
                        <li><a wire:navigate.hover href="{{ route('reports.sales') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Reporting</a></li>
                        <li><a wire:navigate.hover href="{{ route('customers.management') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Customers</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Resources</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('pricing') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Pricing</a></li>
                        <li><a href="{{ route('documentation') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Documentation</a></li>
                        <li><a href="{{ route('guides') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Guides</a></li>
                        <li><a href="{{ route('api-status') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">API Status</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Company</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('about') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">About</a></li>
                        <li><a href="{{ route('blog') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Blog</a></li>
                        <li><a href="{{ route('jobs') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Jobs</a></li>
                        <li><a href="{{ route('press') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Press</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Legal</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('claim') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Claim</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Privacy</a></li>
                        <li><a href="{{ route('terms') }}" class="text-slate-600 transition-colors hover:text-emerald-700 dark:text-slate-400 dark:hover:text-emerald-300">Terms</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-2 border-t border-slate-200 pt-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between dark:border-emerald-500/10 dark:text-slate-500">
            <p>{{ date('Y') }} &copy; DemoPOS, Inc. All rights reserved.</p>
            <p>Built for fast retail operations.</p>
        </div>
    </div>
</footer>