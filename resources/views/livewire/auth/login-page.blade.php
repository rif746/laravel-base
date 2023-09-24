<form method="POST" wire:submit="send">

    <x-forms.group.text-input type="email" wire:model="form.email" label="Email" />
    <x-forms.group.text-input type="password" wire:model="form.password" label="Password" />

    <!-- Remember Me -->
    <div class="block mt-4">
        <x-forms.element.checkbox wire:model="form.remember" />
    </div>

    <div class="flex items-center justify-end mt-4">
        @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        @endif

        <x-forms.button.primary-button class="ml-3">
            {{ __('Log in') }}
        </x-forms.button.primary-button>
    </div>
</form>
