<header x-data="{ open: false }" class="shadow p-5 text-center mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('pos') }}" class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-md bg-blue-50 text-blue-600">
                        <ion-icon name="grid-outline" class="text-xl"></ion-icon>
                    </span>
                    <span class="hidden sm:inline">DemoPOS</span>
                </a>

                <nav class="hidden md:flex items-center space-x-4" aria-label="Primary navigation">
                    <a wire:navigate.hover href="{{ route('inventory.products') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('inventory.*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Inventory</a>
                    <a wire:navigate.hover href="{{ route('customers.management') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('customers.*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Customers</a>
                    <a wire:navigate.hover href="{{ route('reports.sales') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Reports</a>
                    <a wire:navigate.hover href="{{ route('settings') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('settings.*') ? 'text-blue-600' : 'text-gray-600 hover:text-gray-900' }}">Settings</a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Log out" class="text-gray-500 hover:text-red-600 focus:outline-none">
                                <ion-icon name="power-outline" class="text-xl"></ion-icon>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a href="/login" class="text-sm text-gray-700 hover:text-blue-600">Sign in</a>
                        <a href="/register" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">Sign up</a>
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
    <div id="mobile-menu" data-mobile-menu x-show="open" x-transition class="md:hidden" style="display: none;" role="dialog" aria-modal="true">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('pos') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">System</a>
            <a href="{{ route('inventory.products') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Products</a>
            <a href="{{ route('customers.management') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Customers</a>
            <a href="{{ route('reports.sales') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Reports</a>
            <a href="{{ route('settings') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Settings</a>
        </div>
    </div>
</header>
