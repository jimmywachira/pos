<!doctype html>
<html lang="en" class="h-full w-full relative text-lg bg-white bottom-0 left-0 right-0 top-0 bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:50px_100px]">

<head>
    <meta charset=" UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'pos system +'}}</title>

    {{-- <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Delicious Handrawn-handrawn" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Delicious Handrawn">
    <meta name="description" content="A simple Livewire POS system">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full antialiased text-black" style="font-family: 'Delicious Handrawn';">
    <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
        {{-- Main Nav Links --}}
        <div class="flex items-center gap-8">
            <a wire:navigate.hover href="{{ route('pos') }}" class="flex flex-col items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('pos') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
                <ion-icon class="text-3xl" name="grid-outline"></ion-icon>
                <span>POS</span>
            </a>

            <a wire:navigate.hover href="{{ route('inventory.products') }}" class="flex flex-col items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('inventory.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
                <ion-icon class="text-3xl" name="archive-outline"></ion-icon>
                <span>Inventory</span>
            </a>

            <a wire:navigate.hover href="{{ route('customers.management') }}" class="flex flex-col items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('customers.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
                <ion-icon class="text-3xl" name="people-outline"></ion-icon>
                <span>Customers</span>
            </a>
            <a wire:navigate.hover href="{{ route('reports.sales') }}" class="flex flex-col items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('reports.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
                <ion-icon class="text-3xl" name="stats-chart-outline"></ion-icon>
                <span>Reports</span>
            </a>
            <a wire:navigate.hover href="{{ route('settings') }}" class="flex flex-col items-center font-bold hover:text-blue-600 transition-colors {{ request()->routeIs('settings.*') ? 'text-blue-700' : '' }}" style="font-size: 1.1rem;">
                <ion-icon class="text-3xl" name="settings-outline"></ion-icon>
                <span>Settings</span>
            </a>
        </div>

        {{-- Auth Section --}}
        <div class="flex items-center gap-6">
            @auth
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 text-lg">
                    <span>{{ auth()->user()->name }}</span>
                    <ion-icon name="chevron-down-outline"></ion-icon>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
            @else
            <div class="flex items-center gap-4 text-lg">
                <a href="/login" class="hover:text-blue-700">Sign In</a>
                <a href="/register" class="bg-blue-600 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-700">Sign Up</a>
            </div>
            @endauth
        </div>
    </nav>

    <main class="min-h-[calc(100vh-160px)]">
        {{ $slot }}
    </main>

    <footer class="text-center text-sm text-gray-500 p-4">
        <p>© {{ date('Y') }} - Built with Laravel with ❤️</p>
    </footer>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
