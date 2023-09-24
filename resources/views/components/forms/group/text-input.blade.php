@props([
    'label' => '',
    'name' => null,
])

@php($name = $name ?? $attributes->wire('model')->value)

<div class="my-2">
    <x-forms.element.input-label :for="$name" :value="__($label)" />
    <x-forms.element.text-input {{ $attributes->merge([
        'class' => 'block mt-1 w-full',
    ]) }} />
    <x-forms.element.input-error :messages="$errors->get($name)" class="mt-2" />
</div>
