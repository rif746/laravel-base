<div x-data="{ emailNotif: false }">
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <section>
                    <header class="flex justify-between">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Profile Information') }}
                        </h2>
                        <div>
                            <x-element.button.primary
                                x-on:click="$dispatch('open-modal', {name: 'change-password-form-modal'})">
                                <x-heroicon-s-key width="16" /> Reset Password
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
                                Name
                            </div>
                            <div class="w-2/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ auth()->user()->name }}
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="w-1/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                Email
                            </div>
                            <div class="w-2/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="w-1/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                Email Status
                            </div>
                            <div class="w-2/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                @if (auth()->user()->hasVerifiedEmail())
                                    <span>👍 Verified</span>
                                @else
                                    <span wire:click="verifyEmail" class="cursor-pointer">👎 Unverified</span>
                                    <span x-show="emailNotif" x-cloak x-transition
                                        class="mx-6 bg-green-400 rounded text-gray-800 px-4 py-2">
                                        Email Sent
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <div class="w-1/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                Role
                            </div>
                            <div class="w-2/3 p-4 border-b border-b-gray-700 dark:boder-b-gray-200">
                                {{ auth()->user()->roles->first()->name }}
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
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
