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

<body class=" antialiased font-semibold text-2xl uppercase backdrop-blur">

    {{-- Use the component implementation for backward compatibility. --}}
    <x-layouts.app :title="$title ?? null">
        {{ $slot ?? null }}
    </x-layouts.app>
    <footer class="text-center p-4">
