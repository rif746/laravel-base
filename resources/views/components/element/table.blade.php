@props([
    'cols' => null,
    'rows' => null,
    'sort_direction' => null,
    'sort_by' => null,
    'modals' => [],
    'url' => [],
    'permissions' => [],
    'perPage' => 'perPage',
    'export' => [],
    'import' => [],
])

<div class="block">
    <div class="flex flex-row justify-between">
        <div>
            @if ($perPage != '')
                <x-element.select.dropdown wire:model.live='{{ $perPage }}'>
                    <option>5</option>
                    <option>10</option>
                    <option>15</option>
                    <option>20</option>
                    <option>25</option>
                </x-element.select.dropdown>
            @endif
        </div>
        <div class="flex flex-row gap-1">
            @if (!empty($export))
                <div class="relative" x-data="{ open: false }" x-on:click.outside="open = false">
                    <x-element.button.primary x-on:click="open = !open">
                        <x-heroicon-o-cloud-arrow-down width="16" height="16" />
                        &nbsp;Export
                    </x-element.button.primary>
                    <div x-show="open" class="absolute w-full p-2 bg-gray-300 rounded shadow-sm dark:bg-gray-800">
                        @foreach ($export as $label => $method)
                            <x-element.button.flat class="w-full p-2" wire:click='{{ $method }}'
                                x-on:click="open = false;">
                                {{ strtoupper($label) }}
                            </x-element.button.flat>
                        @endforeach
                    </div>
                </div>
            @endif
            @if (!empty($import))
                <div class="relative" x-data="{ open: false }" x-on:click.outside="open = false">
                    <x-element.button.primary x-on:click="open = !open">
                        <x-heroicon-o-cloud-arrow-up width="16" height="16" />
                        &nbsp;Import
                    </x-element.button.primary>
                    <div x-show="open" class="absolute w-full p-2 bg-gray-300 rounded shadow-sm dark:bg-gray-800">
                        @foreach ($import as $label => $method)
                            <x-element.button.flat class="w-full p-2" wire:click='{{ $method }}'
                                x-on:click="open = false;">
                                {{ strtoupper($label) }}
                            </x-element.button.flat>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="flex flex-col"
        @if (isset($permissions['delete']) && $permissions['delete']) x-on:ask.window="window.Swal.fire({
            title: 'Are You Sure?',
            text: $event.detail.message,
            showCancelButton: true,
            customClass: {
                popup: 'dark:bg-gray-800 bg-gray-300 text-gray-800 dark:text-gray-300'
            }
        }).then((e) => {e.isConfirmed && $wire[$event.detail.dispatch]($event.detail.id)})" @endif>
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full text-sm font-light text-left">
                        <thead class="font-bold border-t-2 border-b-2 border-gray-200 dark:border-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-4">#</th>
                                @foreach ($cols as $col)
                                    <th scope="col"
                                        class="@if (isset($col['sort'])) cursor-pointer @endif px-6 py-4"
                                        @if (isset($col['sort'])) wire:click="sort('{{ $col['query'] }}')" @endif>
                                        <div class="flex items-center gap-3">
                                            <span>{{ __($col['label']) }}</span>
                                            <span>
                                                @if (isset($col['sort']) && !is_null($col['sort']))
                                                    @if ($sort_by == $col['query'])
                                                        @if ($sort_direction == 'asc')
                                                            <x-heroicon-s-chevron-up width="16" />
                                                            <x-heroicon-s-chevron-down
                                                                class="text-gray-200 dark:text-gray-600"
                                                                width="16" />
                                                        @elseif($sort_direction == 'desc')
                                                            <x-heroicon-s-chevron-up
                                                                class="text-gray-200 dark:text-gray-600"
                                                                width="16" />
                                                            <x-heroicon-s-chevron-down width="16" />
                                                        @endif
                                                    @else
                                                        <x-heroicon-s-chevron-up width="16" />
                                                        <x-heroicon-s-chevron-down width="16" />
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    </th>
                                @endforeach
                                <th scope="col" class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $key => $row)
                                <tr class="border-b dark:border-neutral-500" wire:key="{{ $key }}">
                                    <td class="px-6 py-4 font-medium whitespace-nowrap">
                                        @if ($rows instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                            {{ ($rows->currentPage() - 1) * $rows->perpage() + $loop->iteration }}
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </td>
                                    @foreach ($cols as $col)
                                        @php($chain = explode('.', $col['query']))
                                        @php($ev = 'return $row["' . implode('"]["', $chain) . '"] ?? " - ";')
                                        @php($dt = eval($ev))
                                        @if (isset($column['type']) && !is_string($column['type']))
                                            <td class="px-6 py-4 whitespace-nowrap">{!! $column['type']($dt) !!}</td>
                                        @else
                                            <td class="px-6 py-4 whitespace-nowrap">{{ __($dt) }}</td>
                                        @endif
                                    @endforeach
                                    <td class="flex items-center justify-center px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="inline-flex items-center justify-center overflow-hidden text-white rounded-md">
                                            @if (isset($permissions['view']) && $permissions['view'])
                                                @if (isset($modals['view']))
                                                    <x-element.button.flat wire:offline.attr="disabled"
                                                        wire:loading.attr="disabled"
                                                        class="p-1 bg-blue-300 rounded-none disabled:bg-blue-200"
                                                        wire:click="$dispatch('open-modal', {name: '{{ $modals['view'] }}', id: {{ $row['id'] }}})">
                                                    </x-element.button.flat>
                                                @elseif(isset($url['view']))
                                                    @php($view_url = $url['view'])
                                                    @php($view_route = $url['view']['route'])
                                                    @php($view_params = [])
                                                    @foreach ($route['view']['params'] as $key => $value)
                                                        @php($view_params[$key] = $row[$value])
                                                    @endforeach
                                                    <x-element.link.anchor
                                                        href="{{ route($view_route, $view_params) }}">
                                                        <x-heroicon-s-eye width="16" class="pointer-events-none" />
                                                    </x-element.link.anchor>
                                                @endif
                                            @endif

                                            @if (isset($permissions['edit']) && $permissions['edit'])
                                                @if (isset($modals['edit']))
                                                    <x-element.button.flat wire:offline.attr="disabled"
                                                        wire:loading.attr="disabled"
                                                        class="p-1 bg-blue-600 rounded-none disabled:bg-blue-400"
                                                        wire:click="$dispatch('modal:{{ $modals['edit'] }}:load', {id: {{ $row['id'] }}})">
                                                        <x-heroicon-s-pencil width="16"
                                                            class="pointer-events-none" />
                                                    </x-element.button.flat>
                                                @elseif(isset($url['edit']))
                                                    @php($edit_url = $url['edit'])
                                                    @php($edit_route = $url['edit']['route'])
                                                    @php($edit_params = [])
                                                    @foreach ($route['edit']['params'] as $key => $value)
                                                        @php($edit_params[$key] = $row[$value])
                                                    @endforeach
                                                    <x-element.link.anchor
                                                        href="{{ route($edit_route, $edit_params) }}">
                                                        <x-heroicon-s-pencil width="16"
                                                            class="pointer-events-none" />
                                                    </x-element.link.anchor>
                                                @endif
                                            @endif
                                            @if (isset($permissions['delete']) && $permissions['delete'])
                                                <x-element.button.flat wire:offline.attr="disabled"
                                                    x-on:click="$dispatch('ask', {message: 'Are You Sure want to delete {{ $row['name'] }}?', dispatch: 'delete', id: {{ $row['id'] }} })"
                                                    class="p-1 bg-red-700 rounded-none disabled:bg-red-500">
                                                    <x-heroicon-s-trash width="16" class="pointer-events-none" />
                                                </x-element.button.flat>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="font-bold border-t-2 border-b-2 border-gray-200 dark:border-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-4">#</th>
                                @foreach ($cols as $col)
                                    <th scope="col"
                                        class="@if (isset($col['sort'])) cursor-pointer @endif px-6 py-4"
                                        @if (isset($col['sort'])) wire:click="sort('{{ $col['query'] }}')" @endif>
                                        <div class="flex items-center gap-3">
                                            <span>{{ __($col['label']) }}</span>
                                            <span>
                                                @if (isset($col['sort']))
                                                    @if ($sort_by == $col['query'])
                                                        @if ($sort_direction == 'asc')
                                                            <x-heroicon-s-chevron-up width="16" />
                                                            <x-heroicon-s-chevron-down
                                                                class="text-gray-200 dark:text-gray-600"
                                                                width="16" />
                                                        @elseif($sort_direction == 'desc')
                                                            <x-heroicon-s-chevron-up
                                                                class="text-gray-200 dark:text-gray-600"
                                                                width="16" />
                                                            <x-heroicon-s-chevron-down width="16" />
                                                        @endif
                                                    @else
                                                        <x-heroicon-s-chevron-up width="16" />
                                                        <x-heroicon-s-chevron-down width="16" />
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                    </th>
                                @endforeach
                                <th scope="col" class="px-6 py-4"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @if ($rows instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $rows->links() }}
        @endif
    </div>
</div>
