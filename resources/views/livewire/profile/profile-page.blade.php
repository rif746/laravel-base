<div x-data="{ emailNotif: false }">
    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
                <section>
                    <header class="flex justify-between">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('User Information') }}
                        </h2>
                        <div>
                            <x-element.button.primary
                                x-on:click="$dispatch('open-modal', {name: 'change-password-form-modal'})">
                                <x-heroicon-s-key width="16" /> {{ __('Reset Password') }}
                            </x-element.button.primary>
                            <x-element.button.primary
                                x-on:click="$dispatch('open-modal', {name: 'update-profile-form-modal', id: {{ auth()->id() }}})">
                                <x-heroicon-s-pencil width="16" />
                            </x-element.button.primary>
                        </div>
                    </header>
                    <div class="flex flex-col text-gray-900 dark:text-gray-100">
                        <div class="flex flex-row">
                            <div class="w-1/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ __('locale/user.field.name') }}
                            </div>
                            <div class="w-2/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ auth()->user()->name }}
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="w-1/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ __('locale/user.field.email') }}
                            </div>
                            <div class="w-2/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="w-1/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ __('locale/user.field.email_status') }}
                            </div>
                            <div class="w-2/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                @if (auth()->user()->hasVerifiedEmail())
                                    <span>👍 {{ __('locale/user.email.verified') }}</span>
                                @else
                                    <span wire:click="verifyEmail" class="cursor-pointer">
                                        👎 {{ __('locale/user.email.unverified') }}
                                    </span>
                                    <span x-show="emailNotif" x-cloak x-transition
                                        class="px-4 py-2 mx-6 text-gray-800 bg-green-400 rounded">
                                        {{ __('locale/user.email.sent') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="w-1/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ __('locale/user.field.gender') }}
                            </div>
                            <div class="w-2/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ \App\Enum\GenderType::tryFrom(auth()->user()->profile->gender)?->label() }}
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="w-1/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ __('locale/user.field.role') }}
                            </div>
                            <div class="w-2/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ auth()->user()->role_name }}
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
                <div>
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Preferences') }}
                            </h2>
                        </header>

                        <div class="flex flex-col text-gray-900 dark:text-gray-100">
                            <div class="flex flex-row">
                                <div class="w-1/3 p-4">
                                    Theme
                                </div>
                                <div class="w-2/3 p-4">
                                    @if (preferenceIs('theme', 'light'))
                                        <x-element.button.primary x-on:click="$wire.updatePreference('theme', 'light')"
                                            type="button">
                                            Light
                                        </x-element.button.primary>
                                    @else
                                        <x-element.button.secondary
                                            x-on:click="$wire.updatePreference('theme', 'light')" type="button">
                                            Light
                                        </x-element.button.secondary>
                                    @endif
                                    @if (preferenceIs('theme', 'dark'))
                                        <x-element.button.primary x-on:click="$wire.updatePreference('theme', 'dark')"
                                            type="button">
                                            Dark
                                        </x-element.button.primary>
                                    @else
                                        <x-element.button.secondary x-on:click="$wire.updatePreference('theme', 'dark')"
                                            type="button">
                                            Dark
                                        </x-element.button.secondary>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Delete Account') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                            </p>
                        </header>

                        <x-element.button.danger type="button"
                            x-on:click="$dispatch('open-modal', {name: 'confirm-user-deletion-modal'})">
                            {{ __('Delete Account') }}
                        </x-element.button.danger>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <livewire:profile.update-profile-form-modal />
    <livewire:profile.confirm-user-deletion-modal />
    <livewire:profile.change-password-form-modal />
</div>
