<footer class="mt-8 border-t border-slate-200/70 bg-white/80 backdrop-blur-xl dark:border-slate-700 dark:bg-slate-950/80" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <a wire:navigate.hover href="{{ route('pos') }}" class="inline-flex items-center gap-2 rounded-lg px-2 py-1 text-slate-900 dark:text-slate-100">
                    <ion-icon name="grid-outline" class="text-xl text-blue-600 dark:text-blue-400"></ion-icon>
                    <span class="text-base font-bold tracking-wide">DemoPOS</span>
                </a>
                <p class="mt-3 max-w-sm text-sm leading-6 text-slate-600 dark:text-slate-300">
                    Smart checkout, inventory, and reporting in one place for retail teams that need speed and clarity.
                </p>
                <div class="mt-4 flex items-center gap-2">
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100" aria-label="Facebook">
                        <ion-icon name="logo-facebook" class="text-lg"></ion-icon>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100" aria-label="Instagram">
                        <ion-icon name="logo-instagram" class="text-lg"></ion-icon>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100" aria-label="X">
                        <ion-icon name="logo-twitter" class="text-lg"></ion-icon>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100" aria-label="GitHub">
                        <ion-icon name="logo-github" class="text-lg"></ion-icon>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-8 lg:grid-cols-4">
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Product</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a wire:navigate.hover href="{{ route('pos') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Point of Sale</a></li>
                        <li><a wire:navigate.hover href="{{ route('inventory.products') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Inventory</a></li>
                        <li><a wire:navigate.hover href="{{ route('reports.sales') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Reporting</a></li>
                        <li><a wire:navigate.hover href="{{ route('customers.management') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Customers</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Resources</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('pricing') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Pricing</a></li>
                        <li><a href="{{ route('documentation') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Documentation</a></li>
                        <li><a href="{{ route('guides') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Guides</a></li>
                        <li><a href="{{ route('api-status') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">API Status</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Company</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('about') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">About</a></li>
                        <li><a href="{{ route('blog') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Blog</a></li>
                        <li><a href="{{ route('jobs') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Jobs</a></li>
                        <li><a href="{{ route('press') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Press</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-300">Legal</h3>
                    <ul class="mt-3 space-y-2 text-sm">
                        <li><a href="{{ route('claim') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Claim</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Privacy</a></li>
                        <li><a href="{{ route('terms') }}" class="text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-300">Terms</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-2 border-t border-slate-200 pt-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700 dark:text-slate-400">
            <p>{{ date('Y') }} &copy; DemoPOS, Inc. All rights reserved.</p>
            <p>Built for fast retail operations.</p>
        </div>
    </div>
</footer>
