{{--
    DemoPOS — Primary Navigation
    Design system: Industrial Terminal
      - Zero border-radius, hairline borders, monospace nav/system text
      - Single accent: emerald (--accent). No blue, no rounded corners, no gradients.
      - Signature element: live terminal-style breadcrumb under the topbar
--}}
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
    class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur-xl font-semibold dark:border-emerald-500/10 dark:bg-[#0a0d0c]/95"
>
    {{-- Topbar --}}
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6">
        <div class="flex h-16 items-center justify-between gap-3">

            {{-- Logo / Brand --}}
            <a
                wire:navigate.hover
                href="{{ route('pos') }}"
                class="group inline-flex items-center gap-2.5 px-2 py-1 text-slate-900 transition-colors dark:text-slate-100"
            >
                <span class="relative flex h-2 w-2 shrink-0">
                    <span class="absolute inline-flex h-full w-full animate-ping bg-emerald-500/60 motion-reduce:animate-none dark:bg-emerald-400/60"></span>
                    <span class="relative inline-flex h-2 w-2 bg-emerald-500 dark:bg-emerald-400"></span>
                </span>
                <span class="text-[15px] font-semibold tracking-tight">
                    Demo<span class="text-emerald-600 dark:text-emerald-400">POS</span>
                </span>
            </a>

            <div class="flex-1"></div>

            {{-- Right-side actions --}}
            <div class="flex items-center gap-2 sm:gap-3">

                {{-- Theme toggle --}}
                <button
                    @click="toggleTheme"
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center border border-slate-300 text-slate-600 transition-colors hover:border-slate-400 hover:text-slate-900 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:ring-offset-1 dark:border-emerald-500/15 dark:text-slate-400 dark:hover:border-emerald-500/40 dark:hover:text-emerald-300 dark:focus:ring-offset-[#0a0d0c]"
                    :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                >
                    <ion-icon x-show="!isDark" name="moon-outline" class="text-base"></ion-icon>
                    <ion-icon x-show="isDark" name="sunny-outline" class="text-base" style="display: none;"></ion-icon>
                </button>

                {{-- Desktop user menu --}}
                @auth
                <div class="hidden sm:flex items-center gap-2 border border-slate-200 px-2 py-1.5 dark:border-emerald-500/10">
                    <span class="inline-flex h-7 w-7 items-center justify-center border border-emerald-500/30 bg-emerald-500/10 text-[11px] font-semibold uppercase text-emerald-700 dark:text-emerald-300">
                        {{ Str::substr(auth()->user()->name, 0, 2) }}
                    </span>
                    <span class="hidden md:inline-block px-1 text-xs text-slate-600 dark:text-slate-400">
                        {{ Str::limit(auth()->user()->name, 15) }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            title="Log out"
                            class="inline-flex h-7 w-7 items-center justify-center text-slate-400 transition-colors hover:text-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 dark:text-slate-500 dark:hover:text-red-400"
                        >
                            <ion-icon name="log-out-outline" class="text-base"></ion-icon>
                        </button>
                    </form>
                </div>
                @else
                <div class="hidden sm:flex items-center gap-2">
                    <a
                        href="/login"
                        class="border border-transparent px-3 py-2 text-xs uppercase tracking-wide text-slate-600 transition-colors hover:text-slate-900 focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:text-slate-400 dark:hover:text-slate-100"
                    >
                        Sign in
                    </a>
                    <a
                        href="/register"
                        class="border border-emerald-600 bg-emerald-600 px-3 py-2 text-xs uppercase tracking-wide text-white transition-colors hover:bg-emerald-700 hover:border-emerald-700 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:ring-offset-1 dark:focus:ring-offset-[#0a0d0c]"
                    >
                        Sign up
                    </a>
                </div>
                @endauth

                {{-- Mobile menu toggle --}}
                <button
                    @click="open = !open"
                    type="button"
                    class="inline-flex h-9 w-9 items-center justify-center border border-slate-300 text-slate-600 transition-colors hover:border-slate-400 focus:outline-none focus:ring-1 focus:ring-emerald-500 lg:hidden dark:border-emerald-500/15 dark:text-slate-300 dark:hover:border-emerald-500/40"
                    aria-controls="mobile-menu"
                    :aria-expanded="open ? 'true' : 'false'"
                >
                    <span class="sr-only">Toggle navigation menu</span>
                    <ion-icon x-show="!open" name="menu-outline" class="text-lg"></ion-icon>
                    <ion-icon x-show="open" name="close-outline" class="text-lg" style="display: none;"></ion-icon>
                </button>
            </div>
        </div>

        {{-- Signature element: live terminal breadcrumb --}}
        <div class="hidden h-8 items-center border-t border-slate-100 text-[11px] text-slate-500 lg:flex dark:border-emerald-500/5 dark:text-slate-500">
            <span class="text-emerald-600 dark:text-emerald-400">operator</span><span class="text-slate-400 dark:text-slate-600">@</span><span>demopos</span><span class="text-slate-400 dark:text-slate-600">:~$</span>
            <span class="ml-2 text-slate-700 dark:text-slate-300">
                @if(request()->routeIs('pos*')) ./pos
                @elseif(request()->routeIs('inventory.products')) ./inventory/products
                @elseif(request()->routeIs('inventory.batches')) ./inventory/batches
                @elseif(request()->routeIs('customers.*')) ./customers
                @elseif(request()->routeIs('reports.*')) ./reports/sales
                @elseif(request()->routeIs('settings.*')) ./settings
                @else ./
                @endif
            </span>
            <span class="ml-1 inline-block h-3 w-1.5 animate-pulse bg-emerald-500/70 motion-reduce:animate-none dark:bg-emerald-400/70"></span>
        </div>
    </div>

    {{-- Desktop sidebar --}}
    <aside class="hidden lg:block fixed left-0 top-24 h-[calc(100vh-6rem)] w-60 overflow-y-auto border-r border-slate-200 bg-white dark:border-emerald-500/10 dark:bg-[#0a0d0c]">
        <nav class="flex h-full flex-col px-2 py-6" aria-label="Sidebar navigation">
            <div class="space-y-0.5">
                @php
                    $navItems = [
                        ['route' => 'pos', 'active' => 'pos*', 'icon' => 'grid-outline', 'label' => 'Point of Sale'],
                        ['route' => 'inventory.products', 'active' => 'inventory.products', 'icon' => 'server-outline', 'label' => 'Products'],
                        ['route' => 'inventory.batches', 'active' => 'inventory.batches', 'icon' => 'list-outline', 'label' => 'Stock Management'],
                        ['route' => 'customers.management', 'active' => 'customers.*', 'icon' => 'people-outline', 'label' => 'Customers'],
                        ['route' => 'reports.sales', 'active' => 'reports.*', 'icon' => 'bar-chart-outline', 'label' => 'Reports & Analytics'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    @php $isActive = request()->routeIs($item['active']); @endphp
                    <a
                        wire:navigate.hover
                        href="{{ route($item['route']) }}"
                        class="group relative flex items-center gap-3 border-l-2 px-3 py-2.5 text-[13px] transition-colors
                            {{ $isActive
                                ? 'border-emerald-500 bg-emerald-500/5 text-emerald-700 dark:text-emerald-300'
                                : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-white/[0.03] dark:hover:text-slate-100' }}"
                    >
                        <ion-icon
                            name="{{ $item['icon'] }}"
                            class="text-lg {{ $isActive ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 group-hover:text-slate-600 dark:text-slate-600 dark:group-hover:text-slate-300' }}"
                        ></ion-icon>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Settings, pinned to bottom --}}
            <div class="mt-auto space-y-0.5 border-t border-slate-100 pt-4 dark:border-emerald-500/5">
                @php $settingsActive = request()->routeIs('settings.*'); @endphp
                <a
                    wire:navigate.hover
                    href="{{ route('settings') }}"
                    class="group relative flex items-center gap-3 border-l-2 px-3 py-2.5 text-[13px] transition-colors
                        {{ $settingsActive
                            ? 'border-emerald-500 bg-emerald-500/5 text-emerald-700 dark:text-emerald-300'
                            : 'border-transparent text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-white/[0.03] dark:hover:text-slate-100' }}"
                >
                    <ion-icon
                        name="settings-outline"
                        class="text-lg {{ $settingsActive ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 group-hover:text-slate-600 dark:text-slate-600 dark:group-hover:text-slate-300' }}"
                    ></ion-icon>
                    <span>Settings</span>
                </a>
            </div>
        </nav>
    </aside>

    {{-- Mobile menu --}}
    <div
        id="mobile-menu"
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="border-t border-slate-200 bg-white px-4 py-4 lg:hidden dark:border-emerald-500/10 dark:bg-[#0a0d0c]"
        style="display: none;"
    >
        <nav class="grid grid-cols-1 gap-1" aria-label="Mobile primary navigation">
            @php
                $mobileItems = [
                    ['route' => 'pos', 'active' => 'pos*', 'icon' => 'grid-outline', 'label' => 'Point of Sale'],
                    ['route' => 'inventory.products', 'active' => 'inventory.products', 'icon' => 'server-outline', 'label' => 'Products'],
                    ['route' => 'inventory.batches', 'active' => 'inventory.batches', 'icon' => 'list-outline', 'label' => 'Stock Management'],
                    ['route' => 'customers.management', 'active' => 'customers.*', 'icon' => 'people-outline', 'label' => 'Customers'],
                    ['route' => 'reports.sales', 'active' => 'reports.*', 'icon' => 'bar-chart-outline', 'label' => 'Reports & Analytics'],
                    ['route' => 'settings', 'active' => 'settings.*', 'icon' => 'settings-outline', 'label' => 'Settings'],
                ];
            @endphp

            @foreach ($mobileItems as $item)
                @php $isActive = request()->routeIs($item['active']); @endphp
                <a
                    @click="open = false"
                    wire:navigate.hover
                    href="{{ route($item['route']) }}"
                    class="inline-flex items-center gap-2.5 border-l-2 px-3 py-2.5 text-[13px]
                        {{ $isActive
                            ? 'border-emerald-500 bg-emerald-500/5 text-emerald-700 dark:text-emerald-300'
                            : 'border-transparent text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-white/[0.03]' }}"
                >
                    <ion-icon name="{{ $item['icon'] }}" class="text-lg"></ion-icon>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        @auth
        <div class="mt-4 flex items-center justify-between border border-slate-200 px-3 py-2 dark:border-emerald-500/10">
            <span class="text-xs text-slate-600 dark:text-slate-400">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-2 py-1 text-xs uppercase tracking-wide text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10">Log out</button>
            </form>
        </div>
        @else
        <div class="mt-4 grid grid-cols-2 gap-2">
            <a href="/login" class="border border-slate-300 px-3 py-2 text-center text-xs uppercase tracking-wide text-slate-700 hover:bg-slate-100 dark:border-emerald-500/15 dark:text-slate-300 dark:hover:bg-white/[0.03]">Sign in</a>
            <a href="/register" class="border border-emerald-600 bg-emerald-600 px-3 py-2 text-center text-xs uppercase tracking-wide text-white hover:bg-emerald-700">Sign up</a>
        </div>
        @endauth
    </div>
</header>