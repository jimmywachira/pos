<header x-data="{ open: false }" class=" p-6 text-center mb-6 text-2xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <nav class="hidden md:flex items-center space-x-4" aria-label="Primary navigation">
                    <a wire:navigate.hover href="{{ route('pos') }}" class="px-3 py-2 rounded-md {{ request()->routeIs('pos*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                        <ion-icon size="large" name="grid-outline" class="text-xl"></ion-icon> DemoPOS
                    </a>

                    <a wire:navigate.hover href="{{ route('inventory.products') }}" class="px-3 py-2 rounded-md  {{ request()->routeIs('inventory.*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                        <ion-icon size="large" name="server-outline" class="text-xl"></ion-icon> Inventory
                    </a>
                    <a wire:navigate.hover href="{{ route('customers.management') }}" class="px-3 py-2 rounded-md  {{ request()->routeIs('customers.*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                        <ion-icon size="large" name="people-outline" class="text-xl"></ion-icon> Customers
                    </a>
                    <a wire:navigate.hover href="{{ route('reports.sales') }}" class="px-3 py-2 rounded-md  {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                        <ion-icon size="large" name="bar-chart-outline" class="text-xl"></ion-icon> Reports
                    </a>
                    <a wire:navigate.hover href="{{ route('settings') }}" class="px-3 py-2 rounded-md  {{ request()->routeIs('settings.*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                        <ion-icon size="large" name="settings-outline" class="text-xl"></ion-icon> Settings
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                @auth
                <div class="flex items-center gap-3">
                    <span class=" py-1 px-2">{{ Str::substr(auth()->user()->name, 0, 3) }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Log out" class="text-blue-500 hover:text-red-600 focus:outline-none">
                            <ion-icon size="large" name="close-outline" class="text-xl"></ion-icon>
                        </button>
                    </form>
                </div>
                @else
                <div class="flex items-center gap-3">
                    <a href="/login" class="text-sm text-gray-700 hover:text-blue-600">Sign in</a>
                    <a href="/register" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white  rounded-md hover:bg-blue-700">Sign up</a>
                </div>
                @endauth

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" data-mobile-menu-button @click="open = !open" type="button" aria-controls="mobile-menu" :aria-expanded="open ? 'true' : 'false'" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <span class="sr-only">Open main menu</span>
                        <ion-icon name="menu-outline" class="h-6 w-6" x-show="!open"></ion-icon>
                        <ion-icon name="close-outline" class="h-6 w-6" x-show="open" style="display: none;"></ion-icon>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-20 bg-white shadow-lg md:hidden" style="display: none;">
        <div class="flex flex-col items-center pt-20 space-y-4">
            <a href="{{ route('pos') }}" title="POS" class="p-3 rounded-lg {{ request()->routeIs('pos*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <ion-icon size="large" name="grid-outline" class="text-2xl"></ion-icon>
            </a>
            <a href="{{ route('inventory.products') }}" title="Inventory" class="p-3 rounded-lg {{ request()->routeIs('inventory.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <ion-icon size="large" name="server-outline" class="text-2xl"></ion-icon>
            </a>
            <a href="{{ route('customers.management') }}" title="Customers" class="p-3 rounded-lg {{ request()->routeIs('customers.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <ion-icon size="large" name="people-outline" class="text-2xl"></ion-icon>
            </a>
            <a href="{{ route('reports.sales') }}" title="Reports" class="p-3 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <ion-icon size="large" name="bar-chart-outline" class="text-2xl"></ion-icon>
            </a>
            <a href="{{ route('settings') }}" title="Settings" class="p-3 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:bg-gray-100' }}">
                <ion-icon size="large" name="settings-outline" class="text-2xl"></ion-icon>
            </a>
        </div>
    </div>
</header>
