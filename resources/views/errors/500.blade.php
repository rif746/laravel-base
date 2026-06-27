<x-layouts.guest>
    <div class="container-tight py-4">
        <div class="empty">
            <div class="empty-header">500</div>
            <p class="empty-title">{{ __('ui.errors.oops') }}</p>
            <p class="empty-subtitle text-secondary">
                {{ __('ui.errors.500') }}
            </p>
            <div class="empty-action">
                <x-link href="{{ route('dashboard') }}" class="btn btn-primary" :label="__('ui.errors.take_me_home')"
                        icon="tabler-arrow-left" :icon-config="['width' => 24, 'height' => 24]" />
            </div>
        </div>
    </div>
</x-layouts.guest>
