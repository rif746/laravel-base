<aside id="sidebar" class="sidebar">
    <!-- Logo & Brand Header -->
    <div class="logo-area">
        <a href="{{ url('/') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none">
            <img src="@logoPath" alt="{{ config('app.name') }}" width="24" height="24" />
            <span class="logo-text ms-2 fw-bold text-body">
                {{ config('seotools.meta.defaults.title') }}
            </span>
        </a>
    </div>

    <!-- Navigation Menu -->
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

        <x-layouts.nav.sidebar.nav-link
            :route="route('system-log.index')"
            icon="tabler-history"
            :text="__('ui/menu.audit')"
            permission="system-log.manage"
        />
    </ul>
</aside>
