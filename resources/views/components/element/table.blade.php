@props([
    'cols' => null,
    'rows' => null,
    'sortDirection' => null,
    'sortField' => null,
    'modal' => [],
    'permission' => [],
])

<div class="flex flex-col"
    x-on:ask.window="Swal.fire({
            title: 'Are You Sure?',
            text: $event.detail.message,
            showCancelButton: true
        }).then((e) => {e.isConfirmed && $wire[$event.detail.dispatch]($event.detail.id)})">
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
                                                @if ($sortField == $col['query'])
                                                    @if ($sortDirection == 'asc')
                                                        <x-heroicon-s-chevron-up width="16" />
                                                        <x-heroicon-s-chevron-down
                                                            class="dark:text-gray-600 text-gray-200" width="16" />
                                                    @elseif($sortDirection == 'desc')
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
                            <tr class="border-b dark:border-neutral-500">
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
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex text-white">
                                        <x-element.button.flat
                                            class="bg-blue-300 rounded-s p-1 rounded-se-none rounded-ee-none">
                                            <x-heroicon-s-eye width="16" class="pointer-events-none" />
                                        </x-element.button.flat>
                                        <x-element.button.flat class="bg-blue-600 p-1 rounded-none"
                                            wire:click="$dispatch('open-modal', {name: 'user-form-modal', id: {{ $row['id'] }}})">
                                            <x-heroicon-s-pencil width="16" class="pointer-events-none" />
                                        </x-element.button.flat>
                                        <x-element.button.flat
                                            x-on:click="$dispatch('ask', {message: 'Are You Sure want to delete {{ $row['name'] }}?', dispatch: 'delete', id: {{ $row['id'] }} })"
                                            class="bg-red-700 p-1 rounded-e rounded-ss-none rounded-es-none">
                                            <x-heroicon-s-trash width="16" class="pointer-events-none" />
                                        </x-element.button.flat>
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
                                                @if ($sortField == $col['query'])
                                                    @if ($sortDirection == 'asc')
                                                        <x-heroicon-s-chevron-up width="16" />
                                                        <x-heroicon-s-chevron-down
                                                            class="dark:text-gray-600 text-gray-200" width="16" />
                                                    @elseif($sortDirection == 'desc')
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
