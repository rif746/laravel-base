<div class="flex flex-col gap-3" x-data="{ emailNotif: false }">
    <x-card :title="__('locale/profile.section.user_info')" separator shadow>
        <x-slot:menu>
            <x-button class="btn-sm" wire:modal.show="change-password-form-modal" responsive icon="o-key"
                :label="__('locale/profile.button.change_password')" />
            <x-button class="btn-sm" wire:modal.show="update-profile-form-modal, {{ auth()->id() }}" responsive
                icon="o-pencil" :label="__('locale/profile.button.edit_profile')" />
        </x-slot:menu>

        <div class="row">
            <div class="sm:col-12 md:col-4 font-bold px-2 py-1">
                {{ __('locale/user.field.name') }}
            </div>
            <div class="sm:col-12 md:col-8 px-2 py-1">
                {{ auth()->user()->name }}
            </div>
            <div class="sm:col-12 md:col-4 font-bold px-2 py-1">
                {{ __('locale/user.field.email') }}
            </div>
            <div class="sm:col-12 md:col-8 px-2 py-1">
                {{ auth()->user()->email }}
            </div>
            <div class="sm:col-12 md:col-4 font-bold px-2 py-1">
                {{ __('locale/user.field.email_status') }}
            </div>
            <div class="sm:col-12 md:col-8 px-2 py-1">
                {{ __('locale/profile.status.email.' . (auth()->user()->email_verified_at ? 'verified' : 'unverified')) }}
            </div>
            <div class="sm:col-12 md:col-4 font-bold px-2 py-1">
                {{ __('locale/user.field.role') }}
            </div>
            <div class="sm:col-12 md:col-8 px-2 py-1">
                {{ auth()->user()->role_name }}
            </div>
            <div class="sm:col-12 md:col-4 font-bold px-2 py-1">
                {{ __('locale/user.field.gender') }}
            </div>
            <div class="sm:col-12 md:col-8 px-2 py-1">
                {{ \App\Enum\GenderType::tryFrom(auth()->user()->profile->gender)->label() }}
            </div>
        </div>
    </x-card>
    <x-card :title="__('locale/profile.section.preference')" sseparator shadow>
        <div class="row items-center">
            <div class="sm:col-12 md:col-4 font-bold px-2 py-1">
                {{ __('locale/profile.prefs.theme.name') }}
            </div>
            <div class="sm:col-12 md:col-8 px-2 py-1">
                <x-radio wire:model.live="theme" :options="[['id' => 'dark', 'name' => __('locale/profile.prefs.theme.dark')], ['id' => 'light', 'name' => __('locale/profile.prefs.theme.light')]]" />
            </div>
            <div class="sm:col-12 md:col-4 font-bold px-2 py-1">
                {{ __('locale/profile.prefs.lang.name') }}
            </div>
            <div class="sm:col-12 md:col-8 px-2 py-1">
                <x-radio wire:model.live="lang" :options="[['id' => 'id', 'name' => __('locale/profile.prefs.lang.id')], ['id' => 'en', 'name' => __('locale/profile.prefs.lang.en')]]" />
            </div>
        </div>
    </x-card>
    <x-card :title="__('locale/profile.section.delete_account')" sseparator shadow>
        <p class="mb-2">
            {{ __('locale/profile.text.user_deletion_text') }}
        </p>
        <x-button class="btn-sm bg-red-600 hover:bg-red-500 text-white" wire:modal.show="confirm-user-deletion-modal" responsive
            icon="o-trash" :label="__('locale/profile.button.delete_account')" />
    </x-card>
    <livewire:profile.change-password-form-modal />
    <livewire:profile.update-profile-form-modal />
    <livewire:profile.confirm-user-deletion-modal />
</div>
