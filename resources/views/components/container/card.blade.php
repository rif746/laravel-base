@props([
    'title' => '',
    'permission' => false,
    'search' => null,
    'modal' => null,
])

<div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex flex-col items-center justify-between gap-5 mb-3 md:flex-row">
                <h2 class="text-2xl font-bold">{{ __($title) }}</h2>
                <div class="flex gap-1">
                    @if (isset($search))
                        <x-element.input.line wire:model.live="search" placeholder="Search..." />
                    @endif

                    @if ($permission && isset($modal))
                        <x-element.button.primary class="px-1 py-2"
                            x-on:click="$dispatch('open-modal', {name: '{{ $modal }}'})">
                            <x-heroicon-s-plus width="16" />
                        </x-element.button.primary>
                    @endif
                </div>
            </div>
            <div class="block">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
