<x-layouts.app>
    <section class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <livewire:pages::account.profile.user-info />
        </div>
        <div class="col-12 col-lg-6">
                <livewire:pages::account.profile.user-settings />
        </div>
    </section>

    <livewire:pages::account.profile.update-profile-modal />
    <livewire:pages::account.profile.update-avatar-modal />
    <livewire:pages::account.profile.update-password-modal />
</x-layouts.app>
