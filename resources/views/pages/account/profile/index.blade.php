<x-layouts.app>
    <div class="row gap-2">
        <div class="col-sm-8 mx-auto">
            <livewire:pages::account.profile.user-info />
        </div>
        <div class="col-sm-8 mx-auto">
            <livewire:pages::account.profile.user-settings />
        </div>
    </div>

    <livewire:pages::account.profile.update-profile-modal />
    <livewire:pages::account.profile.update-password-modal />

</x-layouts.app>
