<form wire:submit="register">

    <x-forms.group.text-input type="text" wire:model="form.name" label="Name" />
    <x-forms.group.text-input type="email" wire:model="form.email" label="Email" />
    <x-forms.group.text-input type="password" wire:model="form.password" label="Password" />
    <x-forms.group.text-input type="password" wire:model="form.password_confirmation" label="Password Confirmation" />

    <div class="flex items-center justify-end mt-4">
        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
            href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a>

        <x-forms.button.primary-button class="ml-4">
            {{ __('Register') }}
        </x-forms.button.primary-button>
    </div>
</form>
