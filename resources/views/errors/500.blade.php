<x-layouts.guest>
    <div class="container-tight py-4">
        <div class="empty">
            <div class="empty-header">500</div>
            <p class="empty-title">{{ __('ui.errors.oops') }}</p>
            <p class="empty-subtitle text-secondary">
                {{ __('ui.errors.500') }}
            </p>
            <div class="empty-action">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 12l14 0"></path>
                        <path d="M5 12l6 6"></path>
                        <path d="M5 12l6 -6"></path>
                    </svg>
                    {{ __('ui.errors.take_me_home') }}
                </a>
            </div>
        </div>
    </div>
</x-layouts.guest>
