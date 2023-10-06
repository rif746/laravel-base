@props([
    'title' => '',
    'permission' => false,
    'search' => null,
])

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <div class="flex justify-between flex-col md:flex-row h-14 items-center">
                <h2 class="text-2xl font-bold">{{ __($title) }}</h2>
                <div class="flex gap-1">
                    @if (isset($search))
                        <x-element.input.line wire:model.live="search" placeholder="Search..." />
                    @endif

                    @if ($permission)
                        <x-element.button.primary class="py-2 px-1"
                            x-on:click="$dispatch('open-modal', {name: 'user-form-modal'})">
                            <x-heroicon-s-plus width="16" />
                        </x-element.button.primary>
                    @endif
                </div>
            </div>

            {{ $slot }}
        </div>
    </div>
</div>
