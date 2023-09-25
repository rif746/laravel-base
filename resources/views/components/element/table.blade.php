@props([
    'cols' => null,
    'rows' => null,
    'sortDirection' => null,
    'sortField' => null,
])
@php($rows instanceof \Illuminate\Support\Collection && $rows->toArray())

<div class="flex flex-col">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-left text-sm font-light">
                    <thead class="border-b font-medium dark:border-neutral-500">
                        <tr>
                            <th scope="col" class="px-6 py-4">#</th>
                            @foreach ($cols as $col)
                                <th scope="col" class="px-6 py-4">
                                    {{ __($col['label']) }}
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $key => $row)
                            <tr class="border-b dark:border-neutral-500">
                                <td class="whitespace-nowrap px-6 py-4 font-medium">
                                    @if ($row instanceof \Illuminate\Pagination\LengthAwarePagination)
                                        {{ ($rowData->currentPage() - 1) * $rowData->perpage() + $loop->iteration }}
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
                                <td class="whitespace-nowrap px-6 py-4"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
