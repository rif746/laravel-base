<x-layouts.app>
    <section class="row g-3 g-lg-4">
        <!-- Top Row: Full-width User Info -->
        <div class="col-12">
            <livewire:pages::account.profile.user-info />
        </div>

        <!-- Bottom Row: User Settings & User Activities -->
        <div class="col-12 col-md-6">
            <livewire:pages::account.profile.user-settings />
        </div>

        <div class="col-12 col-md-6">
            <livewire:widgets::identity.user-activities :user-id="auth()->id()" />
        </div>
    </section>

    <!-- Modals -->
    <livewire:pages::account.profile.update-profile-modal />
    <livewire:pages::account.profile.update-avatar-modal />
    <livewire:pages::account.profile.update-password-modal />
</x-layouts.app>
