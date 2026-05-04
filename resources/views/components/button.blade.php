@props([
    'label' => '',
    'theme' => 'secondary',
    'rounded' => false,
    'size' => '',
    'icon' => '',
    'loading' => null,
    'type' => 'button',
])

<button
    {{ $attributes->merge([
        'type' => $type,
        'class' =>
            'btn btn-' .
            $theme .
            ($rounded ? ' btn-rounded' : '') .
            ($size ? ' btn-' . $size : '') .
            ($loading ? ' d-flex align-items-center justify-content-center' : ''),
    ]) }}
    @if ($loading) x-bind:disabled="{{ $loading }}" @endif>
    @if ($loading)
        <template x-if="!{{ $loading }}">
            <span>
                @if ($icon)
                    <i class="{{ $icon }} me-1"></i>
                @endif
                {{ $label ?: $slot }}
            </span>
        </template>
        <template x-if="{{ $loading }}">
            <span>
                <i class="bx bx-loader bx-spin me-1"></i>
                {{ trans('ui.loading') }}...
            </span>
        </template>
    @else
        @if ($icon)
            @svg($icon)
        @endif
        {{ $label ?: $slot }}
    @endif
</button>
