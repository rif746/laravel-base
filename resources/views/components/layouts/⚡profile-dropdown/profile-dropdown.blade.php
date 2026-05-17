<div>
    <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle" data-bs-toggle="dropdown"
        aria-expanded="false" href="#" role="button">
        <x-tabler-user-circle width="26" class="avatar avatar-sm rounded-circle" />
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
        <div>
            <x-link class="dropdown-item" :href="route('profile.index')">
                <x-slot:label>
                    <div class="d-flex align-items-center gap-3 px-3 py-3">
                        <x-tabler-user-circle width="26" class="avatar avatar-sm rounded-circle" />
                        <div>
                            <h4 class="small mb-0">{{ auth()->user()->name }}</h4>
                            <p class="small mb-0">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </x-slot:label>
            </x-link>
            <hr class="my-0 border-dashed" />
            <x-link class="dropdown-item" :href="route('system-setting.index')">
                <x-slot:label>
                    <x-tabler-settings width="16" />
                    @lang('ui.button.system_setting')
                </x-slot:label>
            </x-link>
            <hr class="my-0 border-dashed" />
            <a class="dropdown-item text-danger" href="javascript:void(0)"
                x-on:click="$remove.session({
                    textMessage: 'Are you sure to logout?',
                    confirmText: 'Yes',
                    cancelText: 'No',
                    onSuccess: async() => {
                        await $wire.logout()
                    }
                })">
                <x-tabler-logout width="16" />
                @lang('ui.button.logout')
            </a>
        </div>
    </div>
</div>
