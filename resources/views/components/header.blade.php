<header
    x-data="{
        open: false,
        isDark: document.documentElement.classList.contains('dark'),
        toggleTheme() {
            this.isDark = !this.isDark;
            document.documentElement.classList.toggle('dark', this.isDark);
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
        }
    }"
    class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur-xl dark:border-slate-700 dark:bg-slate-950/80"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-3">
            <a wire:navigate.hover href="{{ route('pos') }}" class="inline-flex items-center gap-2 rounded-lg px-2 py-1 text-slate-900 dark:text-slate-100">
                <ion-icon name="grid-outline" class="text-xl text-blue-600 dark:text-blue-400"></ion-icon>
                <span class="text-base font-bold tracking-wide">DemoPOS</span>
            </a>

            <nav class="hidden lg:flex items-center gap-1" aria-label="Primary navigation">
                <a wire:navigate.hover href="{{ route('pos') }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('pos*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}">
                    <ion-icon name="grid-outline"></ion-icon>
                    <span>POS</span>
                </a>
                <a wire:navigate.hover href="{{ route('inventory.products') }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('inventory.products') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}">
                    <ion-icon name="server-outline"></ion-icon>
                    <span>Products</span>
                </a>
                <a wire:navigate.hover href="{{ route('inventory.batches') }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('inventory.batches') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}">
                    <ion-icon name="list-outline"></ion-icon>
                    <span>Stock</span>
                </a>
                <a wire:navigate.hover href="{{ route('customers.management') }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('customers.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}">
                    <ion-icon name="people-outline"></ion-icon>
                    <span>Customers</span>
                </a>
                <a wire:navigate.hover href="{{ route('reports.sales') }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}">
                    <ion-icon name="bar-chart-outline"></ion-icon>
                    <span>Reports</span>
                </a>
                <a wire:navigate.hover href="{{ route('settings') }}" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('settings.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}">
                    <ion-icon name="settings-outline"></ion-icon>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                <button
                    @click="toggleTheme"
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 text-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800"
                    :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                >
                    <ion-icon x-show="!isDark" name="moon-outline" class="text-lg"></ion-icon>
                    <ion-icon x-show="isDark" name="sunny-outline" class="text-lg" style="display: none;"></ion-icon>
                </button>

                @auth
                <div class="hidden sm:flex items-center gap-2 rounded-full border border-slate-200 px-2 py-1 dark:border-slate-700">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-xs font-bold capitalize text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">
                        {{ Str::substr(auth()->user()->name, 0, 2) }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Log out" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-500 hover:bg-red-50 hover:text-red-600 dark:text-slate-300 dark:hover:bg-red-900/20 dark:hover:text-red-300">
                            <ion-icon name="finger-print-outline" class="text-lg"></ion-icon>
                        </button>
                    </form>
                </div>
                @else
                <div class="hidden sm:flex items-center gap-2">
                    <a href="/login" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">Sign in</a>
                    <a href="/register" class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">Sign up</a>
                </div>
                @endauth

                <button
                    @click="open = !open"
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 lg:hidden dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800"
                    aria-controls="mobile-menu"
                    :aria-expanded="open ? 'true' : 'false'"
                >
                    <span class="sr-only">Toggle navigation menu</span>
                    <ion-icon x-show="!open" name="menu-outline" class="text-xl"></ion-icon>
                    <ion-icon x-show="open" name="close-outline" class="text-xl" style="display: none;"></ion-icon>
                </button>
            </div>
        </div>
    </div>

    <div
        id="mobile-menu"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="border-t border-slate-200 bg-white/95 px-4 py-4 shadow-lg backdrop-blur-xl lg:hidden dark:border-slate-700 dark:bg-slate-900/95"
        style="display: none;"
    >
        <nav class="grid grid-cols-1 gap-2" aria-label="Mobile primary navigation">
            <a @click="open = false" wire:navigate.hover href="{{ route('pos') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('pos*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}"><ion-icon name="grid-outline"></ion-icon><span>POS</span></a>
            <a @click="open = false" wire:navigate.hover href="{{ route('inventory.products') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('inventory.products') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}"><ion-icon name="server-outline"></ion-icon><span>Products</span></a>
            <a @click="open = false" wire:navigate.hover href="{{ route('inventory.batches') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('inventory.batches') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}"><ion-icon name="list-outline"></ion-icon><span>Stock</span></a>
            <a @click="open = false" wire:navigate.hover href="{{ route('customers.management') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('customers.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}"><ion-icon name="people-outline"></ion-icon><span>Customers</span></a>
            <a @click="open = false" wire:navigate.hover href="{{ route('reports.sales') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}"><ion-icon name="bar-chart-outline"></ion-icon><span>Reports</span></a>
            <a @click="open = false" wire:navigate.hover href="{{ route('settings') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('settings.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' }}"><ion-icon name="settings-outline"></ion-icon><span>Settings</span></a>
        </nav>

        @auth
        <div class="mt-4 flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2 dark:border-slate-700">
            <span class="text-sm text-slate-600 dark:text-slate-300">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-md px-2 py-1 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-900/20">Log out</button>
            </form>
        </div>
        @else
        <div class="mt-4 grid grid-cols-2 gap-2">
            <a href="/login" class="rounded-lg border border-slate-300 px-3 py-2 text-center text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800">Sign in</a>
            <a href="/register" class="rounded-lg bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white hover:bg-blue-700">Sign up</a>
        </div>
        @endauth
    </div>
</header>
