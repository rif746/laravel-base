@props([
    'cols' => null,
    'rows' => null,
    'sort_direction' => null,
    'sort_by' => null,
    'modals' => [],
    'url' => [],
    'permissions' => [],
])

<div class="flex flex-col"
    @if (isset($permissions['delete']) && $permissions['delete']) x-on:ask.window="Swal.fire({
            title: 'Are You Sure?',
            text: $event.detail.message,
            showCancelButton: true
            }).then((e) => {e.isConfirmed && $wire[$event.detail.dispatch]($event.detail.id)})" @endif>
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-left text-sm font-light">
                    <thead class="border-b-2 border-t-2 font-bold border-gray-200 dark:border-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-4">#</th>
                            @foreach ($cols as $col)
                                <th scope="col"
                                    class="px-6 py-4 @if (isset($col['sort'])) cursor-pointer @endif"
                                    @if (isset($col['sort'])) wire:click="sort('{{ $col['query'] }}')" @endif>
                                    <div class="flex items-center gap-3">
                                        <span>{{ __($col['label']) }}</span>
                                        <span>
                                            @if (isset($col['sort']))
                                                @if ($sort_by == $col['query'])
                                                    @if ($sort_direction == 'asc')
                                                        <x-heroicon-s-chevron-up width="16" />
                                                        <x-heroicon-s-chevron-down
                                                            class="dark:text-gray-600 text-gray-200" width="16" />
                                                    @elseif($sort_direction == 'desc')
                                                        <x-heroicon-s-chevron-up
                                                            class="text-gray-200 dark:text-gray-600" width="16" />
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
                                <td class="whitespace-nowrap px-6 py-4 font-medium">
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
                                        <td class="whitespace-nowrap px-6 py-4">{!! $column['type']($dt) !!}</td>
                                    @else
                                        <td class="whitespace-nowrap px-6 py-4">{{ __($dt) }}</td>
                                    @endif
                                @endforeach
                                <td class="whitespace-nowrap px-6 py-4 flex justify-center items-center">
                                    <div
                                        class="inline-flex text-white rounded-md overflow-hidden justify-center items-center">
                                        @if (isset($permissions['view']) && $permissions['view'])
                                            @if (isset($modals['view']))
                                                <x-element.button.flat wire:offline.attr="disabled"
                                                    wire:loading.attr="disabled"
                                                    class="bg-blue-300 disabled:bg-blue-200 p-1 rounded-none"
                                                    wire:click="$dispatch('open-modal', {name: '{{ $modals['view'] }}', id: {{ $row['id'] }}})">
                                                </x-element.button.flat>
                                            @elseif(isset($url['view']))
                                                @php($view_url = $url['view'])
                                                @php($view_route = $url['view']['route'])
                                                @php($view_params = [])
                                                @foreach ($route['view']['params'] as $key => $value)
                                                    @php($view_params[$key] = $row[$value])
                                                @endforeach
                                                <x-element.link.anchor href="{{ route($view_route, $view_params) }}">
                                                    <x-heroicon-s-eye width="16" class="pointer-events-none" />
                                                </x-element.link.anchor>
                                            @endif
                                        @endif

                                        @if (isset($permissions['edit']) && $permissions['edit'])
                                            @if (isset($modals['edit']))
                                                <x-element.button.flat wire:offline.attr="disabled"
                                                    wire:loading.attr="disabled"
                                                    class="bg-blue-600 p-1 rounded-none disabled:bg-blue-400"
                                                    wire:click="$dispatch('modal:{{ $modals['edit'] }}:load', {id: {{ $row['id'] }}})">
                                                    <x-heroicon-s-pencil width="16" class="pointer-events-none" />
                                                </x-element.button.flat>
                                            @elseif(isset($url['edit']))
                                                @php($edit_url = $url['edit'])
                                                @php($edit_route = $url['edit']['route'])
                                                @php($edit_params = [])
                                                @foreach ($route['edit']['params'] as $key => $value)
                                                    @php($edit_params[$key] = $row[$value])
                                                @endforeach
                                                <x-element.link.anchor href="{{ route($edit_route, $edit_params) }}">
                                                    <x-heroicon-s-pencil width="16" class="pointer-events-none" />
                                                </x-element.link.anchor>
                                            @endif
                                        @endif
                                        @if (isset($permissions['delete']) && $permissions['delete'])
                                            <x-element.button.flat wire:offline.attr="disabled"
                                                x-on:click="$dispatch('ask', {message: 'Are You Sure want to delete {{ $row['name'] }}?', dispatch: 'delete', id: {{ $row['id'] }} })"
                                                class="bg-red-700 p-1 rounded-none disabled:bg-red-500">
                                                <x-heroicon-s-trash width="16" class="pointer-events-none" />
                                            </x-element.button.flat>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-b-2 font-bold border-gray-200 dark:border-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-4">#</th>
                            @foreach ($cols as $col)
                                <th scope="col"
                                    class="px-6 py-4 @if (isset($col['sort'])) cursor-pointer @endif"
                                    @if (isset($col['sort'])) wire:click="sort('{{ $col['query'] }}')" @endif>
                                    <div class="flex items-center gap-3">
                                        <span>{{ __($col['label']) }}</span>
                                        <span>
                                            @if (isset($col['sort']))
                                                @if ($sort_by == $col['query'])
                                                    @if ($sort_direction == 'asc')
                                                        <x-heroicon-s-chevron-up width="16" />
                                                        <x-heroicon-s-chevron-down
                                                            class="dark:text-gray-600 text-gray-200" width="16" />
                                                    @elseif($sort_direction == 'desc')
                                                        <x-heroicon-s-chevron-up
                                                            class="text-gray-200 dark:text-gray-600" width="16" />
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
