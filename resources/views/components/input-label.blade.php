@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-gray-700 ']) }}>
    {{ $value ?? $slot }}
</label>
