@props([
    'name' => 'modal',
    'maxWidth' => '2xl',
    'title' => 'Header',
    'method' => null,
])

@php
    $maxWidth =
        [
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
        ][$maxWidth] ?? 'sm:max-w-2xl';
@endphp

<div class="fixed inset-0 z-20 flex items-center justify-center" x-data="{ visible: false, name: '{{ $name }}' }"
    x-on:open-modal.window="($event.detail.id && name === $event.detail.name) ? $wire.load($event.detail.id) : visible = (name === $event.detail.name)"
    x-on:close-modal.window="(name === $event.detail.name) ? $wire.clear() : $event.preventDefault()" x-transition
    x-show="visible" x-cloak>
    <div wire:loading.class="pointer-events-none" class="fixed inset-0 bg-gray-100 dark:bg-gray-800 opacity-80"
        x-on:click="$dispatch('close-modal', {name: name})"></div>
    <div
        class="{{ $maxWidth }} bg-white max-h-[34rem] dark:bg-gray-700 z-30 dark:text-white rounded text-black relative">
        @if (isset($method))
            <form wire:submit="{{ $method }}">
        @endif
        <div class="flex justify-between p-3 border-b">
            {{ __($title) }}
            <button x-on:click="$dispatch('close-modal', {name: name})" type="button">
                &cross;
            </button>
        </div>
        <div class="p-3 my-1 overflow-y-auto max-h-96">
            <div>
                {{ $slot }}
            </div>
        </div>
        <div class="flex justify-end gap-1 p-3 border-t">
            <x-element.button.secondary wire:loading.attr="disabled" type="button"
                x-on:click="$dispatch('close-modal', {name: name})">
                {{ __('Close') }}
            </x-element.button.secondary>
            {{ $button ?? '' }}
        </div>
        @if (isset($method))
            </form>
        @endif
    </div>
</div>
