<nav id="topbar" class="navbar border-bottom fixed-top topbar bg-white px-3">
    <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm">
        <x-tabler-menu-2 width="16" />
    </button>

    <!-- MOBILE -->
    <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2">
        <x-tabler-menu-2 width="16" />
    </button>
    <div>
        <!-- Navbar nav -->
        <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
            <!-- Bell icon -->
            <li>
                <livewire:layouts::notification />
            </li>
            <!-- Language switcher -->
            <li>
                <livewire:layouts::language-switcher />
            </li>
            <!-- Dropdown -->
            <li class="ms-3">
                <livewire:layouts::profile-dropdown />
            </li>
        </ul>
    </div>

</nav>
