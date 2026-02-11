@props(['active'])

@php
    $classes = ($active ?? false)
    ? 'inline-flex items-center p-4 text-blue-600 hover:text-blue-700 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
    : 'inline-flex items-center p-4 text-gray-500 hover:text-black focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
