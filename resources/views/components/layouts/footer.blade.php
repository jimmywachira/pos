<footer class="mt-8 border-t border-zinc-200 bg-white lg:pl-60 dark:border-emerald-500/10 dark:bg-[#0a0d0c]" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">

            {{-- Brand block --}}
            <div class="lg:col-span-4">
                <a wire:navigate.hover href="{{ route('pos') }}" class="inline-flex items-center gap-2 px-2 py-1 text-zinc-900 dark:text-zinc-100">
                    <span class="relative flex h-2 w-2 shrink-0">
                        <span class="absolute inline-flex h-full w-full animate-ping bg-emerald-500/60 dark:bg-emerald-400/60"></span>
                        <span class="relative inline-flex h-2 w-2 bg-emerald-500 dark:bg-emerald-400"></span>
                    </span>
                    <span class="text-[15px] font-semibold tracking-tight">
                        Demo<span class="text-emerald-600 dark:text-emerald-400">POS</span>
                    </span>
                </a>
                <p class="mt-3 max-w-sm text-sm leading-6 text-zinc-600 dark:text-zinc-500">
                    Smart checkout, inventory, and reporting in one place for retail teams that need speed and clarity.
                </p>

                <div class="mt-5 flex items-center gap-2">
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center border border-zinc-300 text-zinc-500 transition-colors hover:border-emerald-500/40 hover:text-emerald-700 dark:border-emerald-500/15 dark:text-zinc-500 dark:hover:border-emerald-500/40 dark:hover:text-emerald-400" aria-label="Facebook">
                        <ion-icon name="logo-facebook" class="text-lg"></ion-icon>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center border border-zinc-300 text-zinc-500 transition-colors hover:border-emerald-500/40 hover:text-emerald-700 dark:border-emerald-500/15 dark:text-zinc-500 dark:hover:border-emerald-500/40 dark:hover:text-emerald-400" aria-label="Instagram">
                        <ion-icon name="logo-instagram" class="text-lg"></ion-icon>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center border border-zinc-300 text-zinc-500 transition-colors hover:border-emerald-500/40 hover:text-emerald-700 dark:border-emerald-500/15 dark:text-zinc-500 dark:hover:border-emerald-500/40 dark:hover:text-emerald-400" aria-label="X">
                        <ion-icon name="logo-twitter" class="text-lg"></ion-icon>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center border border-zinc-300 text-zinc-500 transition-colors hover:border-emerald-500/40 hover:text-emerald-700 dark:border-emerald-500/15 dark:text-zinc-500 dark:hover:border-emerald-500/40 dark:hover:text-emerald-400" aria-label="GitHub">
                        <ion-icon name="logo-github" class="text-lg"></ion-icon>
                    </a>
                </div>
            </div>

            {{-- Link columns --}}
            <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-8 lg:grid-cols-4">
                <div>
                    <h3 class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-zinc-500 dark:text-emerald-500/70">
                        <span class="text-zinc-400 dark:text-emerald-500/40">//</span> Product
                    </h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a wire:navigate.hover href="{{ route('pos') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Point of Sale</a></li>
                        <li><a wire:navigate.hover href="{{ route('inventory.products') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Inventory</a></li>
                        <li><a wire:navigate.hover href="{{ route('reports.sales') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Reporting</a></li>
                        <li><a wire:navigate.hover href="{{ route('customers.management') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Customers</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-zinc-500 dark:text-emerald-500/70">
                        <span class="text-zinc-400 dark:text-emerald-500/40">//</span> Resources
                    </h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('pricing') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Pricing</a></li>
                        <li><a href="{{ route('documentation') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Documentation</a></li>
                        <li><a href="{{ route('guides') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Guides</a></li>
                        <li><a href="{{ route('api-status') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">API Status</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-zinc-500 dark:text-emerald-500/70">
                        <span class="text-zinc-400 dark:text-emerald-500/40">//</span> Company
                    </h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('about') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">About</a></li>
                        <li><a href="{{ route('blog') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Blog</a></li>
                        <li><a href="{{ route('jobs') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Jobs</a></li>
                        <li><a href="{{ route('press') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Press</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-zinc-500 dark:text-emerald-500/70">
                        <span class="text-zinc-400 dark:text-emerald-500/40">//</span> Legal
                    </h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('claim') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Claim</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Privacy</a></li>
                        <li><a href="{{ route('terms') }}" class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400">Terms</a></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-10 flex flex-col gap-2 border-t border-zinc-200 pt-4 text-[11px] uppercase tracking-widest text-zinc-500 sm:flex-row sm:items-center sm:justify-between dark:border-emerald-500/10 dark:text-zinc-600">
            <p>{{ date('Y') }} &copy; DemoPOS, Inc. All rights reserved.</p>
            <p class="normal-case tracking-normal text-zinc-400 dark:text-zinc-600">Built for fast retail operations.</p>
        </div>
    </div>
</footer>