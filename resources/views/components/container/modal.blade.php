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

<div class="z-20 fixed inset-0" x-data="{ visible: false, name: '{{ $name }}' }"
    x-on:open-modal.window="visible = true; if($event.detail.id !== undefined){$dispatch('modal:{{ $name }}:load', {id: $event.detail.id})}"
    x-on:close-modal.window="visible = !(name === $event.detail.name); $dispatch('modal:{{ $name }}:close')"
    x-show="visible" x-cloak>
    <div class="bg-gray-100 dark:bg-gray-800 opacity-80 inset-0 absolute"
        x-on:click="$dispatch('close-modal', {name: name})"></div>
    <div
        class="{{ $maxWidth }} bg-white dark:bg-gray-700 mx-auto mt-4 z-30 dark:text-white p-4 rounded text-black relative">
        @if (isset($method))
            <form wire:submit="{{ $method }}">
        @endif
        <div class="p-4 flex justify-between">
            {{ __($title) }}
            <button x-on:click="visible = false">
                &cross;
            </button>
        </div>
        <div class="p-4">
            {{ $slot }}
        </div>
        <div class="p-4 flex justify-end gap-1">
            <x-element.button.secondary type="button" x-on:click="$dispatch('close-modal', {name: name})">
                {{ __('Close') }}
            </x-element.button.secondary>
            {{ $button ?? '' }}
        </div>
        @if (isset($form))
            </form>
        @endif
    </div>
</div>

@push('scripts')
@endpush
