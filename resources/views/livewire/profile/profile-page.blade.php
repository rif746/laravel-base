<div class="flex flex-col gap-3" x-data="{ emailNotif: false }">
    <x-card :title="__('locale/profile.section.user_info')" separator shadow>
        <x-slot:menu>
            <x-button class="btn-sm" wire:modal.show="change-password-form-modal" responsive icon="o-key"
                :label="__('locale/profile.button.change_password')" />
            <x-button class="btn-sm" wire:modal.show="update-profile-form-modal, {{ auth()->id() }}" responsive
                icon="o-pencil" :label="__('locale/profile.button.edit_profile')" />
        </x-slot:menu>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-2">
            <div class="font-bold">{{ __('locale/user.field.name') }}</div>
            <div class="col-span-4">{{ auth()->user()->name ?? '-' }}</div>
            <div class="font-bold">{{ __('locale/user.field.email') }}</div>
            <div class="col-span-4">{{ auth()->user()->email ?? '-' }}</div>
            <div class="font-bold">{{ __('locale/user.field.gender') }}</div>
            <div class="col-span-4">
                {{ \App\Enum\GenderType::tryFrom(auth()->user()?->profile?->gender)?->label() ?? '-' }}</div>
            <div class="font-bold">{{ __('locale/user.field.village') }}</div>
            <div class="col-span-4">{{ auth()->user()?->profile?->village ?? '-' }}</div>
            <div class="font-bold">{{ __('locale/user.field.district') }}</div>
            <div class="col-span-4">{{ auth()->user()?->profile?->district ?? '-' }}</div>
            <div class="font-bold">{{ __('locale/user.field.city') }}</div>
            <div class="col-span-4">{{ auth()->user()?->profile?->city ?? '-' }}</div>
            <div class="font-bold">{{ __('locale/user.field.province') }}</div>
            <div class="col-span-4">{{ auth()->user()?->profile?->province ?? '-' }}</div>
        </div>
    </x-card>
    <x-card :title="__('locale/profile.section.preference')" separator shadow>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-2">
            <div class="font-bold">
                {{ __('locale/profile.prefs.theme.name') }}
            </div>
            <div class="col-span-4">
                <x-group wire:model="theme" :options="[
                    ['id' => 'dark', 'name' => __('locale/profile.prefs.theme.dark')],
                    ['id' => 'light', 'name' => __('locale/profile.prefs.theme.light')],
                ]" />
            </div>
            <div class="font-bold">
                {{ __('locale/profile.prefs.lang.name') }}
            </div>
            <div class="col-span-4">
                <x-group wire:model="lang" :options="[
                    ['id' => 'id', 'name' => __('locale/profile.prefs.lang.id')],
                    ['id' => 'en', 'name' => __('locale/profile.prefs.lang.en')],
                ]" />
            </div>
        </div>
        <div wire:dirty wire:target="theme,lang">
            <x-button label="Save" wire:click="saveSettings" class="btn-sm btn-primary" />
        </div>
    </x-card>
    <x-card :title="__('locale/profile.section.delete_account')" sseparator shadow>
        <p class="mb-2">
            {{ __('locale/profile.text.user_deletion_text') }}
        </p>
        <x-button class="btn-sm bg-red-600 hover:bg-red-500 text-white" wire:modal.show="confirm-user-deletion-modal"
            responsive icon="o-trash" :label="__('locale/profile.button.delete_account')" />
    </x-card>
    <livewire:profile.change-password-form-modal />
    <livewire:profile.update-profile-form-modal />
    <livewire:profile.confirm-user-deletion-modal />

    @push('scripts')
        @vite(['resources/js/cropper.js'])
    @endpush
</div>
