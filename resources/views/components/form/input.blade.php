<div>
    <label class="block">{{ $label }}</label>
    <input type="{{ $type ?? 'text' }}" wire:model="{{ $name }}"  {{ $attributes->merge(['class' => 'w-full border rounded p-2']) }} >
    @error($name) <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
