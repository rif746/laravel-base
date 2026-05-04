@props([
    'title' => '',
    'id' => 'modal',
    'size' => '',
    'footerSlot' => null,
    'header' => null,
    'tabindex' => -1,
    'form' => false,
    'loadingState' => false,
])

<div
    {{ $attributes->merge([
        'class' => 'modal fade',
        'tabindex' => $tabindex,
        'id' => $id,
        'aria-labelledby' => str($title ?? $id)->snake() . 'Label',
        'aria-hidden' => true,
        'x-ref' => $attributes->has('x-data') ? $id : false,
    ]) }}>
    <div class="modal-dialog {{ $size }}">
        @if ($form)
            <form x-on:submit.prevent="{{ $attributes->get('wire:submit') }}">
        @endif
        <div class="modal-content">
            <div class="modal-header">
                @if ($header)
                    {{ $header }}
                @else
                    <h5 class="modal-title">{{ $title ?? '-' }}</h5>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    @if ($loadingState) x-bind:disabled="{{ $loadingState }} @endif"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body position-relative">
                @if ($loadingState)
                    <div x-show="{{ $loadingState }}" x-cloak x-transition.opacity
                        class="position-absolute w-100 h-100 start-0 top-0 bg-white bg-opacity-75"
                        style="z-index: 1060; border-radius: inherit; display: none;">
                        <div class="d-flex align-items-center justify-content-center h-100 text-center">
                            <div>
                                <div class="spinner-border text-primary mb-2 border-4"
                                    style="width: 3rem; height: 3rem;" role="status">
                                </div>
                                <p class="text-dark fw-bold mb-0">@lang('ui.loading')...</p>
                            </div>
                        </div>
                    </div>
                @endif
                <div {{ $slot->attributes }} x-cloak>
                    {{ $slot }}
                </div>
            </div>
            <div {{ $footer->attributes->merge(['class' => 'modal-footer']) }}>
                {!! $footer !!}
            </div>
        </div>
        @if ($form)
            </form>
        @endif
    </div>
</div>
