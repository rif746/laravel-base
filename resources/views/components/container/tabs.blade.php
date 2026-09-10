@props([
    // Array of tab items: ['general' => 'General Settings', 'security' => 'Security & Password']
    'tabs' => [],
    // Unique component instance ID to prevent DOM ID collision on multiple tabs
    'id' => 'tab-' . md5(Str::random(8)),
])

<div>
    {{-- Tab Navigation Header --}}
    <ul {{ $attributes->merge(['class' => 'nav nav-pills nav-fill my-3']) }} id="{{ $id }}" role="tablist">
        @foreach ($tabs as $key => $label)
            @php
                $tabId = "{$id}-{$key}-tab";
                $paneId = "{$id}-{$key}-pane";
            @endphp
            <li class="nav-item" role="presentation">
                <button
                    @class(['nav-link', 'active' => $loop->first])
                    id="{{ $tabId }}"
                    data-bs-toggle="pill"
                    data-bs-target="#{{ $paneId }}"
                    type="button"
                    role="tab"
                    aria-controls="{{ $paneId }}"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                >
                    {{ $label }}
                </button>
            </li>
        @endforeach
    </ul>

    {{-- Tab Content Panes --}}
    <div class="tab-content" id="{{ $id }}Content">
        @foreach ($tabs as $key => $label)
            @php
                $tabId = "{$id}-{$key}-tab";
                $paneId = "{$id}-{$key}-pane";
                $slotName = "tab_{$key}";
            @endphp
            <div
                @class(['tab-pane fade', 'show active' => $loop->first])
                id="{{ $paneId }}"
                role="tabpanel"
                aria-labelledby="{{ $tabId }}"
                tabindex="0"
            >
                {{-- Render dynamic slot name if exists, fallback to main slot --}}
                {{ ${$slotName} ?? $slot }}
            </div>
        @endforeach
    </div>
</div>
