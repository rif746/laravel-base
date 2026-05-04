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
                <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle" data-bs-toggle="dropdown"
                    aria-expanded="false" href="#" role="button">
                    <x-tabler-bell width="20" />
                    <span
                        class="position-absolute start-100 translate-middle badge rounded-pill bg-danger ms-n2 top-0 mt-2">
                        2
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
                    <ul class="list-unstyled m-0 p-0">
                        <li class="border-bottom p-3">
                            <div class="d-flex gap-3">
                                <img src="./assets/images/avatar/avatar-1.jpg" alt=""
                                    class="avatar avatar-sm rounded-circle" />
                                <div class="flex-grow-1 small">
                                    <p class="mb-0">New order received</p>
                                    <p class="mb-1">Order #12345 has been placed</p>
                                    <div class="text-secondary">5 minutes ago</div>
                                </div>
                            </div>
                        </li>
                        <li class="px-4 py-3 text-center">
                            <a href="#" class="text-primary">View all notifications</a>
                        </li>
                    </ul>
                </div>
            </li>
            <!-- Dropdown -->
            <li class="dropdown ms-3">
                <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <x-tabler-user-circle width="26" class="avatar avatar-sm rounded-circle" />
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 200px;">
                    <div>
                        <div class="d-flex align-items-center border-bottom gap-3 border-dashed px-3 py-3">
                            <x-tabler-user-circle width="26" class="avatar avatar-sm rounded-circle" />
                            <div>
                                <h4 class="small mb-0">{{ auth()->user()->name }}</h4>
                                <p class="small mb-0">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <div class="d-flex flex-column small lh-lg gap-1 p-3">
                            <a href="{{ route('profile.index') }}">
                                <span>Profile</span>
                            </a>
                            <a href="javascript:void(0)" onclick="logout()">
                                <span>Log Out</span>
                            </a>
                        </div>

                    </div>
                </div>
            </li>
        </ul>
    </div>

</nav>
