<div>
    <a class="position-relative btn-icon btn-sm btn-link btn rounded-circle" data-bs-toggle="dropdown"
        aria-expanded="false" href="#" role="button">
        @isset($this->user->avatar)
            <img src="{{ $this->user->avatar->url }}" alt="User Avatar" width="26" class="avatar avatar-sm rounded-circle" />
        @else
            <x-tabler-user-circle width="26" class="avatar avatar-sm rounded-circle" />
        @endisset
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
        <div>
            <x-link class="dropdown-item" :href="route('profile.index')">
                <x-slot:label>
                    <div class="d-flex align-items-center gap-3 px-3 py-3">
                        @isset($this->user->avatar)
                            <img src="{{ $this->user->avatar->url }}" alt="User Avatar" width="26" class="avatar avatar-sm rounded-circle" />
                        @else
                            <x-tabler-user-circle width="26" class="avatar avatar-sm rounded-circle" />
                        @endisset
                        <div>
                            <h4 class="small mb-0">{{ auth('web')->user()->name }}</h4>
                            <p class="small mb-0">{{ auth('web')->user()->email }}</p>
                        </div>
                    </div>
                </x-slot:label>
            </x-link>
            <hr class="my-0 border-dashed" />
            @can('system-setting.manage')
                <x-link class="dropdown-item" :href="route('system-setting.index')">
                    <x-slot:label>
                        <x-tabler-settings width="16" />
                        {{ __('ui/menu.settings') }}
                    </x-slot:label>
                </x-link>
            @endcan
            @can('system-backup.manage')
                <x-link class="dropdown-item" :href="route('system-backup.index')">
                    <x-slot:label>
                        <x-tabler-restore width="16" />
                        {{ __('ui/menu.system_backup') }}
                    </x-slot:label>
                </x-link>
            @endcan
            <hr class="my-0 border-dashed" />
            <a class="dropdown-item text-danger" href="javascript:void(0)"
                x-on:click="$ask.ajax({
                    textMessage: '{{ __('ui/confirmation.logout') }}',
                    confirmText: '{{ __('ui/button.yes') }}',
                    cancelText: '{{ __('ui/button.no') }}',
                    onSuccess: () => {
                        return $wire.logout()
                    }
                })">
                <x-tabler-logout width="16" />
                {{ __('ui/button.logout') }}
            </a>
        </div>
    </div>
</div>
