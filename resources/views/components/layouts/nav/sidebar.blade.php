<aside id="sidebar" class="sidebar">
    <div class="logo-area">
        @if (isset($logo))
            <x-link href="{{ url('/') }}" class="d-inline-flex p-3 w-100 h-100">
                <x-slot:label class="d-flex flex-row gap-2 align-items-center h-100">
                    <img src="{{ asset_static($logo) }}" alt="Web Logo" class="h-100" />
                    <span class="fs-5 fw-bold ms-2">{{ config('seotools.meta.defaults.title') }}</span>
                </x-slot:label>
            </x-link>
        @else
            <x-link href="{{ url('/') }}" class="d-inline-flex p-3" icon="tabler-brand-laravel" :label="env('APP_NAME')">
                <x-slot:label class="fs-5 fw-bold ms-2"> {{ env('APP_NAME') }}</x-slot:label>
            </x-link>
        @endif
    </div>
    <ul class="nav flex-column">
        <x-layouts.nav.sidebar.nav-link :route="route('dashboard')" icon="tabler-home"
                                        :text="__('ui.menu.dashboard')" permission="dashboard.index" />
        <x-layouts.nav.sidebar.nav-link :route="route('roles.index')" icon="tabler-shield"
                                        :text="__('ui.menu.roles')" permission="role.viewAny" />
        <x-layouts.nav.sidebar.nav-link :route="route('users.index')" icon="tabler-user"
                                        :text="__('ui.menu.users')" permission="user.viewAny" />
    </ul>

</aside>
