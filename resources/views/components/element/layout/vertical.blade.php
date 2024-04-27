@props([
    'label' => null,
    'name' => null,
    'message' => '',
])

@php($name = $name ?? $attributes->wire('model')->value)

<div class="flex flex-col gap-1 my-4">
    @isset($label)
        <x-element.input.label for="{{ $name }}">
            {{ __($label) }}
        </x-element.input.label>
    @endisset
    {{ $slot }}
    {{ $message }}
    <x-element.input.error :name="$name" />
</div>
