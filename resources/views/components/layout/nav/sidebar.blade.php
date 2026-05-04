<aside id="sidebar" class="sidebar">
    <div class="logo-area">
        <a href="{{ url('/') }}" class="d-inline-flex">
            <x-tabler-brand-laravel width="24" />
            <span class="logo-text ms-2"> {{ env('APP_NAME') }}</span>
        </a>
    </div>
    <ul class="nav flex-column">
        <x-layout.nav.sidebar.nav-link :route="route('dashboard')" icon="tabler-home" :text="__('ui.menu.dashboard')" />
        <x-layout.nav.sidebar.nav-link :route="route('roles.index')" icon="tabler-shield" :text="__('ui.menu.roles')" />
        <x-layout.nav.sidebar.nav-link :route="route('users.index')" icon="tabler-user" :text="__('ui.menu.users')" />
    </ul>

</aside>
