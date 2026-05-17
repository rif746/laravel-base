<aside id="sidebar" class="sidebar">
    <div class="logo-area">
        <x-link href="{{ url('/') }}" class="d-inline-flex" icon="tabler-brand-laravel" :label="env('APP_NAME')">
            <x-slot:label class="fs-5 ms-2"> {{ env('APP_NAME') }}</x-slot:label>
        </x-link>
    </div>
    <ul class="nav flex-column">
        <x-layouts.nav.sidebar.nav-link :route="route('dashboard')" icon="tabler-home" :text="__('ui.menu.dashboard')" />
        <x-layouts.nav.sidebar.nav-link :route="route('roles.index')" icon="tabler-shield" :text="__('ui.menu.roles')" />
        <x-layouts.nav.sidebar.nav-link :route="route('users.index')" icon="tabler-user" :text="__('ui.menu.users')" />
    </ul>

</aside>
