<x-layouts.app>
    <section class="row g-3 g-lg-4 align-items-start">
        <!-- Top Row: Full-width User Info -->
        <div class="col-12">
            <livewire:pages::account.profile.user-info />
        </div>

        <!-- Left Column Container: User Settings -->
        <div class="col-12 col-lg-6 d-flex flex-column gap-3 gap-lg-4">
            <livewire:pages::account.profile.user-settings />
        </div>

        <!-- Right Column Container: User Activities -->
        <div class="col-12 col-lg-6 d-flex flex-column gap-3 gap-lg-4">
            <livewire:widgets::identity.user-activities :user-id="auth()->id()" />
        </div>
    </section>

    <!-- Modals -->
    <livewire:pages::account.profile.update-profile-modal />
    <livewire:pages::account.profile.update-avatar-modal />
    <livewire:pages::account.profile.update-password-modal />
</x-layouts.app>
