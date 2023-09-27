@props([
    'label' => '',
    'name' => null,
    'message' => '',
])

@php($name = $name ?? $attributes->wire('model')->value)

<div class="my-4">
    <x-element.input.label :for="$name" :value="__($label)" />
    <x-element.input.line {{ $attributes->merge([
        'class' => 'block mt-1 w-full',
    ]) }} />
    {{ $message }}
    <x-element.input.error :messages="$errors->get($name)" class="mt-2" />
</div>
