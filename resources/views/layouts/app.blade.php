<!doctype html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset=" UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'pos system +'}}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=delicious-handrawn" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />

    <meta name="description" content="A simple Livewire POS system">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full antialiased text-gray-900" font-family="delicious handrawn">
    <div nav class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('pos') }}" class="text-2xl font-bold text-gray-800">POS</a>
            <div class="space-x-4">
                <a href="{{ route('pos') }}" class="hover:text-gray-300">Point of Sale</a>
                {{-- <a href="{{ route('inventory') }}" class="hover:text-gray-300">Inventory</a>
                <a href="{{ route('reports') }}" class="hover:text-gray-300">Reports</a>
                <a href="{{ route('customers') }}" class="hover:text-gray-300">Customers</a>
                <a href="{{ route('inventory.products') }}" class="hover:text-gray-300">Products</a>
                <a href="{{ route('inventory.batches') }}" class="hover:text-gray-300">Batches</a>
                <a href="{{ route('settings.general') }}" class="hover:text-gray-300">Settings</a>
                --}}

                @auth
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-gray-300">Logout</button>
                </form>
                @else
                <a href="/login" class="hover:text-gray-300">Sign In</a>
                <a href="{{ route('register') }}" class="hover:text-gray-300">Sign Up</a>
                @endauth
            </div>
        </div>
    </div>

    <div x-data x-on:click.away="$dispatch('search.clear-results')">
        <div class="min-h-screen">
            {{ $slot }}
        </div>

        <footer class="text-center text-sm text-gray-500 p-4">
            <div>
                <p>© {{ date('Y') }} - Built with Laravel with ❤️</p>
            </div>
        </footer>
    </div>
</body>
</html>
