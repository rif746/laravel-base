@props([
    'label' => '',
    'theme' => 'secondary',
    'rounded' => false,
    'size' => '',
    'icon' => '',
    'iconProperty' => [],
    'iconOnly' => false,
    'loading' => null,
    'type' => 'button',
])

<button
    {{ $attributes->except('wire:loading')->merge([
        'type' => $type,
        'class' =>
            'btn btn-' .
            $theme .
            ($rounded ? ' btn-rounded' : '') .
            ($size ? ' btn-' . $size : '') .
            ($iconOnly ? ' btn-icon' : '') .
            ($loading ? ' d-flex align-items-center justify-content-center' : ''),
        'wire:loading.attr' => $attributes->has('wire:loading') ? 'disabled' : false,
    ]) }}
    @if ($loading) x-bind:disabled="{{ $loading }}" @endif>
    @if ($loading)
        <template x-if="!{{ $loading }}">
            <span>
                @if ($icon)
                    @svg($icon, $iconProperty)
                @endif
                {{ $label ?: $slot }}
            </span>
        </template>
        <template x-if="{{ $loading }}">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">{{ __('ui.loading') }}</span>
            </div>
        </template>
    @elseif ($attributes->has('wire:loading'))
        <div wire:loading.remove
            @if ($attributes->has('wire:target')) wire:target="{{ $attributes->get('wire:target') }}" @endif>
            <span>
                @if ($icon)
                    @svg($icon, $iconProperty)
                @endif
                {{ $label ?: $slot }}
            </span>
        </div>
        <div wire:loading @if ($attributes->has('wire:target')) wire:target="{{ $attributes->get('wire:target') }}" @endif>
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">{{ __('ui.loading') }}</span>
            </div>
        </div>
    @else
        @if ($icon)
            @svg($icon, $iconProperty)
        @endif
        {{ $label ?: $slot }}
    @endif
</button>
