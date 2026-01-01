<!doctype html>
<html lang="en">

<meta charset=" UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>{{ $title ?? 'DemoPOS'}}</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Code&display=swap" rel="stylesheet">

<meta name="description" content="A simple Livewire POS system">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="absolute inset-0 -z-10 h-full w-full bg-white bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:36px_36px]">

    <x-header />

    <div class="relative h-full w-full ">
        <main class="min-h-[calc(100vh-160px)] md:pl-0 pl-20">
            {{ $slot }}
        </main>
    </div>

    <x-layouts.footer />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
