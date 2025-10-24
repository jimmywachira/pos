<!doctype html>
<html lang="en" class="h-full w-full relative bottom-0 left-0 right-0 top-0 bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:50px_50px]">

<head>
    <meta charset=" UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'DemoPOS'}}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Delicious Handrawn">
    <meta name="description" content="A simple Livewire POS system">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class=" antialiased font-semibold text-2xl uppercase">
    <header x-data="{ open: false }" class="shadow p-5 text-center mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        {{-- Replace with your logo if you have one --}}
                        <a href="{{ route('pos') }}" class="text-2xl font-bold text-gray-800">
                            Demo<span class="text-blue-600">POS</span>
                        </a>
                    </div>
                    <!-- Desktop Navigation -->
                    <div class="hidden md:ml-6 md:flex md:space-x-4">
                        <x-nav-link :href="route('pos')" :active="request()->routeIs('pos')">
                            <ion-icon size="large" name="grid-outline" class="text-xl"></ion-icon>
                            <span>System</span>
                        </x-nav-link>
                        <x-nav-link :href="route('inventory.products')" :active="request()->routeIs('inventory.*')">
                            <ion-icon size="large" name="server-outline" class="text-xl"></ion-icon>
                            <span>Products</span>
                        </x-nav-link>
                        <x-nav-link :href="route('customers.management')" :active="request()->routeIs('customers.*')">
                            <ion-icon size="large" name="people-outline" class="text-xl"></ion-icon>
                            <span>Customers</span>
                        </x-nav-link>
                        <x-nav-link :href="route('reports.sales')" :active="request()->routeIs('reports.*')">
                            <ion-icon size="large" name="bar-chart-outline" class="text-xl"></ion-icon>
                            <span>Reports</span>
                        </x-nav-link>
                        <x-nav-link :href="route('settings')" :active="request()->routeIs('settings.*')">
                            <ion-icon size="large" name="settings-outline" class="text-xl"></ion-icon>
                            <span>Settings</span>
                        </x-nav-link>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @auth
                        <div class="flex items-center gap-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" title="Log out" class="">
                                    <span class="uppercase test-md text-black/50">{{ auth()->user()->name }}</span>
                                    <ion-icon size="large" name="close-outline" class="text-blue-500 rounded-full p-2 hover:text-red-600 focus:outline-none"></ion-icon>
                                </button>
                            </form>
                        </div>
                        @endauth
                        @guest
                        <div class="flex items-center gap-3">
                            <a href="/login" class="text-gray-700 hover:text-blue-600">Sign in</a>
                            <a href="/register" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700">Sign up</a>
                        </div>
                        @endguest
                    </div>
                    <!-- Hamburger Menu Button -->
                    <div class="ml-2 -mr-2 flex items-center md:hidden">
                        <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <ion-icon name="menu-outline" class="h-6 w-6" x-show="!open"></ion-icon>
                            <ion-icon name="close-outline" class="h-6 w-6" x-show="open" style="display: none;"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="open" x-transition class="md:hidden" id="mobile-menu" style="display: none;">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('pos')" :active="request()->routeIs('pos')">System</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('inventory.products')" :active="request()->routeIs('inventory.*')">Products</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customers.management')" :active="request()->routeIs('customers.*')">Customers</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.sales')" :active="request()->routeIs('reports.*')">Reports</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('settings')" :active="request()->routeIs('settings.*')">Settings</x-responsive-nav-link>
            </div>
        </div>
    </header>

    <main class="min-h-[calc(100vh-160px)]">
        {{ $slot }}
    </main>

    <footer class="text-center text-black p-4">
        <p> © {{ date('Y') }} - Laravel Build</p>
    </footer>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

{{-- <!doctype html>
<html lang="en" x-data="{
        theme: localStorage.getItem('theme') || 'light',
        init() {
            this.setTheme(this.theme);
            window.addEventListener('theme-changed', e => this.setTheme(e.detail));
        },
        setTheme(theme) {
            this.theme = theme;
            localStorage.setItem('theme', theme);
            document.documentElement.classList.remove('light', 'dark');
            document.documentElement.classList.add(theme);
        }
    }" class="h-full w-full relative bg-white bottom-0 left-0 right-0 top-0 bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] dark:bg-gray-900 dark:bg-[linear-gradient(to_right,#ffffff0d_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0d_1px,transparent_1px)]">
--}}
