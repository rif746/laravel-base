<aside id="sidebar" class="sidebar">
    <div class="logo-area">
        <x-link href="{{ url('/') }}" class="d-inline-flex h-100 w-100 p-3">
            <x-slot:label class="d-flex align-items-center h-100 flex-row gap-2">
                <img src="@logoPath" alt="Web Logo" class="h-100" />
                <span class="fs-5 fw-bold ms-2">{{ config('seotools.meta.defaults.title') }}</span>
            </x-slot:label>
        </x-link>
    </div>
    <ul class="nav flex-column">
        <x-layouts.nav.sidebar.nav-link
            :route="route('dashboard')"
            icon="tabler-home"
            :text="__('ui/menu.dashboard')"
            permission="dashboard.index"
        />
        <x-layouts.nav.sidebar.nav-link
            :route="route('roles.index')"
            icon="tabler-shield"
            :text="__('ui/menu.roles')"
            permission="role.viewAny"
        />
        <x-layouts.nav.sidebar.nav-link
            :route="route('users.index')"
            icon="tabler-user"
            :text="__('ui/menu.users')"
            permission="user.viewAny"
        />
    </ul>
</aside>
