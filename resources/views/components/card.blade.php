@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    <div class="card-body">
        @if ($title)
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title">{{ $title }}</h3>
                    @if ($subtitle)
                        <h6 class="card-subtitle text-muted">{{ $subtitle }}</h6>
                    @endif
                </div>
                @isset($actions)
                    <div>
                        {{ $actions }}
                    </div>
                @endisset
            </div>
        @endif

        {{ $slot }}
    </div>
</div>
