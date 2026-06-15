<x-layouts.app>
    <div class="container-fluid">
        @if(auth()->user()->hasPermissionTo('dashboard index'))
        <div class="row">
            <div class="col-12">
                <div class="mb-6">
                    <h1 class="fs-3 mb-1">{{ __('ui.menu.dashboard') }}</h1>
                    <p>{{ __('domains/system.seo.dashboard.description') }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-layouts.app>
